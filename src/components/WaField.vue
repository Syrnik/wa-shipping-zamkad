<template>
  <div class="field">
    <div v-if="name && name.length" class="name" v-html="name" />
    <slot />
  </div>
</template>

<script setup lang="ts">
defineProps<{ name?: string }>()
</script>
