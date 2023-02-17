<template>
  <wa-field :name="l10n(name)">
    <simple-checkbox class="value no-shift" v-model="setting.show"
                     :ns="addns('show', ns)">{{ l10n('Показывать ориентировочную дату доставки') }}
    </simple-checkbox>
    <div class="value">
      <input type="text" v-model="setting.interval" class="validated input" pattern="[0-9]+(-[0-9]+)?"
             :name="addns('interval', ns)"
             placeholder="0"><span class="validity"></span><br><span class="hint">{{ l10n('Укажите количество дней на доставку. Число или диапазон через тире. Например «1» — один день, т.е. доставка на следующий день, «1-2» — один или два дня, т.е. завтра-послезавтра') }}</span>
    </div>
  </wa-field>
</template>

<script>
import SimpleCheckbox from "../simple-checkbox.vue";
import WaL10n from "../wa-l10n";

export default {
  mixins: [WaL10n],
  components: {SimpleCheckbox},
  props: {
    name: {type: String, default: "Дата доставки"},
    ns: {type: String, default: ""},
    value: {type: Object}
  },
  data() {
    return {
      setting: this.value
    }
  }
}
</script>
