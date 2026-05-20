<template>
  <WaField :name="name">
    <div class="value no-shift">
      <ul class="vue-dates-list">
        <li v-for="d in sortedDates" :key="d" :class="listItemClass">
          <input type="hidden" :name="addns(d, ns)" :value="d">
          {{ dmyDate(d) }}
          <a href="#" @click.prevent="toggle(d)"><i class="icon10 no"></i></a>
        </li>
      </ul>
    </div>
    <div class="value">
      <button type="button" @click="pickerOpen = !pickerOpen">
        <i class="icon16 calendar"></i>
        {{ l10n(pickerOpen ? 'Закрыть' : 'Выбрать') }}
      </button>
      <DatePickerMultiple
        v-if="pickerOpen"
        :modelValue="modelValue"
        :locale="currentLocale"
        @update:modelValue="emit('update:modelValue', $event)"
      />
    </div>
  </WaField>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import dateformat from 'dateformat'
import WaField from '../WaField.vue'
import DatePickerMultiple from '../DatePickerMultiple.vue'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'

const props = withDefaults(defineProps<{
  name?: string
  ns?: string
  modelValue: string[]
  listItemClass?: string
}>(), {
  name: '',
  ns: '',
  listItemClass: '',
})
const emit = defineEmits<{ 'update:modelValue': [value: string[]] }>()
const { l10n } = useL10n()
const { addns } = useNamespace()
const pickerOpen = ref(false)

const currentLocale = computed(() => {
  const loc = (window as unknown as Record<string, string>).$_syrnik_current_locale ?? 'ru_RU'
  return loc.replace('_', '-')
})

const sortedDates = computed(() => [...props.modelValue].sort())

function dmyDate(ymd: string): string {
  const d = new Date(ymd)
  if (isNaN(d.getTime())) return ''
  return dateformat(d, 'dd.mm.yyyy')
}

function toggle(ymd: string): void {
  const next = props.modelValue.filter(v => v !== ymd)
  emit('update:modelValue', next)
}
</script>
