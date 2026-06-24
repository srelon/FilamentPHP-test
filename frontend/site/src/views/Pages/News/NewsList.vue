<template>
    <PageBanner title="News" />

    <section class="news section">
        <div class="container">
            <div class="news__inner">
                <div class="news__main">
                    <div class="news__grid">
                        <BlogCard
                            v-for="post in posts"
                            :key="post.title"
                            v-bind="post"
                        />
                    </div>

                    <BasePagination
                        :current_page="current_page"
                        :last_page="last_page"
                        class="news__pagination"
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
import BlogCard from '@/components/ui/blog/BlogCard.vue'
import Sidebar from '@/components/ui/sidebar/Sidebar.vue'
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

const posts = [
    {
        title: 'Blandit Praesent Morbi Faucibus',
        excerpt: 'Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Praesent sapien massa, convallis a pellentesque nec.',
        category: 'Literature',
        author: 'Admin',
        date: 'December 20, 2025',
        image: '/images/blog-image-3.webp',
        href: '/news/blandit-praesent',
    },
    {
        title: 'Ornare Curabitur Vitae Scelerisque',
        excerpt: 'Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Curabitur aliquet quam id dui posuere blandit.',
        category: 'Cultural',
        author: 'Admin',
        date: 'December 18, 2025',
        image: '/images/blog-image-4.webp',
        href: '/news/ornare-curabitur',
    },
    {
        title: 'Massa Fames Eleifend Convallis',
        excerpt: 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; proin vel ante a orci tempus eleifend.',
        category: 'Literature',
        author: 'Admin',
        date: 'December 15, 2025',
        image: '/images/blog-image-5.webp',
        href: '/news/massa-fames',
    },
    {
        title: 'Porttitor Suspendisse Bibendum',
        excerpt: 'Nulla porttitor accumsan tincidunt. Cras ultricies ligula sed magna dictum porta.',
        category: 'Reading',
        author: 'Admin',
        date: 'December 12, 2025',
        image: '/images/blog-image-6.webp',
        href: '/news/porttitor-suspendisse',
    },
    {
        title: 'Platea Justo Curabitur Consequat',
        excerpt: 'Quisque velit nisi, pretium ut lacinia in, elementum id enim. Donec rutrum congue leo eget malesuada.',
        category: 'Authors',
        author: 'Admin',
        date: 'December 10, 2025',
        image: '/images/blog-image-7.webp',
        href: '/news/platea-justo',
    },
    {
        title: 'Volutpat Tempor Accumsan Porta',
        excerpt: 'Pellentesque in ipsum id orci porta dapibus. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus.',
        category: 'Cultural',
        author: 'Admin',
        date: 'December 8, 2025',
        image: '/images/blog-image-8.webp',
        href: '/news/volutpat-tempor',
    },
]
</script>

<style lang="scss" scoped>
.news {
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
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }

    &__pagination {
        margin-top: 40px;
    }
}
</style>
