<template>
  <wa-field-simple :name="name" value_class="no-shift">
    <input type="text" class="long" :name="addns('variant_name', ns)" v-model.trim="setting"
           @input="$emit('input', setting)" placeholder="Доставка за город">
  </wa-field-simple>
</template>

<script>
export default {
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
