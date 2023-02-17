<template>
  <wa-field-simple :name="l10n(name)" value_class="no-shift">
    <select v-model="setting" @change="$emit('input', setting)" :name="ns">
      <option v-for="o in options" :value="o.value" :selected="o.value===setting">{{ o.title | localized }}</option>
    </select>
  </wa-field-simple>
</template>

<script>
import WaL10n from "../wa-l10n";

export default {
  mixins: [WaL10n],
  props: {
    name: {type: String, default: 'Поле адреса «Улица, дом, квартира»'},
    ns: String,
    value: String
  },
  data() {
    return {
      setting: this.value,
      options: [
        {value: 'no', title: 'не запрашивать'},
        {value: 'yes', title: 'запрашивать'},
        {value: 'required', title: 'требовать'}
      ]
    }
  }
}
</script>
