/**
 * @name Chart MarkerClusterer for Google Maps v3
 * @version version 1.0
 * @author Hassan Mughal (v3 author: Luke Mahe, v2 author: Xiaoxi Wu)
 * @fileoverview
 * The library creates and manages chart based per-zoom-level clusters for large amounts of
 * markers.
 * <br/>
 * This library is derived from
 * <a href="http://gmaps-utility-library-dev.googlecode.com/svn/tags/markerclusterer/"
 * >v2 MarkerClusterer</a>.
 */

/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


/**
 * A Marker Clusterer that clusters markers.
 *
 * @param {google.maps.Map} map The Google map to attach to.
 * @param {Array.<google.maps.Marker>=} opt_markers Optional markers to add to
 *   the cluster.
 * @param {Object=} opt_options support the following options:
 *     'gridSize': (number) The grid size of a cluster in pixels.
 *     'maxZoom': (number) The maximum zoom level that a marker can be part of a
 *                cluster.
 *     'zoomOnClick': (boolean) Whether the default behaviour of clicking on a
 *                    cluster is to zoom into it.
 *     'averageCenter': (boolean) Wether the center of each cluster should be
 *                      the average of all markers in the cluster.
 *     'minimumClusterSize': (number) The minimum number of markers to be in a
 *                           cluster before the markers are hidden and a count
 *                           is shown.
 *     'styles': (object) An object that has style properties:
 *       'url': (string) The image url.
 *       'height': (number) The image height.
 *       'width': (number) The image width.
 *       'anchor': (Array) The anchor position of the label text.
 *       'textColor': (string) The text color.
 *       'textSize': (number) The text size.
 *       'backgroundPosition': (string) The position of the backgound x, y.
 * @constructor
 * @extends google.maps.OverlayView
 */

stArr =[];
    for (var i = 53, len; len < 250; i++) {
        stArr.push(i);
    }

function MarkerClusterer(map, opt_markers, opt_options, maxId) {
    // MarkerClusterer implements google.maps.OverlayView interface. We use the
    // extend function to extend MarkerClusterer with google.maps.OverlayView
    // because it might not always be available when the code is defined so we
    // look for it at the last possible moment. If it doesn't exist now then
    // there is no point going ahead :)
    this.extend(MarkerClusterer, google.maps.OverlayView);
    this.map_ = map;

    /**
     * @type {Array.<google.maps.Marker>}
     * @private
     */
    this.markers_ = [];
    this.maxIded_ = maxId;
    /**
     *  @type {Array.<Cluster>}
     */
    this.clusters_ = [];

    //this.sizes = [53, 56, 66, 78, 90];
    /*this.sizes =[];
    for (var i = 53, len; len = 250; i++) {
        this.sizes.push(i);
    }*/

    this.sizes = [53,60,68,77,87,98,110,123,137,152,168,185,203,222,242,261];


    // this.sizes = stArr;

    /**
     * @private
     */
    this.styles_ = [];

    /**
     * @type {boolean}
     * @private
     */
    this.ready_ = false;

    var options = opt_options || {};

    /**
     * @private
     */
    this.legend_ = options['legend'] || {};

    /**
     * @type {number}
     * @private
     */
    this.gridSize_ = options['gridSize'] || 60;

    /**
     * @private
     */
    this.minClusterSize_ = options['minimumClusterSize'] || 3;


    /**
     * @type {?number}
     * @private
     */
    this.maxZoom_ = options['maxZoom'] || null;

    this.styles_ = options['styles'] || [];

    /**
     * @type {string}
     * @private
     */
    this.imagePath_ = options['imagePath'] ||
        this.MARKER_CLUSTER_IMAGE_PATH_;

    /**
     * @type {string}
     * @private
     */
    this.imageExtension_ = options['imageExtension'] ||
        this.MARKER_CLUSTER_IMAGE_EXTENSION_;

    /**
     * @type {boolean}
     * @private
     */
    this.zoomOnClick_ = true;

    if (options['zoomOnClick'] != undefined) {
        this.zoomOnClick_ = options['zoomOnClick'];
    }

    /**
     * @type {boolean}
     * @private
     */
    this.averageCenter_ = false;

    if (options['averageCenter'] != undefined) {
        this.averageCenter_ = options['averageCenter'];
    }

    this.setupStyles_();

    if (opt_markers && opt_markers.length) {
        this.setupLegend_(opt_markers);
    }
    //console.log(opt_markers);
    this.setMap(map);

    /**
     * @type {number}
     * @private
     */
    this.prevZoom_ = this.map_.getZoom();

    // Add the map event listeners
    var that = this;
    google.maps.event.addListener(this.map_, 'zoom_changed', function () {
        var zoom = that.map_.getZoom();

        if (that.prevZoom_ != zoom) {
            that.prevZoom_ = zoom;
            that.resetViewport();
        }
    });

    google.maps.event.addListener(this.map_, 'idle', function () {
        that.redraw();
    });

    // Finally, add the markers
    if (opt_markers && opt_markers.length) {
        this.addMarkers(opt_markers, false);
    }
}


/**
 * The marker cluster image path.
 *
 * @type {string}
 * @private
 */
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_PATH_ =
    'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/' +
    'images/m';


/**
 * The marker cluster image path.
 *
 * @type {string}
 * @private
 */
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_EXTENSION_ = 'png';


/**
 * Extends a objects prototype by anothers.
 *
 * @param {Object} obj1 The object to be extended.
 * @param {Object} obj2 The object to extend with.
 * @return {Object} The new extended object.
 * @ignore
 */
MarkerClusterer.prototype.extend = function (obj1, obj2) {
    return (function (object) {
        for (var property in object.prototype) {
            this.prototype[property] = object.prototype[property];
        }
        return this;
    }).apply(obj1, [obj2]);
};


/**
 * Implementaion of the interface method.
 * @ignore
 */
MarkerClusterer.prototype.onAdd = function () {
    this.setReady_(true);
};

/**
 * Implementaion of the interface method.
 * @ignore
 */
MarkerClusterer.prototype.draw = function () {
};

/**
 * Sets up the styles object.
 *
 * @private
 */
