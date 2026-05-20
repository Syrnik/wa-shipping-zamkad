<template>
  <WaField :name="l10n(name)">
    <SimpleCheckbox class="value no-shift" v-model="setting.show" :ns="addns('show', ns)">
      {{ l10n('Показывать ориентировочную дату доставки') }}
    </SimpleCheckbox>
    <div class="value">
      <input type="text" v-model="setting.interval" class="validated input"
             pattern="[0-9]+(-[0-9]+)?" :name="addns('interval', ns)" placeholder="0">
      <span class="validity"></span><br>
      <span class="hint">{{ l10n('Укажите количество дней на доставку. Число или диапазон через тире. Например «1» — один день, т.е. доставка на следующий день, «1-2» — один или два дня, т.е. завтра-послезавтра') }}</span>
    </div>
  </WaField>
</template>

<script setup lang="ts">
import WaField from '../WaField.vue'
import SimpleCheckbox from '../SimpleCheckbox.vue'
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
