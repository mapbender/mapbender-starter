(function($) {

    'use strict';

    $.widget("mapbender.baseElement", {

        /** Element API URL */
        elementUrl: null,

        /** Default options */
        options: {
        },

        /**
         * Constructor
         *
         * @private
         */
        _create: function() {
            var widget = this;
            var element = widget.element;
            var urls = Mapbender.configuration.application.urls;

            widget.elementUrl = urls.element + '/' + element.attr('id')+'/';
        },

        /**
         * Get URL by URI
         * @param uri
         */
        getUrlByUri: function(uri) {
            var urls = Mapbender.configuration.application.urls;
            var webPath = urls.asset;
            return webPath + uri;

        },

        /**
         * Query Backend API
         *
         * @param uri suffix
         * @param request query
         * @return xhr jQuery XHR object
         * @version 0.2
         */
        query: function(uri, request) {
            var widget = this;
            return $.ajax({
                url:         widget.elementUrl + uri,
                type:        'POST',
                contentType: "application/json; charset=utf-8",
                dataType:    "json",
                data:        JSON.stringify(request)
            }).error(function(xhr) {
                var errorMessage = Mapbender.trans("Mapbender API Error:\n");
                var errorDom = $(xhr.responseText);

                if(errorDom.size() && errorDom.is(".sf-reset")) {
                    errorMessage += "\n" + errorDom.find(".block_exception h2").text() + "\n";
                    errorMessage += "Trace:\n";
                    _.each(errorDom.find(".traces li"), function(li) {
                        errorMessage += $(li).text() + "\n";
                    });

                } else if(errorDom.has(".loginBox.login").size()) {
                    var loginURL = errorDom.find(".loginBox.login form").attr("action").replace(/\/check$/, '');
                    location.href = loginURL;
                    Mapbender.info("Bitte loggen sie sich ein.")
                } else {
                    errorMessage += errorDom.text();
                }

                $.notify(errorMessage, {
                    autoHide: false
                });
                console.log(errorMessage, xhr);
            });
        },

        /**
         * On ready callback
         */
        ready: function () {
            this.functionIsDeprecated();

            var widget = this;

            _.each(widget.readyCallbacks, function (readyCallback) {
                if (typeof (readyCallback) === 'function') {
                    readyCallback();
                }
            });

            // Mark as ready
            widget.readyState = true;

            // Remove handlers
            widget.readyCallbacks.splice(0, widget.readyCallbacks.length);

        },

        /**
         * Private on ready
         *
         * @private
         */
        _ready: function (callback) {
            this.functionIsDeprecated();

            var widget = this;
            if (widget.readyState) {
                if (typeof (callback) === 'function') {
                    callback();
                }
            } else {
                widget.readyCallbacks.push(callback);
            }
        },

        /**
         * Destroy callback
         *
         * @private
         */
        destroy: function () {
            this.functionIsDeprecated();
        },

        /**
         * Private destroy
         *
         * @private
         */
        _destroy: function () {
            this.functionIsDeprecated();
        },

        /**
         * Notification that function is deprecated
         */
        functionIsDeprecated: function () {
            console.warn(new Error("Function marked as deprecated"));
        }

    });
})(jQuery);