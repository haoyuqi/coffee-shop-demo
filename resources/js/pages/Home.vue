<style lang="scss">
@import '~@/abstracts/_variables.scss';

div#home {
    a.add-cafe-button {
        float: right;
        display: block;
        margin-top: 10px;
        margin-bottom: 10px;
        background-color: $dark-color;
        color: white;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-left: 10px;
        padding-right: 10px;
    }
}
</style>

<template>
    <div>
        <span v-show="cafesLoadStatus == 1">Loading</span>
        <span v-show="cafesLoadStatus == 2">Cafes loaded successfully!</span>
        <span v-show="cafesLoadStatus == 3">Cafes loaded unsuccessfully!</span>
    </div>
    <div class="grid-container">
        <div class="grid-x">
            <div class="large-12 medium-12 small-12 columns">
                <router-link :to="{ name: 'newcafe' }" class="add-cafe-button">+ 新增咖啡店</router-link>
            </div>
        </div>
    </div>

    <cafe-filter></cafe-filter>
</template>

<script>
import CafeFilter from "../components/cafes/CafeFilter.vue";

export default {
    created() {
        this.$store.dispatch('loadCafes')
    },

    computed: {
        // 获取 cafes 加载状态
        cafesLoadStatus() {
            return this.$store.getters.getCafesLoadStatus
        },
        // 获取 cafes
        cafes() {
            return this.$store.getters.getCafes
        }
    },
    components: {
        CafeFilter
    }
}
</script>
