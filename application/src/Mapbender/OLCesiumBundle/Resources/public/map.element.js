(function($) {
    $.widget("mapbender.mbOlCesiumMap", {

        /** Element API URL */
        elementUrl: null,

        _create: function() {
            var widget = this;
            var element = widget.element;
            var options = widget.options;

            widget.elementUrl = Mapbender.configuration.application.urls.element + '/' + element.attr('id');

            debugger;
        }
    });
})(jQuery);