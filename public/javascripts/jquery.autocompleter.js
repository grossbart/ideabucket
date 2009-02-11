/**
 * AutoCompleter jQuery plugin - autocompletion plugin for jQuery
 * Copyright (C) 2009 Emmanuel Surleau
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Email: emmanuel.surleau@gmail.com
 */

// See included README for details on how to use it

jQuery.fn.autoCompleter = function(url, options) {
  if (options == null && url == null)
    throwError("No options and no URL specified");
  if (options == null) {
    if (typeof url == "string" || url instanceof String)
      options = { url: url };
    else
      options = url;
  }
  else {
    options.url = url;
  }
  var $input = jQuery(this);
  addAutoCompleterDivs($input);
  $input.attr("autocomplete", "off"); // don't want the browser to try and auto complete 

  // lock to avoid concurrency issues
  var lock = false;

  // cache is not stored in a single autocompleter - in theory, we might have
  // several autocompleters plugged to the same URL - in this case, it's
  // smarter to share the result of the queries
  var cache = {};
  var cacheSize = 0;

  var settings = jQuery.extend({
    rows: 10, // number of rows to display
    resultKey: "results", // name of the key in the JSON result
    autoWidth: true,  // autocompute width based on the input tag (otherwise rely on CSS)
    autoPosition: true, // autocompute position based on the input tag (otherwise rely on CSS)
    rowHash: false, // JSON results are { display: "something to display", value : "something else to insert in the input box" }
    method: "GET", // method to do the AJAX request
    delay: 100, // delay in ms between invocation of the autocomplete
    minChars: 1, // minimum number of characters to have in order to trigger the autocomplete
    debugLevel: 0, // verbosity of the debug code (works only in FF but doesn't break in IE - from 0 (no output) to 3 (maximum verbosity)
    maxQueriesToCache: 5, // hide five queries
    autoCompleterKey: "autocompleter.self", // key used in the input's data hash to store its associated autocompleter object
    originalValueKey: "autocompleter.originalValue", // key used in the jquery hash to remember the input's original value
    rowValueKey: "autocompleter.rowValue", // key used to store the real value of a row
    inputFilterCallback: null, // callback to filter the input value before making the query
    queryCallback: null, // query to do the callback
    resultsFilterCallback: null, // callback to filter the AJAX result hash
    createResultRowCallback: null // callback to create a row to add to the autocomplete popup
  }, options);

  // "Global" functions
  function throwError(text) {
    throw "Error in the jQuery AutoCompleter plugin: " + text;
  }
  function debug1(text) {
    if (settings.debugLevel >= 1 && window.console)
      window.console.debug("jQuery AutoCompleter debug: ", text);
  }
  function debug2(text) {
    if (settings.debugLevel >= 2 && window.console)
      window.console.debug("jQuery AutoCompleter debug: ", text);
  }
  function debug3(text) {
    if (settings.debugLevel >= 3 && window.console)
      window.console.debug("jQuery AutoCompleter debug: ", text);
  }

  function addAutoCompleterDivs($input) {
    $input.after(
        "<div class=\"autocompleter\" style=\"display: none;\"/>");
  };

  // Make sure that this particular autocompler is not accessed concurrently
  function synchronize(callback) {
    if (!lock) {
      lock = true;
      try {
        callback();
      }
      catch(e) {
        throwError(e);
      }
      finally {
        lock = false;
      }
    }
    // try again in 10 ms, pray the lock will be free
    else setTimeout(callback, 10);
  };

  // Generates an autocompleter if needed
  function getAutoCompleter($input) {
    var key = settings.autoCompleterKey;
    if (!$input.data(key))
      $input.data(key, new AutoCompleter(settings, $input));
    return $input.data(key);
  }

  // Handles an autocomplete session
  function AutoCompleter(settings, $input) {
    this.init(settings, $input);
  };

  // Constructor
  AutoCompleter.prototype.init = function(settings, $input) {
    this.settings = settings;
    this.$input = $input;
    this.$popup = this.getPopup();
  }

  // Returns the popup
  AutoCompleter.prototype.getPopup = function() {
    var $popup = this.$input.next().eq(0);
    if (!$popup || !$popup.hasClass("autocompleter"))
      throwError("The autocompleter div has disappeared!");
    return $popup;
  }

  // Handles non-movement key processing
  AutoCompleter.prototype.processKey = function(e) {
    debug3("Received keypress event with code " + e.keyCode);
    var isPopupVisible = this.isPopupVisible();
    if (isPopupVisible)
      this.$selectedRow = this.getSelectedRow();
    switch (e.keyCode) {
      case 40: // down
      case 38: // up
      case 39: // right
      case 37: // left
        break; // catch it in processMovementKeys
      case 13: // enter
        if (isPopupVisible) {
          this.strangleEvent(e);
          this.confirmSuggestion();
        }
        break;
      case 27: // escape
        this.killPopup();
        break;
      case 8: // backspace
        this.killPopup();
        this.delayedAutoComplete();
        break;
      default:
        this.$input.data(this.settings.originalValueKey, this.$input.val());
        this.delayedAutoComplete();
        break;
    };
  };

  // Separate function, just to handle movement keys (due to problems in IE and
  // webkit-based browsers)
  AutoCompleter.prototype.processMovementKey = function(e) {
    debug3("Received keydown event with code " + e.keyCode);
    var isPopupVisible = this.isPopupVisible();
    if (isPopupVisible)
      this.$selectedRow = this.getSelectedRow();
    switch (e.keyCode) {
      case 40: // down
        if (isPopupVisible) {
          this.strangleEvent(e);
          if (!this.nextSuggestion())
            this.rollback();
        }
        else
          this.delayedAutoComplete();
        break;
      case 38: // up
        if (isPopupVisible) {
          this.strangleEvent(e);
          if (!this.previousSuggestion())
            this.rollback();
        }
        break;
      case 39: // right
        if (isPopupVisible) {
          this.strangleEvent(e);
          this.confirmSuggestion();
        }
        break;
      case 37: // left arrow
        this.killPopup();
        break;
    };
  }

  // Strangles the event (prevents it from propagating)
  AutoCompleter.prototype.strangleEvent = function(e) {
    if (e.preventDefault) e.preventDefault();
    if (e.stopPropagation) e.stopPropagation();
  }

  // Retrieves the currently selected row, if any
  AutoCompleter.prototype.getSelectedRow = function() {
    var $selectedRows = this.$popup.find(".selected");
    return $selectedRows.length > 0 ? $selectedRows.eq(0) : null;
  }

  // Select the next suggestion in the popup
  AutoCompleter.prototype.nextSuggestion = function() {
    debug2("Selecting next suggestion");
    var newSelectedRows = this.$selectedRow != null ?
      this.$selectedRow.next() : // select the row followig the current one
      this.$popup.children(); // select the first row
    var newSelectedRow = newSelectedRows.length > 0 ?
      newSelectedRows.eq(0) :
      null;
    this.selectRow(newSelectedRow);
    return (newSelectedRow != null);
  }

  // Select the previous suggestion in the popup
  AutoCompleter.prototype.previousSuggestion = function() {
    debug2("Selecting previous suggestion");
    var newSelectedRows = this.$selectedRow != null ?
      this.$selectedRow.prev() : // select the row preceding the current one
      this.$popup.children(); // select the last row
    var newSelectedRow = newSelectedRows.length > 0 ?
      newSelectedRows.eq(newSelectedRows.length - 1) :
      null;
    this.selectRow(newSelectedRow);
    return (newSelectedRow != null);
  }

  // Select a new row, if any
  AutoCompleter.prototype.selectRow = function($row) {
    this.$popup.find(".selected").removeClass("selected");
    this.$selectedRow = $row; 
    if ($row != null) {
      debug3("Selected row: " + $row);
      $row.addClass("selected"); 
      var rowOffset = 0;
      $row.prevAll().each(function() { rowOffset += $(this).height() });
      // check if the newly selected row is visible
      if ((rowOffset >= (this.$popup.innerHeight() + this.$popup.scrollTop())) ||
          (rowOffset < this.$popup.scrollTop()) )
        this.$popup.scrollTop(rowOffset);

      var key = this.settings.originalValueKey;
      var originalValue = $input.data(key);
      if (!originalValue)
        $input.data(key, this.$input.val());
      var value = null;
      if (this.settings.rowValueKey)
        value = $row.data(this.settings.rowValueKey);
      if (value == null)
        value = $row.text();
      $input.val(value);
    }
    else {
      debug3("Selected null row");
    }
  }

  // Returns the original value of the input box, before the popup was displayed
  AutoCompleter.prototype.originalValue = function() {
    return this.$input.data(this.settings.originalValueKey);
  }

  // Revert to original value
  AutoCompleter.prototype.rollback = function() {
    var originalValue = this.originalValue();
    this.$input.val(originalValue);  
  }

  // Closes the popup and rolls back the changes
  AutoCompleter.prototype.killPopup = function() {
    if (this.isPopupVisible()) {
      this.rollback(); // restore original content
      this.$input.removeData(this.settings.originalValueKey);
      this.hidePopup();
    }
  }

  // User has selected an item
  AutoCompleter.prototype.confirmSuggestion = function() {
    if (this.$selectedRow != null) {
      this.$input.removeData(this.settings.originalValueKey);
      this.hidePopup();
    }
    else {
      this.killPopup();
    }
  }


  // Returns true or false depending on whether the autocomplete popup is
  // visible
  AutoCompleter.prototype.isPopupVisible = function() {
    return this.$popup.is(":visible");
  }

  // Starts the autocomplete with a delay if one has been set
  AutoCompleter.prototype.delayedAutoComplete = function() {
    var delay = this.settings.delay;
    if (delay) {
      var me = this;
      setTimeout(function () { me.autoComplete(); }, delay);
    }
  }

  // Utility function to return the caret position in the input field
  //  Borrowed from http://www.webdeveloper.com/forum/showthread.php?t=74982
  AutoCompleter.prototype.caretPosition = function() {
    // Initialize
    var iCaretPos = 0;
    var oField = this.$input.get(0);
    // IE Support
    if (document.selection) { 

        // Set focus on the element
        oField.focus ();

        // To get cursor position, get empty selection range
        var oSel = document.selection.createRange ();

        // Move selection start to 0 position
        oSel.moveStart ('character', -oField.value.length);

        // The caret position is selection length
        iCaretPos = oSel.text.length;
    }
    // Firefox support
    else if (oField.selectionStart || oField.selectionStart == '0')
        iCaretPos = oField.selectionStart;

    // Return results
    return iCaretPos;
  }

  // Main action
  AutoCompleter.prototype.autoComplete = function() {
    var criterion = this.$input.val();

    //Save the criterion for restoring it later
    this.$input.data(this.settings.originalValueKey, criterion);
    // apply filter function, if any
    if (this.settings.inputFilterCallback)
      criterion = this.settings.inputFilterCallback.call(this, criterion);
    if (!criterion)
      return;
    // Not enough characters in the input, move along
    if (this.settings.minChars && criterion.length < this.settings.minChars) {
      debug2("Not enough characters to start the autocompletion");
      return;
    }
    var me = this;

    if (cache[criterion]) {
      var resultsHash = me.cache(criterion);
      me.handleQueryResults(resultsHash);
    }
    // no cache
    else {
      var callback = function (results) {
        me.cache(criterion, results);
        me.handleQueryResults(results)
      };
      if (this.settings.queryCallback) {
        debug1("Triggering queryCallback");
        this.settings.queryCallback.call(this, callback);
      }
      else {
        var url = this.settings.url;
        if (!url)
          throwError("No url specified and no queryCallback");
        var method = this.settings.method;
        var params = {
          q: criterion,
          contentType: 'application/json; charset=utf-8'
        };
        debug1("Triggering AJAX call with method " + method + " to URL " + url + " with parameter q=" + criterion);
        if (method == 'POST')
          jQuery.post(url, params, callback, "json");
        else jQuery.getJSON(url, params, callback);
      }
    }
  }

  AutoCompleter.prototype.cache = function(criterion, resultsHash) {
    // we want to retrieve the results
    if (resultsHash == null)  {
      var resultsData = cache[criterion];
      if (resultsData) {
        // update last access date
        resultsData.lastAccess = new Date().getTime();
        return resultsData.results;
      }
      else {
        return null;
      }
    }
    // we want to store them
    else {
      if (cacheSize >= this.maxQueriesToCache) {
        // oups, we're exceeding our allowance, discard the oldest query
        var oldestTime = new Date().getTime();
        var oldestCrit = null;
        var accessTime;
        for (var key in cache) {
          accessTime = cache[key].latestAccess;
          if (accessTime < oldestTime) {
            oldestCrit = key;
            oldestTime = accessTime;
          }
        }
        delete cache[oldestCrit];
        cacheSize--;
      }
      // actually store in cache
      cache[criterion] = {
        results: resultsHash,
        lastAccess: new Date().getTime()
      };
      cacheSize++;
    }
  }

  // Handles the JSON results
  AutoCompleter.prototype.handleQueryResults = function(resultsHash) {
    debug2("Received following data: ")
    debug2(resultsHash);
    if (!resultsHash instanceof Object)
      throwError("The data received is not a hash");
    var results = null;
    if (this.settings.resultsFilterCallback) {
      results = this.settings.resultsFilterCallback.call(this, resultsHash);
    }
    else {
      var key = this.settings.resultKey;
      results = resultsHash[key];
      if (results == null)
        throwError("The result hash didn't contain the results key " + key);
    }
    if (results.length > 0) {
      var me = this;
      var callback = this.settings.createResultRowCallback ?
        function(i, val) {
          return me.settings.createResultRowCallback.call(me, i, val);
        } :
        function(i, val) {
          return me.createResultRow(val);
        }
      this.$popup.empty();
      // Create rows and append them to the popup
      var maxRowCount = Math.min(this.settings.rows, (results.length - 1) );
      me.$popup.css("height", "");
      // Add this to the height of the popup to accomodate IE
      var borderWidth = jQuery.browser.msie ?
        (this.$popup.outerHeight() - this.$popup.innerHeight()) :
        0;
      jQuery.each(results, function(i, val) {
        var display = me.settings.rowHash ? val.display : val;
        var value = me.settings.rowHash ? val.value : val;
        var row = callback(i, display);
        debug3("Created from val " + value + " :row =" + row);
        if (row) {
          var $row = $(row); 
          if (me.settings.rowHash)
          $row.data(me.settings.rowValueKey, value);
          me.$popup.append($row);
          if (maxRowCount != null && maxRowCount == i) {
            // Looks stupid, innit? But this way we can force the height,
            // and make sure we only display maxRowCount
            me.$popup.height(me.$popup.height() + borderWidth);
          }
          $row.click(function(e) {
            me.strangleEvent(e);
            me.selectRow($(this));
            me.confirmSuggestion();
          });
        }
      });
      this.showPopup();
    }
  };

  // Creates a single row to display a line of result in the popup
  AutoCompleter.prototype.createResultRow = function(val) {
    var row = document.createElement("div");
    row.setAttribute("class", "row");
    row.appendChild(document.createTextNode(val));
    return row;
  };

  AutoCompleter.prototype.showPopup = function() {
    // same width as parent input, otherwise rely on CSS
    var $popup = this.$popup;
    var $input = this.$input;
    if (settings.autoWidth == true)
      $popup.width($input.width());
    // same position wrt to the parent element as the input, otherwise rely on
    // CSS
    if (settings.autoPosition == true) {
      var position = $input.position();
      $popup.css("left", position.left);
      $popup.css("top", position.top + $input.outerHeight());
    };
    debug3("Displaying the popup");
    $popup.show();
  };

  AutoCompleter.prototype.hidePopup = function() {
    this.$popup.hide();
  };

  // JQuery callback
  return this.each(function() {
    var $me = jQuery(this);

    $me.keypress(function(e) {
      synchronize(function() {
        getAutoCompleter($me).processKey(e); 
      });
    }).keyup(function(e) {
      synchronize(function() {
        getAutoCompleter($me).processMovementKey(e); 
      });
    });
    // Make the popup when the user clicks away
    $me.blur(function() {
      // use setTimeout, because blur will be sent also when the user clicks on
      // a row in the popup - we need to associated action to do its thing first
      setTimeout(function() {
        synchronize(function() {
          getAutoCompleter($me).killPopup();
        });
      }, 100);
    });
  });
};
