<template>
  <div class="field">
    <div v-if="name && name.length" :class="['name', nameClass]" v-html="name" />
    <slot />
  </div>
</template>

<script setup lang="ts">
defineProps<{ name?: string; nameClass?: string }>()
</script>
