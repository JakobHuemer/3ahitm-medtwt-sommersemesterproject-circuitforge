<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { computed } from 'vue'
import type { IconDefinition } from '@fortawesome/fontawesome-svg-core'
import {
    faCheckSquare,
    faExclamationTriangle,
    faInfoCircle,
    faSkull,
} from '@fortawesome/free-solid-svg-icons'

type NoticeType = 'warn' | 'info' | 'error' | 'success'

const iconDictionary: Map<NoticeType, IconDefinition> = new Map<NoticeType, IconDefinition>([
    ['error', faSkull],
    ['success', faCheckSquare],
    ['warn', faExclamationTriangle],
    ['info', faInfoCircle],
])

const props = defineProps<{
    type: NoticeType
}>()

const icon = computed(() => {
    return iconDictionary.get(props.type)
})
</script>

<template>
    <div :class="'notice notice-type-' + props.type">
        <FontAwesomeIcon v-if="icon" :icon="icon" />
        <span class="notice-text">
            <slot />
        </span>
    </div>
</template>

<style scoped>
.notice {
    --color: var(--col-info);

    display: flex;
    align-items: center;

    gap: var(--gap-8);
    padding: var(--gap-8);

    border: 1px solid var(--color);
    background: color-mix(in srgb, var(--color) 20%, transparent);
    border-radius: var(--border-radius-s);

    svg {
        color: var(--color);
    }

    &.notice-type-error {
        --color: var(--col-error);
    }

    &.notice-type-warn {
        --color: var(--col-warn);
    }

    &.notice-type-success {
        --color: var(--col-success);
    }
}
</style>
