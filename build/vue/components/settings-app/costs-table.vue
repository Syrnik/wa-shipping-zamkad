<template>
  <wa-field name="Тарифы доставки">
    <div class="value no-shift">
      <table class="zebra" style="width: auto">
        <thead>
        <tr>
          <th>Расстояние</th>
          <th>Фикс. цена</th>
          <th colspan="2">Цена за 1 км.</th>
          <th></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
          <td colspan="5"><a href="#" @click.prevent="addRow"><i class="icon16 add"></i> Добавить тариф</a></td>
        </tr>
        </tfoot>
        <tbody>
        <tr is="TariffRow"
            v-for="(t, idx) in setting"
            :key="array_unique_key()"
            :value="t"
            :ns="addns(''+idx, ns)"
            @delete="deleteRow(idx)"/>
        </tbody>
      </table>
    </div>
  </wa-field>
</template>

<script>

import TariffRow from "./tariff-row.vue";
import UniqueKey from '../vue-array-key-uid'

export default {
  components: {TariffRow},
  mixins: [UniqueKey],
  props: {
    value: Array,
    ns: String
  },
  data() {
    return {
      setting: this.value
    }
  },
  methods: {
    addRow() {
      const new_row = Object.assign({}, this.setting[0]);
      this.setting.forEach(t => {
        if (t.to >= new_row.to) {
          new_row.to = t.to + 1;
          new_row.base = t.base;
          new_row.price = t.price;
        }
      });
      this.setting.push(new_row);
    },
    deleteRow(index) {
      if (this.setting.length === 1) {
        this.setting[0]['to'] = 1;
        this.setting[0]['base'] = this.setting[0]['price'] = 0;
      } else
        this.setting.splice(index, 1);
    }
  }
}
</script>
