<template>
  <wa-field name="Интервалы доставки">
    <div class="value">
      <table class="zebra">
        <thead>
        <tr>
          <th colspan="2">Интервал доставки</th>
          <th>Пн</th>
          <th>Вт</th>
          <th>Ср</th>
          <th>Чт</th>
          <th>Пт</th>
          <th>Сб</th>
          <th>Вс</th>
          <th>Доп. выходной</th>
          <th>Доп. рабочий день</th>
          <th></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
          <td colspan="12"><a href="#" @click.prevent="addInterval"><i class="icon16 add"></i> Добавить интервал</a>
          </td>
        </tr>
        </tfoot>
        <tbody>
        <tr is="Timeframe"
            v-for="(t, idx) in timeframes"
            :key="array_unique_key()"
            :value="t"
            :ns="addns(''+idx, ns)"
            :show-delete="timeframes.length > 1"
            @delete="deleteInterval(idx)"
        />
        </tbody>
      </table>
    </div>
  </wa-field>
</template>

<script>
import Timeframe from './timeframe.vue'
import UniqueKey from '../vue-array-key-uid'

export default {
  components: {Timeframe},
  mixins: [UniqueKey],
  props: {
    value: Array,
    ns: String
  },
  data() {
    return {
      timeframes: this.value
    }
  },
  methods: {
    addInterval() {
      const last_idx = this.timeframes.length - 1;
      const new_interval = Object.assign({}, this.timeframes[last_idx]);
      if (new_interval.from_hour < 23) new_interval.from_hour++;
      if (new_interval.to_hour < 23) new_interval.to_hour++;
      new_interval[1] = true;
      this.timeframes.push(new_interval);
    },
    deleteInterval(idx) {
      if(this.timeframes.length > 1) this.timeframes.splice(idx, 1);
    }
  }
}
</script>
