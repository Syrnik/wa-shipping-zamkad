<template>
  <div>
    <input type="hidden" :value="falseValue" :name="ns">
    <input type="checkbox" :value="trueValue" :name="ns" v-model="val" @change="emit('update:modelValue', val)">
    <template v-if="hasSlot"> &mdash; </template>
    <slot />
    <br v-if="hasDescription">
    <slot name="description" />
  </div>
</template>

<script setup lang="ts">
import { ref, useSlots } from 'vue'

const props = withDefaults(defineProps<{
  ns: string
  trueValue?: string
  falseValue?: string
  modelValue?: unknown
}>(), {
  trueValue: '1',
  falseValue: '0',
})

const emit = defineEmits<{ 'update:modelValue': [value: unknown] }>()
const slots = useSlots()
const hasSlot = !!slots.default
const hasDescription = !!slots.description
const val = ref(props.modelValue)
</script>
