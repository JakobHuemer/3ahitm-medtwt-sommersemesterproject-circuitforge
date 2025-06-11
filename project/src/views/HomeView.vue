<script setup lang="ts">
import PostCard from '@/components/Post/PostCard.vue'
import { ref } from 'vue'
import { useApi } from '@/store/useApi.ts'
import ButtonComponent from '@/components/ButtonComponent.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const api = useApi()
const fetchedPostIDs = ref<number[]>([])

api.api.get<number[]>('/posts').then((res) => {
    fetchedPostIDs.value = res.data
})
</script>

<template>
    <div class="create-button-wrapper" v-if="api.state.isAuthenticated">
        <RouterLink to="/post/create">
            <ButtonComponent button-type="primary" @click="">Create Post </ButtonComponent>
        </RouterLink>

        <br />
        <br />
        <br />
    </div>

    <div class="posts">
        <Suspense v-for="postId in fetchedPostIDs">
            <template #default>
                <PostCard :post-id="postId" @click="router.push('/post/' + postId)" />
            </template>
        </Suspense>
    </div>
</template>

<style>
.posts {
    display: grid;

    /* 200 - 300 px big grid cards */
    grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
    gap: var(--gap-32);
}
</style>
