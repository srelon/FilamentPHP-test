<template>
    <section class="rated section">
        <div class="container">
            <div class="section__header">
                <h2 class="section__title">{{ title }}</h2>
                <router-link :to="view_all_href" class="rated__view-all">
                    {{ view_all_label }}
                    <svg viewBox="0 0 15 15" aria-hidden="true">
                        <path d="M1 15a1 1 0 0 1-.707-1.707L11.586 2H1.52a1 1 0 0 1 0-2h12.483q.202.002.379.075a1 1 0 0 1 .542.543 1 1 0 0 1 .076.38V13.48a1 1 0 1 1-2 0V3.414L1.707 14.707A1 1 0 0 1 1 15"/>
                    </svg>
                </router-link>
            </div>
            <div class="rated__grid">
                <RatedCard
                    v-for="product in products"
                    :key="product.slug"
                    :title="product.title"
                    :category="product.category"
                    :price="product.price"
                    :rating="product.rating"
                    :image="product.image"
                    :href="`/product/${product.slug}`"
                />
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import RatedCard from '@/components/ui/product/RatedCard.vue'

interface Props {
    title?: string
    view_all_label?: string
    view_all_href?: string
}

withDefaults(defineProps<Props>(), {
    title: 'Best Rated Books',
    view_all_label: 'View All Books',
    view_all_href: '/products',
})

const products = [
    {
        slug: 'astral-journey',
        title: 'Astral Journey',
        category: 'Fantasy',
        price: '28.00',
        rating: 0,
        image: '/images/book-image-24.webp',
    },
    {
        slug: 'autumn-journey',
        title: 'Autumn Journey',
        category: 'Adventure',
        price: '17.00',
        rating: 5,
        image: '/images/book-image-29.webp',
    },
    {
        slug: 'unforgettable-moments',
        title: 'Unforgettable Moments',
        category: 'Romance',
        price: '28.00',
        rating: 5,
        image: '/images/book-image-26.webp',
    },
    {
        slug: 'anxiety-unmasked',
        title: 'Anxiety Unmasked',
        category: 'Self-help',
        price: '18.00',
        rating: 0,
        image: '/images/book-image-19.webp',
    },
    {
        slug: 'grand-science-fair',
        title: 'Grand Science Fair',
        category: 'Science',
        price: '23.00',
        rating: 0,
        image: '/images/book-image-18.webp',
    },
    {
        slug: 'best-italian-cuisines',
        title: 'Best Italian Cuisines',
        category: 'Cooking',
        price: '25.00',
        rating: 0,
        image: '/images/book-image-7.webp',
    },
    {
        slug: 'the-future-with-crypto',
        title: 'The Future With Crypto',
        category: 'Business',
        price: '28.00',
        rating: 5,
        image: '/images/book-image-33.webp',
    },
    {
        slug: 'economic-opportunity',
        title: 'Economic Opportunity',
        category: 'Business',
        price: '15.00',
        rating: 4,
        image: '/images/book-image-32.webp',
    },
    {
        slug: 'go-where-the-love-is',
        title: 'Go Where The Love Is',
        category: 'Romance',
        price: '30.00',
        rating: 4,
        image: '/images/book-image-25.webp',
    },
]
</script>

<style lang="scss" scoped>
.rated {
    background: $color-lightest;

    &__view-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: $color-dark;
        white-space: nowrap;
        flex-shrink: 0;
        transition: color 0.2s;

        svg {
            width: 12px;
            height: 12px;
            fill: currentColor;
        }

        &:hover {
            color: $color-primary;
        }
    }

    &__grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
}
</style>
