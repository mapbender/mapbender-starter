(function($) {
    $.widget("mapbender.mbOlCesiumMap", {

        /** Element API URL */
        elementUrl: null,

        /** OpenLayers 4 / cesium map element */
        olMapElement: null,

        options: {
            fps: 60,

            // Show Cesium by default?
            ceMap: true,

            // Show OpenLayers by default?
            olMap: true
        },

        toggle3D: function() {
            var widget = this;
            var ol3d = widget.olMapElement.data('olCesiumMap');
            widget.enable3D(!ol3d.getEnabled());
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
        },

        updateMapContainerGeometries: function() {
            var widget = this;
            var height = $(window).height();
            // var width = $(window).width();
            // var options = widget.options;

            widget.olMapElement.css({height: height});
        },

        _create: function() {
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

            var map = new ol.Map({
                layers:   [],
                target:   olMapElement[0],
                controls: ol.control.defaults({
                    attributionOptions: /** @type {olx.control.AttributionOptions} */ ({
                        collapsible: false
                    })
                }),
                view:     new ol.View({
                    center: [0, 0],
                    zoom:   2
                })
            });
            olMapElement.data('olMap', map);

            var ol3d = new olcs.OLCesium({
                map:    map
            });

            olMapElement.data('olCesiumMap', ol3d);

            // EXAMPLES

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

            var textStyle = [new ol.style.Style({
                text: new ol.style.Text({
                    text: 'Only text',
                    textAlign: 'center',
                    textBaseline: 'middle',
                    stroke: new ol.style.Stroke({
                        color: 'red',
                        width: 3
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgba(0, 0, 155, 0.3)'
                    })
                })
            }), new ol.style.Style({
                geometry: new ol.geom.Circle([1000000, 3000000, 10000], 2e6),
                stroke: new ol.style.Stroke({
                    color: 'blue',
                    width: 2
                }),
                fill:new ol.style.Fill({
                    color: 'rgba(0, 0, 255, 0.2)'
                })
            })];

            iconFeature.setStyle(iconStyle);

            textFeature.setStyle(textStyle);

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
                features: [iconFeature, textFeature, cervinFeature, cartographicRectangle,
                    cartographicRectangle2]
            });
            var imageVectorSource = new ol.source.ImageVector({
                source: vectorSource2
            });
            var vectorLayer2 = new ol.layer.Image({
                source: imageVectorSource
            });



            // map.addInteraction()
            map.addLayer(new ol.layer.Tile({
                source: new ol.source.OSM()
            }));
            map.addLayer(vectorLayer);
            map.addLayer(vectorLayer2);

            // var dragAndDropInteraction = new ol.interaction.DragAndDrop({
            //     formatConstructors: [
            //         ol.format.GPX,
            //         ol.format.GeoJSON,
            //         ol.format.IGC,
            //         ol.format.KML,
            //         ol.format.TopoJSON
            //     ]
            // });
            //
            // map.addInteraction(ol.interaction.defaults().extend([dragAndDropInteraction]));
            // dragAndDropInteraction.on('addfeatures', function(event) {
            //     var vectorSource = new ol.source.Vector({
            //         features: event.features,
            //         projection: event.projection
            //     });
            //     map.getLayers().push(new ol.layer.Vector({
            //         source: vectorSource,
            //         style: styleFunction
            //     }));
            //     var view = map.getView();
            //     view.fitExtent(
            //         vectorSource.getExtent(), /** @type {ol.Size} */ (map.getSize()));
            // });



            var scene = ol3d.getCesiumScene();
            var terrainProvider = new Cesium.CesiumTerrainProvider({
                url: '//assets.agi.com/stk-terrain/world',
                requestVertexNormals: true
            });


            scene.terrainProvider = terrainProvider;
            scene.globe.enableLighting = true;
            ol3d.setEnabled(true);

            var csLabels = new Cesium.LabelCollection();
            csLabels.add({
                position: Cesium.Cartesian3.fromRadians(20, 20, 0),
                text: 'Pre-existing primitive'
            });
            scene.primitives.add(csLabels);

            // Adding a feature after the layer has been synchronized.
            vectorSource.addFeature(theCircle);

            var hasTheVectorLayer = true;
            function addOrRemoveOneVectorLayer() {
                if (hasTheVectorLayer) {
                    map.getLayers().remove(vectorLayer);
                } else {
                    map.getLayers().insertAt(1, vectorLayer);
                }
                hasTheVectorLayer = !hasTheVectorLayer;
            }

            function addOrRemoveOneFeature() {
                var found = vectorSource2.getFeatures().indexOf(iconFeature);
                if (found === -1) {
                    vectorSource2.addFeature(iconFeature);
                } else {
                    vectorSource2.removeFeature(iconFeature);
                }
            }

            var oldStyle = new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'blue',
                    width: 2
                }),
                fill: new ol.style.Fill({
                    color: 'green'
                })
            });

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
                .append($('<a class="button">Feature X</a>').on('click', function() {
                }))
                .append($('<a class="button">Disable 3D</a>').on('click', function() {
                    var button = $(this)
                    widget.toggle3D();

                    if(widget.is3DEnabled()){
                        button.text('Disable 3D');
                    }else{
                        button.text('Enable 3D');
                    }
                }))
            );

            // setTargetFrameRate
            // ol3d.setTargetFrameRate(options.fps);
            ol3d.enableAutoRenderLoop();
        }
    });
})(jQuery);