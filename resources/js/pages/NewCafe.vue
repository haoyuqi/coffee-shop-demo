<style>

</style>

<template>
    <div class="page">
        <form>
            <div class="grid-container">
                <div class="grid-x grid-padding-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>名称
                            <input type="text" placeholder="咖啡店名" v-model="name">
                        </label>
                        <span class="validation" v-show="!validations.name.is_valid">{{ validations.name.text }}</span>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <label>网址
                            <input type="text" placeholder="网址" v-model="website">
                        </label>
                        <span class="validation" v-show="!validations.website.is_valid">{{
                                validations.website.text
                            }}</span>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <label>简介
                            <input type="text" placeholder="简介" v-model="description">
                        </label>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <label>图片
                            <input type="file" id="cafe-photo" ref="photo" v-on:change="handleFileUpload()"/>
                        </label>
                    </div>
                </div>
                <div class="grid-x grid-padding-x" v-for="(location, key) in locations">
                    <div class="large-12 medium-12 small-12 cell">
                        <h3>位置</h3>
                    </div>
                    <div class="large-6 medium-6 small-12 cell">
                        <label>位置名称
                            <input type="text" placeholder="位置名称" v-model="locations[key].name">
                        </label>
                    </div>
                    <div class="large-6 medium-6 small-12 cell">
                        <label>详细地址
                            <input type="text" placeholder="详细地址" v-model="locations[key].address">
                        </label>
                        <span class="validation" v-show="!validations.locations[key].address.is_valid">{{
                                validations.locations[key].address.text
                            }}</span>
                    </div>
                    <div class="large-6 medium-6 small-12 cell">
                        <label>城市
                            <input type="text" placeholder="城市" v-model="locations[key].city">
                        </label>
                        <span class="validation" v-show="!validations.locations[key].city.is_valid">{{
                                validations.locations[key].city.text
                            }}</span>
                    </div>
                    <div class="large-6 medium-6 small-12 cell">
                        <label>省份
                            <input type="text" placeholder="省份" v-model="locations[key].state">
                        </label>
                        <span class="validation" v-show="!validations.locations[key].state.is_valid">{{
                                validations.locations[key].state.text
                            }}</span>
                    </div>
                    <div class="large-6 medium-6 small-12 cell">
                        <label>邮编
                            <input type="text" placeholder="邮编" v-model="locations[key].zip">
                        </label>
                        <span class="validation" v-show="!validations.locations[key].zip.is_valid">{{
                                validations.locations[key].zip.text
                            }}</span>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <label>支持的冲泡方法</label>
                        <span class="brew-method" v-for="brewMethod in brewMethods">
                        <input v-bind:id="'brew-method-'+brewMethod.id+'-'+key" type="checkbox"
                               v-bind:value="brewMethod.id"
                               v-model="locations[key].methodsAvailable">
                        <label v-bind:for="'brew-method-'+brewMethod.id+'-'+key">{{ brewMethod.method }}</label>
                    </span>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <tags-input v-bind:unique="key"></tags-input>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <a class="button" v-on:click="removeLocation(key)">移除位置</a>
                    </div>
                </div>
                <div class="grid-x grid-padding-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <a class="button" v-on:click="addLocation()">新增位置</a>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <a class="button" v-on:click="submitNewCafe()">提交表单</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import TagsInput from "../components/global/forms/TagsInput.vue";
import {EventBus} from "../event-bus.js";

