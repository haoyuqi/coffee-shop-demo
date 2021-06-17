<style lang="scss">
div#cafe-map {
    width: 100%;
    height: 400px;
}
div#cafe-map-container {
    position: absolute;
    top: 50px;
    left: 0px;
    right: 0px;
    bottom: 50px;

    div#cafe-map {
        position: absolute;
        top: 0px;
        left: 0px;
        right: 0px;
        bottom: 0px;
    }
}
</style>

<template>
    <div id="cafe-map-container">
        <div id="cafe-map">

        </div>
        <cafe-map-filter></cafe-map-filter>
    </div>
</template>

<script>

import {COFE_CONFIG} from "../../config";
import CafeMapFilter from "./CafeMapFilter.vue"

export default {
    components: { CafeMapFilter},
    props: {
        'latitude': {
            type: Number,
            default: function () {
                return 120.21
            }
        },
        'longitude': {
            type: Number,
            default: function () {
                return 30.29
            }
        },
        'zoom': {
            type: Number,
            default: function () {
                return 4
            }
        }
    },
    data() {
        return {
            markers: [],
            infoWindows: []
        }
    },
    computed: {
        cafes() {
            return this.$store.getters.getCafes;
        }
    },
    methods: {
        buildMarkers() {
            this.clearMarkers();

            // 自定义点标记图标
            var image = COFE_CONFIG.APP_URL + '/img/coffee-marker.png';
            var icon = new AMap.Icon({
                image: image,  // 图像 URL
                imageSize: new AMap.Size(19, 33)  // 设置图标尺寸
            });

            for (var i = 0; i < this.cafes.length; i++) {
                var marker = new AMap.Marker({
                    position: new AMap.LngLat(parseFloat(this.cafes[i].latitude), parseFloat(this.cafes[i].longitude)),
                    title: this.cafes[i].name,
                    icon: icon,
                    map: this.map
                });

                var infoWindow = new AMap.InfoWindow({
                    content: this.cafes[i].name
                });
                this.infoWindows.push(infoWindow);

                marker.on('click', function () {
                    infoWindow.open(this.getMap(), this.getPosition());
                })

                this.markers.push(marker)
            }

            if (this.markers.length > 0) {
                this.map.add(this.markers)
            }
        },

        clearMarkers() {
            for (var i = 0; i < this.markers.length; i++) {
                this.markers[i].setMap(null);
            }
        }
    },
    mounted() {
        this.map = new AMap.Map('cafe-map', {
            center: [this.latitude, this.longitude],
            zoom: this.zoom
        });
        // this.clearMarkers();
        this.buildMarkers();
    },
    watch: {
        cafes() {
            // this.clearMarkers();
            this.buildMarkers();
        }
    }
}
</script>
