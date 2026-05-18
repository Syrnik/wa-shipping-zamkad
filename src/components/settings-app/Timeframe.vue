<template>
  <tr>
    <td>
      {{ l10n('с') }}
      <input type="text" class="short" :name="addns('from_hour', ns)" v-model="fromHour" required>
      :
      <input type="text" class="short" :name="addns('from_minutes', ns)" v-model="fromMinutes" required>
    </td>
    <td>
      {{ l10n('до') }}
      <input type="text" class="short" :name="addns('to_hour', ns)" v-model="toHour" required>
      :
      <input type="text" class="short" :name="addns('to_minutes', ns)" v-model="toMinutes" required>
    </td>
    <td v-for="day in weekDays" :key="day">
      <input type="hidden" :name="addns(day, ns)" value="0">
      <input type="checkbox" :name="addns(day, ns)" value="1" v-model="timeframe[day]">
    </td>
    <td>
      <input type="hidden" :name="addns('holidays', ns)" value="0">
      <input type="checkbox" :name="addns('holidays', ns)" value="1" v-model="timeframe['holidays']">
    </td>
    <td>
      <input type="hidden" :name="addns('workdays', ns)" value="0">
      <input type="checkbox" :name="addns('workdays', ns)" value="1" v-model="timeframe['workdays']">
    </td>
    <td>
      <a v-if="showDelete" href="javascript:void(0)" @click.prevent="emit('delete')">
        <i class="icon16 no"></i>
      </a>
    </td>
  </tr>
</template>

<script setup lang="ts">
import { computed, nextTick } from 'vue'
import _toNumber from 'lodash.tonumber'
import format from 'number-formatter'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'
import type { TimeframeData } from '../../types'

const props = defineProps<{ modelValue: TimeframeData; ns: string; showDelete: boolean }>()
const emit = defineEmits<{ delete: [] }>()
const { l10n } = useL10n()
const { addns } = useNamespace()
const timeframe: TimeframeData = props.modelValue
const weekDays: number[] = [1, 2, 3, 4, 5, 6, 7]

function getTime(type: keyof TimeframeData): string {
  const v = timeframe[type]
  if (v === null || v === undefined) return ''
  return format('0#', v as number)
}

function setTime(value: string, type: keyof TimeframeData, maximum: number): void {
  if (typeof value === 'string' && !value.length) {
    timeframe[type] = null
    return
  }
  if (/^[0-9]+$/i.test(value)) {
    const v = _toNumber(value)
    if (v < maximum) {
      timeframe[type] = v
      return
    }
  }
  const saved = timeframe[type]
  timeframe[type] = 0
  nextTick(() => { timeframe[type] = saved })
}

const fromHour = computed({
  get: () => getTime('from_hour'),
  set: (v: string) => setTime(v, 'from_hour', 24),
})
const toHour = computed({
  get: () => getTime('to_hour'),
  set: (v: string) => setTime(v, 'to_hour', 24),
})
const fromMinutes = computed({
  get: () => getTime('from_minutes'),
  set: (v: string) => setTime(v, 'from_minutes', 60),
})
const toMinutes = computed({
  get: () => getTime('to_minutes'),
  set: (v: string) => setTime(v, 'to_minutes', 60),
})
</script>
