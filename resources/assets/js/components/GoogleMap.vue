<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input  placeholder="Search" type="text" class="form-control" @keyup.enter="onSearch"/>
                </div>
            </div>
        </div>
        <div class="row row-eq-height">
            <div class="col-md-8">
                <div class="panel panel-default full-height">
                    <div class="panel-body full-height">
                        <div class="full-height" id="map"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Nearby</div>
                    <div class="panel-body">
                        <template v-for="brewery in this.nearby">
                            <a v-on:click="setSelectedBrewery(brewery)">{{ brewery.name }}</a> <small>{{ brewery.distance.toFixed(1) }} km</small><br />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="selectedBrewery">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Information</div>
                    <div class="panel-body">
                        Name: {{ selectedBrewery.name }} <br />
                        Address: {{ selectedBrewery.address }}<br />
                        Postal Code: {{ selectedBrewery.postalCode }}<br />
                        City: {{ selectedBrewery.city }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="beers.length > 0">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Available beers</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4" v-for="beer in beers">
                                <h3>{{ beer.name }}</h3>
                                <p>
                                    Volume: {{ beer.volume }} cl<br />
                                    Alcohol: {{ beer.alcohol }} %<br />
                                    keg: {{ beer.keg }} <br />
                                    Style: {{ beer.style }} <br />
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {

        methods: {

            calculateDistance(pointA, pointB, unit='K') {

                  var theta = pointA.lng - pointB.lng
                  var radTheta = Math.PI * theta/180

                  var dist =    Math.acos(
                                    Math.sin(Math.PI * pointA.lat/180) *
                                    Math.sin(Math.PI * pointB.lat/180) +
                                    Math.cos(Math.PI * pointA.lat/180) *
                                    Math.cos(Math.PI * pointB.lat/180) *
                                    Math.cos(radTheta)
                                ) * 180/Math.PI;

                  dist = dist * 60 * 1.1515

                  if (unit=="K") { dist = dist * 1.609344 }
                  if (unit=="N") { dist = dist * 0.8684 }

                  return dist
            },

            onSearch: function(e) {
                var search = e.target.value;

                this.$http.get('http://maps.googleapis.com/maps/api/geocode/json?address=' + search).then(function(response) {
                    var results = response.body.results;

                    // we have result
                    if (results.length > 0) {
                        this.map.setCenter(results[0].geometry.location);
                        this.map.setZoom(12);
                    }
                    else {
                        alert('nothing found');
                    }
                });
            },

            calculateDistances: function(position) {

                // recalculate distance and resort
                for(var i = 0, len = this.breweries.length; i < len; i++) {
                    this.breweries[i].distance = this.calculateDistance(this.breweries[i].position, position);
                }
                this.breweries.sort(function(a, b) {
                    return a.distance - b.distance;
                });

                // set nearby collection
                var len = this.breweries.length;
                this.nearby.splice(0);
                for(var i = 0; i < 15; i++ ) {
                    if(i < len) {
                        this.nearby[i] = this.breweries[i];
                    }
                }
            },

            setSelectedBrewery(brewery, focus=false) {
                this.selectedBrewery = brewery;

                if (focus) {
                    this.map.setCenter(brewery.position);
                }

                this.$http.get('breweries/' + brewery.id + '/beers').then(function(response) {
                    var beers = response.body.data;
                    this.beers = beers;
                });
            }
        },

        data() {
            return {
               selectedBrewery: false,      // current selected brewery
               breweries: [],               // breweries sorted by distance
               nearby: [],                  // top 15 nearby breweries
               beers: []                    // all beers at selected brewery
            }
        },

        /**
         * Component ready
         *
         */
        mounted() {

            // amsterdam as initial view
            this.map = new google.maps.Map(document.getElementById('map'), {
              zoom: 12,
              center: {lat: 52.379189, lng: 4.899431}
            });

            this.map.addListener('center_changed', function(e) {
                var center = this.map.getCenter();
                this.calculateDistances({
                    lng: center.lng(),
                    lat: center.lat()
                });
            }.bind(this));

            // Fetch all breweries
            this.$http.get('breweries').then(function(response) {

                var breweries = response.body.data;

                // Create markers and add event listeners
                for(var i = 0, len = breweries.length; i < len; i++) {

                    this.breweries.push(breweries[i]);

                    var marker = new google.maps.Marker({
                        position: breweries[i].position,
                        map: this.map,
                        title: breweries[i].name,
                        animation: google.maps.Animation.DROP,
                        icon: '/images/beer-icon.png'
                    });
                    marker.addListener('click',
                        (function(brewery, instance) {
                            return function() {
                                instance.setSelectedBrewery(brewery);
                            }
                        }(breweries[i], this))
                    );

                    // recalculate distances
                    var center = this.map.getCenter();
                    this.calculateDistances({
                        lng: center.lng(),
                        lat: center.lat()
                    });
                }
            });

        },

    }
</script>
