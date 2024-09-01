<!-- eslint-disable vue/multi-word-component-names -->
<script setup lang="ts">
import { Tag } from '@/lib/datatypes.ts';

defineProps<{ tag: Tag }>();
</script>

<template>
  <div class="w-auto py-1 px-4 bg-slate-300 rounded">
    <p>{{ tag.name }}</p>
  </div>
</template>
