<script setup lang="ts">
import { Editor, EditorContent, Extension, type JSONContent } from '@tiptap/vue-3'
import { shallowRef } from 'vue'
import { StarterKit } from '@tiptap/starter-kit'

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

import type PostEditorInstance from '@/types/post-editor-instance'

const props = defineProps<{
    editable: boolean
    autoFocus: boolean
    entityId?: number
}>()

const editor = shallowRef<Editor>(
    new Editor({
        content: '<h1>hello world</h1><p>Ja moin</p>',
        editable: props.editable,
        autofocus: props.autoFocus,
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
        ],
    }),
)

function getJson(): JSONContent {
    return { text: editor.value.getHTML() }
}

function setJson(json: JSONContent) {
    editor.value?.commands.setContent(json)
}

if (props.entityId != undefined) {
    // fetch post content
}

defineExpose<PostEditorInstance>({
    getJson,
    setJson,
})
</script>

<template>
    <EditorContent :editor="editor" />
</template>

<style scoped></style>
