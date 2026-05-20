<template>
  <WaField :name="l10n(name)" name-class="for-checkbox">
    <div class="value">
      <input type="hidden" value="0" :name="addns('show', ns)">
      <label>
        <span class="wa-checkbox">
          <input type="checkbox" value="1" v-model="setting.show" :name="addns('show', ns)">
          <span><span class="icon"><i class="fas fa-check"></i></span></span>
        </span>
        {{ l10n('Показывать ориентировочную дату доставки') }}
      </label>
      <div>
        <input type="text" v-model="setting.interval" class="validated input"
               pattern="[0-9]+(-[0-9]+)?" :name="addns('interval', ns)" placeholder="0">
        <span class="validity"></span>
        <p class="hint">{{ l10n('Укажите количество дней на доставку. Число или диапазон через тире. Например «1» — один день, т.е. доставка на следующий день, «1-2» — один или два дня, т.е. завтра-послезавтра') }}</p>
      </div>
    </div>
  </WaField>
</template>

<script setup lang="ts">
import WaField from './WaField.vue'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'

interface DeliveryDateSettings { show: unknown; interval: string }

const props = withDefaults(defineProps<{ name?: string; ns?: string; modelValue: DeliveryDateSettings }>(), {
  name: 'Дата доставки',
  ns: '',
})
const { l10n } = useL10n()
const { addns } = useNamespace()
const setting = props.modelValue
</script>
