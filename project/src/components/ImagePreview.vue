<script setup lang="ts">
import { ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faGear, faImage } from '@fortawesome/free-solid-svg-icons'

const props = defineProps<{
    file: File | Promise<File>
}>()

const reader = new FileReader()
const preview = ref<string | null>(null)
const actualFile = ref<File | null>(null)

reader.onload = (e: ProgressEvent<FileReader>) => {
    if (e.target && typeof e.target.result === 'string') {
        preview.value = e.target.result
    }
}

async function load() {
    actualFile.value = await props.file
    reader.readAsDataURL(actualFile.value)
}

load()
</script>

<template>
    <!--        show loading until image is loaded-->
    <img v-if="preview" class="thumbnail" :src="preview" :alt="actualFile?.name" />
    <div v-else class="loading thumbnail">
        <FontAwesomeIcon :icon="faImage" />
    </div>
</template>

<style scoped>
@keyframes rotate {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}

.loading {
    display: grid;
    place-items: center;

    background: var(--col-content-active);

    svg {
        height: 18%;
        fill: var(--col-text-secondary);
    }
}
</style>
