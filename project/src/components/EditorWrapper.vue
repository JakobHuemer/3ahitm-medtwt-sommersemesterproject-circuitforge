<script setup lang="ts">
import { type Content, Editor, EditorContent, type JSONContent, useEditor } from '@tiptap/vue-3'
import { onBeforeUnmount, onMounted, ref, shallowRef, watch } from 'vue'

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

const model = defineModel<JSONContent>()

const props = defineProps<{
    initialContent?: JSONContent
}>()

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
        Placeholder.configure({
            placeholder: 'Write your Post...',
        }),
    ],

    onUpdate: () => {
        if (!editor.value || editor.value.getJSON() === model.value) {
            return
        }

        model.value = editor.value.getJSON()
    },
})

// watch(model, () => {
//     if (!editor.value || editor.value === model.value || !model.value) {
//         return
//     }
//
//     editor.value.commands.setContent(model.value)
// })

onBeforeUnmount(() => {
    editor.value?.destroy()
})
</script>

<template>
    <EditorContent :editor="editor" />
</template>

<style>
.tiptap {
    outline: none;
    min-height: 4rem;

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

    /* Blockquote */

    blockquote {
        border-left: 2px solid var(--col-accent);
        background: var(--col-content-hover);
        color: var(--col-text-secondary);
        font-style: italic;
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
</style>
