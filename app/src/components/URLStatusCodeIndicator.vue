<!-- eslint-disable vue/multi-word-component-names -->
<script setup lang="ts">
defineProps<{ statusCode: number, color: string }>();
</script>

<template>
  <span :class="`text-black px-1 rounded ${color}`">{{ statusCode }}</span>
</template>

