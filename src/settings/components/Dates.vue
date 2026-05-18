<template>
  <WaField :name="name">
    <div class="value">
      <ul class="vue-dates-list">
        <li v-for="d in sortedDates" :key="d" :class="listItemClass">
          <input type="hidden" :name="addns(d, ns)" :value="d">
          {{ dmyDate(d) }}
          <a href="#" @click.prevent="toggle(d)"><i class="fas fa-times"></i></a>
        </li>
      </ul>
      <div class="custom-mt-12" style="position:relative">
        <button type="button" class="button small outlined" @click="pickerOpen = !pickerOpen">
          <i class="fas fa-calendar"></i>
          {{ l10n(pickerOpen ? 'Закрыть' : 'Выбрать') }}
        </button>
        <div v-if="pickerOpen" style="position:absolute;top:100%;left:0;z-index:100;margin-top:4px;background:Canvas;box-shadow:0 4px 16px rgba(0,0,0,.15);border-radius:4px">
          <DatePickerMultiple
            :modelValue="modelValue"
            :locale="currentLocale"
            @update:modelValue="emit('update:modelValue', $event)"
          />
        </div>
      </div>
    </div>
  </WaField>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import dateformat from 'dateformat'
import WaField from './WaField.vue'
import DatePickerMultiple from '../../components/DatePickerMultiple.vue'
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
