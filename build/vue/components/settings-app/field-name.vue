<template>
  <wa-field-simple :name="l10n('Название поля с расстоянием')" value_class="no-shift">
    <input type="text" class="long" :name="addns('field_name', ns)" v-model.trim="setting"
           @input="$emit('input', setting)" :placeholder="l10n('Расстояние от города (км.)')">
  </wa-field-simple>
</template>

<script>
import WaL10n from "../wa-l10n";

export default {
  mixins: [WaL10n],
  props: {
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
