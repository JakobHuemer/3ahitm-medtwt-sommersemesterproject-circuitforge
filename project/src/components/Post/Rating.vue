<script setup lang="ts">
import { useApi } from '@/store/useApi.ts'
import { computed, reactive } from 'vue'
import { faArrowDown, faArrowUp } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Rating from '@/components/Post/Rating.vue'
import { Skeleton } from '@brayamvalero/vue3-skeleton'

const props = defineProps<{
    entityId: number
}>()

const api = useApi()

type Rating = 1 | -1 | 0
type State = 'idle' | 'loading' | 'pending' | 'error'

interface RatingResponse {
    rating: number
    userRating: Rating
}

interface RatingState {
    serverState: RatingResponse | null

    optimisticState: RatingResponse | null

    state: State
    error: string | null
}

const ratingState = reactive<RatingState>({
    state: 'loading',
    serverState: null,
    optimisticState: null,
    error: null,
})

const currentUserRating = computed(() => {
    return ratingState.optimisticState?.userRating ?? ratingState.serverState?.userRating ?? 0
})

api.api
    .get<RatingResponse>(`/entities/${props.entityId}/ratings`)
    .then((res) => {
        ratingState.serverState = res.data
    })
    .catch((err) => {
        ratingState.error = err.message
    })

let abortController: AbortController | null = null

// TODO: rework this whole system and make a polished experience like reddit has it

async function rate(newRating: Rating) {
    // cancel update rating promise controlled
    if (abortController) {
        abortController.abort()
    }

    abortController = new AbortController()

    // switching opt

    if (currentUserRating.value == newRating) {
        newRating = 0
    }

    ratingState.optimisticState = getOptimistic(newRating)
    ratingState.state = 'pending'

    try {
        // TODO: Backend, return new ratings
        const res = await api.api.get<RatingResponse>(
            `/entities/${props.entityId}/ratings/${newRating}`,
            { signal: abortController.signal }, // Add abort signal
        )

        abortController = null

        ratingState.serverState = res.data
        ratingState.state = 'idle'
        ratingState.optimisticState = null
    } catch (error: any) {
        if (error.name == 'AbortError') return

        ratingState.error = error.message ?? 'failed to fetch likes'
        // TODO: find out different error cases and inform the user about them
        ratingState.optimisticState = null
        ratingState.state = 'error'
    }
}

function getOptimistic(newUserRating: Rating): RatingResponse {
    const serverRating = ratingState.serverState
    if (serverRating == null) throw new Error('Server state is null when trying to get optimistic')
    const oldUserRating = serverRating.userRating
    const currentRating = currentUserRating.value

    let ratingDelta = -oldUserRating

    ratingDelta += newUserRating

    return {
        rating: currentRating + ratingDelta,
        userRating: newUserRating,
    }
}

function getDisplayRating(): number | null {
    return ratingState.optimisticState?.rating ?? ratingState.serverState?.rating ?? null
}
</script>

<template>
    <div class="rating-container" :data-rating="currentUserRating">
        <!--        <p>Current: "{{ currentUserRating }}"</p>-->
        <div class="rating-button rating-up" @click="rate(1)">
            <FontAwesomeIcon :icon="faArrowUp" />
        </div>
        <span class="rating-number">{{
            ratingState.optimisticState?.rating ?? ratingState.serverState?.rating ?? 'loading...'
        }}</span>
        <div class="rating-button rating-down" @click="rate(-1)">
            <FontAwesomeIcon :icon="faArrowDown" />
        </div>
    </div>
</template>

<style scoped>
.rating-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: var(--gap-8);
    padding: var(--gap-4) var(--gap-4);

    font-weight: bold;
    background: var(--col-content);

    border-radius: var(--border-radius);

    cursor: pointer;

    &:has(.selected) {
        background: var(--col-surface);
    }

    .rating-button {
        padding: var(--gap-4) var(--gap-4);
        background: color-mix(in srgb, var(--col-surface) 10%, transparent);
        border-radius: var(--border-radius-s);

        transition: background 0.1s;

        &:hover {
            background: color-mix(in srgb, var(--col-surface-hover) 40%, transparent);
        }

        &:active {
            background: color-mix(in srgb, var(--col-surface-active) 60%, transparent);
        }
    }

    &[data-rating='1'] {
        background: var(--col-primary);

        .rating-up {
            color: var(--col-accent);
        }
    }

    &[data-rating='-1'] {
        background: var(--col-secondary);

        .rating-down {
            color: var(--col-accent);
        }
    }

    .rating-number {
        min-width: 1rem;
        text-align: center;
    }
}
</style>
