<template>
  <WaField :name="l10n(name)">
    <div class="value no-shift">
      <input type="hidden" value="0" :name="addns('date', ns)">
      <label>
        <input type="checkbox" value="1" v-model="setting.date" :name="addns('date', ns)">
        &mdash; {{ l10n('Запрашивать желаемую дату доставки') }}
      </label>
    </div>
    <div class="value">
      <input type="hidden" value="0" :name="addns('interval', ns)">
      <label>
        <input type="checkbox" value="1" v-model="setting.interval" :name="addns('interval', ns)">
        &mdash; {{ l10n('Запрашивать желаемый интервал доставки') }}
      </label>
    </div>
  </WaField>
</template>

<script setup lang="ts">
import WaField from '../WaField.vue'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'

interface DesiredDelivery { date: unknown; interval: unknown }

const props = withDefaults(defineProps<{ name?: string; modelValue: DesiredDelivery; ns?: string }>(), {
  name: 'Поля даты и интервала доставки',
  ns: '',
})
const { l10n } = useL10n()
const { addns } = useNamespace()
const setting = props.modelValue
</script>
