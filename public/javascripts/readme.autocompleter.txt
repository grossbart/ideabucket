The Autocompleter JQuery plugin adds autocomplete functionalities to a standard
input field. It is quite flexible and powerful, but offers sane defaults.
Example:

Given this element:

  <input id="autocomplete_me" type="text"/>

You can add autocomplete functionalities to this element via:

  $("input#autocomplete_me").autocompleter("http://mysite/autocomplete.json");

Or:

  // Same autocompleter, but displaying 5 rows instead of the default 10
  $("input#autocomplete_me").autocompleter("http://mysite/autocomplete.json", {
      rows: 5
    });

Or:

  // Same autocompleter, url in the settings:
  $("input#autocomplete_me").autocompleter({
      url: "http://mysite/autocomplete.json",
      rows: 5
    });

Per default, the autocompleter will trigger when the user has entered at least
one character in the input box.
It will trigger a call to the URL given to the autocompleter, with the parameter
q=value_of_the_input_field and the content-type
'application/json; charset=utf-8'.

If any results are returned, the autocompleter fills the autocomplete popup with
one row per result returned. The results should have the format:

  { results: [ "suggestion1", "suggestion2", "suggestion3"... ] }

Each value should be a string with no tag inside (everything will be escaped).

The content of the autocomplete popup will look like this:

  <div class="autocompleter">
    <div class="row">suggestion1</div>
    <div class="row">suggestion2</div>
    <div class="row">suggestion3</div>
    ...
  </div>

Per default, the autocomplete popup displays 8 rows of suggestions, with a
scrollbar if needed. The autocomplete will cache 5 queries per default.

Apart from rows and url, a large number of settings and callbacks are supported.

JSON-related settings:

* url: string - URL of the page which will answer autocompletion queries (can
also be given as a parameter)

* method: "GET" or "POST" (default: "POST")

* resultKey: string (default: "results") - name of the key in the JSON hash sent
by the autocompletion URL

* rowHash: boolean (default: false) - determines the format of the results to
expect from JSON or the results callback - if set to false, the Autocompleter
will expect something like:
  { results: [ "suggestion1", "suggestion2"... ]}
  
If set to true, the Autocompleter will expect something like
  { results:
    [
      {
        value: "suggestion1",
        display: "suggestion1_formatted_for_display"
      },
      {
        value: "suggestion2",
        display: "suggestion1_formatted_for_display"
      }
    ]
  }

The 'value' field is what will be inserted in the input field if the user
selects this option, the 'display' field is what will be displayed in the
autocomplete. Note that 'display' should still be a string with no tags inside (it will be escaped).

Display-related settings:

* rows: integer (default: 8) - determines the maximum height of the autocomplete
popup before a scrollbar is displayed.

* autoWidth: boolean (default: true) - computes the width of the
autocomplete popup based on the width of the input tag (otherwise rely on CSS)

* autoPosition: boolean (default: true) - computes the position of the
autocomplete popup based on the input tag (otherwise rely on CSS)

Behaviour-related settings:

* delay: integer (default: 100) - delay in ms between invocation of the autocomplete

* minChars: integer (default: 1) - minimum number of characters to have in order to trigger the autocomplete

* debugLevel: integer (default: 0), verbosity of the debug code (works only in FF but doesn't break in IE - from 0 (no output) to 3 (maximum verbosity)

* maxQueriesToCache: 5 - number of queries to cache

Callbacks:

The autocompleter has several callbacks. In each case, the object it is applied
to (and hence the value of 'this') is the autocompleter itself. It grants the
user access to this.$input (the jQuery object encapsulating the input field) and
this.$popup (the jQuery object encapsulating the autocomplete popup div
element). You might find useful this.caretPosition() (returns the position of
the caret in the input field) and this.originalValue() (returns the value of the
input field before the autocomplete popup was displayed).

* inputFilterCallback: triggered when the user starts typing, before checking
whether he has entered enough characters to trigger the autocompletion. Receives
the content of the input field as parameter. Should return a string, or null.
Example:

  function(inputString) {
    if (inputString == "Windows Vista")
      return null; // don't trigger autocompletion for obscene terms
    else
      return inputString;
  }

A return value of null will cancel the autocompletion atempty. However,
returning "" may still trigger it if the minimum number of characters is set to 0.

* queryCallback: does the autocompletion request. Can be useful, particularly
to insert fake static data. Receives a function to call upon success. Don't need
to return anything.
Example:

  function(successCallback) {
    var fakeData = {
      results: [ "Debian", "Sidux" ],
    }
    successCallback(fakeData);
  }

* resultsFilterCallback: triggered after the success of the query. Receives the
results hash in parameter. Should return an array of results.
Example:

  function(resultsHash) {
    var resultsArray = resultsHash[results]; 
    return resultsArray.reverse(); 
  }

* createResultRowCallback: creates a row. Receives the index of the result and
its value in parameter (if the autocompleter has rowHash set to true, it's the
'display' field of a result which is given as parameter). Can return a string
containing tags, a DOM element or a jQuery object wrapping a DOM element.
Example:

  function(index, value) {
    return "<div id='row'" + index + "' class='my_row'>" + value + "</div>"; 
  }