MarkerClusterer.prototype.setupStyles_ = function () {
    if (this.styles_.length) {
        return;
    }

    for (var i = 0, size; size = this.sizes[i]; i++) {
        this.styles_.push({
            url: this.imagePath_ + (i + 1) + '.' + this.imageExtension_,
            height: size,
            width: size
        });
        //console.log(this.imagePath_ + (i + 1) + '.' + this.imageExtension_);
    }
};


MarkerClusterer.prototype.setupLegend_ = function (markers) {

    var colorSeries = ["#3366cc", "#dc3912", "#ff9900", "#109618", "#990099", "#0099c6", "#dd4477", "#66aa00",
        "#b82e2e", "#316395", "#994499", "#22aa99", "#aaaa11", "#6633cc", "#e67300", "#8b0707", "#651067",
        "#329262", "#5574a6", "#3b3eac", "#b77322", "#16d620", "#b91383", "#f4359e", "#9c5935", "#a9c413",
        "#2a778d", "#668d1c", "#bea413", "#0c5922", "#743411"];
    var peopleSVG='M374.07,326.221c-60.435-21.791-79.744-40.18-79.744-79.543c0-4.342,0.625-7.617,1.691-10.352            c4.743-12.161,18.245-13.512,24.857-48.836c3.365-17.954,19.663-0.292,22.779-41.27c0-16.328-8.897-20.392-8.897-20.392            s4.52-24.173,6.292-42.771C343.246,59.881,327.517,0,243.638,0c-0.362,0-0.704,0.02-1.058,0.025            C242.223,0.019,241.882,0,241.516,0c-83.878,0-99.604,59.881-97.409,83.057c1.771,18.598,6.294,42.771,6.294,42.771            s-8.897,4.063-8.897,20.392c3.12,40.978,19.417,23.315,22.779,41.27c6.613,35.324,20.119,36.675,24.86,48.836            c1.064,2.734,1.689,6.01,1.689,10.352c0,39.363-19.313,57.752-79.747,79.543c-60.62,21.859-87.884,44.15-87.884,59.354            c0,15.182,0,99.583,0,99.583h219.227h0.302h219.227c0,0,0-84.401,0-99.583C461.957,370.371,434.697,348.08,374.07,326.221z';

    var buildingSVG='M344.046,298.535c0,0-3.254,0-4.338,0c-2.375,0-2.375-2.811-2.375-2.811V99.094c0-4.95-4.051-9-9-9c0,0-52.219,0-69.625,0            c-2.5,0-2.367-2.37-2.367-2.37V49.818c0-0.375,0.211-0.796,1.455-0.796c2.75,0,5-2.25,5-5v-8.881c0-2.75-2.25-5-5-5H96.13            c-2.75,0-5,2.25-5,5v8.881c0,2.75,2.25,5,5,5c1.453,0,1.456,1.302,1.456,1.453V86.85c0,0,0.247,3.245-1.878,3.245            c-18.799,0-75.193,0-75.193,0c-4.95,0-9,4.05-9,9c0,0,0,147.941,0,197.255c0,1.875-1.016,2.186-3.068,2.186            c-2.654,0-3.447,0-3.447,0c-2.75,0-5,2.25-5,5v10.369c0,2.75,2.25,5,5,5h339.046c2.75,0,5-2.25,5-5v-10.369            C349.046,300.785,346.796,298.535,344.046,298.535z M196.075,133.209h29.924c2.201,0,4,1.8,4,4v29.925c0,2.2-1.799,4-4,4h-29.924            c-2.201,0-4-1.8-4-4v-29.925C192.075,135.009,193.874,133.209,196.075,133.209z M192.075,114.647V84.722c0-2.2,1.799-4,4-4h29.924            c2.201,0,4,1.8,4,4v29.925c0,2.2-1.799,4-4,4h-29.924C193.874,118.647,192.075,116.847,192.075,114.647z M196.075,183.846h29.924            c2.201,0,4,1.8,4,4v29.925c0,2.2-1.799,4-4,4h-29.924c-2.201,0-4-1.8-4-4v-29.925            C192.075,185.647,193.874,183.846,196.075,183.846z M200.516,244.008c2.199,0,4,1.8,4,4v46.335c0,2.2-1.801,4-4,4h-46.335            c-2.199,0-4-1.8-4-4v-46.335c0-2.2,1.801-4,4-4H200.516z M161.903,114.647c0,2.2-1.8,4-4,4h-29.925c-2.2,0-4-1.8-4-4V84.722            c0-2.2,1.8-4,4-4h29.925c2.2,0,4,1.8,4,4V114.647z M127.978,133.209h29.925c2.2,0,4,1.8,4,4v29.925c0,2.2-1.8,4-4,4h-29.925            c-2.2,0-4-1.8-4-4v-29.925C123.978,135.009,125.778,133.209,127.978,133.209z M127.978,183.846h29.925c2.2,0,4,1.8,4,4v29.925            c0,2.2-1.8,4-4,4h-29.925c-2.2,0-4-1.8-4-4v-29.925C123.978,185.647,125.778,183.846,127.978,183.846z M268.122,136.284            c0-2.2,1.801-4,4-4h29.926c2.199,0,4,1.8,4,4v29.925c0,2.2-1.801,4-4,4h-29.926c-2.199,0-4-1.8-4-4V136.284z M268.122,188.772            c0-2.2,1.801-4,4-4h29.926c2.199,0,4,1.8,4,4v29.925c0,2.2-1.801,4-4,4h-29.926c-2.199,0-4-1.8-4-4V188.772z M268.122,239.408            c0-2.2,1.801-4,4-4h29.926c2.199,0,4,1.8,4,4v29.925c0,2.2-1.801,4-4,4h-29.926c-2.199,0-4-1.8-4-4V239.408z M42.8,136.284            c0-2.2,1.8-4,4-4h29.925c2.2,0,4,1.8,4,4v29.925c0,2.2-1.8,4-4,4H46.8c-2.2,0-4-1.8-4-4V136.284z M42.8,188.772c0-2.2,1.8-4,4-4            h29.925c2.2,0,4,1.8,4,4v29.925c0,2.2-1.8,4-4,4H46.8c-2.2,0-4-1.8-4-4V188.772z M42.8,239.408c0-2.2,1.8-4,4-4h29.925            c2.2,0,4,1.8,4,4v29.925c0,2.2-1.8,4-4,4H46.8c-2.2,0-4-1.8-4-4V239.408z';
 
    var markerSymbol = {
        path: 'M256 14.316c-91.31 0-165.325 74.025-165.325 165.325 0.010 91.32 165.325 318.044 165.325 318.044s165.315-226.724 165.315-318.034c0.010-91.31-73.984-165.335-165.315-165.335zM256 245.494c-34.56 0-62.608-28.078-62.608-62.648 0-34.55 28.037-62.566 62.608-62.566 34.591 0 62.618 28.027 62.618 62.566 0 34.57-28.017 62.649-62.618 62.649z',
        fillOpacity: 1,
        scale: 0.07,
        //strokeColor: 'white',
        //strokeWeight: 1,
        title: "THOMAS",
        anchor: new google.maps.Point(250, 500),
        //icon: 'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/images/m5.png';
    };

    //Check for user defined legend
    for (var key in this.legend_) {
        if (this.legend_.hasOwnProperty(key)) {
            var index = colorSeries.indexOf(this.legend_[key]);
            if (index > -1) {
                colorSeries.splice(index, 1);
            }
        }
    }

    var colorIndex = 0;
    var minSizeAcceptedPeople=0.01;
    var maxSizeAcceptedPeople=0.09; 
    var minSizeAcceptedBuilding=0.03;
    var maxSizeAcceptedBuilding=0.1;    
    var maxShares=this.maxIded_;

    for (var i = 0, marker; marker = markers[i]; i++) {
        //Math.sqrt(marker.shares))*100;
        //console.log(Math.max.apply(Math,markers.));
        peopleScale=((marker.shares/maxShares)*0.1*2);
        buildingScale=((marker.shares/maxShares)*0.12*2);

        //markerSymbol["title"]=marker.shares;

        if (!(marker.title in this.legend_)) {
            this.legend_[marker.title] = (colorSeries[colorIndex]);
            if(marker.title=='Retail')
            {
                markerSymbol["path"] = peopleSVG;
            }
            else if(marker.title=="Institutions")
            {
                markerSymbol["path"] = buildingSVG;
            }
            markerSymbol["fillColor"] = (colorSeries[colorIndex]);
            marker.setIcon(markerSymbol);
            colorIndex++;
        }
        else {
            markerSymbol["fillColor"] = this.legend_[marker.title];
            if(marker.title=='Retail')
            {
                if(peopleScale<0.02)
                {
                    peopleScale=0.02;
                }
                //console.log((marker.shares/maxShares)*0.07+ ": "+marker.shares+" "+maxShares);
                markerSymbol["path"] = peopleSVG;
                markerSymbol["scale"] = peopleScale;
            }
            else if(marker.title=="Institutions")
            {
                if(buildingScale<0.03)
                {
                    buildingScale=0.03;
                }                
                //console.log((marker.shares/maxShares)*0.07+ ": "+marker.shares+" "+maxShares);                
                markerSymbol["path"] = buildingSVG;
                markerSymbol["scale"] = buildingScale;
            }            
            marker.setIcon(markerSymbol);
        }
    }

    var legend_div = document.createElement('DIV');
    legend_div.style.cssText = "margin-right: 5px; background-color: rgba(255, 255, 255, 0.9); padding: 10px; width: 123px";
    this.map_.controls[google.maps.ControlPosition.RIGHT_TOP].push(legend_div);

    for (var title in this.legend_) {
        var color = this.legend_[title];
        var color_div = document.createElement('div');
        color_div.style.cssText = "float: left; margin:0; overflow:hidden; background-color:" + color + "; width: 12px; height: 12px;";
        legend_div.appendChild(color_div);

        var title_div = document.createElement('div');
        title_div.innerHTML = title;

        title_div.style.cssText = "padding-bottom: 5px; padding-left: 5%; float: left; margin-left:0; width:80%; overflow:hidden;";
        legend_div.appendChild(title_div);


    }
    //var panes = this.getPanes();
    //panes.overlayMouseTarget.appendChild(this.div_);
     //console.log(panes);

};
/**
 *  Fit the map to the bounds of the markers in the clusterer.
 */
