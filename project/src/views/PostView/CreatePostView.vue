<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCloudArrowUp, faPlus, faTrash, faXmark } from '@fortawesome/free-solid-svg-icons'
import { onMounted, reactive, ref, toRaw, watch } from 'vue'
import TagsContainer from '@/components/Post/TagsContainer.vue'
import ButtonComponent from '@/components/ButtonComponent.vue'
import EditorWrapper from '@/components/Post/EditorWrapper.vue'
import type { JSONContent } from '@tiptap/vue-3'
import FilePreview from '@/components/ImagePreview.vue'
import levenshtein from '@/util/levenshtein.ts'
import { useApi } from '@/store/useApi.ts'
import { useEventListener, watchDebounced } from '@vueuse/core'
import { type Version, versionOptions, type VersionType } from '@/types/version-types.d'
import VersionListItem from '@/components/Post/VersionListItem.vue'
import type { Post } from '@/types/post.d'
import router from '@/router'

const hashtags = ref<string[]>([])
const versions = ref<Version[]>([])

const title = ref<string>('')
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

watch(title, () => {
    title.value = title.value?.replace(/\n/g, '') ?? ''
})

const content = ref<JSONContent>({})

watch(content, () => {
    hashtags.value = []

    updateHashTagsFromObj(content.value)
})

type IndexedFileMap = Map<number, File>

const imageInput = ref<HTMLInputElement>()
const imageList = ref<IndexedFileMap>(new Map())

const assetInput = ref<HTMLInputElement>()
const assetList = ref<IndexedFileMap>(new Map())

let prevImageNum = 1
let prevAssetNum = 1

function handleFile(
    input: HTMLInputElement | undefined,
    list: IndexedFileMap,
    countRef: 'img' | 'asset',
) {
    if (!input?.files || input.files.length == 0) return

    // add files
    for (let file of input.files) {
        let num = countRef == 'img' ? prevImageNum++ : prevAssetNum++
        if (
            !(
                countRef == 'asset' &&
                Array.from(assetList.value.values()).some((e) => e.name == file.name)
            )
        ) {
            list.set(num, file)
        }
    }
}

function getHandleImage(event: Event) {
    handleFile(imageInput.value, imageList.value, 'img')
}

function getHandleAsset(event: Event) {
    handleFile(assetInput.value, assetList.value, 'asset')
}

const hashtagMaxLength = ref(40)

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

// versions

const fetchedVersionsList = ref<Version[]>([])
const displayFilteredVersionsList = ref<Version[]>([])

const versionQuery = ref<string>('')
const versionTypeQuery = reactive<Set<VersionType>>(new Set(['release']))

const selectedVersionIndex = ref<number>(0)
const selectedVersionElement = ref<null | HTMLElement>(null)

// statuses
const isFetching = ref<boolean>(false)
const isErrorAfterFetching = ref<boolean>(false)

const showVersionsList = ref<boolean>(false)
const versionsContainerRef = ref<null | HTMLElement>(null)

function toggleVersionType(item: VersionType) {
    if (versionTypeQuery.has(item)) {
        versionTypeQuery.delete(item)
    } else {
        versionTypeQuery.add(item)
    }
}

// displayFilteredVersionsList updater
watch([fetchedVersionsList, versionQuery, versionTypeQuery], () => {
    // filter by query
    displayFilteredVersionsList.value = fetchedVersionsList.value.filter((item) => {
        return item.version.toLowerCase().includes(versionQuery.value.toLowerCase())
    })

    // filter by type
    displayFilteredVersionsList.value = fetchedVersionsList.value.filter((item) => {
        return versionTypeQuery.has(item.type)
    })

    // sort by levenshtein
    displayFilteredVersionsList.value = displayFilteredVersionsList.value.sort((a, b) => {
        return (
            levenshtein(a.version, versionQuery.value) - levenshtein(b.version, versionQuery.value)
        )
    })

    // limit list to max 10 items
    const maxItems = 100
    if (displayFilteredVersionsList.value.length > maxItems) {
        displayFilteredVersionsList.value = displayFilteredVersionsList.value.slice(0, maxItems)
    }
})

