<script setup lang="ts">
import { Bookmark } from '@/lib/datatypes';
import TagElement from '@/components/TagElement.vue';
import URLStatusCodeIndicator from '@/components/URLStatusCodeIndicator.vue';

defineProps<{ bookmark: Bookmark }>();

/**
 * Get the span colour code to indicate the validity of the URL
 * @param code 
 */
function getFailStatusCodeColor(code: number): string {
  if (code > 499) {
    return 'bg-red-400';
  }
  if (code > 399) {
    return 'bg-red-300';
  }
  return 'bg-gray-200';
}
</script>

<template>
  <div class="flex flex-col p-4 rounded bg-slate-100 gap-2">
    <a
      v-if="bookmark.urlStatusCode>=200 && bookmark.urlStatusCode<=299"
      :href="bookmark.url"
      class="text-blue-600"
    >
      <URLStatusCodeIndicator
        :status-code="bookmark.urlStatusCode"
        color="bg-green-200"
      />
      {{ bookmark.url }}
    </a>
    <p v-else>
      <URLStatusCodeIndicator
        :status-code="bookmark.urlStatusCode"
        :color="getFailStatusCodeColor(bookmark.urlStatusCode)"
      />
      {{ bookmark.url }}
    </p>
    <h1 class="font-bold text-xl">
      {{ bookmark.title }}
    </h1>
    <p>{{ bookmark.description }}</p>
    <p>{{ bookmark.createdAt.toLocaleString() }}</p>
    <div class="flex gap-2">
      <TagElement
        v-for="tag in bookmark.tags"
        :key="tag.name"
        :tag="tag"
      />
    </div>
  </div>
</template>
