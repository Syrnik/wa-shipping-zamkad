<template>
  <wa-field-simple :name="name" value_class="no-shift">
    <select v-model="setting" @change="$emit('input', setting)" :name="ns">
      <option v-for="o in options" :value="o.value" :selected="o.value===setting">{{ o.title }}</option>
    </select>
  </wa-field-simple>
</template>

<script>
export default {
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