MarkerClusterer.prototype.fitMapToMarkers = function () {
    var markers = this.getMarkers();
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0, marker; marker = markers[i]; i++) {
        bounds.extend(marker.getPosition());
    }

    this.map_.fitBounds(bounds);
};


/**
 *  Sets the styles.
 *
 *  @param {Object} styles The style to set.
 */
MarkerClusterer.prototype.setStyles = function (styles) {
    this.styles_ = styles;
};


/**
 *  Gets the styles.
 *
 *  @return {Object} The styles object.
 */
MarkerClusterer.prototype.getStyles = function () {
    return this.styles_;
};

/**
 *  Sets the Legend.
 *
 *  @param {Object} styles The legend to set.
 */
MarkerClusterer.prototype.setLegend = function (legend) {
    this.legend_ = legend;
};


/**
 *  Gets the Legend.
 *
 *  @return {Object} The legend object.
 */
MarkerClusterer.prototype.getLegend = function () {
    return this.legend_;
};


/**
 * Whether zoom on click is set.
 *
 * @return {boolean} True if zoomOnClick_ is set.
 */
MarkerClusterer.prototype.isZoomOnClick = function () {
    return this.zoomOnClick_;
};

/**
 * Whether average center is set.
 *
 * @return {boolean} True if averageCenter_ is set.
 */
MarkerClusterer.prototype.isAverageCenter = function () {
    return this.averageCenter_;
};


/**
 *  Returns the array of markers in the clusterer.
 *
 *  @return {Array.<google.maps.Marker>} The markers.
 */
MarkerClusterer.prototype.getMarkers = function () {
    return this.markers_;
};


/**
 *  Returns the number of markers in the clusterer
 *
 *  @return {Number} The number of markers.
 */
MarkerClusterer.prototype.getTotalMarkers = function () {
    return this.markers_.length;
};


