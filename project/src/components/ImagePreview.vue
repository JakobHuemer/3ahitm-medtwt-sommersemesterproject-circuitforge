<script setup lang="ts">
import { ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faGear } from '@fortawesome/free-solid-svg-icons'

const props = defineProps<{
    file: File
}>()

const reader = new FileReader()
const preview = ref<string | null>(null)

reader.onload = (e: ProgressEvent<FileReader>) => {
    if (e.target && typeof e.target.result === 'string') {
        preview.value = e.target.result
    }
}

reader.readAsDataURL(props.file)
</script>

<template>
    <!--        show loading until image is loaded-->
    <img v-if="preview" class="thumbnail" :src="preview" :alt="file.name" />
    <div v-else class="loading thumbnail">
        <FontAwesomeIcon :icon="faGear" />
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
        animation: rotate 1.5s linear infinite;
        height: 18%;
    }
}
</style>