watch(displayFilteredVersionsList, () => {
    // handle selected item
    setSelectedVersionIndex(selectedVersionIndex.value)
})

const api = useApi()

// fetch new versions
watchDebounced(
    [versionQuery, versionTypeQuery],
    async () => {
        isErrorAfterFetching.value = false

        if (versionQuery.value == '') {
            return
        }

        try {
            isFetching.value = true
            const response = await api.api.get<Version[]>(`/versions`, {
                params: {
                    query: versionQuery.value,
                    // array of types[]={0}&
                    types: Array.from(versionTypeQuery.values()),
                    limit: 100,
                },
            })

            fetchedVersionsList.value = response.data

            isFetching.value = false
        } catch (e) {
            console.error('Failed to fetch versions')
            isErrorAfterFetching.value = true
            isFetching.value = false
        }
    },
    {
        debounce: 100,
        maxWait: 300,
    },
)

/**
 * update selected index inside the available range of matching versions
 * when the target index is outside the range it will be clamped
 * @param newIndex target index
 * @param doScroll
 */
function setSelectedVersionIndex(newIndex: number, doScroll: boolean = true) {
    selectedVersionIndex.value = Math.max(
        0,
        Math.min(newIndex, displayFilteredVersionsList.value.length - 1),
    )

    if (doScroll && selectedVersionElement.value) {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches
        selectedVersionElement.value.scrollIntoView({
            behavior: prefersReducedMotion ? 'auto' : 'smooth',
            block: 'center',
        })
    }
}

function submitSelectedVersion() {
    const submittedVersion = displayFilteredVersionsList.value[selectedVersionIndex.value]

    if (submittedVersion) {
        versionQuery.value = ''

        // add version
        if (!versions.value.find((e) => e.version === submittedVersion.version)) {
            versions.value.push(submittedVersion)
            // sort by date
            versions.value.sort((a, b) => {
                return new Date(b.released).getTime() - new Date(a.released).getTime()
            })
        }
    }
}

function handleClickOutside(event: MouseEvent) {
    if (versionsContainerRef.value && !versionsContainerRef.value.contains(event.target as Node)) {
        showVersionsList.value = false
    }
}

useEventListener(document, 'mousedown', handleClickOutside)
useEventListener(document, 'keydown', (event) => {
    if (!showVersionsList.value) return

    if (['ArrowDown', 'ArrowUp', 'Enter'].includes(event.key)) event.preventDefault()
    if (event.key == 'ArrowDown') setSelectedVersionIndex(selectedVersionIndex.value + 1)
    else if (event.key == 'ArrowUp') setSelectedVersionIndex(selectedVersionIndex.value - 1)
    else if (event.key == 'Enter') submitSelectedVersion()
})

async function createPost() {
    const formData = new FormData()

    // title
    // content
    // versions
    // images
    // assets

    formData.append('title', title.value)
    formData.append('content', JSON.stringify(content.value))

    versions.value.forEach((v) => formData.append('versions[]', v.version))

    // iterate over imageList Map
    for (const [key, file] of imageList.value) {
        formData.append(`images[]`, file)
    }

    for (const [key, file] of assetList.value) {
        formData.append(`assets[]`, file)
    }

    const res = await api.api.post<Post>('/posts', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })

    router.push('/posts/' + res.data.id)
}
</script>

