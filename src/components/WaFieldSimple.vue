<template>
  <WaField :name="name">
    <div class="value" :class="value_class"><slot /></div>
  </WaField>
</template>

<script setup lang="ts">
import WaField from './WaField.vue'
defineProps<{ name?: string; value_class?: string }>()
</script>
