<script setup lang="ts">
import { Bookmark, BookmarkResponse, Tag } from '@/lib/datatypes';
import { onMounted, Ref, ref } from 'vue';
import axios from '@/lib/axios';
import { AxiosResponse } from 'axios';
import BookmarkElement from '@/components/BookmarkElement.vue';
import TagElement from '@/components/TagElement.vue';

// State data
const hasData: Ref<boolean> = ref<boolean>(false);
const activeTags: Ref<number[]> = ref<number[]>([]);
const bookmarks: Ref<Bookmark[]> = ref<Bookmark[]>([]);
const tags: Ref<Tag[]> = ref<Tag[]>([]);

// Check that we have data from the endpoints
const checkData = async () => {
  const bookmarkCount = await axios.get('bookmarks/count')
    .then((response) => response.data)
    .catch(() => 0);
  hasData.value = bookmarkCount !== 0;  
};

// Method to fetch all bookmark data from the backend
const fetchBoomarks = async () => {
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
};

// Method to fetch all tag data from the backend
const fetchTags = async () => {
  await axios.get('tags')
    .then((response: AxiosResponse) => {
      tags.value = [...response.data];
    })
    .catch((error) => {
      console.log(error);
    });
};

// Method to fetch all bookmarks belonging to a tag from the backend
const fetchBookmarkByTag = async (tagId: number) => {
  // Check if the tagId already exists in activeTags
  if (activeTags.value.includes(tagId)) {
    // Pop the "active tag"
    const index = activeTags.value.indexOf(tagId);
    if (index !== -1) {
      activeTags.value.splice(index, 1);
    }
    
  } else {
    activeTags.value.push(tagId);
  }
  if (activeTags.value.length) {
    // Fetch the bookmarks that have the specififed tags
    await axios.post(`bookmarks/bytags`, { tags: activeTags.value })
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
  } else {
    fetchBoomarks();
  }
};

// Method to crawl the specified webpage, gather data from it, and seed to DB
const seedDatabase = async (attempts: number) => {
  const fetch = async () => {
    fetchBoomarks();
    await fetchTags();
    checkData();
  };
  if (attempts <= 3) {
    await axios.get('/bookmarks/fetch')
      .then(async () => await fetch())
      .catch(async () => await seedDatabase(attempts++));
  }
};

// Call the onMounted lifecycle hook
onMounted(async () => {
  await checkData();
  if (hasData.value) {
    fetchBoomarks();
    await fetchTags();
  }
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
      @click="seedDatabase(1)"
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
        :class="activeTags.includes(tag.id) ? 
          'bg-slate-500 hover:bg-slate-600' : 
          'hover:bg-slate-400'
        "
        @click="fetchBookmarkByTag(tag.id)"
      />
    </div>
    <div
      id="bookmark-container"
      class="flex flex-col justify-center p-4 gap-4 w-2/3"
    >
      <template v-if="bookmarks.length">
        <BookmarkElement
          v-for="bookmark in bookmarks"
          :key="bookmark.id"
          :bookmark="bookmark"
        />
      </template>
      <p
        v-else
        class="w-full text-center"
      >
        No data!
      </p>
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
