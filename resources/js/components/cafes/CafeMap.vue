<style lang="scss">
div#cafe-map {
    width: 100%;
    height: 400px;
}
</style>

<template>
    <div id="cafe-map">

    </div>
</template>

<script>
import AMapLoader from "@amap/amap-jsapi-loader";
import {COFE_CONFIG} from "../../config";

export default {
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
            // map: {}
        }
    },
    mounted() {
        this.map = AMapLoader.load({
            "key": COFE_CONFIG.GAODE_MAPS_JS_API_KEY
        })
            .then((AMap) => {
                new AMap.Map('cafe-map', {
                    center: [this.latitude, this.longitude],
                    zoom: this.zoom
                })
            })
            .catch(e => {
                // console.log(e)
            })
    }
}
</script>
