<script setup lang="ts">
import type { Color } from '@/types/Util'

interface Props {
    label: string
    type?: string
    noticeText?: string
    showNotice?: boolean
    showOutline?: boolean
    autocomplete?: string
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    outline: false,
    showNotice: false,
    showOutline: false,
})

const model = defineModel()

const emit = defineEmits(['input'])
</script>

<template>
    <div class="input-field">
        <label :for="label">
            <span>{{ label }}</span>
            <span v-if="showNotice">{{ noticeText }}</span>
        </label>
        <input
            @input="$emit('input')"
            v-model="model"
            :type="type"
            :name="label"
            :id="label"
            :autocomplete="autocomplete"
            :data-outline="showOutline"
        />
    </div>
</template>

<style scoped>
.input-field {
    position: relative;

    --gap-inset: var(--gap-12);

    label {
        --error-gap: calc(var(--font-size-body) / 2);

        display: flex;
        gap: var(--error-gap);

        & > *:nth-child(2) {
            color: var(--col-error);

            &::before {
                color: var(--col-text-primary);
                content: '-';
                margin-right: var(--error-gap);
            }
        }
    }

    /* Tod overwrite the others */
    input[data-outline='true'] {
        outline: 1px solid var(--col-error);
    }

    input {
        margin-top: 0.4rem;
        padding: var(--gap-inset);
        background: var(--col-surface);
        width: 100%;
        border: none;

        overflow: hidden;
        border-radius: var(--border-radius-s);

        color: var(--col-text-primary);
    }
}
</style>
