<template>
  <wa-field :name="l10n('Интервалы доставки')">
    <div class="value">
      <table class="zebra">
        <thead>
        <tr>
          <th colspan="2">{{'Интервал доставки'|localized}}</th>
          <th>{{'Пн'|localized}}</th>
          <th>{{'Вт'|localized}}</th>
          <th>{{'Ср'|localized}}</th>
          <th>{{'Чт'|localized}}</th>
          <th>{{'Пт'|localized}}</th>
          <th>{{'Сб'|localized}}</th>
          <th>{{'Вс'|localized}}</th>
          <th>{{'Доп. выходной'|localized}}</th>
          <th>{{'Доп. рабочий день'|localized}}</th>
          <th></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
          <td colspan="12"><a href="#" @click.prevent="addInterval"><i class="icon16 add"></i> {{'Добавить интервал'|localized}}</a>
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
import WaL10n from "../wa-l10n";

export default {
  components: {Timeframe},
  mixins: [UniqueKey, WaL10n],
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
      if (this.timeframes.length > 1) this.timeframes.splice(idx, 1);
    }
  }
}
</script>
