<script setup lang="ts">
import { useApi } from '@/store/useApi.ts'
import { computed, reactive } from 'vue'
import { faArrowDown, faArrowUp } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Rating from '@/components/Post/Rating.vue'

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

api.api
    .get<RatingResponse>(`/entities/${props.entityId}/ratings`)
    .then((res) => {
        ratingState.serverState = res.data
    })
    .catch((err) => {
        ratingState.error = err.message
    })

async function rate(newRating: Rating) {
    // switching opt
    const currentRating = getDisplayRating()

    if (newRating == currentRating) {
        newRating = 0
    }

    ratingState.optimisticState = getOptimistic(newRating)
    ratingState.state = 'pending'

    try {
        // TODO: Backend, return new ratings
        const res = await api.api.get<RatingResponse>(
            `/entities/${props.entityId}/ratings/${newRating}`,
        )

        ratingState.serverState = res.data
        ratingState.state = 'idle'
        ratingState.optimisticState = null
    } catch (error: any) {
        ratingState.error = error.message ?? 'failed to fetch likes'
        // TODO: find out different error cases and inform the user about them
        ratingState.optimisticState == null
        ratingState.state = 'error'
    }
}

function getOptimistic(newUserRating: Rating): RatingResponse {
    const serverRating = ratingState.serverState
    if (serverRating == null) throw new Error('Server state is null when trying to get optimistic')
    const oldUserRating = serverRating.userRating
    const currentRating = serverRating.rating

    let ratingDelta = -oldUserRating

    ratingDelta += newUserRating

    return {
        rating: currentRating + ratingDelta,
        userRating: newUserRating,
    }
    //
}

function getDisplayRating(): number | null {
    return ratingState.optimisticState?.rating ?? ratingState.serverState?.rating ?? null
}
</script>

<template>
    <div class="rating-container">
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

    &:hover {
        background: var(--col-content-hover);
    }

    &:has(.selected) {
        background: var(--col-surface);
    }

    .rating-button {
        padding: var(--gap-4) var(--gap-4);
        background: var(--col-surface);
        border-radius: var(--border-radius-s);

        transition: background 0.1s;

        &:hover {
            background: var(--col-surface-hover);
        }

        &.selected {
            background: var(--col-accent);
        }
    }
}
</style>
