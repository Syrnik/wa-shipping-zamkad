<template>
  <wa-field-simple :name="l10n(name)" value_class="no-shift">
    <input type="text" class="long" :name="addns('variant_name', ns)" v-model.trim="setting"
           @input="$emit('input', setting)" :placeholder="l10n('Доставка за город')">
  </wa-field-simple>
</template>

<script>
import WaL10n from "../wa-l10n";
export default {
  mixins:[WaL10n],
  props: {
    name: {type: String, default: 'Название варианта доставки'},
    value: {type: String, default: ''},
    ns: {type: String, default: ''}
  },
  data() {
    return {
      setting: this.value
    }
  }
}
</script>
