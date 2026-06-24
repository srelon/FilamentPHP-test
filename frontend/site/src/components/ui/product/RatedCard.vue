<template>
    <div class="rated-card">
        <router-link :to="href" class="rated-card__img-wrap">
            <img :src="image" :alt="title" class="rated-card__img">
        </router-link>
        <div class="rated-card__info">
            <h3 class="rated-card__title">
                <router-link :to="href">{{ title }}</router-link>
            </h3>
            <div v-if="rating" class="rated-card__stars" :aria-label="`Rated ${rating} out of 5`">
                <span
                    v-for="i in 5"
                    :key="i"
                    class="rated-card__star"
                    :class="{ 'rated-card__star--filled': i <= Math.round(rating) }"
                >★</span>
            </div>
            <span class="rated-card__category">{{ category }}</span>
            <span class="rated-card__price">${{ price }}</span>
        </div>
    </div>
</template>

<script setup lang="ts">
interface Props {
    title: string
    category: string
    price: string
    rating: number
    image: string
    href?: string
}

withDefaults(defineProps<Props>(), {
    href: '/product',
})
</script>

<style lang="scss" scoped>
.rated-card {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    background: $color-white;
    border-radius: 10px;
    padding: 16px;

    &__img-wrap {
        flex-shrink: 0;
        width: 70px;
        border-radius: 6px;
        overflow: hidden;
    }

    &__img {
        width: 70px;
        aspect-ratio: 2 / 3;
        object-fit: cover;
        display: block;
        border-radius: 6px;
        transition: transform 0.3s ease;

        .rated-card:hover & {
            transform: scale(1.04);
        }
    }

    &__info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    &__title {
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;

        a {
            color: $color-dark;
            transition: color 0.2s;

            &:hover {
                color: $color-primary;
            }
        }
    }

    &__stars {
        display: flex;
        gap: 2px;
    }

    &__star {
        font-size: 14px;
        color: $color-lighter;

        &--filled {
            color: #f4a41e;
        }
    }

    &__category {
        font-size: 12px;
        color: $color-gray;
    }

    &__price {
        font-size: 16px;
        font-weight: 700;
        color: $color-primary;
        margin-top: 4px;
    }
}
</style>
