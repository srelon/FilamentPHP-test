<template>
    <PageBanner title="Authors" />

    <section class="authors section">
        <div class="container">
            <div class="authors__inner">
                <div class="authors__main">
                    <div class="authors__grid">
                        <AuthorCard
                            v-for="author in authors"
                            :key="author.slug"
                            v-bind="author"
                        />
                    </div>

                    <BasePagination
                        :current_page="current_page"
                        :last_page="last_page"
                        class="authors__pagination"
                    />
                </div>

                <Sidebar
                    :show_newsletter="true"
                    :best_books="best_books"
                    :book_categories="book_categories"
                />
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import PageBanner from '@/components/ui/base/PageBanner.vue'
import Sidebar from '@/components/ui/sidebar/Sidebar.vue'
import AuthorCard from '@/components/ui/author/AuthorCard.vue'
import BasePagination from '@/components/ui/base/BasePagination.vue'

const route = useRoute()
const last_page = 2
const current_page = computed(() => Number(route.query.page) || 1)

const best_books = [
    {
        title: 'Anxiety Unmasked',
        price: '18.00',
        image: '/images/book-image-19.webp',
    },
    {
        title: 'Astral Journey',
        price: '28.00',
        image: '/images/book-image-24.webp',
    },
    {
        title: 'Autumn Journey',
        price: '17.00',
        image: '/images/book-image-29.webp',
    },
    {
        title: 'Best Italian Cuisines',
        price: '25.00',
        image: '/images/book-image-7.webp',
    },
    {
        title: 'Economic Opportunity',
        price: '22.00',
        image: '/images/book-image-32.webp',
    },
]

const book_categories = [
    { name: 'Art & Design', count: 8 },
    { name: 'Self-help', count: 12 },
    { name: 'Science', count: 6 },
    { name: 'Romance', count: 9 },
    { name: 'Fantasy', count: 11 },
    { name: 'Cooking', count: 10 },
]

const authors = [
    {
        name: 'Oliver Hartman',
        initials: 'OH',
        color: '#ff6310',
        bio: 'Oliver Hartman is a bestselling author known for his compelling narratives and deep character studies across multiple genres.',
        books: 6,
    },
    {
        name: 'Clara Whitfield',
        initials: 'CW',
        color: '#6b4fff',
        bio: 'Clara Whitfield writes with elegance and precision, crafting stories that linger long after the last page is turned.',
        books: 4,
    },
    {
        name: 'Henry Caldwell',
        initials: 'HC',
        color: '#18b96e',
        bio: 'Henry Caldwell is celebrated for his rich historical fiction and immersive world-building that transports readers to another era.',
        books: 5,
    },
    {
        name: 'Amelia Brooks',
        initials: 'AB',
        color: '#e84393',
        bio: 'Amelia Brooks combines vivid imagination with emotional depth to create unforgettable journeys through her books.',
        books: 4,
    },
    {
        name: 'Nathaniel Parker',
        initials: 'NP',
        color: '#f5a623',
        bio: 'Nathaniel Parker is a prolific author whose works span science, fantasy, and adventure with a unique poetic voice.',
        books: 5,
    },
    {
        name: 'Eleanor Finch',
        initials: 'EF',
        color: '#2196f3',
        bio: 'Eleanor Finch writes thought-provoking literary fiction that challenges conventions and celebrates the human spirit.',
        books: 3,
    },
]
</script>

<style lang="scss" scoped>
.authors {
    &__inner {
        display: flex;
        gap: 40px;
        align-items: flex-start;
    }

    &__main {
        flex: 1;
        min-width: 0;
    }

    &__grid {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    &__pagination {
        margin-top: 40px;
    }
}
</style>
