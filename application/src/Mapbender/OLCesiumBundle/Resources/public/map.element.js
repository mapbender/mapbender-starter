(function($) {
    $.widget("mapbender.mbOlCesiumMap", {

        /** Element API URL */
        elementUrl: null,

        /** OpenLayers 4 / cesium map element */
        olMapElement: null,

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
            enable3DByDefault: false
        },

        toggle3D: function() {
            var widget = this;
            var ol3d = widget.olMapElement.data('olCesiumMap');
            return widget.enable3D(!ol3d.getEnabled());
        },

        is3DEnabled: function() {
            var widget = this;
            var ol3d = widget.olMapElement.data('olCesiumMap');
            return ol3d.getEnabled();
        },

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

        _create: function() {
            var widget = this;
            var element = widget.element;
            var options = widget.options;
            var olMapElement = widget.olMapElement = $("<div class='ol-map'/>");
            var urls = Mapbender.configuration.application.urls;
            var elementUrl = widget.elementUrl = urls.element + '/' + element.attr('id');
            var webPath = urls.asset;
            var cesiumPath = window.CESIUM_BASE_URL = webPath + "components/ol-cesium/Cesium/";
            var url = 'http://osm-demo.wheregroup.com/service';

            element.append(olMapElement);

            widget.updateMapContainerGeometries();

            // Add DHDN / Soldner Berlin https://epsg.io/3068
            // This seems to be used by OpenLayers
            proj4.defs("EPSG:3068", "+proj=cass +lat_0=52.41864827777778 +lon_0=13.62720366666667 +x_0=40000 +y_0=10000 +ellps=bessel +towgs84=598.1,73.7,418.2,0.202,0.045,-2.455,6.7 +units=m +no_defs");
            proj4.defs("EPSG:4326", "+proj=longlat +datum=WGS84 +no_defs");

            var berlin = ol.proj.transform([60644.513695901, 63808.743475716], 'EPSG:3068', 'EPSG:3857');
            var map = new ol.Map({
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


            var ol3d = new olcs.OLCesium({
                map:    map
            });

            olMapElement.data('olMap', map);
            olMapElement.data('olCesiumMap', ol3d);

            // map.addInteraction()
            map.addLayer(new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    // projection: 'EPSG:4326',
                    url:    url,
                    params: {
                        'LAYERS': 'osm',
                        'TILED':  true
                    }
                })
            }));

            var vectorSource = new ol.source.Vector({
                url: webPath + 'data/featureCollection5.geo.json',
                format: new ol.format.GeoJSON({
                    // Feature own projection
                    // defaultDataProjection:    "EPSG:3068",

                    // Map projection
                    // featureProjection: "EPSG:3857"
                })
            });


            var featureCollectionLayer1 = new ol.layer.Vector({
                source: vectorSource,
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

            map.addLayer(featureCollectionLayer1);

            var scene = ol3d.getCesiumScene();
            // scene.terrainProvider = new Cesium.CesiumTerrainProvider();
            scene.globe.enableLighting = true;
            ol3d.setEnabled(options.enable3DByDefault);

            element.append($('<div class="button-navigation"/>')
                .append($('<a class="button">Toggle clamp to ground</a>').on('click', function() {
                    var altitudeMode;
                    if(!featureCollectionLayer1.get('altitudeMode')) {
                        altitudeMode = 'clampToGround';
                    }

                    featureCollectionLayer1.set('altitudeMode', altitudeMode);
                    map.removeLayer(featureCollectionLayer1);
                    map.addLayer(featureCollectionLayer1);
                }))
                .append($('<a class="button">Terrain (disabled)</a>').on('click', function() {
                    var button = $(this);

                    if(!options.terrain) {
                        return;
                    }

                    // if(!scene.terrainProvider) {
                        scene.terrainProvider = new Cesium.CesiumTerrainProvider(options.terrain);
                        button.text('Terrain (enabled)');
                    // } else {
                    //     scene.terrainProvider = null;
                    //     button.text('Terrain (disabled)');
                    // }
                }))
                .append($('<a class="button">3D ('+(widget.is3DEnabled()?'enabled':'disabled')+')</a>').on('click', function() {
                    var button = $(this);
                    if(widget.toggle3D()) {
                        button.text('3D (enabled)');
                    } else {
                        button.text('3D (disabled)');
                    }
                })));

            // setTargetFrameRate
            // ol3d.setTargetFrameRate(options.fps);
            ol3d.enableAutoRenderLoop();

            $(window).resize(function() {
                widget.updateMapContainerGeometries();
            });

            element.on('toggle3D', function(event, enabled) {
                widget.updateMapContainerGeometries();
            });
        },

        /**
         * Set feature random height
         *
         * @param vectorSource
         */
        setSourceFeaturesRandomHeight: function(vectorSource) {

            function setGeometryHeight(geometry) {
                var geometryType = geometry.getType();
                var geometryProperties = geometry.getProperties();

                var height = 6 + Math.floor(Math.random() * 3);
                geometry.height = geometryProperties.height = height;
                geometry.extrude = geometryProperties.extrude = height;
                geometry.extrudedHeight = geometryProperties.extrudedHeight = height;

                geometry.setProperties(geometryProperties);

                if(geometryType == 'MultiPolygon') {
                    _.each(geometry.getPolygons(), function(polygone) {
                        var properties = polygone.getProperties();
                        polygone.height = properties.height = Math.floor(Math.random() * 100);
                        polygone.extrude = properties.extrude = Math.floor(Math.random() * 100);
                        polygone.extrudedHeight = properties.extrudedHeight = Math.floor(Math.random() * 100);
                        polygone.setProperties(properties);
                        polygone.changed();

                        // console.log(polygone);
                    })
                }
                geometry.changed();
            }

            vectorSource.on("addfeature", function(event, b, c) {
                var feature = event.feature;
                var properties = feature.getProperties();
                var geometry = properties.geometry;

                var height = 600 + Math.floor(Math.random() * 3);
                properties.height = height;
                properties.extrude = height;
                properties.extrudedHeight = height;

                feature.setProperties(properties);

                setGeometryHeight(geometry);

                feature.changed();
            }, widget);
        }
    });
})(jQuery);