<script async setup lang="ts">
import { ref } from 'vue'
import TagsContainer from '@/components/Post/TagsContainer.vue'
import { type Version } from '@/types/version-types.d'
import type { Post } from '@/types/post'
import { useApi } from '@/store/useApi.ts'
import getHashtags from '@/util/get-hashtags.ts'
import ImagePreview from '@/components/ImagePreview.vue'
import fileFromAsset from '@/util/file-from-asset.ts'

const api = useApi()

const props = defineProps<{
    postId: number
}>()

async function loadPost(id: number): Promise<Post> {
    const res = await api.api.get<Post>('/posts/' + id)

    return res.data
}

const post = await loadPost(props.postId)
</script>

<template>
    <div class="post-card content">
        <div class="img-header">
            <ImagePreview
                v-if="post.assets.length > 0"
                :file="fileFromAsset(post.assets.filter((a) => a.asset_type == 'image')[0])"
            />
        </div>
        <div class="post-content">
            <span class="post-title">{{ post.title }}</span>
            <div class="post-author-date">
                <span class="post-author">{{ 'jakki_' /* TODO: will be resolved later */ }}</span>
                <span class="post-date">{{
                    new Date(post.created_at).toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric',
                    })
                }}</span>
            </div>

            <div class="post-stats-container">
                <!--                <div class="post-stat post-stat-views">-->
                <!--                    <div class="post-stat-icon">-->
                <!--                        <img src="../../assets/img/stats/ender_eye.png" alt="ender_eye" />-->
                <!--                    </div>-->
                <!--                    <span class="post-stat-text">{{ post.views }}</span>-->
                <!--                </div>-->
                <div class="post-stat post-stat-likes">
                    <div class="post-stat-icon">
                        <img src="../../assets/img/stats/redstone.png" alt="redstone" />
                    </div>
                    <span class="post-stat-text">{{ post.rating }}</span>
                </div>
                <!--                <div class="post-stat post-stat-comments">-->
                <!--                    <div class="post-stat-icon">-->
                <!--                        <img src="../../assets/img/stats/book.png" alt="paper" />-->
                <!--                    </div>-->
                <!--                    <span class="post-stat-text">{{ post.comments }}</span>-->
                <!--                </div>-->
                <div class="post-stat post-stat-downloads">
                    <div class="post-stat-icon">
                        <img src="../../assets/img/stats/paper.png" alt="paper" />
                    </div>
                    <span class="post-stat-text">{{
                        post.assets
                            .filter((a) => a.asset_type == 'asset')
                            .map((a) => a.downloads)
                            .reduce((p, c) => c + p)
                    }}</span>
                </div>
            </div>

            <TagsContainer :versions="post.versions" :hashtags="getHashtags(post.content)" />
        </div>
    </div>
</template>

<style scoped>
img:not(.img-header img) {
    image-rendering: pixelated;
}

.post-card {
    isolation: isolate;
    background: var(--col-content);
    border-radius: var(--border-radius);
    position: relative;

    overflow: hidden;

    display: flex;
    flex-direction: column;

    .img-header {
        width: 100%;
        height: 100%;
        max-height: 400px;
        z-index: -1;
        overflow: hidden;

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }

    .post-content {
        padding: var(--gap-16);
        display: grid;
        gap: var(--gap-8);

        .post-title {
            font-size: var(--font-size-card-title);
        }

        .post-author-date {
            display: flex;
            gap: var(--gap-4);
            color: var(--col-text-secondary);

            .post-author {
                color: var(--col-accent);

                &::after {
                    content: ' •';
                    color: var(--col-text-secondary);
                }
            }
        }

        .post-stats-container {
            display: flex;
            gap: var(--gap-8);
            flex-wrap: wrap;

            .post-stat {
                display: flex;
                gap: var(--gap-4);
            }
        }
    }
}
</style>
