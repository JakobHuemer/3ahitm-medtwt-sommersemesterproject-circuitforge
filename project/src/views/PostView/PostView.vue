<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTrash, faXmark } from '@fortawesome/free-solid-svg-icons'
import { ref, watch } from 'vue'
import TagsContainer from '@/components/Post/TagsContainer.vue'
import EditorWrapper from '@/components/Post/EditorWrapper.vue'
import type { JSONContent } from '@tiptap/vue-3'
import FilePreview from '@/components/ImagePreview.vue'
import { type Version } from '@/types/version-types.d'
import type { Asset } from '@/types/asset'
import type User from '@/types/user.d'
import { useApi } from '@/store/useApi.ts'
import { useRoute } from 'vue-router'
import type { Post } from '@/types/post.d'

const hashtags = ref<string[]>([])

const title = ref<string | null>(null)
const content = ref<JSONContent>({})
const imagesList = ref<Asset[] | null>(null)
const assetsList = ref<Asset[] | null>(null)
const versionsList = ref<Version[] | null>(null)
const rating = ref<number | null>(null)
const author = ref<User | null>(null)
const created_at = ref<string | null>(null)
const updated_at = ref<string | null>(null)

const api = useApi()
const route = useRoute()

api.api.get<Post>(`/posts/${route.params.id}`).then((res) => {
    const post: Post = res.data

    title.value = post.title
    content.value = post.content
    assetsList.value = post.assets.filter((a) => a.asset_type == 'asset')
    imagesList.value = post.assets.filter((a) => a.asset_type == 'image')
    versionsList.value = post.versions
    rating.value = post.rating
    author.value = post.author
    created_at.value = post.created_at
    updated_at.value = post.updated_at
})

const titleElement = ref<null | HTMLTextAreaElement>(null)
const maxlength = ref(100)

window.addEventListener('resize', () => resizeTextarea())

const resizeTextarea = () => {
    const textarea = titleElement.value
    if (textarea) {
        textarea.style.height = 'auto'
        textarea.style.height = `${textarea.scrollHeight}px`
    }
}

function updateHashTagsFromObj(obj: any) {
    if (!obj) return
    if (obj['content'] && Array.isArray(obj['content'])) {
        for (const c of obj['content']) {
            updateHashTagsFromObj(c)
        }
    } else if (obj['text']) {
        // check if it is a mark?
        if (
            obj['marks'] &&
            (obj['marks'] as { type: string }[]).some((obj) => obj['type'] === 'hashtag')
        ) {
            //  but should be not matching
            let text: string = obj['text']

            let hashtag = text.slice(1)

            hashtags.value.push(hashtag)
        }
    }
}

function downloadAsset(asset: Asset) {
    const link = document.createElement('a')
    link.href = import.meta.env.BASE_URL + '/assets/' + asset.id
    link.download = asset.file_name

    link.click()
}

async function fileFromAsset(asset: Asset): Promise<File> {
    const res = await api.api.get('/assets/' + asset.id, {
        responseType: 'blob',
    })

    return new File([res.data], asset.file_name, {
        type: res.data.type || asset.mime_type,
    })
}

watch(content, () => {
    updateHashTagsFromObj(content.value)
})
</script>

<template>
    <div class="container-wrapper">
        <h2>Post</h2>
        <div class="container container-images">
            <div class="scroll-container">
                <div class="post-image" v-for="image in imagesList" :key="image.id">
                    <div class="delete-button">
                        <FontAwesomeIcon :icon="faTrash" />
                    </div>
                    <FilePreview :file="fileFromAsset(image)" />
                </div>
            </div>
        </div>

        <div class="container container-post">
            <p ref="titleElement" class="title title-input" @input="resizeTextarea()" disabled>
                {{ title }}
            </p>

            <TagsContainer
                v-if="versionsList"
                :versions="versionsList"
                :hashtags="hashtags"
                :deletion-mode="false"
            />

            <div class="editor-wrapper">
                <EditorWrapper
                    v-if="content"
                    :editable="false"
                    v-model="content"
                    :hashtag-length="40"
                    :character-limit="4096"
                    @mounted="updateHashTagsFromObj"
                />
            </div>

            <div class="downloadables" v-if="assetsList?.length !== 0">
                <h3 class="downloads-title">Downloads</h3>
                <div class="downloads-container">
                    <div
                        class="download"
                        v-for="asset of assetsList"
                        :key="asset.id"
                        @click="downloadAsset(asset)"
                    >
                        <div class="thumbnail">
                            <img src="../../assets/img/icons/shulker-white.png" alt="download" />
                        </div>
                        <span class="asset-file-name">{{ asset.file_name }}</span>
                    </div>
                </div>
            </div>

            <div class="final-section"></div>
        </div>
    </div>
