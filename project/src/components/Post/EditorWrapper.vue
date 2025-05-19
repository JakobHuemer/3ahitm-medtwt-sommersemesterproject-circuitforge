<script setup lang="ts">
import { EditorContent, type JSONContent, useEditor } from '@tiptap/vue-3'
import { onBeforeUnmount, onMounted, ref, watch } from 'vue'

import Document from '@tiptap/extension-document'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import Bold from '@tiptap/extension-bold'
import Italic from '@tiptap/extension-italic'
import Underline from '@tiptap/extension-underline'
import Strike from '@tiptap/extension-strike'
import Heading from '@tiptap/extension-heading'
import Blockquote from '@tiptap/extension-blockquote'
import Code from '@tiptap/extension-code'
import CodeBlock from '@tiptap/extension-code-block'
import BulletList from '@tiptap/extension-bullet-list'
import OrderedList from '@tiptap/extension-ordered-list'
import ListItem from '@tiptap/extension-list-item'
import { Placeholder } from '@tiptap/extension-placeholder'
import { Highlight } from '@tiptap/extension-highlight'
import { Hashtag } from '@/tiptap-extensions/HashtagExtension.ts'
import { CharacterCount } from '@tiptap/extension-character-count'

const COLOR_SUCCESS = '#57c287'
const COLOR_WARN = '#f5b64b'
const COLOR_ERROR = '#f04747'

const model = defineModel<JSONContent>()

const props = defineProps<{
    initialContent?: JSONContent
    hashtagLength: number
    characterLimit: number
}>()

const wordCountPercentage = ref(0)

const bgBarBackground = ref<string>('')

const editor = useEditor({
    content: props.initialContent,
    extensions: [
        Document,
        Paragraph,
        Text,
        Bold,
        Italic,
        Underline,
        Strike,
        Heading,
        Blockquote,
        Code,
        CodeBlock,
        BulletList,
        OrderedList,
        ListItem,
        Highlight,
        Placeholder.configure({
            placeholder: 'Write your Post...',
        }),
        Hashtag.configure({
            HTMLAttributes: {
                class: 'hashtag-mark',
            },
            hashtagLength: props.hashtagLength,
        }),
        CharacterCount.configure({
            mode: 'textSize',
            limit: props.characterLimit,
        }),
    ],

    onUpdate: () => {
        if (!editor.value || editor.value.getJSON() === model.value) {
            return
        }

        model.value = editor.value.getJSON()

        wordCountPercentage.value =
            editor.value?.storage.characterCount.characters() / props.characterLimit

        bgBarBackground.value = `color-mix(in srgb, var(--col-error) ${wordCountPercentage.value * 100}%, var(--col-success))`
    },
})

const emit = defineEmits(['mounted'])

onMounted(() => {
    model.value = editor.value?.getJSON()
    emit('mounted')
})

onBeforeUnmount(() => {
    editor.value?.destroy()
})
</script>

<template>
    <div class="editor-container">
        <EditorContent :editor="editor" />
        <div class="word-count-wrapper">
            <div class="word-count-container">
                <span class="count count-actual">{{
                    editor?.storage.characterCount.characters()
                }}</span
                >/<span class="count count-limit">{{ props.characterLimit }}</span>
                <div class="bg-bar" :style="'width: ' + wordCountPercentage * 100 + '%'"></div>
            </div>
        </div>
    </div>
</template>

<style>
.editor-container {
    outline: none;
    min-height: 4rem;

    /* Editor Container from TipTap */

    display: grid;
    width: 100%;

    position: relative;

    & > div {
        width: 100%;
        overflow-y: auto;

        .tiptap {
            width: 100%;
            overflow-x: auto;
            outline: none;
            height: 100%;
        }
    }

    /* TipTap Editor Component Styles */

    /* AI-NOTICE: Initial styling by claude.ai */

    /* Placeholder */

    .is-editor-empty:first-child::before {
        content: attr(data-placeholder);
        float: left;
        color: var(--col-text-secondary);
        pointer-events: none;
        height: 0;
    }

    /* Paragraphs */

    p {
        margin-bottom: var(--gap-4);
        color: var(--col-text-primary);
        line-height: 1.5;
    }

    /* Headings */

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: var(--font-body);
        color: var(--col-text-primary);
        margin-top: var(--gap-24);
        margin-bottom: var(--gap-16);
        line-height: 1.2;
    }

    h1 {
        font-size: var(--font-size-heading);
    }

    h2 {
        font-size: var(--font-size-title);
    }

    h3 {
        font-size: var(--font-size-card-title);
    }

    h4 {
        font-size: calc(var(--font-size-card-title) * 0.9);
    }

    h5 {
        font-size: calc(var(--font-size-card-title) * 0.8);
    }

    h6 {
        font-size: calc(var(--font-size-card-title) * 0.7);
    }

    /* Text formatting */

    strong,
    b {
        font-weight: bold;

        mark {
            font-weight: inherit;
        }
    }

    mark {
        background: var(--col-accent);
    }

    em,
    i {
        font-style: italic;
        color: var(--col-text-primary);
    }

    u {
        text-decoration: underline;
        text-decoration-color: white;
    }

    s {
        text-decoration: line-through;
        color: var(--col-text-secondary);
    }

    .hashtag-mark {
        color: var(--col-accent);
        border-radius: var(--border-radius-s);
    }

    /* Blockquote */

    blockquote {
        border-left: 2px solid var(--col-accent);
        background: var(--col-content-hover);
        color: var(--col-text-secondary);
        font-style: italic;
        margin-block: var(--gap-8);
        padding: var(--gap-4);
        padding-left: var(--gap-8);
    }

    /* Code */

    /* inline */

    *:not(pre) > code {
        font-family: 'JetBrains Mono', monospace;
        font-size: calc(var(--font-size-body) * 0.9);
        background: var(--col-container);
        padding: 2px 4px;
        border-radius: var(--border-radius-s);
    }

    /* block */

    pre {
        font-family: 'JetBrains Mono', monospace;
        margin-block: var(--gap-8);
        padding: var(--gap-8);
        background: var(--col-container);
        border-radius: var(--border-radius-s);

        code {
            min-height: 2rem;
            font-size: calc(var(--font-size-body) * 0.9);
            line-height: 1.5;
            place-items: center;
        }
    }

    /* Lists */

    ul:not(ul ul, ol ul),
    ol:not(ul ol, ol ol) {
        padding-block: var(--gap-8);
    }

    ul,
    ol {
        padding-left: var(--gap-24);
        padding-block: var(--gap-4);

        li {
            margin: 0;
        }

        p {
            margin-bottom: 0;
        }
    }

    ul {
        list-style-type: disc;
    }

    ul ul {
        list-style-type: circle;
    }

    ul ul ul {
        list-style-type: square;
    }

    ol {
        list-style-type: decimal;
    }

    ol ol {
        list-style-type: lower-alpha;
    }

    ol ol ol {
        list-style-type: upper-roman;
    }

    ol ol ol ol {
        list-style-type: lower-roman;
    }

    li {
        margin-bottom: var(--gap-8);
    }
}

.word-count-wrapper {
    position: absolute;
    right: 0;
    bottom: -3.2rem;

    display: flex;
    justify-content: end;

    .word-count-container {
        display: flex;
        gap: 0.2rem;
        position: relative;
        isolation: isolate;

        padding: 0.4rem 0.6rem;

        background: var(--col-surface);
        border-radius: var(--border-radius-s);
        overflow-x: hidden;

        .bg-bar {
            z-index: -1;
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            /*background: v-bind(bgBarColor);*/
            background: v-bind(bgBarBackground);
        }
    }
}
</style>
