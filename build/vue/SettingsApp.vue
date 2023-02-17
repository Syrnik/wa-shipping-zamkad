<template>
  <div style="margin: 0 2px" id="shipping-zamkad-settings-block">
    <field-name v-model="setting.field_name" :ns="info.namespace"/>
    <variant-name v-model="setting.variant_name" :ns="info.namespace"/>
    <currency-selector v-model="setting.currency" :ns="info.namespace" :currencies="info.currencies" />
    <geography-limits :urls="info.action_url" v-model="setting.geography_limits" :name="l10n('Регион доставки')"
                      :ns="addns('geography_limits', info.namespace)"/>
    <weight-limits v-model="setting.weight_limits" :ns="info.namespace"/>
    <price-limits v-model="setting.price_limits" :ns="info.namespace" :currency="selectedCurrency"/>
    <costs-table v-model="setting.km_table" :ns="addns('km_table', info.namespace)" :currency="selectedCurrency"/>
    <street-field v-model="setting.street_field" :ns="addns('street_field', info.namespace)"/>
    <delivery-date :name="l10n('Дата доставки')" :ns="addns('delivery_date', info.namespace)"
                   v-model="setting.delivery_date"/>
    <date-time-custom-fields-toggle v-model="setting.desired_delivery" :ns="addns('desired_delivery', info.namespace)"/>
    <timeframes v-model="setting.timeframes" :ns="addns('timeframes', info.namespace)"/>
    <dates class="holidays" :name="l10n('Дополнительные выходные')" v-model="setting.holidays"
           :ns="addns('holidays', info.namespace)"/>
    <dates class="workdays" :name="l10n('Дополнительные рабочие дни')" v-model="setting.workdays"
           :ns="addns('workdays', info.namespace)"/>
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
import DeliveryDate from "./components/settings-app/delivery-date.vue";
import VariantName from "./components/settings-app/variant-name.vue";
import StreetField from "./components/settings-app/street-field.vue";
import GeographyLimits from "./components/settings-app/geography-limits.vue";
import WaL10n from "./components/wa-l10n";
import CurrencySelector from "./components/settings-app/currency-selector.vue";

export default {
  mixins: [WaL10n],
  props: {
    settings: Object,
    info: Object
  },
  data() {
    return {
      setting: this.settings
    }
  },
  components: {
    CurrencySelector,
    GeographyLimits, StreetField, VariantName, DeliveryDate,
    DateTimeCustomFieldsToggle, Dates, Timeframes, CostsTable, PriceLimits, WeightLimits, FieldName
  },
  computed: {
    selectedCurrency() {
      if (this.info.currencies[this.setting.currency]) return this.info.currencies[this.setting.currency];
      return null;
    }
  }
}
</script>
