<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCloudArrowUp, faImage, faPlus } from '@fortawesome/free-solid-svg-icons'
import { ref, watch } from 'vue'
import TagsContainer from '@/components/TagsContainer.vue'
import ButtonComponent from '@/components/ButtonComponent.vue'
import EditorWrapper from '@/components/EditorWrapper.vue'
import type { JSONContent } from '@tiptap/vue-3'

const hashtags = ref<string[]>(['hello', 'thisIsTag', 'memo'])
const versions = ref<string[]>(['1.21+', '1.8.9'])
const downloadables = ref<string[]>(['world.zip', 'door.litematic', 'door.schematic'])

const title = ref<string>('asdf')
const titleElement = ref<null | HTMLTextAreaElement>(null)
const maxlength = ref(255)

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

function adjustHeight() {
    const textarea = this.$refs.textarea
    textarea.style.height = 'auto'
    textarea.style.height = `${textarea.scrollHeight}px`
}

const content = ref<JSONContent>({
    type: 'doc',
    content: [
        {
            type: 'paragraph',
            content: [
                {
                    type: 'text',
                    text: 'hello this is my post',
                },
            ],
        },
        {
            type: 'paragraph',
        },
        {
            type: 'blockquote',
            content: [
                {
                    type: 'paragraph',
                    content: [
                        {
                            type: 'text',
                            text: 'this is a nice quote',
                        },
                    ],
                },
                {
                    type: 'paragraph',
                    content: [
                        {
                            type: 'text',
                            text: 'another line asin the quote',
                        },
                    ],
                },
            ],
        },
        {
            type: 'paragraph',
        },
        {
            type: 'codeBlock',
            attrs: {
                language: 'shell',
            },
            content: [
                {
                    type: 'text',
                    text: 'some codeblock here\nand there\netc.',
                },
            ],
        },
        {
            type: 'paragraph',
        },
        {
            type: 'paragraph',
            content: [
                {
                    type: 'text',
                    text: 'this is ',
                },
                {
                    type: 'text',
                    marks: [
                        {
                            type: 'code',
                        },
                    ],
                    text: 'marked',
                },
                {
                    type: 'text',
                    text: ' and ',
                },
                {
                    type: 'text',
                    marks: [
                        {
                            type: 'bold',
                        },
                    ],
                    text: 'this is bold',
                },
                {
                    type: 'text',
                    text: ' and ',
                },
                {
                    type: 'text',
                    marks: [
                        {
                            type: 'italic',
                        },
                    ],
                    text: 'this is italic',
                },
                {
                    type: 'text',
                    text: ' and ',
                },
                {
                    type: 'text',
                    marks: [
                        {
                            type: 'underline',
                        },
                    ],
                    text: 'this is underlined',
                },
            ],
        },
        {
            type: 'paragraph',
            content: [
                {
                    type: 'text',
                    text: 'this is paragraph',
                },
            ],
        },
        {
            type: 'paragraph',
            content: [
                {
                    type: 'text',
                    text: 'anotehr paragrap',
                },
            ],
        },
        {
            type: 'paragraph',
            content: [
                {
                    type: 'text',
                    text: 'this is a paragraph',
                },
            ],
        },
        {
            type: 'codeBlock',
            attrs: {
                language: 'shell',
            },
            content: [
                {
                    type: 'text',
                    text: 'this is a code block\nthis also\nand this also',
                },
            ],
        },
        {
            type: 'paragraph',
            content: [
                {
                    type: 'text',
                    text: 'this is the lower end',
                },
            ],
        },
        {
            type: 'paragraph',
            content: [
                {
                    type: 'text',
                    text: 'new paragraph',
                },
            ],
        },
    ],
})

const syncedContent = ref<string>('')
</script>

<template>
    <div class="container-wrapper">
        <h2>Create Post</h2>
        <div class="container container-images">
            <div class="scroll-container">
                <div class="post-image add-image-button">
                    <FontAwesomeIcon :icon="faCloudArrowUp" />
                    <span>Add Image</span>
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
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

            <TagsContainer :versions="versions" :hashtags="hashtags" />

            <div class="editor-wrapper">
                <EditorWrapper v-model="content" :initial-content="content" />
            </div>

            <div class="content">
                <h4>Content:</h4>
                <textarea
                    class="title"
                    style="font-size: 13px; font-family: monospace"
                    name="te"
                    id="te"
                    cols="30"
                    rows="10"
                    >{{ JSON.stringify(content, null, 2) }}</textarea
                >
            </div>

            <div class="downloadables">
                <div class="download" v-for="asset of downloadables">
                    <div class="thumbnail">
                        <img src="../assets/img/icons/shulker-white.png" alt="download" />
                    </div>
                    <span>{{ asset }}</span>
                </div>
                <ButtonComponent :icon="faPlus">Add File</ButtonComponent>
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

            &:first-child {
                margin-left: var(--container-images-gap);
            }

            &:last-child {
                margin-right: var(--container-images-gap);
            }

            img {
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
</style>
