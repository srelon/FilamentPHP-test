<template>
    <section class="rated section">
        <div class="container">
            <div class="section__header">
                <h2 class="section__title">Best Rated Books</h2>
                <a href="/shop" class="rated__view-all">
                    View All Books
                    <svg viewBox="0 0 15 15" aria-hidden="true">
                        <path d="M1 15a1 1 0 0 1-.707-1.707L11.586 2H1.52a1 1 0 0 1 0-2h12.483q.202.002.379.075a1 1 0 0 1 .542.543 1 1 0 0 1 .076.38V13.48a1 1 0 1 1-2 0V3.414L1.707 14.707A1 1 0 0 1 1 15"/>
                    </svg>
                </a>
            </div>

            <div class="rated__grid">
                <div
                    v-for="product in products"
                    :key="product.title"
                    class="rated-item"
                >
                    <a href="#" class="rated-item__img-wrap">
                        <img :src="product.image" :alt="product.title" class="rated-item__img">
                    </a>
                    <div class="rated-item__info">
                        <h3 class="rated-item__title">
                            <a href="#">{{ product.title }}</a>
                        </h3>
                        <div v-if="product.rating" class="rated-item__stars" :aria-label="`Rated ${product.rating} out of 5`">
                            <span
                                v-for="i in 5"
                                :key="i"
                                class="rated-item__star"
                                :class="{ 'rated-item__star--filled': i <= Math.round(product.rating) }"
                            >★</span>
                        </div>
                        <span class="rated-item__category">{{ product.category }}</span>
                        <span class="rated-item__price">${{ product.price }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
const products = [
    {
        title: 'Astral Journey',
        category: 'Fantasy',
        price: '28.00',
        rating: 0,
        image: '/images/book-image-24.webp',
    },
    {
        title: 'Autumn Journey',
        category: 'Adventure',
        price: '17.00',
        rating: 5,
        image: '/images/book-image-29.webp',
    },
    {
        title: 'Unforgettable Moments',
        category: 'Romance',
        price: '28.00',
        rating: 5,
        image: '/images/book-image-26.webp',
    },
    {
        title: 'Anxiety Unmasked',
        category: 'Self-help',
        price: '18.00',
        rating: 0,
        image: '/images/book-image-19.webp',
    },
    {
        title: 'Grand Science Fair',
        category: 'Science',
        price: '23.00',
        rating: 0,
        image: '/images/book-image-18.webp',
    },
    {
        title: 'Best Italian Cuisines',
        category: 'Cooking',
        price: '25.00',
        rating: 0,
        image: '/images/book-image-7.webp',
    },
    {
        title: 'The Future With Crypto',
        category: 'Business',
        price: '28.00',
        rating: 5,
        image: '/images/book-image-33.webp',
    },
    {
        title: 'Economic Opportunity',
        category: 'Business',
        price: '15.00',
        rating: 4,
        image: '/images/book-image-32.webp',
    },
    {
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

.rated-item {
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

        .rated-item:hover & {
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
