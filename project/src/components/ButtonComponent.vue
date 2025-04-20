<script setup lang="ts">
import type { IconDefinition } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

type ButtonType = 'primary' | 'secondary' | 'accent' | 'normal' | 'error'
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
    --color: var(--col-surface);
    --color-hover: var(--col-surface-hover);
    --color-active: var(--col-surface-active);
    --color-disabled: var(--col-container);

    padding: var(--gap-8) var(--gap-12);

    outline: none;
    border: none;
    border-radius: var(--border-radius-s);
    color: white;

    display: flex;
    gap: var(--gap-4);
    align-items: center;
    justify-content: center;

    background: var(--color);

    &:hover {
        background: var(--color-hover);
    }

    &:active {
        background: var(--color-active);
    }

    &:disabled {
        background: var(--color-disabled);
        color: var(--col-text-secondary);
    }

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
    --color: var(--col-primary);
    --color-hover: var(--col-primary-hover);
    --color-active: var(--col-primary-active);
    --color-disabled: var(--col-primary-disabled);
}

.button-secondary {
    --color: var(--col-secondary);
    --color-hover: var(--col-secondary-hover);
    --color-active: var(--col-secondary-active);
    --color-disabled: var(--col-secondary-disabled);
}

.button-accent {
    --color: var(--col-accent);
    --color-hover: var(--col-accent-hover);
    --color-active: var(--col-accent-active);
    --color-disabled: var(--col-accent-disabled);
}

.button-error {
    --color: var(--col-error);

    /* Hover: lighten + reduce saturation slightly */
    --color-hover: hsl(from var(--col-error) h calc(s * 1.6) calc(l * 1.2));

    /* Active: darken + increase saturation */
    --color-active: hsl(from var(--col-error) h calc(s * 0.7) calc(l * 0.8));

    /* Disabled: darken further + desaturate more */
    --color-disabled: hsl(from var(--col-error) h calc(s * 0.7) calc(l * 0.5));
}
</style>
