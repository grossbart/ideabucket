/* Nano Templates (Tomasz Mazur, Jacek Becela) */

(function($){
  $.nano = function(template, data){
    return template.replace(/\{([\w\.]*)}/g, function(str, key){
      var keys = key.split("."), value = data[keys.shift()]
      $.each(keys, function(){ value = value[this] })
      return value
    })
  }
  $.fn.nano = function(data){
    return this.html($.nano(this.html(), data));
  }
  $.extend($.nano, {
    cache: {},
    queue: {},
    group: {},
    html: function(url, data, options) {
      if ($.nano.cache[url]) return $.nano($.nano.cache[url], data);
      var guid = (((1+Math.random())*0x10000)|0).toString(16);
      var options = $.extend(true, {guid: guid, data: data, element: "div"}, options);
      
      if (options.group) {
        if ($.nano.group[options.group]) {
          if ($.inArray(url, $.nano.group[options.group]) == -1) {
            $.nano.group[options.group].push(url);
          }
        } else {
          $.nano.group[options.group] = [url];
        }
      }
      
      if ($.nano.queue[url]) {
        $.nano.queue[url].push(options);
      } else {
        $.nano.queue[url] = [options];
        $.get(url, function(html) {
          $.nano.cache[url] = html;
          $.each($.nano.queue[url], function(i, row){
            $("#nano-"+row.guid).replaceWith($.nano(html, row.data));
          });
          
          if (options.group) {
            var idx = $.inArray(url, $.nano.group[options.group])
            if (idx >= 0) {
              $.nano.group[options.group].splice(idx, 1);
            }
          }

          if (options.event) {
            delete $.nano.queue[url];
            $(document).trigger(options.event);
          } else {
            delete $.nano.queue[url];
            $(document).trigger("nano_loaded");
          }
          if (options.group && $.nano.group[options.group].length == 0) {
            if (options.event) $(document).trigger(options.event+"_completed");
          }
          if ($.isEmpty($.nano.queue)) {
            $(document).trigger("nano_completed");
          }
        }, "html");
      }
      return $.nano('<{element} id="nano-{guid}"/>', options);
    }
  });
})(jQuery)