/**
 *  Sets the max zoom for the clusterer.
 *
 *  @param {number} maxZoom The max zoom level.
 */
MarkerClusterer.prototype.setMaxZoom = function (maxZoom) {
    this.maxZoom_ = maxZoom;
};


/**
 *  Gets the max zoom for the clusterer.
 *
 *  @return {number} The max zoom level.
 */
MarkerClusterer.prototype.getMaxZoom = function () {
    return this.maxZoom_;
};


/**
 *  The function for calculating the cluster icon image.
 *
 *  @param {Array.<google.maps.Marker>} markers The markers in the clusterer.
 *  @param {number} numStyles The number of styles available.
 *  @return {Object} A object properties: 'text' (string) and 'index' (number).
 *  @private
 */

/*
while (dv !== 0) {
    console.log(dv);
    dv = parseInt(dv / 10, 10);
}
*/
 //console.log(parseInt(260, 10));
 
MarkerClusterer.prototype.calculator_ = function (markers, numStyles) {
    //console.log("Thomas");
    var index = 0;
    var count = markers.length;
    var totShares=0;
     
    for (var i = 0; i < count; i++) {
        totShares=totShares+Number(markers[i].shares);
        var dv = totShares;
    }
      
    // console.log(totShares);

    //console.log(dv);

    while (dv !== 0) {
        // console.log(dv);
        //Calculated based on the number of digit Integer has...
        dv = parseInt(dv / 10, 10);
        // console.log(dv);

        index++;
        
    }
    

    //index=dv.toString().length;
    //console.log(markers[i]);

    index = Math.min(index, numStyles);
     //console.log(totShares+" "+index);
    return {
        text: count,
        index: index
    };
};


/**
 * Set the calculator function.
 *
 * @param {function(Array, number)} calculator The function to set as the
 *     calculator. The function should return a object properties:
 *     'text' (string) and 'index' (number).
 *
 */
MarkerClusterer.prototype.setCalculator = function (calculator) {
    this.calculator_ = calculator;
};


/**
 * Get the calculator function.
 *
 * @return {function(Array, number)} the calculator function.
 */
MarkerClusterer.prototype.getCalculator = function () {
    return this.calculator_;
};


/**
 * Add an array of markers to the clusterer.
 *
 * @param {Array.<google.maps.Marker>} markers The markers to add.
 * @param {boolean=} opt_nodraw Whether to redraw the clusters.
 */
MarkerClusterer.prototype.addMarkers = function (markers, opt_nodraw) {
    for (var i = 0, marker; marker = markers[i]; i++) {
        this.pushMarkerTo_(marker);
    }
    if (!opt_nodraw) {
        this.redraw();
    }
};


/**
 * Pushes a marker to the clusterer.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @private
 */
MarkerClusterer.prototype.pushMarkerTo_ = function (marker) {
    marker.isAdded = false;
    if (marker['draggable']) {
        // If the marker is draggable add a listener so we update the clusters on
        // the drag end.
        var that = this;
        google.maps.event.addListener(marker, 'dragend', function () {
            marker.isAdded = false;
            that.repaint();
        });
    }
    this.markers_.push(marker);
};


/**
 * Adds a marker to the clusterer and redraws if needed.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @param {boolean=} opt_nodraw Whether to redraw the clusters.
 */
MarkerClusterer.prototype.addMarker = function (marker, opt_nodraw) { //Hassan
    this.pushMarkerTo_(marker);
    if (!opt_nodraw) {
        this.redraw();
    }
};


/**
 * Removes a marker and returns true if removed, false if not
 *
 * @param {google.maps.Marker} marker The marker to remove
 * @return {boolean} Whether the marker was removed or not
 * @private
 */
MarkerClusterer.prototype.removeMarker_ = function (marker) {
    var index = -1;
    if (this.markers_.indexOf) {
        index = this.markers_.indexOf(marker);
    }
    else 
    {
        for (var i = 0, m; m = this.markers_[i]; i++) {
            if (m == marker) {
                index = i;
                break;
            }
        }
    }

    if (index == -1) {
        // Marker is not in our list of markers.
        return false;
    }

    marker.setMap(null);

    this.markers_.splice(index, 1);

    return true;
};


/**
 * Remove a marker from the cluster.
 *
 * @param {google.maps.Marker} marker The marker to remove.
 * @param {boolean=} opt_nodraw Optional boolean to force no redraw.
 * @return {boolean} True if the marker was removed.
 */
MarkerClusterer.prototype.removeMarker = function (marker, opt_nodraw) {
    var removed = this.removeMarker_(marker);

    if (!opt_nodraw && removed) {
        this.resetViewport();
        this.redraw();
        return true;
    } else {
        return false;
    }
};


/**
 * Removes an array of markers from the cluster.
 *
 * @param {Array.<google.maps.Marker>} markers The markers to remove.
 * @param {boolean=} opt_nodraw Optional boolean to force no redraw.
 */
MarkerClusterer.prototype.removeMarkers = function (markers, opt_nodraw) {
    var removed = false;

    for (var i = 0, marker; marker = markers[i]; i++) {
        var r = this.removeMarker_(marker);
        removed = removed || r;
    }

    if (!opt_nodraw && removed) {
        this.resetViewport();
        this.redraw();
        return true;
    }
};


/**
 * Sets the clusterer's ready state.
 *
 * @param {boolean} ready The state.
 * @private
 */
MarkerClusterer.prototype.setReady_ = function (ready) {
    if (!this.ready_) {
        this.ready_ = ready;
        this.createClusters_();
    }
};


/**
 * Returns the number of clusters in the clusterer.
 *
 * @return {number} The number of clusters.
 */
MarkerClusterer.prototype.getTotalClusters = function () {
    return this.clusters_.length;
};


/**
 * Returns the google map that the clusterer is associated with.
 *
 * @return {google.maps.Map} The map.
 */
MarkerClusterer.prototype.getMap = function () {
    return this.map_;
};


/**
 * Sets the google map that the clusterer is associated with.
 *
 * @param {google.maps.Map} map The map.
 */
