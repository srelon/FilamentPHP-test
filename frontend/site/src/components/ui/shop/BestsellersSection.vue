<template>
    <section class="bestsellers section">
        <div class="container">
            <div class="section__header">
                <div>
                    <h2 class="section__title">{{ title }}</h2>
                    <p class="bestsellers__desc">{{ description }}</p>
                </div>
                <a href="/shop" class="bestsellers__view-all">
                    View All
                    <svg viewBox="0 0 15 15" aria-hidden="true">
                        <path d="M1 15a1 1 0 0 1-.707-1.707L11.586 2H1.52a1 1 0 0 1 0-2h12.483q.202.002.379.075a1 1 0 0 1 .542.543 1 1 0 0 1 .076.38V13.48a1 1 0 1 1-2 0V3.414L1.707 14.707A1 1 0 0 1 1 15"/>
                    </svg>
                </a>
            </div>

            <div class="bestsellers__grid">
                <ProductCard
                    v-for="product in products"
                    :key="product.title"
                    :title="product.title"
                    :author="product.author"
                    :category="product.category"
                    :price="product.price"
                    :image="product.image"
                />
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import ProductCard from '@/components/ui/shop/ProductCard.vue'

interface Props {
    title?: string
    description?: string
}

withDefaults(defineProps<Props>(), {
    title: 'Bestsellers of the week',
    description: 'Quam elementum pulvinar etiam non quam. Faucibus nisl tincidunt eget nullam non nisi elementum sagittis vitae et leo duis ut diam quam.',
})

const products = [
    {
        title: 'Anxiety Unmasked',
        author: 'Theodore Langley',
        category: 'Self-help',
        price: '18.00',
        image: '/images/book-image-19.webp',
    },
    {
        title: 'Astral Journey',
        author: 'Nathaniel Parker',
        category: 'Fantasy',
        price: '28.00',
        image: '/images/book-image-24.webp',
    },
    {
        title: 'Autumn Journey',
        author: 'Samuel Wright',
        category: 'Adventure',
        price: '17.00',
        image: '/images/book-image-29.webp',
    },
    {
        title: 'Best Italian Cuisines',
        author: 'Nathaniel Parker',
        category: 'Cooking',
        price: '25.00',
        image: '/images/book-image-7.webp',
    },
]
</script>

<style lang="scss" scoped>
.bestsellers {
    &__desc {
        font-size: 14px;
        color: $color-gray;
        margin-top: 8px;
        max-width: 600px;
    }

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
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }
}
</style>