export default {
    data() {
        return {
            name: '',
            locations: [],
            website: '',
            description: '',
            picture: '',
            roaster: false,
            validations: {
                name: {
                    is_valid: true,
                    text: ''
                },
                locations: [],
                oneLocation: {
                    is_valid: true,
                    text: ''
                },
                website: {
                    is_valid: true,
                    text: ''
                }
            }
        }
    },

    computed: {
        brewMethods() {
            return this.$store.getters.getBrewMethods;
        },
        addCafeStatus() {
            return this.$store.getters.getCafeAddStatus;
        }
    },

    methods: {
        handleFileUpload: function () {
          this.picture = this.$refs.photo.files[0]
        },

        submitNewCafe: function () {
            if (this.validateNewCafe()) {
                let data = new FormData;
                data.append('name', this.name);
                data.append('locations', JSON.stringify(this.locations));
                data.append('website', this.website);
                data.append('description', this.description);
                data.append('roaster', this.roaster);
                data.append('picture', this.picture);
                this.$store.dispatch('addCafe', data);
            }
        },

        addLocation: function () {
            this.locations.push({name: '', address: '', city: '', state: '', zip: '', methodsAvailable: [], tags: ''})
            this.validations.locations.push({
                address: {
                    is_valid: true,
                    'text': ''
                },
                city: {
                    is_valid: true,
                    'text': ''
                },
                state: {
                    is_valid: true,
                    'text': ''
                },
                zip: {
                    is_valid: true,
                    'text': ''
                }
            })
        },

        validateNewCafe: function () {
            let validNewCafeForm = true;

            for (var index in this.locations) {
                if (this.locations.hasOwnProperty(index)) {
                    // 确保地址字段不为空
                    if (this.locations[index].address.trim() === '') {
                        validNewCafeForm = false;
                        this.validations.locations[index].address.is_valid = false;
                        this.validations.locations[index].address.text = 'Please enter an address for the new cafe!';
                    } else {
                        this.validations.locations[index].address.is_valid = true;
                        this.validations.locations[index].address.text = '';
                    }
                }

                // 确保城市字段不为空
                if (this.locations[index].city.trim() === '') {
                    validNewCafeForm = false;
                    this.validations.locations[index].city.is_valid = false;
                    this.validations.locations[index].city.text = 'Please enter a city for the new cafe!';
                } else {
                    this.validations.locations[index].city.is_valid = true;
                    this.validations.locations[index].city.text = '';
                }

                // 确保省份字段不为空
                if (this.locations[index].state.trim() === '') {
                    validNewCafeForm = false;
                    this.validations.locations[index].state.is_valid = false;
                    this.validations.locations[index].state.text = 'Please enter a state for the new cafe!';
                } else {
                    this.validations.locations[index].state.is_valid = true;
                    this.validations.locations[index].state.text = '';
                }

                // 确保邮编字段不为空
                if (this.locations[index].zip.trim() === '' || !this.locations[index].zip.match(/(^\d{6}$)/)) {
                    validNewCafeForm = false;
                    this.validations.locations[index].zip.is_valid = false;
                    this.validations.locations[index].zip.text = 'Please enter a valid zip code for the new cafe!';
                } else {
                    this.validations.locations[index].zip.is_valid = true;
                    this.validations.locations[index].zip.text = '';
                }
            }

            // 确保网址是有效的 URL
            if (this.website.trim !== '' && !this.website.match(/^((https?):\/\/)?([w|W]{3}\.)+[a-zA-Z0-9\-\.]{3,}\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,})?$/)) {
                validNewCafeForm = false;
                this.validations.website.is_valid = false;
                this.validations.website.text = '请输入有效的网址 URL';
            } else {
                this.validations.website.is_valid = true;
                this.validations.website.text = '';
            }

            return validNewCafeForm;
        },
        removeLocation: function (key) {
            this.locations.splice(key, 1);
            this.validations.locations.splice(key, 1)
        },

        clearForm() {
            this.name = '';
            this.locations = [];
            this.website = '';
            this.description = '';
            this.picture = '';
            this.roaster = false;
            this.validations = {
                name: {
                    is_valid: true,
                    text: ''
                },
                locations: [],
                oneLocation: {
                    is_valid: true,
                    text: ''
                },
                website: {
                    is_valid: true,
                    text: ''
                }
            };

            EventBus.$emit('clear-tags');
            this.addLocation();
        }
    },
    created() {
        this.addLocation();
    },

    watch: {
        'addCafeStatus': function () {
            if (this.addCafeStatus === 2) {
                // 添加成功
                this.clearForm();
                $("#cafe-added-successfully").show().delay(5000).fadeOut();
            }

            if (this.addCafeStatus === 3) {
                // 添加失败
                $("#cafe-added-unsuccessfully").show().delay(5000).fadeOut();
            }
        }
    },

    components: {
        TagsInput
    },

    mounted() {
        EventBus.$on('tags-edited', function (tagsAdded) {
            this.locations[tagsAdded.unique].tags = tagsAdded.tags;
        }.bind(this))
    }
}
</script>