MarkerClusterer.prototype.setMap = function (map) {
    this.map_ = map;
};


/**
 * Returns the size of the grid.
 *
 * @return {number} The grid size.
 */
MarkerClusterer.prototype.getGridSize = function () {
    return this.gridSize_;
};


/**
 * Sets the size of the grid.
 *
 * @param {number} size The grid size.
 */
MarkerClusterer.prototype.setGridSize = function (size) {
    this.gridSize_ = size;
};


/**
 * Returns the min cluster size.
 *
 * @return {number} The grid size.
 */
MarkerClusterer.prototype.getMinClusterSize = function () {
    return this.minClusterSize_;
};

/**
 * Sets the min cluster size.
 *
 * @param {number} size The grid size.
 */
MarkerClusterer.prototype.setMinClusterSize = function (size) {
    this.minClusterSize_ = size;
};


/**
 * Extends a bounds object by the grid size.
 *
 * @param {google.maps.LatLngBounds} bounds The bounds to extend.
 * @return {google.maps.LatLngBounds} The extended bounds.
 */
MarkerClusterer.prototype.getExtendedBounds = function (bounds) {
    var projection = this.getProjection();

    // Turn the bounds into latlng.
    var tr = new google.maps.LatLng(bounds.getNorthEast().lat(),
        bounds.getNorthEast().lng());
    var bl = new google.maps.LatLng(bounds.getSouthWest().lat(),
        bounds.getSouthWest().lng());

    // Convert the points to pixels and the extend out by the grid size.
    var trPix = projection.fromLatLngToDivPixel(tr);
    trPix.x += this.gridSize_;
    trPix.y -= this.gridSize_;

    var blPix = projection.fromLatLngToDivPixel(bl);
    blPix.x -= this.gridSize_;
    blPix.y += this.gridSize_;

    // Convert the pixel points back to LatLng
    var ne = projection.fromDivPixelToLatLng(trPix);
    var sw = projection.fromDivPixelToLatLng(blPix);

    // Extend the bounds to contain the new bounds.
    bounds.extend(ne);
    bounds.extend(sw);

    return bounds;
};


/**
 * Determins if a marker is contained in a bounds.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @param {google.maps.LatLngBounds} bounds The bounds to check against.
 * @return {boolean} True if the marker is in the bounds.
 * @private
 */
MarkerClusterer.prototype.isMarkerInBounds_ = function (marker, bounds) {
    return bounds.contains(marker.getPosition());
};


/**
 * Clears all clusters and markers from the clusterer.
 */
MarkerClusterer.prototype.clearMarkers = function () {
    this.resetViewport(true);

    // Set the markers a empty array.
    this.markers_ = [];
};


/**
 * Clears all existing clusters and recreates them.
 * @param {boolean} opt_hide To also hide the marker.
 */
MarkerClusterer.prototype.resetViewport = function (opt_hide) {
    // Remove all the clusters
    for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
        cluster.remove();
    }

    // Reset the markers to not be added and to be invisible.
    for (var i = 0, marker; marker = this.markers_[i]; i++) {
        marker.isAdded = false;
        if (opt_hide) {
            marker.setMap(null);
        }
    }

    this.clusters_ = [];
};

/**
 *
 */
MarkerClusterer.prototype.repaint = function () {
    var oldClusters = this.clusters_.slice();
    this.clusters_.length = 0;
    this.resetViewport();
    this.redraw();

    // Remove the old clusters.
    // Do it in a timeout so the other clusters have been drawn first.
    window.setTimeout(function () {
        for (var i = 0, cluster; cluster = oldClusters[i]; i++) {
            cluster.remove();
        }
    }, 0);
};


/**
 * Redraws the clusters.
 */
MarkerClusterer.prototype.redraw = function () {
    this.createClusters_();
};


/**
 * Calculates the distance between two latlng locations in km.
 * @see http://www.movable-type.co.uk/scripts/latlong.html
 *
 * @param {google.maps.LatLng} p1 The first lat lng point.
 * @param {google.maps.LatLng} p2 The second lat lng point.
 * @return {number} The distance between the two points in km.
 * @private
 */
MarkerClusterer.prototype.distanceBetweenPoints_ = function (p1, p2) {
    if (!p1 || !p2) {
        return 0;
    }

    var R = 6371; // Radius of the Earth in km
    var dLat = (p2.lat() - p1.lat()) * Math.PI / 180;
    var dLon = (p2.lng() - p1.lng()) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return d;
};


/**
 * Add a marker to a cluster, or creates a new cluster.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @private
 */
MarkerClusterer.prototype.addToClosestCluster_ = function (marker) {
    var distance = 400000; // Some large number
    var clusterToAddTo = null;
    var pos = marker.getPosition();
    for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
        var center = cluster.getCenter();
        if (center) {
            var d = this.distanceBetweenPoints_(center, marker.getPosition());
            if (d < distance) {
                distance = d;
                clusterToAddTo = cluster;
            }
        }
    }

    if (clusterToAddTo && clusterToAddTo.isMarkerInClusterBounds(marker)) {
        clusterToAddTo.addMarker(marker);
    } else {
        var cluster = new Cluster(this);
        cluster.addMarker(marker);
        this.clusters_.push(cluster);
    }
};


/**
 * Creates the clusters.
 *
 * @private
 */
MarkerClusterer.prototype.createClusters_ = function () {
    if (!this.ready_) {
        return;
    }

    // Get our current map view bounds.
    // Create a new bounds object so we don't affect the map.
    var mapBounds = new google.maps.LatLngBounds(this.map_.getBounds().getSouthWest(),
        this.map_.getBounds().getNorthEast());
    var bounds = this.getExtendedBounds(mapBounds);

    for (var i = 0, marker; marker = this.markers_[i]; i++) {
        if (!marker.isAdded && this.isMarkerInBounds_(marker, bounds)) {
            this.addToClosestCluster_(marker);
        }
    }
};


/**
 * A cluster that contains markers.
 *
 * @param {MarkerClusterer} markerClusterer The markerclusterer that this
 *     cluster is associated with.
 * @constructor
 * @ignore
 */
