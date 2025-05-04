<script setup lang="ts">
const props = defineProps<{
    versions: string[]
    hashtags: string[]
}>()
</script>

<template>
    <div class="tags-container">
        <div class="tags-list versions">
            <div class="tag tag-version" v-for="version of versions">
                <div class="tag-version-icon">
                    <img src="../assets/img/tags/name_tag.png" alt="v" />
                </div>
                <span>{{ version }}</span>
            </div>
        </div>

        <div class="tags-list hashtags">
            <div class="tag tag-hashtag" v-for="hashtag of hashtags">
                <span>{{ hashtag }}</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
.tags-container {
    display: grid;
    gap: var(--gap-8);
    font-size: calc(var(--font-size-body) * 0.8);
    font-family: 'Minecraftia', sans-serif;
    image-rendering: pixelated;

    .tags-list {
        display: flex;
        gap: var(--gap-4);
        flex-wrap: wrap;

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
</style>