<template>
    <div class="container-wrapper">
        <h2>Create Post</h2>
        <div class="container container-images">
            <div class="scroll-container">
                <label for="image-upload" class="post-image add-image-button">
                    <FontAwesomeIcon :icon="faCloudArrowUp" />
                    <span>Add Image</span>
                    <input
                        multiple
                        accept="image/*"
                        ref="imageInput"
                        type="file"
                        @change="getHandleImage"
                        name="image-upload"
                        id="image-upload"
                    />
                </label>

                <div class="post-image" v-for="[key, file] in imageList" :key="key.toString()">
                    <div class="delete-button">
                        <FontAwesomeIcon :icon="faTrash" @click="imageList.delete(key)" />
                    </div>
                    <FilePreview :file="file" />
                </div>
            </div>
        </div>

        <div class="container container-post">
            <textarea
                ref="titleElement"
                v-model="title"
                rows="1"
                class="title title-input"
                @input="resizeTextarea()"
                placeholder="Title..."
                :maxlength="maxlength"
            ></textarea>

            <div
                class="version-finder"
                @focusin="
                    () => {
                        showVersionsList = true
                    }
                "
                ref="versionsContainerRef"
            >
                <div class="version-finder-input">
                    <input
                        placeholder="add version"
                        type="text"
                        v-model="versionQuery"
                        name="version-finder"
                        id="version-finder"
                    />
                </div>
                <div class="version-finder-selected">
                    <label :for="opt" v-for="opt in versionOptions" :key="opt" class="selected">
                        <label :for="opt" class="checkbox-repr">
                            <input
                                type="checkbox"
                                :name="opt"
                                :id="opt"
                                :checked="versionTypeQuery.has(opt)"
                                @change="toggleVersionType(opt)"
                            />
                        </label>
                        <label :for="opt" class="checkbox-label">{{
                            opt.replace(/_/g, ' ')
                        }}</label>
                    </label>
                </div>
                <div
                    v-if="versionQuery !== '' && showVersionsList"
                    class="version-results-container"
                >
                    <div class="version-results-list">
                        <div
                            class="version-results-info"
                            v-if="displayFilteredVersionsList.length === 0"
                        >
                            <span v-if="isFetching">searching...</span>
                            <span v-else-if="isErrorAfterFetching" style="color: var(--col-error)"
                                >could not get versions!</span
                            >
                            <span v-else>nothing found!</span>
                        </div>
                        <VersionListItem
                            v-if="displayFilteredVersionsList.length > 0"
                            v-for="(version, index) in displayFilteredVersionsList"
                            :data-selected="index == selectedVersionIndex"
                            :key="version.version"
                            :version="version"
                            class="version-results-item"
                            @mouseenter="
                                (ev) => {
                                    setSelectedVersionIndex(index, false)
                                }
                            "
                            @click="
                                (ev) => {
                                    setSelectedVersionIndex(index, false)
                                    submitSelectedVersion()
                                }
                            "
                            :ref="
                                (el) => {
                                    if (index === selectedVersionIndex) {
                                        // converting proxy to actual element
                                        // @ts-ignore
                                        selectedVersionElement = el.$el
                                    }
                                }
                            "
                        />
                    </div>
                </div>
            </div>

            <TagsContainer
                :versions="versions"
                :hashtags="hashtags"
                :deletion-mode="true"
                @delete="
                    (version) => {
                        versions = versions.filter((v) => v.version !== version.version)
                    }
                "
            />

            <div class="editor-wrapper">
                <EditorWrapper
                    v-model="content"
                    :initial-content="content"
                    :hashtag-length="hashtagMaxLength"
                    :character-limit="4096"
                    @mounted="updateHashTagsFromObj"
                />
            </div>

            <!--<div class="content">-->
            <!--    <h4>Content:</h4>-->
            <!--    <pre class="title" style="font-size: 13px; font-family: monospace" id="te">{{-->
            <!--        JSON.stringify(content, null, 2)-->
            <!--    }}</pre>-->
            <!--</div>-->

            <div class="downloadables">
                <h3 class="downloads-title">Downloads</h3>
                <div class="downloads-container">
                    <span v-if="assetList.size == 0" style="color: var(--col-text-secondary)"
                        >No Files uploaded yet...</span
                    >
                    <div
                        @click="assetList.delete(key)"
                        class="download"
                        v-for="[key, asset] of assetList"
                        :key="key"
                    >
                        <div class="thumbnail">
                            <img src="../../assets/img/icons/shulker-white.png" alt="download" />
                        </div>
                        <span class="asset-file-name">{{ asset.name }}</span>
                        <FontAwesomeIcon :icon="faXmark" class="delete-asset" />
                    </div>
                </div>

                <ButtonComponent @click="assetInput?.click()" :icon="faPlus"
                    >Add File
                </ButtonComponent>
                <input
                    style="display: none"
                    multiple
                    ref="assetInput"
                    type="file"
                    @change="getHandleAsset"
                    name="asset-upload"
                    id="asset-upload"
                />
            </div>

            <div class="final-section">
                <ButtonComponent>Cancel</ButtonComponent>
                <ButtonComponent button-type="primary" @click="createPost"
                    >Create Post</ButtonComponent
                >
            </div>
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
                position: absolute;
                top: var(--gap-8);
                right: var(--gap-8);
                color: color-mix(in srgb, var(--col-error) 70%, transparent);
                aspect-ratio: 1;
                height: 10%;

                transition:
                    color 0.1s,
                    transform 0.1s;

                cursor: pointer;

                svg {
                    height: 100%;
                }

                &:hover {
                    color: var(--col-error);
                    transform: scale(1.1);
                }

                &:active {
                    transform: scale(0.8);
                }
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

        .add-image-button {
            gap: var(--gap-8);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background: var(--col-surface);

            user-select: none;
            -moz-user-select: none;
            -webkit-user-select: none;

            cursor: pointer;

            transition: background-color 0.2s;

            &:hover {
                background: var(--col-surface-hover);
            }

            &:active {
                background: var(--col-surface-active);
            }

            svg {
                height: 20%;
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
        background: var(--col-content);

        outline: none;
        border: none;

        padding: var(--gap-8) var(--gap-12);

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

            span {
                color: inherit;
            }

            .thumbnail {
                height: 16px;
            }

            &:hover {
                cursor: pointer;
                text-decoration: line-through;
                text-decoration-color: var(--col-error);
                color: var(--col-error);
            }

            .delete-asset {
                display: none;
            }

            &:hover .delete-asset {
                display: block;
                margin-left: 0.2rem;
                color: color-mix(in srgb, var(--col-error) 70%, transparent);
                aspect-ratio: 1;
            }
        }
    }

    button {
        justify-self: start;
        margin-left: var(--gap-16);
        cursor: pointer;
    }
}

