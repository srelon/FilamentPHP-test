<template>
    <section class="blog section">
        <div class="container">
            <div class="section__header">
                <h2 class="section__title">Latest Blog Posts</h2>
                <a href="/news" class="blog__view-all">
                    View All Posts
                    <svg viewBox="0 0 15 15" aria-hidden="true">
                        <path d="M1 15a1 1 0 0 1-.707-1.707L11.586 2H1.52a1 1 0 0 1 0-2h12.483q.202.002.379.075a1 1 0 0 1 .542.543 1 1 0 0 1 .076.38V13.48a1 1 0 1 1-2 0V3.414L1.707 14.707A1 1 0 0 1 1 15"/>
                    </svg>
                </a>
            </div>

            <div class="blog__grid">
                <div class="blog__col blog__col--list">
                    <article v-for="post in left_posts" :key="post.title" class="blog-card blog-card--horizontal">
                        <a href="#" class="blog-card__img-wrap">
                            <img :src="post.image" :alt="post.title" class="blog-card__img">
                        </a>
                        <div class="blog-card__body">
                            <time class="blog-card__date">{{ post.date }}</time>
                            <h3 class="blog-card__title">
                                <a href="#">{{ post.title }}</a>
                            </h3>
                        </div>
                    </article>
                </div>

                <div class="blog__col blog__col--featured">
                    <article class="blog-card blog-card--cover">
                        <img :src="featured_post.image" :alt="featured_post.title" class="blog-card__cover-img">
                        <div class="blog-card__cover-overlay">
                            <time class="blog-card__date">{{ featured_post.date }}</time>
                            <h2 class="blog-card__cover-title">
                                <a href="#">{{ featured_post.title }}</a>
                            </h2>
                            <p class="blog-card__cover-cats">In {{ featured_post.categories }}</p>
                        </div>
                    </article>
                </div>

                <div class="blog__col blog__col--list">
                    <article v-for="post in right_posts" :key="post.title" class="blog-card blog-card--horizontal">
                        <a href="#" class="blog-card__img-wrap">
                            <img :src="post.image" :alt="post.title" class="blog-card__img">
                        </a>
                        <div class="blog-card__body">
                            <time class="blog-card__date">{{ post.date }}</time>
                            <h3 class="blog-card__title">
                                <a href="#">{{ post.title }}</a>
                            </h3>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
const left_posts = [
    {
        title: 'Crasduis Aliquam Dolor Aliquam',
        date: 'December 16, 2025',
        image: '/images/blog-image-9.webp',
    },
    {
        title: 'Ornare Curabitur Vitae Scelerisque',
        date: 'December 16, 2025',
        image: '/images/blog-image-8.webp',
    },
    {
        title: 'Lobortis Habitasse Dictumst Turpis',
        date: 'December 16, 2025',
        image: '/images/blog-image-7.webp',
    },
]

const featured_post = {
    title: 'Blandit Praesent Morbi Faucibus',
    date: 'December 16, 2025',
    categories: 'Cultural, Literature',
    image: '/images/blog-image-6.webp',
}

const right_posts = [
    {
        title: 'Massa Fames Eleifend Convallis',
        date: 'December 16, 2025',
        image: '/images/blog-image-5.webp',
    },
    {
        title: 'Platea Justo Curabitur Consequat',
        date: 'December 16, 2025',
        image: '/images/blog-image-4.webp',
    },
    {
        title: 'Porttitor Suspendisse Bibendum',
        date: 'December 16, 2025',
        image: '/images/blog-image-3.webp',
    },
]
</script>

<style lang="scss" scoped>
.blog {
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
        grid-template-columns: 1fr 1fr 1fr;
        gap: 24px;
        align-items: stretch;
    }

    &__col {
        display: flex;
        flex-direction: column;
        gap: 24px;

        &--featured {
            display: flex;
        }
    }
}

.blog-card {
    &--horizontal {
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    &__img-wrap {
        flex-shrink: 0;
        border-radius: 8px;
        overflow: hidden;
        width: 50%;
    }

    &__img {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;

        .blog-card:hover & {
            transform: scale(1.04);
        }
    }

    &__body {
        flex: 1;
        padding-top: 4px;
    }

    &__date {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: $color-gray;
        margin-bottom: 6px;
    }

    &__title {
        font-size: 14px;
        font-weight: 600;
        line-height: 1.45;

        a {
            color: $color-dark;
            transition: color 0.2s;

            &:hover {
                color: $color-primary;
            }
        }
    }

    &--cover {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        min-height: 300px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    &__cover-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    &__cover-overlay {
        position: relative;
        padding: 24px;
        background: linear-gradient(0deg, rgba(0, 0, 0, 0.75) 0%, transparent 70%);
        color: $color-white;
    }

    &__cover-title {
        font-size: clamp(16px, 1.4vw, 22px);
        font-weight: 700;
        color: $color-white;
        line-height: 1.35;
        margin-bottom: 8px;

        a {
            color: inherit;

            &:hover {
                color: $color-primary-light;
            }
        }
    }

    &__cover-cats {
        font-size: 11px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.7);
    }
}
</style>
