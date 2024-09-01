<script setup lang="ts">
import { Bookmark, BookmarkResponse, Tag } from '@/lib/datatypes';
import { onMounted, Ref, ref } from 'vue';
import axios from '@/lib/axios';
import { AxiosResponse } from 'axios';
import BookmarkElement from '@/components/BookmarkElement.vue';
import TagElement from '@/components/TagElement.vue';

// State data
const hasData: Ref<boolean> = ref<boolean>(false);
const activeTag: Ref<number|null> = ref<number|null>(null);
const bookmarks: Ref<Bookmark[]> = ref<Bookmark[]>([]);
const tags: Ref<Tag[]> = ref<Tag[]>([]);

// Check that we have data from the endpoints
const checkData = () => {
  if (bookmarks.value.length && tags.value.length) {
    hasData.value = true;
  }
};

const fetchData = async () => {
  await axios.get('bookmarks')
    .then((response: AxiosResponse) => {
      bookmarks.value = [...response.data.map((bookmark: BookmarkResponse) => {
        return {
          id: bookmark.id,
          title: bookmark.title,
          description: bookmark.description,
          createdAt: new Date(bookmark.created_at),
          url: bookmark.url,
          urlStatusCode: bookmark.url_status_code,
          tags: bookmark.tags,
        };
      })];
    })
    .catch((error) => {
      console.log(error);
    });
  await axios.get('tags')
    .then((response: AxiosResponse) => {
      tags.value = [...response.data];
    })
    .catch((error) => {
      console.log(error);
    });
  // Unset the "active tag"
  activeTag.value = null;
};

const seedDatabase = async () => {
  await axios.get('/bookmarks/fetch')
    .then(async () => {
      await fetchData();
      checkData();
    })
    .catch((error) => {
      console.log(error);
    });
};

const fetchBookmarkByTag = async (tagId: number) => {
  activeTag.value = tagId;
  await axios.get(`bookmarks/tag/${tagId}`)
    .then((response: AxiosResponse) => {
      bookmarks.value = [...response.data.map((bookmark: BookmarkResponse) => {
        return {
          id: bookmark.id,
          title: bookmark.title,
          description: bookmark.description,
          createdAt: new Date(bookmark.created_at),
          url: bookmark.url,
          urlStatusCode: bookmark.url_status_code,
          tags: bookmark.tags,
        };
      })];
      // Set the "active tag"
    })
    .catch((error) => {
      console.log(error);
    });
};

// Call the onMounted lifecycle hook
onMounted(async () => {
  await fetchData();
  checkData();
});
</script>

<template>
  <main
    v-if="!hasData"
    class="flex flex-col justify-center items-center gap-4 h-screen w-screen"
  >
    <h1 class="text-3xl font-bold">
      No data!
    </h1>
    <button
      class="py-2 px-4 bg-slate-300 hover:bg-slate-400 rounded cursor-pointer"
      @click="seedDatabase()"
    >
      Fetch Data!
    </button>
  </main> 
  <main
    v-else
    class="flex flex-col items-center py-4"
  >
    <div
      id="tag-container"
      class="flex justify-center p-4 gap-4 w-2/3"
    >
      <TagElement
        v-for="tag in tags"
        :key="tag.id"
        :tag="tag"
        class="cursor-pointer shadow-sm"
        :class="tag.id === activeTag ? 'bg-slate-500 hover:bg-slate-600' : 'hover:bg-slate-400 '"
        @click="tag.id === activeTag ? fetchData() : fetchBookmarkByTag(tag.id)"
      />
    </div>
    <div
      id="bookmark-container"
      class="flex flex-col justify-center p-4 gap-4 w-2/3"
    >
      <BookmarkElement
        v-for="bookmark in bookmarks"
        :key="bookmark.id"
        :bookmark="bookmark"
      />
    </div>
  </main>
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
