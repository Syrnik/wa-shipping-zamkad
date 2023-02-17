<template>
  <wa-field-simple :name="l10n('Ограничение по стоимости заказа')" value_class="no-shift">
    {{ 'от' | localized }}
    <input :name="addns('min', addns('price_limits', ns))" type="number" class="short numerical" min="0" :step="currency_precision"
           v-model.number="setting.min" placeholder="0"
           @input="$emit('input', setting)">
    {{ 'до' | localized }}
    <input :name="addns('max', addns('price_limits', ns))" type="number" class="short numerical" min="0" :step="currency_precision"
           v-model.number="setting.max" placeholder="∞"
           @input="$emit('input', setting)">
    {{ currency.sign }}
  </wa-field-simple>
</template>

<script>
import waL10n from "../wa-l10n";

export default {
  mixins: [waL10n],
  props: {
    ns: String,
    value: Object,
    currency: Object
  },
  data() {
    return {
      setting: this.value
    }
  },
  computed: {
    currency_precision() {
      return Math.pow(10, this.currency.precision ? 0-this.currency.precision : -2);
    }
  }
}
</script>
