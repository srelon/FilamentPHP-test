<template>
    <PageBanner title="Products" />

    <section class="products section">
        <div class="container">
            <div class="products__inner">
                <ProductSidebar @filter="on_filter" />

                <div class="products__main">
                    <div class="products__bar">
                        <p class="products__results">Showing {{ page_start }}–{{ page_end }} of {{ total }} results</p>
                        <select v-model="sort_by" class="products__sort">
                            <option value="default">Default sorting</option>
                            <option value="popularity">Sort by popularity</option>
                            <option value="rating">Sort by rating</option>
                            <option value="price_asc">Sort by price: low to high</option>
                            <option value="price_desc">Sort by price: high to low</option>
                        </select>
                    </div>

                    <div class="products__grid">
                        <ProductCard
                            v-for="product in current_products"
                            :key="product.title"
                            v-bind="product"
                        />
                    </div>

                    <BasePagination
                        :current_page="current_page"
                        :last_page="total_pages"
                        class="products__pagination"
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import PageBanner from '@/components/ui/base/PageBanner.vue'
import ProductSidebar from '@/components/ui/sidebar/ProductSidebar.vue'
import ProductCard from '@/components/ui/product/ProductCard.vue'
import BasePagination from '@/components/ui/base/BasePagination.vue'

const route = useRoute()
const sort_by = ref('default')
const current_page = computed(() => Number(route.query.page) || 1)
const per_page = 9
const total = 33
const total_pages = 4

const page_start = computed(() => (current_page.value - 1) * per_page + 1)
const page_end = computed(() => Math.min(current_page.value * per_page, total))

const all_products = [
    {
        title: 'Anxiety Unmasked',
        author: 'Theodore Langley',
        category: 'Self-help',
        price: '18.00',
        rating: 4,
        image: '/images/book-image-19.webp',
        href: '/product/anxiety-unmasked',
    },
    {
        title: 'Astral Journey',
        author: 'Nathaniel Parker',
        category: 'Fantasy',
        price: '28.00',
        rating: 5,
        image: '/images/book-image-24.webp',
        href: '/product/astral-journey',
    },
    {
        title: 'Autumn Journey',
        author: 'Samuel Wright',
        category: 'Adventure',
        price: '17.00',
        rating: 3,
        image: '/images/book-image-29.webp',
        href: '/product/autumn-journey',
    },
    {
        title: 'Best Italian Cuisines',
        author: 'Nathaniel Parker',
        category: 'Cooking',
        price: '25.00',
        rating: 4,
        image: '/images/book-image-7.webp',
        href: '/product/best-italian-cuisines',
    },
    {
        title: 'Economic Opportunity',
        author: 'Clara Whitfield',
        category: 'Business',
        price: '22.00',
        rating: 5,
        image: '/images/book-image-32.webp',
        href: '/product/economic-opportunity',
    },
    {
        title: 'Journey Through Time',
        author: 'Amelia Brooks',
        category: 'History',
        price: '19.00',
        rating: 4,
        image: '/images/book-image-25.webp',
        href: '/product/journey-through-time',
    },
    {
        title: 'Simple Aesthetics',
        author: 'Amelia Brooks',
        category: 'Art & Design',
        price: '32.00',
        rating: 5,
        image: '/images/book-image-26.webp',
        href: '/product/simple-aesthetics',
    },
    {
        title: 'The Silent Forest',
        author: 'Eleanor Finch',
        category: 'Novels',
        price: '21.00',
        rating: 3,
        image: '/images/book-image-33.webp',
        href: '/product/the-silent-forest',
    },
    {
        title: 'Mind & Wellness',
        author: 'Oliver Hartman',
        category: 'Self-help',
        price: '15.00',
        rating: 4,
        image: '/images/book-image-18.webp',
        href: '/product/mind-wellness',
    },
]

const current_products = computed(() => all_products.slice(0, per_page))

function on_filter() {}
</script>

<style lang="scss" scoped>
.products {
    &__inner {
        display: flex;
        gap: 40px;
        align-items: flex-start;
    }

    &__main {
        flex: 1;
        min-width: 0;
    }

    &__bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid $color-light;
    }

    &__results {
        font-size: 14px;
        color: $color-gray;
    }

    &__sort {
        padding: 8px 14px;
        border: 1.5px solid $color-light;
        border-radius: 6px;
        font-size: 14px;
        font-family: $font-body;
        color: $color-dark;
        outline: none;
        cursor: pointer;

        &:focus {
            border-color: $color-primary;
        }
    }

    &__grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    &__pagination {
        margin-top: 40px;
    }
}
</style>