.final-section {
    display: flex;
    justify-content: end;
    gap: var(--gap-8);
}

.version-finder {
    position: relative;
    display: flex;
    gap: var(--gap-32);
    align-items: center;
    font-family: var(--font-special);

    input {
        background: var(--col-content);
        border-radius: var(--border-radius-s);
        padding: var(--gap-8) var(--gap-12);
        outline: none;
        border: none;
        z-index: 30;
    }

    /* popup visibility */

    .version-finder-selected {
        display: flex;
        gap: var(--gap-16);

        .selected {
            display: flex;
            align-items: center;
            gap: var(--gap-8);
            cursor: pointer;
            transition: color 0.2s;
            user-select: none;
            background: var(--col-content);
            padding: var(--gap-4);
            padding-right: calc(var(--font-size-nav-item) + var(--gap-4));
            border-radius: var(--border-radius);

            &:hover {
                color: var(--col-accent);
                background: var(--col-content-hover);
            }
        }

        input {
            display: none;
        }

        .selected:hover label.checkbox-repr:not(:has(input:checked)) {
            background: var(--col-surface-hover);
        }

        label.checkbox-repr {
            cursor: inherit;
            aspect-ratio: 1;
            height: 100%;
            background: var(--col-content-hover);
            border-radius: var(--border-radius-s);

            &:has(input:checked) {
                background: var(--col-primary);
            }
        }

        label.checkbox-label {
            cursor: inherit;
            padding-bottom: 0.2rem;
            white-space: nowrap;
        }
    }

    .version-results-container {
        display: none;
        overflow-y: auto;
        max-height: 12rem;
    }

    .version-results-container {
        z-index: 10;
        position: absolute;
        min-width: 235px;
        top: 120%;
        left: 0;
        background-color: var(--col-container);
        box-shadow: 0 0 20px black;
        padding: var(--gap-8);
        border-radius: var(--border-radius);
        display: grid;

        .version-results-info {
            color: var(--col-text-secondary);
            margin-top: -0.2em;
            font-size: 90%;
        }

        .version-results-list {
            display: grid;
            gap: var(--gap-4);
            flex-direction: column;

            .version-results-item {
                height: min-content;
                outline: 1px solid var(--col-container);

                &[data-selected='true'] {
                    background: var(--col-surface-hover);
                    outline: 1px solid var(--col-accent);
                }
            }
        }
    }
}
</style>
