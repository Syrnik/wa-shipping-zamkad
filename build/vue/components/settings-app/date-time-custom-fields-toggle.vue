<template>
  <wa-field :name="name">
    <div class="value no-shift"><input type="hidden" value="0"
                                       :name="addns('date', ns)"><label><input
        type="checkbox" value="1" v-model="setting.date" :name="addns('date', ns)">
    &mdash; Запрашивать желаемую дату доставки</label>
    </div>
    <div class="value"><input type="hidden" value="0"
                                       :name="addns('interval', ns)"><label><input
        type="checkbox" value="1" v-model="setting.interval" :name="addns('interval', ns)">
    &mdash; Запрашивать желаемый интервал доставки</label>
    </div>
  </wa-field>
</template>

<script>
export default {
  props: {
    name: {type: String, default: "Поля даты и интервала доставки"},
    value: Object, // see settings.php 'desired_delivery',
    ns: String
  },
  data() {
    return {
      setting: this.value
    }
  }
}
</script>
