<script setup lang="ts">
import { Editor, EditorContent, type JSONContent } from '@tiptap/vue-3'
import { onBeforeUnmount, onMounted, ref, shallowRef } from 'vue'
import { StarterKit } from '@tiptap/starter-kit'
import PostEditor from '@/components/EditorWrapper.vue'
import type PostEditorInstance from '@/types/post-editor-instance'

const outputJson = ref('')
const componentRef = ref<PostEditorInstance>()

function getJson() {
    var json = componentRef.value?.getJson()

    outputJson.value = JSON.stringify(json, null, 2)
}

function setFromJson() {
    console.log('Setting json', outputJson.value)

    var json = JSON.parse(outputJson.value)

    componentRef.value?.setJson(json)
}
</script>

<template>
    <!--    <EditorContent :editor="editor"/>-->
    <PostEditor :editable="true" :auto-focus="true" ref="componentRef" />

    <hr />
    <button @click="getJson">PRINT</button>
    <button @click="setFromJson">SET</button>
    <hr />

<!--    <textarea-->
<!--        :value="outputJson"-->
<!--        @input="outputJson = $event.target.value"-->
<!--        class="output-json"-->
<!--    ></textarea>-->
</template>
