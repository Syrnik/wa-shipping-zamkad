<template>
  <div style="margin: 0 2px" id="shipping-zamkad-settings-block">
    <field-name v-model="setting.field_name" :ns="info.namespace" />
    <weight-limits v-model="setting.weight_limits" :ns="info.namespace" />
    <price-limits v-model="setting.price_limits" :ns="info.namespace" />
    <costs-table v-model="setting.km_table" :ns="addns('km_table', info.namespace)" />
    <date-time-custom-fields-toggle v-model="setting.desired_delivery" :ns="addns('desired_delivery', info.namespace)" />
    <timeframes v-model="setting.timeframes" :ns="addns('timeframes', info.namespace)" />
    <dates class="holidays" name="Дополнительные выходные" v-model="setting.holidays" :ns="addns('holidays', info.namespace)" />
    <dates class="workdays" name="Дополнительные рабочие дни" v-model="setting.workdays" :ns="addns('workdays', info.namespace)" />
  </div>
</template>

<script>
import FieldName from "./components/settings-app/field-name.vue";
import WeightLimits from "./components/settings-app/weight-limits.vue";
import PriceLimits from "./components/settings-app/price-limits.vue";
import CostsTable from "./components/settings-app/costs-table.vue";
import Timeframes from "./components/settings-app/timeframes.vue";
import Dates from "./components/settings-app/dates.vue";
import DateTimeCustomFieldsToggle from "./components/settings-app/date-time-custom-fields-toggle.vue";

export default {
  props: {
    settings: Object,
    info: Object
  },
  data() {
    return {
      setting: this.settings
    }
  },
  components: {DateTimeCustomFieldsToggle, Dates, Timeframes, CostsTable, PriceLimits, WeightLimits, FieldName}
}
</script>
