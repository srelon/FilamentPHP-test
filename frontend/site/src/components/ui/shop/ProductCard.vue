<template>
    <div class="book-card">
        <div class="book-card__figure">
            <img :src="image" :alt="title" class="book-card__img">
            <div class="book-card__actions">
                <button aria-label="Add to wishlist" class="book-card__action">
                    <svg viewBox="0 0 15 15" aria-hidden="true">
                        <path d="M13.4,3.2c-0.9-0.8-2.3-1-3.5-0.8C8.9,2.6,8.1,3,7.5,3.7C6.9,3,6.1,2.6,5.2,2.4c-1.3-0.2-2.6,0-3.6,0.8C0.7,3.9,0.1,5,0,6.1c-0.1,1.3,0.3,2.6,1.3,3.7c1.2,1.4,5.6,4.7,5.8,4.8L7.5,15L8,14.6c0.2-0.1,4.5-3.5,5.7-4.8c1-1.1,1.4-2.4,1.3-3.7C14.9,5,14.3,3.9,13.4,3.2z"/>
                    </svg>
                </button>
                <button aria-label="Add to cart" class="book-card__action">
                    <svg viewBox="0 0 15 15" aria-hidden="true">
                        <path d="M14.1,1.6C14,0.7,13.3,0,12.4,0H2.7C1.7,0,1,0.7,0.9,1.6L0.1,13.1c0,0.5,0.1,1,0.5,1.3C0.9,14.8,1.3,15,1.8,15h11.4c0.5,0,0.9-0.2,1.3-0.6c0.3-0.4,0.5-0.8,0.5-1.3L14.1,1.6z"/>
                    </svg>
                </button>
                <button aria-label="Quick view" class="book-card__action">
                    <svg viewBox="0 0 15 15" aria-hidden="true">
                        <path d="M7.5,5.5c-1.1,0-1.9,0.9-1.9,2s0.9,2,1.9,2s1.9-0.9,1.9-2S8.6,5.5,7.5,5.5z M14.7,6.9c-0.9-1.6-2.9-5.2-7.1-5.2S1.3,5.3,0.4,6.9L0,7.5l0.4,0.6c0.9,1.6,2.9,5.2,7.1,5.2s6.3-3.7,7.1-5.2L15,7.5L14.7,6.9zM7.5,11.8c-3.2,0-4.9-2.8-5.7-4.3C2.6,6,4.3,3.2,7.5,3.2s4.9,2.8,5.7,4.3C12.4,9,10.8,11.8,7.5,11.8z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="book-card__body">
            <h3 class="book-card__title">
                <a :href="href">{{ title }}</a>
            </h3>
            <div class="book-card__meta">
                <span class="book-card__author">{{ author }}</span>
                <span class="book-card__separator">·</span>
                <span class="book-card__category">{{ category }}</span>
            </div>
            <div class="book-card__footer">
                <span class="book-card__price">${{ price }}</span>
                <button class="book-card__cart">Add to cart</button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
interface Props {
    title: string
    author: string
    category: string
    price: string
    image: string
    href?: string
}

withDefaults(defineProps<Props>(), {
    href: '#',
})
</script>

<style lang="scss" scoped>
.book-card {
    &__figure {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 16px;
        aspect-ratio: 2 / 3;
    }

    &__img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;

        .book-card:hover & {
            transform: scale(1.04);
        }
    }

    &__actions {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%) translateX(60px);
        display: flex;
        flex-direction: column;
        gap: 6px;
        transition: transform 0.3s ease;

        .book-card:hover & {
            transform: translateY(-50%) translateX(0);
        }
    }

    &__action {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: $color-white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        transition: background 0.2s;

        svg {
            width: 14px;
            height: 14px;
            fill: $color-dark;
            transition: fill 0.2s;
        }

        &:hover {
            background: $color-primary;

            svg {
                fill: $color-white;
            }
        }
    }

    &__title {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 6px;

        a {
            color: $color-dark;
            transition: color 0.2s;

            &:hover {
                color: $color-primary;
            }
        }
    }

    &__meta {
        font-size: 13px;
        color: $color-gray;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    &__footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    &__price {
        font-size: 16px;
        font-weight: 700;
        color: $color-primary;
    }

    &__cart {
        font-size: 13px;
        font-weight: 600;
        color: $color-dark;
        font-family: $font-body;
        cursor: pointer;
        padding: 6px 14px;
        border: 1.5px solid $color-light;
        border-radius: 4px;
        transition: background 0.2s, color 0.2s, border-color 0.2s;

        &:hover {
            background: $color-primary;
            color: $color-white;
            border-color: $color-primary;
        }
    }
}
</style>
