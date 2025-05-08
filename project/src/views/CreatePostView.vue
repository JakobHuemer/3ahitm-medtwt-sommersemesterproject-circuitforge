<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCloudArrowUp, faPlus, faTrash, faXmark } from '@fortawesome/free-solid-svg-icons'
import { reactive, ref, shallowRef, watch } from 'vue'
import TagsContainer from '@/components/TagsContainer.vue'
import ButtonComponent from '@/components/ButtonComponent.vue'
import EditorWrapper from '@/components/EditorWrapper.vue'
import type { JSONContent } from '@tiptap/vue-3'
import FilePreview from '@/components/ImagePreview.vue'

const hashtags = ref<string[]>([])
const versions = ref<string[]>([])

const title = ref<string>('')
const titleElement = ref<null | HTMLTextAreaElement>(null)
const maxlength = ref(100)

window.addEventListener('resize', () => resizeTextarea())

const resizeTextarea = () => {
    const textarea = titleElement.value
    console.log(textarea)
    if (textarea) {
        console.log('UPDATING')
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
            countRef == 'asset' &&
            Array.from(assetList.value.values()).some((e) => e.name == file.name)
        ) {
            // check if file is already in list and then continue
            continue
        }
        list.set(num, file)
        console.log('putting (' + countRef + ' ): ', num + ' as ' + file.name)
    }
}

function getHandleImage(event: Event) {
    handleFile(imageInput.value, imageList.value, 'img')
}

function getHandleAsset(event: Event) {
    handleFile(assetInput.value, assetList.value, 'asset')
}

function updateHashTagsFromObj(obj: any) {
    if (obj['content'] && Array.isArray(obj['content'])) {
        for (const c of obj['content']) {
            console.log('DEEP')
            updateHashTagsFromObj(c)
        }
    } else if (obj['text']) {
        console.log()
        let text = obj['text']

        const regex = /(?<=#)[A-Za-z0-9_-]{1,20}(?=([^A-Za-z0-9_-]|$))/g

        const matches = (text.matchAll(regex).toArray() as string[][]).map((a) => a[0])

        if (matches.length > 0) {
            console.log(matches)
            hashtags.value.push(...matches)
        }
    }
}

updateHashTagsFromObj(content.value)

// versions

const versionOptions = reactive(['release', 'snapshot', 'old_beta', 'old_alpha'])
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

            <!-- Version Finder selector -->
            <div class="version-finder">
                <div class="version-finder-input">
                    <input
                        placeholder="add version"
                        type="text"
                        name="version-finder"
                        id="version-finder"
                    />
                </div>
                <div class="version-finder-selected">
                    <div v-for="opt in versionOptions" :key="opt" class="selected">
                        <label :for="opt" class="checkbox-repr">
                            <input type="checkbox" :name="opt" :id="opt" />
                        </label>
                        <label :for="opt" class="checkbox-label">{{
                            opt.replace(/_/g, ' ')
                        }}</label>
                    </div>
                </div>
                <div class="version-results-container">
                    <div class="version-result-item">
                        <span class="version-content">1.21.4</span>
                    </div>
                    <div class="version-result-item">
                        <span class="version-content">1.21.4-rc2</span>
                    </div>
                </div>
            </div>

            <TagsContainer :versions="versions" :hashtags="hashtags" />

            <div class="editor-wrapper">
                <EditorWrapper v-model="content" :initial-content="content" />
            </div>

            <!--            <div class="content">-->
            <!--                <h4>Content:</h4>-->
            <!--                <textarea-->
            <!--                    class="title"-->
            <!--                    style="font-size: 13px; font-family: monospace"-->
            <!--                    name="te"-->
            <!--                    id="te"-->
            <!--                    cols="30"-->
            <!--                    rows="10"-->
            <!--                    >{{ JSON.stringify(content, null, 2) }}</textarea-->
            <!--                >-->
            <!--            </div>-->

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
                            <img src="../assets/img/icons/shulker-white.png" alt="download" />
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
                <ButtonComponent button-type="primary">Create Post</ButtonComponent>
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
    }

    .version-finder-selected {
        display: flex;
        gap: var(--gap-32);

        .selected {
            display: flex;
            align-items: center;
            gap: var(--gap-8);
            cursor: pointer;
            transition: color 0.2s;
            user-select: none;

            &:hover {
                color: var(--col-accent);
            }
        }

        input {
            display: none;
        }

        label.checkbox-repr {
            cursor: inherit;
            aspect-ratio: 1;
            height: 100%;
            background: var(--col-content-hover);
            border-radius: var(--border-radius-s);
            transition: background-color 0.2s;

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
        gap: var(--gap-8);

        .version-result-item {
            padding: var(--gap-4) var(--gap-8);
            background-color: var(--col-surface);
            border-radius: var(--border-radius-s);
        }
    }
}
</style>
