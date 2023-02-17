<template>
  <wa-field-simple :name="l10n('Ограничение по весу')" value_class="no-shift">
    {{'от'|localized}}
    <input :name="addns('min', addns('weight_limits', ns))" type="number" class="short numerical" min="0" step="1"
           v-model.number="setting.min" placeholder="0"
           @input="$emit('input', setting)">
    {{'до'|localized}}
    <input :name="addns('max', addns('weight_limits', ns))" type="number" class="short numerical" min="0" step="1"
           v-model.number="setting.max" placeholder="∞"
           @input="$emit('input', setting)">
    {{'кг.'|}}
  </wa-field-simple>
</template>

<script>
import WaL10n from "../wa-l10n";
export default {
  mixins: [WaL10n],
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
