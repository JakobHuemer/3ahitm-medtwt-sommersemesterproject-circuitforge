<script async setup lang="ts">
import { ref } from 'vue'

const props = defineProps<{
    postId: number
}>()

// fetching post data
const loadPost = async (
    id: number,
): Promise<{
    post_id: number
    author_id: number
    title: string
    content: string
    created_at: Date
    updated_at: Date
    assets: string[]
    likes: number
    views: number
    comments: number
    downloads: number
    hashtags: string[]
    versions: string[]
}> => {
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve({
                post_id: id,
                // TODO: will be a user object with necessary things
                author_id: 123,
                title: 'Some random Title',
                content:
                    'Um die Piston-Tür zu öffnen, muss man einfach\nnur' +
                    ' auf die Druckplatten steigen und schon sind sie offen',
                created_at: new Date('2025-01-04 18:34:43'),
                updated_at: new Date(),
                assets: ['world.tar.gz', 'door.schematic'],
                views: 6537,
                likes: 123,
                comments: 54,
                downloads: 23,
                versions: ['1.20.2', '1.20.1', '1.19.4'],
                hashtags: ['piston', 'door', 'redstone', 'tutorial'],
            })
        }, Math.random() * 1000)
    })
}

const post = await loadPost(123)
</script>

<template>
    <div class="post-card">
        <div class="img-header">
            <img src="../assets/img/temp/post-thumbnail-tall.png" alt="thumbnail tall" />
        </div>
        <div class="post-content">
            <span class="post-title">{{ post.title }}</span>
            <div class="post-author-date">
                <span class="post-author">{{ 'jakki_' /* TODO: will be resolved later */ }}</span>
                <span class="post-date">{{
                    post.created_at.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric',
                    })
                }}</span>
            </div>

            <div class="post-stats-container">
                <div class="post-stat post-stat-views">
                    <div class="post-stat-icon">
                        <img src="../assets/img/stats/ender_eye.png" alt="ender_eye" />
                    </div>
                    <span class="post-stat-text">{{ post.views }}</span>
                </div>
                <div class="post-stat post-stat-likes">
                    <div class="post-stat-icon">
                        <img src="../assets/img/stats/redstone.png" alt="redstone" />
                    </div>
                    <span class="post-stat-text">{{ post.likes }}</span>
                </div>
                <div class="post-stat post-stat-comments">
                    <div class="post-stat-icon">
                        <img src="../assets/img/stats/book.png" alt="paper" />
                    </div>
                    <span class="post-stat-text">{{ post.comments }}</span>
                </div>
                <div class="post-stat post-stat-downloads">
                    <div class="post-stat-icon">
                        <img src="../assets/img/stats/paper.png" alt="paper" />
                    </div>
                    <span class="post-stat-text">{{ post.downloads }}</span>
                </div>
            </div>

            <div class="tags-container">
                <div class="tags-list versions">
                    <div class="tag tag-version" v-for="version of post.versions">
                        <div class="tag-version-icon">
                            <img src="../assets/img/tags/name_tag.png" alt="v" />
                        </div>
                        <span>{{ version }}</span>
                    </div>
                </div>

                <div class="tags-list hashtags">
                    <div class="tag tag-hashtag" v-for="hashtag of post.hashtags">
                        <span>{{ hashtag }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
img:not(.img-header img) {
    image-rendering: pixelated;
}

.post-card {
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

            .post-stat {
                display: flex;
                gap: var(--gap-4);
            }
        }

        .tags-container {
            display: grid;
            gap: var(--gap-8);
            font-family: 'Minecraftia', sans-serif;

            .tags-list {
                display: flex;
                gap: var(--gap-4);

                .tag {
                    padding: 6px 8px;
                    border-radius: var(--border-radius-s);
                    transition: background-color 0.1s;
                    cursor: pointer;

                    &.tag-version {
                        background: var(--col-secondary);
                        display: flex;
                        gap: var(--gap-4);
                        align-items: center;

                        &:hover {
                            background: var(--col-secondary-hover);
                        }
                    }

                    &.tag-hashtag {
                        background: var(--col-primary);

                        &:hover {
                            background-color: var(--col-primary-hover);
                        }

                        &::before {
                            content: '#';
                        }
                    }
                }
            }
        }
    }
}
</style>
