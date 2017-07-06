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

        updateMapContainerGeometries: function() {
            var widget = this;
            var height = $(window).height();
            // var width = $(window).width();
            // var options = widget.options;

            widget.olMapElement.css({height: height});
        },

        getTextFeatureStyles: function() {
            return [new ol.style.Style({
                text: new ol.style.Text({
                    text:         'Only text',
                    textAlign:    'center',
                    textBaseline: 'middle',
                    stroke:       new ol.style.Stroke({
                        color: 'red',
                        width: 3
                    }),
                    fill:         new ol.style.Fill({
                        color: 'rgba(0, 0, 155, 0.3)'
                    })
                })
            }), new ol.style.Style({
                geometry: new ol.geom.Circle([1000000, 3000000, 10000], 2e6),
                stroke:   new ol.style.Stroke({
                    color: 'blue',
                    width: 2
                }),
                fill:     new ol.style.Fill({
                    color: 'rgba(0, 0, 255, 0.2)'
                })
            })];
        },

        _create:              function() {
            var widget = this;
            var element = widget.element;
            var options = widget.options;
            var olMapElement = widget.olMapElement = $("<div class='ol-map'/>");
            var urls = Mapbender.configuration.application.urls;
            var webPath = urls.asset;
            var cesiumPath = window.CESIUM_BASE_URL = webPath + "components/ol-cesium/Cesium/";
            var iconPath = cesiumPath + "../examples/data/icon.png";
            var vectorDataUrl = cesiumPath + "../examples/data/geojson/vector_data.geojson";
            var elementUrl = widget.elementUrl = urls.element + '/' + element.attr('id');

            element.append(olMapElement);

            widget.updateMapContainerGeometries();
            $(window).resize(function() {
                widget.updateMapContainerGeometries();
            });

            element.on('toggle3D', function(event, enabled) {
                widget.updateMapContainerGeometries();
            });

            // Add DHDN / Soldner Berlin https://epsg.io/3068
            // This seems to be used by OpenLayers
            proj4.defs("EPSG:3068", "+proj=cass +lat_0=52.41864827777778 +lon_0=13.62720366666667 +x_0=40000 +y_0=10000 +ellps=bessel +towgs84=598.1,73.7,418.2,0.202,0.045,-2.455,6.7 +units=m +no_defs");

            var berlin = ol.proj.transform([60644.513695901, 63808.743475716], 'EPSG:3068', 'EPSG:3857');

            // var format = new ol.format.WKT();
            // var feature = format.readFeature("POLYGON((60586.458514127 63712.219187492,61163.541485873 63712.219187492,61163.541485873 64027.780812508,60586.458514127 64027.780812508,60586.458514127 63712.219187492))", {
            //     dataProjection: 'EPSG:3068',
            //     featureProjection: 'EPSG:3857'
            // });


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

            olMapElement.data('olMap', map);

            var ol3d = new olcs.OLCesium({
                map:    map
            });

            olMapElement.data('olCesiumMap', ol3d);

            // EXAMPLES
            var oldStyle = new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'blue',
                    width: 2
                }),
                fill: new ol.style.Fill({
                    color: 'green'
                })
            });

            // vecotor.js
            var iconFeature = new ol.Feature({
                geometry: new ol.geom.Point([700000, 200000, 100000])
            });

            var textFeature = new ol.Feature({
                geometry: new ol.geom.Point([1000000, 3000000, 500000])
            });

            var cervinFeature = new ol.Feature({
                geometry: new ol.geom.Point([852541, 5776649])
            });

            cervinFeature.getGeometry().set('altitudeMode', 'clampToGround');

            var iconStyle = new ol.style.Style({
                image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
                    anchor: [0.5, 46],
                    anchorXUnits: 'fraction',
                    anchorYUnits: 'pixels',
                    opacity: 0.75,
                    src: iconPath
                })),
                text: new ol.style.Text({
                    text: 'Some text',
                    textAlign: 'center',
                    textBaseline: 'middle',
                    stroke: new ol.style.Stroke({
                        color: 'magenta',
                        width: 3
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgba(0, 0, 155, 0.3)'
                    })
                })
            });


            iconFeature.setStyle(iconStyle);

            textFeature.setStyle(widget.getTextFeatureStyles());

            cervinFeature.setStyle(iconStyle);

            var image = new ol.style.Circle({
                radius: 5,
                fill: null,
                stroke: new ol.style.Stroke({color: 'red', width: 1})
            });

            var styles = {
                'Point': [new ol.style.Style({
                    image: image
                })],
                'LineString': [new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'green',
                        lineDash: [12],
                        width: 10
                    })
                })],
                'MultiLineString': [new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'green',
                        width: 10
                    })
                })],
                'MultiPoint': [new ol.style.Style({
                    image: image,
                    text: new ol.style.Text({
                        text: 'MP',
                        stroke: new ol.style.Stroke({
                            color: 'purple'
                        })
                    })
                })],
                'MultiPolygon': [new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'yellow',
                        width: 1
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgba(255, 255, 0, 0.1)'
                    })
                })],
                'Polygon': [new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'blue',
                        lineDash: [4],
                        width: 3
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgba(0, 0, 255, 0.1)'
                    })
                })],
                'GeometryCollection': [new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'magenta',
                        width: 2
                    }),
                    fill: new ol.style.Fill({
                        color: 'magenta'
                    }),
                    image: new ol.style.Circle({
                        radius: 10, // pixels
                        fill: null,
                        stroke: new ol.style.Stroke({
                            color: 'magenta'
                        })
                    })
                })],
                'Circle': [new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'red',
                        width: 2
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgba(255,0,0,0.2)'
                    })
                })]
            };

            var styleFunction = function(feature, resolution) {
                var geo = feature.getGeometry();
                // always assign a style to prevent feature skipping
                return geo ? styles[geo.getType()] : styles['Point'];
            };

            var vectorSource = new ol.source.Vector({
                format: new ol.format.GeoJSON(),
                url:    vectorDataUrl
            });

            var theCircle = new ol.Feature(new ol.geom.Circle([5e6, 7e6, 5e5], 1e6));

            // Add a Cesium rectangle, via setting the property olcs.polygon_kind
            var cartographicRectangleStyle = new ol.style.Style({
                fill: new ol.style.Fill({
                    color: 'rgba(255, 69, 0, 0.7)'
                }),
                stroke: new ol.style.Stroke({
                    color: 'rgba(255, 69, 0, 0.9)',
                    width: 1
                })
            });
            var cartographicRectangleGeometry = new ol.geom.Polygon([[[-5e6, 11e6],
                [4e6, 11e6], [4e6, 10.5e6], [-5e6, 10.5e6], [-5e6, 11e6]]]);
            cartographicRectangleGeometry.set('olcs.polygon_kind', 'rectangle');
            var cartographicRectangle = new ol.Feature({
                geometry: cartographicRectangleGeometry
            });
            cartographicRectangle.setStyle(cartographicRectangleStyle);

            // Add two Cesium rectangles with height and the property olcs.polygon_kind
            var cartographicRectangleGeometry2 = new ol.geom.MultiPolygon([
                [[
                    [-5e6, 12e6, 0], [4e6, 12e6, 0], [4e6, 11.5e6, 0], [-5e6, 11.5e6, 0],
                    [-5e6, 12e6, 0]
                ]],
                [[
                    [-5e6, 11.5e6, 1e6], [4e6, 11.5e6, 1e6], [4e6, 11e6, 1e6],
                    [-5e6, 11e6, 1e6], [-5e6, 11.5e6, 1e6]
                ]]
            ]);
            cartographicRectangleGeometry2.set('olcs.polygon_kind', 'rectangle');
            var cartographicRectangle2 = new ol.Feature({
                geometry: cartographicRectangleGeometry2
            });
            cartographicRectangle2.setStyle(cartographicRectangleStyle);

            var vectorLayer = new ol.layer.Vector({
                source: vectorSource,
                style: styleFunction
            });

            var vectorSource2 = new ol.source.Vector({
                features: [iconFeature, textFeature, cervinFeature, cartographicRectangle, cartographicRectangle2]
            });
            var vectorLayer2 = new ol.layer.Image({
                source: new ol.source.ImageVector({
                    source: vectorSource2
                })
            });


            // // // map.addInteraction()
            // map.addLayer(new ol.layer.Tile({
            //     source: new ol.source.OSM({url: 'https://{a-c}.tile.openstreetmap.org/{z}/{x}/{y}.png'})
            // }));

            // map.addInteraction()
            map.addLayer(new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    // projection: 'EPSG:4326',
                    url:    'http://osm-demo.wheregroup.com/service',
                    params: {
                        'LAYERS': 'osm',
                        'TILED':  true
                    }
                })
            }));

            map.addLayer(vectorLayer);
            map.addLayer(vectorLayer2);
            //

            var vectorSource = new ol.source.Vector({
                format: new ol.format.GeoJSON(),
                url: vectorDataUrl
            });

            var featureCollectionLayer1 = new ol.layer.Vector({
                source: new ol.source.Vector({
                    url: webPath + 'data/featureCollection5.geo.json',
                    format: new ol.format.GeoJSON({
                        // Feautre own projection
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

            map.addLayer(featureCollectionLayer1);

            var scene = ol3d.getCesiumScene();
            // scene.terrainProvider = new Cesium.CesiumTerrainProvider();
            scene.globe.enableLighting = true;
            ol3d.setEnabled(options.enable3DByDefault);

            vectorSource.addFeature(theCircle);


            element.append($('<div class="button-navigation"/>')
                .append($('<a class="button">Toggle style</a>').on('click', function() {
                    var swap = theCircle.getStyle();
                    theCircle.setStyle(oldStyle);
                    oldStyle = swap;
                }))
                .append($('<a class="button">Toggle clamp to ground</a>').on('click', function() {
                    var altitudeMode;
                    if(!vectorLayer.get('altitudeMode')) {
                        altitudeMode = 'clampToGround';
                    }
                    vectorLayer.set('altitudeMode', altitudeMode);
                    vectorLayer2.set('altitudeMode', altitudeMode);
                    map.removeLayer(vectorLayer);
                    map.removeLayer(vectorLayer2);
                    map.addLayer(vectorLayer);
                    map.addLayer(vectorLayer2);
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
                .append($('<a class="button">3D (enabled)</a>').on('click', function() {
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
        }
    });
})(jQuery);