function Cluster(markerClusterer) {
    this.markerClusterer_ = markerClusterer;
    this.map_ = markerClusterer.getMap();
    this.gridSize_ = markerClusterer.getGridSize();
    this.minClusterSize_ = markerClusterer.getMinClusterSize();
    this.averageCenter_ = markerClusterer.isAverageCenter();
    this.center_ = null;
    this.markers_ = [];
    this.bounds_ = null;

    this.legend_ = markerClusterer.getLegend();
    this.chartData_ = {};
    this.initializeChartData_();

    this.clusterIcon_ = new ClusterIcon(this, markerClusterer.getStyles(),
        markerClusterer.getGridSize());
}


/**
 * Initialize the chart slice values for the cluster chart
 */
Cluster.prototype.initializeChartData_ = function () {
    for (var key in this.legend_) {
        if (this.legend_.hasOwnProperty(key)) {
            this.chartData_[key] = this.legend_[key];
            this.chartData_[key] = 0;
        }
    }
};


Cluster.prototype.getChartData = function () {
    return this.chartData_;
};

/**
 * Determins if a marker is already added to the cluster.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @return {boolean} True if the marker is already added.
 */
Cluster.prototype.isMarkerAlreadyAdded = function (marker) {
    if (this.markers_.indexOf) {
        return this.markers_.indexOf(marker) != -1;
    } else {
        for (var i = 0, m; m = this.markers_[i]; i++) {
            if (m == marker) {
                return true;
            }
        }
    }
    return false;
};


/**
 * Add a marker the cluster.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @return {boolean} True if the marker was added.
 */
Cluster.prototype.addMarker = function (marker) {
    if (this.isMarkerAlreadyAdded(marker)) {
        return false;
    }

    if (!this.center_) {
        this.center_ = marker.getPosition();
        this.calculateBounds_();
    } else {
        if (this.averageCenter_) {
            var l = this.markers_.length + 1;
            var lat = (this.center_.lat() * (l - 1) + marker.getPosition().lat()) / l;
            var lng = (this.center_.lng() * (l - 1) + marker.getPosition().lng()) / l;
            this.center_ = new google.maps.LatLng(lat, lng);
            this.calculateBounds_();
        }
    }

    marker.isAdded = true;
    this.markers_.push(marker);

    //this.chartData_[marker.getTitle()]++; //Hassan
    var newTot=this.chartData_[marker.getTitle()]+Number(marker.shares);
    this.chartData_[marker.getTitle()]=newTot;
    //console.log(this.chartData_);

    var len = this.markers_.length;
    //console.log(len);
    if (len < this.minClusterSize_ && marker.getMap() != this.map_) {
        // Min cluster size not reached so show the marker.
        marker.setMap(this.map_);
    }

    if (len == this.minClusterSize_) {
        // Hide the markers that were showing.
        for (var i = 0; i < len; i++) {
            this.markers_[i].setMap(null);
        }
    }

    if (len >= this.minClusterSize_) {
        marker.setMap(null);
    }

    this.updateIcon();
    return true;
};


/**
 * Returns the marker clusterer that the cluster is associated with.
 *
 * @return {MarkerClusterer} The associated marker clusterer.
 */
Cluster.prototype.getMarkerClusterer = function () {
    return this.markerClusterer_;
};


/**
 * Returns the bounds of the cluster.
 *
 * @return {google.maps.LatLngBounds} the cluster bounds.
 */
Cluster.prototype.getBounds = function () {
    var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
    var markers = this.getMarkers();
    for (var i = 0, marker; marker = markers[i]; i++) {
        bounds.extend(marker.getPosition());
    }
    return bounds;
};


/**
 * Removes the cluster
 */
Cluster.prototype.remove = function () {
    this.clusterIcon_.remove();
    this.markers_.length = 0;
    delete this.markers_;
};


/**
 * Returns the center of the cluster.
 *
 * @return {number} The cluster center.
 */
Cluster.prototype.getSize = function () {
    return this.markers_.length;
};


/**
 * Returns the center of the cluster.
 *
 * @return {Array.<google.maps.Marker>} The cluster center.
 */
Cluster.prototype.getMarkers = function () {
    return this.markers_;
};


/**
 * Returns the center of the cluster.
 *
 * @return {google.maps.LatLng} The cluster center.
 */
Cluster.prototype.getCenter = function () {
    return this.center_;
};


/**
 * Calculated the extended bounds of the cluster with the grid.
 *
 * @private
 */
Cluster.prototype.calculateBounds_ = function () {
    var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
    this.bounds_ = this.markerClusterer_.getExtendedBounds(bounds);
};


/**
 * Determines if a marker lies in the clusters bounds.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @return {boolean} True if the marker lies in the bounds.
 */
Cluster.prototype.isMarkerInClusterBounds = function (marker) {
    return this.bounds_.contains(marker.getPosition());
};


/**
 * Returns the map that the cluster is associated with.
 *
 * @return {google.maps.Map} The map.
 */
Cluster.prototype.getMap = function () {
    return this.map_;
};


/**
 * Updates the cluster icon
 */
Cluster.prototype.updateIcon = function () {
    var zoom = this.map_.getZoom();
    var mz = this.markerClusterer_.getMaxZoom();

    if (mz && zoom > mz) {
        // The zoom is greater than our max zoom so show all the markers in cluster.
        for (var i = 0, marker; marker = this.markers_[i]; i++) {
            marker.setMap(this.map_);
        }
        return;
    }

    if (this.markers_.length < this.minClusterSize_ && zoom!==20) {
        // Min cluster size not yet reached.
        this.clusterIcon_.hide();
        return;
    }

    var numStyles = this.markerClusterer_.getStyles().length;
   // console.log(this.markers_[0].shares);
    var sums = this.markerClusterer_.getCalculator()(this.markers_, numStyles);

    this.clusterIcon_.setCenter(this.center_);
    this.clusterIcon_.setSums(sums);
    //console.log(sums);
    this.clusterIcon_.show();
};


