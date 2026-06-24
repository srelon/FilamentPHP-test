<template>
    <aside class="product-sidebar">
        <div v-for="group in filter_groups" :key="group.title" class="product-sidebar__block">
            <h4 class="product-sidebar__heading">{{ group.title }}</h4>

            <PriceFilter
                v-if="group.type === 'price'"
                :min="0"
                :max="100"
                :model_min="price_min"
                :model_max="price_max"
                @filter="on_price_filter"
            />

            <FilterGroup
                v-else-if="group.type === 'checkbox'"
                :title="group.title"
                :items="group.items ?? []"
                :modelValue="group.selected"
                @update:modelValue="on_checkbox_change(group, $event)"
            />

            <RatingFilter
                v-else-if="group.type === 'rating'"
                :modelValue="group.selected"
                @update:modelValue="on_rating_change(group, $event)"
            />
        </div>
    </aside>
</template>

<script setup lang="ts">
import { reactive, ref, watch, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import PriceFilter from '@/components/ui/filters/PriceFilter.vue'
import FilterGroup from '@/components/ui/filters/FilterGroup.vue'
import RatingFilter from '@/components/ui/filters/RatingFilter.vue'

const route = useRoute()
const router = useRouter()

const emit = defineEmits<{
    filter: []
}>()

type FilterType = 'price' | 'checkbox' | 'rating'

interface FilterConfig {
    title: string
    type: FilterType
    query_key: string
    selected: string[] | number | null
    items?: { name: string, count?: number }[]
}

const price_min = ref(0)
const price_max = ref(100)

const filter_groups = reactive<FilterConfig[]>([
    {
        title: 'Filter by Price',
        type: 'price',
        query_key: 'price',
        selected: null,
    },
    {
        title: 'Categories',
        type: 'checkbox',
        query_key: 'category',
        selected: [],
        items: [
            { name: 'Art & Design', count: 8 },
            { name: 'Self-help', count: 12 },
            { name: 'Science', count: 6 },
            { name: 'Romance', count: 9 },
            { name: 'Novels', count: 14 },
            { name: 'History', count: 5 },
            { name: 'Fantasy', count: 11 },
            { name: 'Business', count: 7 },
            { name: 'Cooking', count: 10 },
            { name: 'Adventure', count: 4 },
        ],
    },
    {
        title: 'Authors',
        type: 'checkbox',
        query_key: 'author',
        selected: [],
        items: [
            { name: 'Oliver Hartman', count: 6 },
            { name: 'Clara Whitfield', count: 4 },
            { name: 'Amelia Brooks', count: 4 },
            { name: 'Nathaniel Parker', count: 5 },
            { name: 'Eleanor Finch', count: 3 },
            { name: 'Samuel Wright', count: 4 },
        ],
    },
    {
        title: 'Rating',
        type: 'rating',
        query_key: 'rating',
        selected: null,
    },
    {
        title: 'Status',
        type: 'checkbox',
        query_key: 'status',
        selected: [],
        items: [
            { name: 'In Stock' },
            { name: 'On Sale' },
        ],
    },
])

let sync_in_progress = false

function sync_from_query() {
    sync_in_progress = true
    for (const group of filter_groups) {
        if (group.type === 'checkbox') {
            const val = route.query[group.query_key]
            group.selected = val ? (Array.isArray(val) ? val as string[] : [val as string]) : []
        } else if (group.type === 'rating') {
            group.selected = route.query.rating ? Number(route.query.rating) : null
        }
    }
    price_min.value = route.query.price_min ? Number(route.query.price_min) : 0
    price_max.value = route.query.price_max ? Number(route.query.price_max) : 100
    nextTick(() => { sync_in_progress = false })
}

watch(() => route.query, sync_from_query, { immediate: true, deep: true })

function build_query() {
    const q: Record<string, string | string[]> = {}
    for (const group of filter_groups) {
        if (group.type === 'checkbox') {
            const sel = group.selected as string[]
            if (sel.length) q[group.query_key] = sel.length === 1 ? sel[0] : sel
        } else if (group.type === 'rating' && group.selected) {
            q.rating = String(group.selected)
        }
    }
    if (price_min.value > 0) q.price_min = String(price_min.value)
    if (price_max.value < 100) q.price_max = String(price_max.value)
    return q
}

function push_to_url() {
    router.replace({ query: build_query() })
}

function on_checkbox_change(group: FilterConfig, val: string[]) {
    group.selected = val
    if (!sync_in_progress) push_to_url()
}

function on_rating_change(group: FilterConfig, val: number | null) {
    group.selected = val
    if (!sync_in_progress) push_to_url()
}

function on_price_filter(val: { min: number, max: number }) {
    price_min.value = val.min
    price_max.value = val.max
    push_to_url()
}
</script>

<style lang="scss" scoped>
.product-sidebar {
    width: 260px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 32px;

    &__block {
        border-bottom: 1px solid $color-light;
        padding-bottom: 28px;

        &:last-child {
            border-bottom: none;
        }
    }

    &__heading {
        font-size: 15px;
        font-weight: 700;
        color: $color-dark;
        font-family: $font-heading;
        margin-bottom: 16px;
    }
}
</style>
