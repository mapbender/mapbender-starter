(function($) {

    $.widget("mapbender.mbOlCesiumMap", {

        /** Element API URL */
        elementUrl: null,

        /** OpenLayers 4 / cesium map element */
        olMapElement: null,

        /** Cesium map */
        ol3dMap: null,

        /** OpenLayers 4 map */
        ol2dMap: null,

        /** Default options */
        options: {
            // 3D FPS
            fps: 60,

            // Terrain service
            terrain: {
                url:                  '//assets.agi.com/stk-terrain/world',
                requestWaterMask:     false,
                requestVertexNormals: false
            },

            // Show Cesium by default?
            enable3DByDefault: true
        },

        /**
         * Toggle between 2D and 3D
         *
         * @returns {*}
         */
        toggle3D: function() {
            var widget = this;
            var ol3d = widget.olMapElement.data('olCesiumMap');

            return widget.enable3D(!ol3d.getEnabled());
        },

        /**
         * Is 3D map active?
         *
         * @returns {boolean|*}
         */
        is3DEnabled: function() {
            var widget = this;
            var ol3d = widget.olMapElement.data('olCesiumMap');

            return ol3d.getEnabled();
        },

        /**
         * Turn 3D map on
         *
         * @param enabled
         * @returns {*}
         */
        enable3D: function(enabled) {
            var widget = this;
            var ol3d = widget.olMapElement.data('olCesiumMap');
            var options = widget.options;

            options.ceMap = enabled;
            ol3d.setEnabled(enabled);
            widget.element.trigger("toggle3D", enabled);

            return enabled;
        },

        /**
         * Update map container geometries
         */
        updateMapContainerGeometries: function() {
            var widget = this;
            var height = $(window).height();

            widget.olMapElement.css({height: height});
        },

        /**
         * Load and create JSON layer
         *
         * @param url URL string
         * @returns {ol.layer.Vector}
         */
        createJsonLayer: function(url) {
            return new ol.layer.Vector({
                source: new ol.source.Vector({
                    url:    url,
                    format: new ol.format.GeoJSON({
                        // Feature own projection
                        // defaultDataProjection:    "EPSG:3068",

                        // Map projection
                        // featureProjection: "EPSG:3857"
                    })
                }),
                style:  [new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'blue',
                        width: 1
                    }),
                    fill:   new ol.style.Fill({
                        color: 'rgba(255, 255, 0, 0.1)'
                    })
                })]
            });
        },

        /**
         * Load and create WMS layer
         *
         * @param url
         * @param params
         * @returns {ol.layer.Tile}
         */
        loadWmsLayer: function(url, params) {
            return new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    // projection: 'EPSG:4326',
                    url:    url,
                    params: params || {
                        'LAYERS': 'osm',
                        'TILED':  true
                    }
                })
            });
        },

        /**
         * Create button navigation
         */
        createNavigation: function() {
            var widget = this;
            var element = widget.element;

            element.append($('<div class="button-navigation"/>')
                .append($('<a class="button">3D (enabled)</a>').on('click', function() {
                    var button = $(this);
                    if(widget.toggle3D()) {
                        button.text('3D (enabled)');
                    } else {
                        button.text('3D (disabled)');
                    }
                })));
        },

        /**
         * Constructor
         *
         * @private
         */
        _create:          function() {
            var widget = this;
            var element = widget.element;
            var options = widget.options;
            var olMapElement = widget.olMapElement = $("<div class='ol-map'/>");
            var urls = Mapbender.configuration.application.urls;
            var elementUrl = widget.elementUrl = urls.element + '/' + element.attr('id');
            var webPath = urls.asset;
            var cesiumPath = window.CESIUM_BASE_URL = webPath + "components/ol-cesium/Cesium/";
            var featureCollectionUrl = webPath + 'components/data/featureCollection5.geo.json';
            var wmsUrl = 'http://osm-demo.wheregroup.com/service';

            // Add DHDN / Soldner Berlin https://epsg.io/3068
            // This seems to be used by OpenLayers
            proj4.defs("EPSG:3068", "+proj=cass +lat_0=52.41864827777778 +lon_0=13.62720366666667 +x_0=40000 +y_0=10000 +ellps=bessel +towgs84=598.1,73.7,418.2,0.202,0.045,-2.455,6.7 +units=m +no_defs");
            proj4.defs("EPSG:4326", "+proj=longlat +datum=WGS84 +no_defs");

            element.append(olMapElement);

            widget.updateMapContainerGeometries();

            var berlin = ol.proj.transform([60644.513695901, 63808], 'EPSG:3068', 'EPSG:3857');
            var map = widget.ol2dMap = new ol.Map({
                layers:   [],
                target:   olMapElement[0],
                controls: ol.control.defaults({
                    attributionOptions: /** @type {olx.control.AttributionOptions} */ ({
                        collapsible: false
                    })
                }),
                //default EPSG:3857
                view:     new ol.View({
                    projection: "EPSG:3857",
                    // maxResolution: options.maxResolution,
                    // mandatory !!!
                    center: berlin, // extent:     options.extents.start,
                    zoom:   17
                })
            });


            var ol3d = widget.ol3dMap = new olcs.OLCesium({
                map:    map
            });

            olMapElement.data('olMap', map);
            olMapElement.data('olCesiumMap', ol3d);

            // map.addInteraction()
            map.addLayer(widget.loadWmsLayer(wmsUrl));
            map.addLayer(widget.createJsonLayer(featureCollectionUrl));

            var scene = ol3d.getCesiumScene();
            // scene.terrainProvider = new Cesium.CesiumTerrainProvider();
            scene.globe.enableLighting = true;

            if(options.enable3DByDefault){
                window.setTimeout(function() {
                    ol3d.setEnabled(options.enable3DByDefault);
                }, 1000);
            }

            console.log(options);

            widget.createNavigation();

            ol3d.enableAutoRenderLoop();

            $(window).resize(function() {
                widget.updateMapContainerGeometries();
            });

            element.on('toggle3D', function(event, enabled) {
                widget.updateMapContainerGeometries();
            });
        }
    });
})(jQuery);