/**
 * A cluster icon
 *
 * @param {Cluster} cluster The cluster to be associated with.
 * @param {Object} styles An object that has style properties:
 *     'url': (string) The image url.
 *     'height': (number) The image height.
 *     'width': (number) The image width.
 *     'anchor': (Array) The anchor position of the label text.
 *     'textColor': (string) The text color.
 *     'textSize': (number) The text size.
 *     'backgroundPosition: (string) The background postition x, y.
 * @param {number=} opt_padding Optional padding to apply to the cluster icon.
 * @constructor
 * @extends google.maps.OverlayView
 * @ignore
 */
function ClusterIcon(cluster, styles, opt_padding) {
    cluster.getMarkerClusterer().extend(ClusterIcon, google.maps.OverlayView);

    this.styles_ = styles;
    this.padding_ = opt_padding || 0;
    this.cluster_ = cluster;
    this.center_ = null;
    this.map_ = cluster.getMap();
    this.div_ = null;
    this.chart_div_ = null;
    this.sums_ = null;
    this.visible_ = false;

    this.setMap(this.map_);
}


/**
 * Triggers the clusterclick event and zoom's if the option is set.
 */
ClusterIcon.prototype.triggerClusterClick = function () {
    var markerClusterer = this.cluster_.getMarkerClusterer();

    // Trigger the clusterclick event.
    google.maps.event.trigger(markerClusterer, 'clusterclick', this.cluster_);
    var zoomT = this.map_.getZoom();
            console.log(zoomT);
    if (markerClusterer.isZoomOnClick() && zoomT<20) {
        // Zoom into the cluster.
        this.map_.fitBounds(this.cluster_.getBounds());
    }
};


/**
 * Adding the cluster icon to the dom.
 * @ignore
 */
ClusterIcon.prototype.onAdd = function () {
    this.div_ = document.createElement('DIV');
    this.chart_div_ = document.createElement('DIV');

    if (this.visible_) {
        var pos = this.getPosFromLatLng_(this.center_);
        this.div_.style.cssText = this.createCss(pos);
        this.div_.innerHTML = this.sums_.text;
        this.chart_div_.style.cssText = this.createCss(pos);
    }

    var panes = this.getPanes();
    panes.overlayMouseTarget.appendChild(this.div_);
    panes.overlayMouseTarget.appendChild(this.chart_div_);

    var that = this;

    google.maps.event.addDomListener(this.chart_div_, 'click', function () {
        that.triggerClusterClick();
    });

    google.maps.event.addDomListener(this.div_, 'click', function () {
        that.triggerClusterClick();
    });
};


/**
 * Returns the position to place the div dending on the latlng.
 *
 * @param {google.maps.LatLng} latlng The position in latlng.
 * @return {google.maps.Point} The position in pixels.
 * @private
 */
ClusterIcon.prototype.getPosFromLatLng_ = function (latlng) {
    var pos = this.getProjection().fromLatLngToDivPixel(latlng);
    pos.x -= parseInt(this.width_ / 2, 10);
    pos.y -= parseInt(this.height_ / 2, 10);
    return pos;
};


/**
 * Draw the icon.
 * @ignore
 */
ClusterIcon.prototype.draw = function () {
    if (this.visible_) {
        var pos = this.getPosFromLatLng_(this.center_);
        this.div_.style.top = pos.y + 'px';
        this.div_.style.left = pos.x + 'px';
        this.chart_div_.style.top = pos.y + 'px';
        this.chart_div_.style.left = pos.x + 'px';
        this.renderCharts_();

    }
};


/**
 * Hide the icon.
 */
ClusterIcon.prototype.hide = function () {
    if (this.div_) {
        this.div_.style.display = 'none';
    }

    if (this.chart_div_) {
        this.chart_div_.style.display = 'none';
    }

    this.visible_ = false;
};


/**
 * Position and show the icon.
 */
ClusterIcon.prototype.show = function () {
    if (this.div_) {
        var pos = this.getPosFromLatLng_(this.center_);
        this.div_.style.cssText = this.createCss(pos);
        this.div_.style.display = '';
    }

    if (this.chart_div_) {
        this.chart_div_.style.cssText = this.createCss(pos);
        this.chart_div_.style.display = '';
        this.renderCharts_();
    }
    this.visible_ = true;

};


ClusterIcon.prototype.renderCharts_ = function () {

    var clusterChartData = this.cluster_.getChartData();
    var clusterLegend = this.cluster_.getMarkerClusterer().getLegend();

    var dataArray = [['Title', 'Count']];
    var chartColorsSeq = [];

    for (var key in clusterChartData) {
        if (clusterChartData.hasOwnProperty(key)) {
            var dataRow = [];
            dataRow.push(key);
            dataRow.push(clusterChartData[key]);
            dataArray.push(dataRow);
            chartColorsSeq.push(clusterLegend[key]);
        }

    }
    //console.log(dataArray);
    var data = google.visualization.arrayToDataTable(dataArray);
    var formatter = new google.visualization.NumberFormat({
       pattern: '###,###'
    
    }); 
    formatter.format(data, 1);
       
    var options = {
        is3D: true,
        fontSize: 8,
        backgroundColor: 'transparent',
        legend: 'none',
        pieHole: 0,
        tooltip: {text: 'value'},
        colors: chartColorsSeq,
        pieSliceText: '5',
        pieSliceTextStyle: {
            color: 'white', fontSize:8
          },
       // slices: {  0: {offset: 0.05},
        //        1: {offset: 0.05}}        
    };

    var chart = new google.visualization.PieChart(this.chart_div_);
    chart.draw(data, options);

};


/**
 * Remove the icon from the map
 */
ClusterIcon.prototype.remove = function () {
    this.setMap(null);
};


/**
 * Implementation of the onRemove interface.
 * @ignore
 */
ClusterIcon.prototype.onRemove = function () {
    if (this.div_ && this.div_.parentNode) {
        this.hide();
        this.div_.parentNode.removeChild(this.div_);
        this.div_ = null;
    }

    if (this.chart_div_ && this.chart_div_.parentNode) {
        this.hide();
        this.chart_div_.parentNode.removeChild(this.chart_div_);
        this.chart_div_ = null;
    }
};


/**
 * Set the sums of the icon.
 *
 * @param {Object} sums The sums containing:
 *   'text': (string) The text to display in the icon.
 *   'index': (number) The style index of the icon.
 */
