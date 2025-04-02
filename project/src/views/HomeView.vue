<script setup lang="ts">
import PostCard from '@/components/PostCard.vue'
</script>

<template>
    <span>hello world i am mom</span>
    <div class="posts">
        <Suspense>
            <template #fallback>
                <h1>Posts</h1>
            </template>
            <template #default>
                <PostCard :post-id="123" />
            </template>
        </Suspense>
    </div>
</template>
