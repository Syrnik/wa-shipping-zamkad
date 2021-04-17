<template>
  <wa-field-simple name="Название поля с расстоянием" value_class="no-shift">
    <input type="text" class="long" :name="addns('field_name', ns)" v-model.trim="setting"
           @input="$emit('input', setting)" placeholder="Расстояние от города (км.)">
  </wa-field-simple>
</template>

<script>
export default {
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
