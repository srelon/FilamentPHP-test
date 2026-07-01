<template>
    <aside class="sidebar">
        <div v-if="show_newsletter" class="sidebar__block">
            <h4 class="sidebar__heading">Newsletter</h4>
            <p class="sidebar__newsletter-text">Subscribe to get the latest news and offers.</p>
            <form class="sidebar__newsletter-form" @submit.prevent>
                <input type="email" placeholder="Your email address" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>

        <div v-if="recent_posts?.length" class="sidebar__block">
            <h4 class="sidebar__heading">Recent Posts</h4>
            <ul class="sidebar__posts">
                <li v-for="post in recent_posts" :key="post.href" class="sidebar__post">
                    <router-link :to="post.href" class="sidebar__post-img-wrap">
                        <img :src="post.image" :alt="post.title" class="sidebar__post-img">
                    </router-link>
                    <div class="sidebar__post-info">
                        <router-link :to="post.href" class="sidebar__post-title">{{ post.title }}</router-link>
                        <time class="sidebar__post-date">{{ post.date }}</time>
                    </div>
                </li>
            </ul>
        </div>

        <div v-if="best_books?.length" class="sidebar__block">
            <h4 class="sidebar__heading">Best Selling Books</h4>
            <ul class="sidebar__books">
                <li v-for="book in best_books" :key="book.title" class="sidebar__book">
                    <router-link to="/products" class="sidebar__book-img-wrap">
                        <img :src="book.image" :alt="book.title" class="sidebar__book-img">
                    </router-link>
                    <div class="sidebar__book-info">
                        <router-link to="/products" class="sidebar__book-title">{{ book.title }}</router-link>
                        <span class="sidebar__book-price">${{ book.price }}</span>
                    </div>
                </li>
            </ul>
        </div>

        <div v-if="book_categories?.length" class="sidebar__block">
            <h4 class="sidebar__heading">Book Categories</h4>
            <ul class="sidebar__cats">
                <li v-for="cat in book_categories" :key="cat.name" class="sidebar__cat">
                    <router-link to="/products">{{ cat.name }}</router-link>
                    <span>{{ cat.count }}</span>
                </li>
            </ul>
        </div>
    </aside>
</template>

<script setup lang="ts">
interface Book {
    title: string
    price: string
    image: string
}

interface Category {
    name: string
    count: number
}

interface Post {
    title: string
    date: string
    image: string
    href: string
}

interface Props {
    show_newsletter?: boolean
    best_books?: Book[]
    book_categories?: Category[]
    recent_posts?: Post[]
}

withDefaults(defineProps<Props>(), {
    show_newsletter: false,
})
</script>

<style lang="scss" scoped>
@use "sass:color";

.sidebar {
    width: 280px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 36px;

    &__block {
        border-bottom: 1px solid $color-light;
        padding-bottom: 32px;

        &:last-child {
            border-bottom: none;
        }
    }

    &__heading {
        font-size: 16px;
        font-weight: 700;
        color: $color-dark;
        font-family: $font-heading;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 2px solid $color-primary;
        display: inline-block;
    }

    &__newsletter-text {
        font-size: 14px;
        color: $color-gray;
        line-height: 1.6;
        margin-bottom: 14px;
    }

    &__newsletter-form {
        display: flex;
        flex-direction: column;
        gap: 8px;

        input {
            padding: 10px 14px;
            border: 1.5px solid $color-light;
            border-radius: 6px;
            font-size: 14px;
            font-family: $font-body;
            outline: none;

            &:focus {
                border-color: $color-primary;
            }
        }

        button {
            padding: 10px;
            background: $color-primary;
            color: $color-white;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            font-family: $font-body;
            cursor: pointer;
            transition: background 0.2s;

            &:hover {
                background: color.adjust($color-primary, $lightness: -8%);
            }
        }
    }

    &__posts {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    &__post {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    &__post-img-wrap {
        width: 70px;
        flex-shrink: 0;
        border-radius: 6px;
        overflow: hidden;
        aspect-ratio: 4 / 3;
        display: block;
    }

    &__post-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;

        .sidebar__post:hover & {
            transform: scale(1.05);
        }
    }

    &__post-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding-top: 2px;
    }

    &__post-title {
        font-size: 14px;
        font-weight: 600;
        color: $color-dark;
        line-height: 1.4;
        transition: color 0.2s;

        &:hover {
            color: $color-primary;
        }
    }

    &__post-date {
        font-size: 12px;
        color: $color-gray;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    &__books {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    &__book {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    &__book-img-wrap {
        width: 52px;
        flex-shrink: 0;
        border-radius: 4px;
        overflow: hidden;
        aspect-ratio: 2 / 3;
        display: block;
    }

    &__book-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;

        .sidebar__book:hover & {
            transform: scale(1.05);
        }
    }

    &__book-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    &__book-title {
        font-size: 14px;
        font-weight: 600;
        color: $color-dark;
        line-height: 1.35;
        transition: color 0.2s;

        &:hover {
            color: $color-primary;
        }
    }

    &__book-price {
        font-size: 14px;
        font-weight: 700;
        color: $color-primary;
    }

    &__cats {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    &__cat {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;

        a {
            color: $color-dark;
            transition: color 0.2s;

            &:hover {
                color: $color-primary;
            }
        }

        span {
            font-size: 12px;
            color: $color-gray;
            background: $color-lightest;
            padding: 2px 8px;
            border-radius: 12px;
        }
    }
}
</style>
