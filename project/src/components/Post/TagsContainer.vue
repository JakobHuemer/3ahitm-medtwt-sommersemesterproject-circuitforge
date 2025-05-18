<script setup lang="ts">
import type { Version } from '@/types/version-types.ts'

const props = defineProps<{
    versions: Version[]
    hashtags: string[]
    deletionMode?: boolean
}>()

defineEmits<{
    (e: 'delete', version: Version): void
}>()
</script>

<template>
    <div class="tags-container">
        <div class="tags-list versions">
            <div
                :data-deletion-mode="deletionMode"
                class="tag tag-version"
                v-for="version of versions"
                :key="version.version"
                @click="
                    (ev) => {
                        $emit('delete', version)
                    }
                "
            >
                <div class="tag-version-icon">
                    <img src="../../assets/img/tags/name_tag.png" alt="v" />
                </div>
                <span>{{ version.version }}</span>
            </div>
        </div>
        <div class="no-versions-warning" v-if="versions.length === 0">
            <span>no versions...</span>
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
            user-select: none;
            padding: 6px 8px;
            border-radius: var(--border-radius-s);
            transition: background-color 0.1s;
            cursor: pointer;

            &.tag-version {
                background: var(--col-secondary);
                display: flex;
                gap: var(--gap-4);
                align-items: center;

                &[data-deletion-mode='true']:hover {
                    color: var(--col-error);
                    text-decoration: line-through;
                }

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

.no-versions-warning {
    color: var(--col-text-secondary);
    margin-left: var(--gap-8);
}
</style>
