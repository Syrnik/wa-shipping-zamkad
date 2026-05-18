<template>
  <WaField :name="l10n(name)" name-class="for-input">
    <div class="value">
      <div class="wa-select">
        <select v-model="val" @change="emit('update:modelValue', val)" :name="ns">
          <option v-for="o in options" :key="o.value" :value="o.value">{{ l10n(o.title) }}</option>
        </select>
      </div>
    </div>
  </WaField>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import WaField from './WaField.vue'
import { useL10n } from '../../composables/useL10n'

const props = withDefaults(defineProps<{ name?: string; modelValue?: string; ns?: string }>(), {
  name: 'Поле адреса «Улица, дом, квартира»',
  modelValue: 'no',
  ns: '',
})
const emit = defineEmits<{ 'update:modelValue': [value: string] }>()
const { l10n } = useL10n()
const val = ref(props.modelValue)
const options = [
  { value: 'no', title: 'не запрашивать' },
  { value: 'yes', title: 'запрашивать' },
  { value: 'required', title: 'требовать' },
]
</script>
