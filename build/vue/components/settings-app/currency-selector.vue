<template>
  <wa-field :name="l10n('Валюта расчёта')">
    <div class="value">
      <select @change="$emit('input', $event.target.value ? $event.target.value : $event)" :name="addns('currency', ns)">
        <option v-for="c in currencies" :value="c.code" :selected="setting===c.code">{{ c.name }} ({{ c.code }})</option>
      </select>
      <br>
      <span class="hint"><i class="icon16 exclamation"></i> {{'Убедитесь, что выбранная валюта есть в настройках магазина! Рекомендуется указывать здесь основную валюту магазина.'|localized}}</span>
    </div>
  </wa-field>
</template>

<script>
import AddNs from '../wa-namespace'
import WaL10n from "../wa-l10n";

export default {
  name: "CurrencySelector",
  props: ['setting', 'ns', 'currencies'],
  mixins: [AddNs, WaL10n],
}
</script>
