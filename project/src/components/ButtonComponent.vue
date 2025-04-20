<script setup lang="ts">
import type { IconDefinition } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

type ButtonType = 'primary' | 'secondary' | 'normal'
type ButtonSize = 'normal' | 'medium' | 'big'

const props = withDefaults(
    defineProps<{
        icon?: IconDefinition
        disabled?: boolean
        buttonType?: ButtonType
        size?: ButtonSize
        type?: typeof HTMLButtonElement.prototype.type
    }>(),
    {
        buttonType: 'normal',
        size: 'normal',
        disabled: false,
        big: false,
    },
)

const emit = defineEmits(['click'])
</script>

<template>
    <button
        :type="type"
        :class="'button-' + buttonType + ' size-' + size"
        @click="$emit('click')"
        :disabled="disabled"
    >
        <FontAwesomeIcon :icon="icon" v-if="icon" />
        <slot />
    </button>
</template>

<style scoped>
button {
    padding: var(--gap-8) var(--gap-12);

    outline: none;
    border: none;
    border-radius: var(--border-radius-s);
    color: white;

    display: flex;
    gap: var(--gap-4);
    align-items: center;
    justify-content: center;

    &.size-medium {
        gap: calc((var(--gap-8) + var(--gap-4)) / 2);
        font-size: calc((var(--font-size-nav-item) + 100%) / 2);
        padding: calc((var(--gap-12) + var(--gap-8)) / 2) calc((var(--gap-12) + var(--gap-24)) / 2);
        font-weight: 500;
    }

    &.size-big {
        gap: var(--gap-8);
        font-size: var(--font-size-nav-item);
        padding: var(--gap-12) var(--gap-24);
        font-weight: 600;
    }
}

.button-primary {
    background: var(--col-primary);
    transition: background 0.2s;

    cursor: pointer;

    &:hover {
        background: var(--col-primary-hover);
    }

    &:active {
        background: var(--col-primary-active);
    }
}

.button-secondary {
    background: var(--col-secondary);
    transition: background 0.2s;

    cursor: pointer;

    &:hover {
        background: var(--col-secondary-hover);
    }

    &:active {
        background: var(--col-secondary-active);
    }
}

.button-normal {
    background: var(--col-surface);
    transition: background 0.2s;

    cursor: pointer;

    &:hover {
        background: var(--col-surface-hover);
    }

    &:active {
        background: var(--col-surface-active);
    }
}
</style>
