import Vue from 'vue';
import SettingsApp from './vue/SettingsApp.vue';
import AddNs from './vue/components/wa-namespace'
import WaField from './vue/components/wa-field.vue'
import WaFieldSimple from './vue/components/wa-field-simple.vue'

Vue.mixin(AddNs);
//Vue.mixin(jqContent);
Vue.component('WaField', WaField);
Vue.component('WaFieldSimple', WaFieldSimple);

export default function (options) {
    options = options || {};

    const v = new Vue({
        el: options.el || '#zamkad-shipping-settings',
        render(h) {
            return h(SettingsApp, {props: {info: options.info || {}, settings: options.settings || {}}});
        }
    });
}