</template>

<style scoped>
h2 {
    text-align: center;
    font-family: var(--font-title);
    font-size: var(--font-size-body);
}

.container {
    border-radius: var(--border-radius);
}

.container-wrapper {
    display: flex;
    flex-direction: column;
    gap: var(--gap-16);
    position: relative;
}

.container-images {
    --container-images-gap: var(--gap-16);
    width: 100%;
    position: relative;
    padding: var(--container-images-gap) 0;
    overflow: hidden;

    &::before,
    &::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: calc(var(--container-images-gap) * 2.5);
        z-index: 100;
        background: linear-gradient(
            to left,
            transparent 0%,
            color-mix(in srgb, var(--col-container) 5%, transparent) 30%,
            color-mix(in srgb, var(--col-container) 10%, transparent) 50%,
            color-mix(in srgb, var(--col-container) 25%, transparent) 65%,
            color-mix(in srgb, var(--col-container) 50%, transparent) 80%,
            color-mix(in srgb, var(--col-container) 85%, transparent) 90%,
            var(--col-container) 100%
        );
        /*mask-image: linear-gradient(to right, white, transparent);*/
    }

    &::after {
        left: 100%;
        transform: translateX(-100%) scaleX(-1);
    }

    .scroll-container {
        overflow-x: scroll;
        overflow-y: hidden;

        padding-bottom: 4px;
        margin-bottom: -4px;

        width: auto;
        gap: var(--gap-8);
        display: flex;

        /* Scrollbar */

        /* Chrome etc. */

        &::-webkit-scrollbar {
            height: 3px;
            padding: 10px;
        }

        &::-webkit-scrollbar-track {
            background: transparent;
            margin: var(--container-images-gap);
        }

        &::-webkit-scrollbar-thumb {
            background: color-mix(in srgb, var(--col-surface-active) 60%, white);
            border-radius: 10px;
        }

        /* Firefox scrollbar styling */

        & {
            scrollbar-width: thin; /* "auto", "thin", or "none" */
            scrollbar-color: color-mix(in srgb, var(--col-surface-active) 60%, white) transparent;
        }

        .post-image {
            display: inline;
            height: 14rem;
            aspect-ratio: 1;
            border-radius: var(--border-radius-s);
            position: relative;

            #image-upload {
                display: none;
            }

            .delete-button {
                display: none;
            }

            &:first-child {
                margin-left: var(--container-images-gap);
            }

            &:last-child {
                margin-right: var(--container-images-gap);
            }

            .thumbnail {
                border-radius: var(--border-radius-s);
                height: 100%;
                width: 100%;
                object-fit: cover;
            }
        }
    }
}

.container-post {
    padding: var(--gap-16);

    display: grid;
    gap: var(--gap-16);

    .title {
        font-size: var(--font-size-title);
        font-family: var(--font-body);
        font-weight: bold;

        border-radius: var(--border-radius);

        width: 100%;

        resize: none;
        overflow-y: hidden;
    }
}

.editor-wrapper {
    width: 100%;
    overflow-x: visible;
    padding: var(--gap-8);
    border-radius: var(--border-radius);
    background: var(--col-content);
}

.downloads-title {
    font-size: 1.8rem;
    font-weight: bold;
}

.downloadables {
    display: grid;
    gap: var(--gap-12);

    .downloads-container {
        padding-left: var(--gap-16);
        display: grid;
        gap: var(--gap-8);

        .download {
            display: flex;
            align-items: center;
            gap: var(--gap-4);
            justify-self: start;
            color: var(--col-accent);
            user-select: none;
            cursor: pointer;

            span {
                color: inherit;
            }

            .thumbnail {
                height: 16px;
            }

            &:hover {
                text-decoration: underline;
            }
        }
    }
}

.final-section {
    display: flex;
    justify-content: end;
    gap: var(--gap-8);
}
</style>
