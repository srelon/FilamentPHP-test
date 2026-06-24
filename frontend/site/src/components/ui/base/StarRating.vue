<template>
    <div class="star-rating">
        <span
            v-for="i in max"
            :key="i"
            class="star-rating__star"
            :class="{ 'star-rating__star--filled': i <= rating }"
        >★</span>
        <span v-if="count !== undefined" class="star-rating__count">({{ count }} reviews)</span>
    </div>
</template>

<script setup lang="ts">
interface Props {
    rating: number
    count?: number
    max?: number
}

withDefaults(defineProps<Props>(), {
    max: 5,
})
</script>

<style lang="scss" scoped>
.star-rating {
    display: flex;
    align-items: center;
    gap: 2px;

    &__star {
        font-size: 18px;
        color: $color-light;

        &--filled {
            color: #f5a623;
        }
    }

    &__count {
        font-size: 13px;
        color: $color-gray;
        margin-left: 6px;
    }
}
</style>
