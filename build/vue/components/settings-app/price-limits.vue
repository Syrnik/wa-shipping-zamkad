<template>
  <wa-field-simple :name="l10n('Ограничение по стоимости заказа')" value_class="no-shift">
    {{ 'от' | localized }}
    <input :name="addns('min', addns('price_limits', ns))" type="number" class="short numerical" min="0" step="0.01"
           v-model.number="setting.min" placeholder="0"
           @input="$emit('input', setting)">
    {{ 'до' | localized }}
    <input :name="addns('max', addns('price_limits', ns))" type="number" class="short numerical" min="0" step="0.01"
           v-model.number="setting.max" placeholder="∞"
           @input="$emit('input', setting)">
    ₽.
  </wa-field-simple>
</template>

<script>
import waL10n from "../wa-l10n";

export default {
  mixins: [waL10n],
  props: {
    ns: String,
    value: Object
  },
  data() {
    return {
      setting: this.value
    }
  }
}
</script>