ClusterIcon.prototype.setSums = function (sums) {
    this.sums_ = sums;
    this.text_ = sums.text;
    this.index_ = sums.index;
    if (this.div_) {
        this.div_.innerHTML = sums.text;
    }

    this.useStyle();
};


/**
 * Sets the icon to the the styles.
 */
ClusterIcon.prototype.useStyle = function () {
    var index = Math.max(0, this.sums_.index - 1);
    index = Math.min(this.styles_.length - 1, index);
    var style = this.styles_[index];
    //this.url_ = style['url'];
    this.height_ = style['height'];
    this.width_ = style['width'];
    this.textColor_ = style['textColor'];
    this.anchor_ = style['anchor'];
    this.textSize_ = style['textSize'];
    this.backgroundPosition_ = style['backgroundPosition'];
};


/**
 * Sets the center of the icon.
 *
 * @param {google.maps.LatLng} center The latlng to set as the center.
 */
ClusterIcon.prototype.setCenter = function (center) {
    this.center_ = center;
};


/**
 * Create the css text based on the position of the icon.
 *
 * @param {google.maps.Point} pos The position.
 * @return {string} The css style text.
 */
ClusterIcon.prototype.createCss = function (pos) {
    var style = [];
    style.push('background-image:url(' + this.url_ + ');');
    var backgroundPosition = this.backgroundPosition_ ? this.backgroundPosition_ : '0 0';
    style.push('background-position:' + backgroundPosition + ';');

    if (typeof this.anchor_ === 'object') {
        if (typeof this.anchor_[0] === 'number' && this.anchor_[0] > 0 &&
            this.anchor_[0] < this.height_) {
            style.push('height:' + (this.height_ - this.anchor_[0]) +
                'px; padding-top:' + this.anchor_[0] + 'px;');
        } else {
            style.push('height:' + this.height_ + 'px; line-height:' + this.height_ +
                'px;');
        }
        if (typeof this.anchor_[1] === 'number' && this.anchor_[1] > 0 &&
            this.anchor_[1] < this.width_) {
            style.push('width:' + (this.width_ - this.anchor_[1]) +
                'px; padding-left:' + this.anchor_[1] + 'px;');
        } else {
            style.push('width:' + this.width_ + 'px; text-align:center;');
        }
    } else {
        style.push('height:' + this.height_ + 'px; line-height:' +
            this.height_ + 'px; width:' + this.width_ + 'px; text-align:center;');
    }

    var txtColor = this.textColor_ ? this.textColor_ : 'White';
    var txtSize = this.textSize_ ? this.textSize_ : 11;

    style.push('cursor:pointer; top:' + pos.y + 'px; left:' +
        pos.x + 'px; color:' + txtColor + '; position:absolute;  font-size:' +
        txtSize + 'px; font-family:Arial,sans-serif; font-weight:bold');
    return style.join('');
};


// Export Symbols for Closure
// If you are not going to compile with closure then you can remove the
// code below.
window['MarkerClusterer'] = MarkerClusterer;
MarkerClusterer.prototype['addMarker'] = MarkerClusterer.prototype.addMarker;
MarkerClusterer.prototype['addMarkers'] = MarkerClusterer.prototype.addMarkers;
MarkerClusterer.prototype['clearMarkers'] =
    MarkerClusterer.prototype.clearMarkers;
MarkerClusterer.prototype['fitMapToMarkers'] =
    MarkerClusterer.prototype.fitMapToMarkers;
MarkerClusterer.prototype['getCalculator'] =
    MarkerClusterer.prototype.getCalculator;
MarkerClusterer.prototype['getGridSize'] =
    MarkerClusterer.prototype.getGridSize;
MarkerClusterer.prototype['getExtendedBounds'] =
    MarkerClusterer.prototype.getExtendedBounds;
MarkerClusterer.prototype['getMap'] = MarkerClusterer.prototype.getMap;
MarkerClusterer.prototype['getMarkers'] = MarkerClusterer.prototype.getMarkers;
MarkerClusterer.prototype['getMaxZoom'] = MarkerClusterer.prototype.getMaxZoom;
MarkerClusterer.prototype['getStyles'] = MarkerClusterer.prototype.getStyles;
MarkerClusterer.prototype['getLegend'] = MarkerClusterer.prototype.getLegend;
MarkerClusterer.prototype['getTotalClusters'] =
    MarkerClusterer.prototype.getTotalClusters;
MarkerClusterer.prototype['getTotalMarkers'] =
    MarkerClusterer.prototype.getTotalMarkers;
MarkerClusterer.prototype['redraw'] = MarkerClusterer.prototype.redraw;
MarkerClusterer.prototype['removeMarker'] =
    MarkerClusterer.prototype.removeMarker;
MarkerClusterer.prototype['removeMarkers'] =
    MarkerClusterer.prototype.removeMarkers;
MarkerClusterer.prototype['resetViewport'] =
    MarkerClusterer.prototype.resetViewport;
MarkerClusterer.prototype['repaint'] =
    MarkerClusterer.prototype.repaint;
MarkerClusterer.prototype['setCalculator'] =
    MarkerClusterer.prototype.setCalculator;
MarkerClusterer.prototype['setGridSize'] =
    MarkerClusterer.prototype.setGridSize;
MarkerClusterer.prototype['setMaxZoom'] =
    MarkerClusterer.prototype.setMaxZoom;
MarkerClusterer.prototype['onAdd'] = MarkerClusterer.prototype.onAdd;
MarkerClusterer.prototype['draw'] = MarkerClusterer.prototype.draw;

Cluster.prototype['getCenter'] = Cluster.prototype.getCenter;
Cluster.prototype['getSize'] = Cluster.prototype.getSize;
Cluster.prototype['getMarkers'] = Cluster.prototype.getMarkers;

ClusterIcon.prototype['onAdd'] = ClusterIcon.prototype.onAdd;
ClusterIcon.prototype['draw'] = ClusterIcon.prototype.draw;
ClusterIcon.prototype['onRemove'] = ClusterIcon.prototype.onRemove;
