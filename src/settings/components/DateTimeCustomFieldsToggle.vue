<template>
  <div class="field">
    <div class="name for-checkbox">{{ l10n(name) }}</div>
    <div class="value">
      <ul>
        <li>
          <input type="hidden" value="0" :name="addns('date', ns)">
          <label>
            <span class="wa-checkbox">
              <input type="checkbox" value="1" v-model="setting.date" :name="addns('date', ns)">
              <span><span class="icon"><i class="fas fa-check"></i></span></span>
            </span>
            {{ l10n('Запрашивать желаемую дату доставки') }}
          </label>
        </li>
        <li>
          <input type="hidden" value="0" :name="addns('interval', ns)">
          <label>
            <span class="wa-checkbox">
              <input type="checkbox" value="1" v-model="setting.interval" :name="addns('interval', ns)">
              <span><span class="icon"><i class="fas fa-check"></i></span></span>
            </span>
            {{ l10n('Запрашивать желаемый интервал доставки') }}
          </label>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
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
