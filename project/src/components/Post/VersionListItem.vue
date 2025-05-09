<script setup lang="ts">
import type { Version, VersionType } from '@/types/version-types'
import { ref } from 'vue'

const props = defineProps<{
    version: Version
}>()

const translations = ref<Map<VersionType, string>>(
    new Map([
        ['release', 'rel'],
        ['snapshot', 'snap'],
        ['old_alpha', 'alpha'],
        ['old_beta', 'beta'],
    ]),
)
</script>

<template>
    <div class="version-list-item" :data-version-type="version.type">
        <div class="type-tag">
            <span>{{ translations.get(version.type) }}</span>
        </div>
        <div class="version-tag">
            <span>{{ version.version }}</span>
        </div>
        <div v-if="version.is_latest" class="latest-tag">
            <span>latest</span>
        </div>
    </div>
</template>

<style scoped>
.version-list-item {
    --color-release: #55aa55;
    --color-snapshot: #5555ff;
    --color-old_alpha: #aa55aa;
    --color-old_beta: #aa5555;

    --version-tag-color: var(--color-release);

    padding: var(--gap-4);
    border-radius: var(--border-radius-s);
    background: var(--col-surface);

    display: grid;
    align-items: center;
    grid-template-columns: 2.9em 1fr;
    gap: var(--gap-8);

    position: relative;
    overflow: hidden;

    &[data-version-type='release'] {
        --version-tag-color: var(--color-release);
    }
    &[data-version-type='snapshot'] {
        --version-tag-color: var(--color-snapshot);
    }
    &[data-version-type='old_alpha'] {
        --version-tag-color: var(--color-old_alpha);
    }
    &[data-version-type='old_beta'] {
        --version-tag-color: var(--color-old_beta);
    }

    .type-tag {
        display: grid;
        place-items: center;
        background: var(--version-tag-color);
        border-radius: var(--border-radius-s);
        padding: 2px 2px;
        font-size: 70%;

        span {
            padding-bottom: 0.3em;
        }
    }

    .latest-tag {
        position: absolute;
        top: 0;
        right: 0;
        border-radius: 0 0 0 var(--border-radius);
        background: var(--col-primary);
        padding: 3px 4px 6px 6px;
        display: block;
        font-size: 50%;
    }
}
</style>
