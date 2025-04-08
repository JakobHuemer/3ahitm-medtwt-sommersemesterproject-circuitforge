<script setup lang="ts">
import PostCard from '@/components/PostCard.vue'
</script>

<template>
    <div class="posts">
        <Suspense>
            <template #default>
                <PostCard :post-id="123" />
            </template>
        </Suspense>
        <Suspense>
            <template #default>
                <PostCard :post-id="123" />
            </template>
        </Suspense>
        <Suspense>
            <template #default>
                <PostCard :post-id="123" />
            </template>
        </Suspense>
        <Suspense>
            <template #default>
                <PostCard :post-id="123" />
            </template>
        </Suspense>
    </div>
</template>

<style></style>
