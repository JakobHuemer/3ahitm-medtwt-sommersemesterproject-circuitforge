import type { JSONContent } from '@tiptap/vue-3'

export default interface PostEditorInstance {
    getJson(): JSONContent,

    setJson(json: JSONContent): void
}
