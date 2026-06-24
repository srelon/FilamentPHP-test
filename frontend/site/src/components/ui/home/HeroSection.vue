<template>
    <section class="hero">
        <div class="container">
            <div class="hero__top">
                <h1 class="hero__title">Where Quiet Moments Meet Great Books</h1>
                <p class="hero__desc">Discover new releases, bestsellers, and hidden gems on our virtual shelves. Enjoy smooth navigation and secure checkout for a seamless book-buying experience.</p>
            </div>

            <div class="hero__bottom">
                <div class="hero__col hero__col--left">
                    <div class="hero__buttons">
                        <router-link to="/products" class="hero__btn hero__btn--primary">
                            Shop Now
                            <svg viewBox="0 0 15 15" aria-hidden="true">
                                <path d="M1 15a1 1 0 0 1-.707-1.707L11.586 2H1.52a1 1 0 0 1 0-2h12.483q.202.002.379.075a1 1 0 0 1 .542.543 1 1 0 0 1 .076.38V13.48a1 1 0 1 1-2 0V3.414L1.707 14.707A1 1 0 0 1 1 15"/>
                            </svg>
                        </router-link>
                        <router-link to="/products" class="hero__btn hero__btn--outline">
                            View Categories
                            <svg viewBox="0 0 15 15" aria-hidden="true">
                                <path d="M1 15a1 1 0 0 1-.707-1.707L11.586 2H1.52a1 1 0 0 1 0-2h12.483q.202.002.379.075a1 1 0 0 1 .542.543 1 1 0 0 1 .076.38V13.48a1 1 0 1 1-2 0V3.414L1.707 14.707A1 1 0 0 1 1 15"/>
                            </svg>
                        </router-link>
                    </div>

                    <div class="hero__card hero__card--stretch">
                        <img src="/images/home-hero-image-1.webp" alt="Cozy classic books" class="hero__card-img">
                        <div class="hero__card-overlay">
                            <button class="hero__card-arrow" aria-label="View">
                                <svg viewBox="0 0 15 15" aria-hidden="true">
                                    <path d="M1 15a1 1 0 0 1-.707-1.707L11.586 2H1.52a1 1 0 0 1 0-2h12.483q.202.002.379.075a1 1 0 0 1 .542.543 1 1 0 0 1 .076.38V13.48a1 1 0 1 1-2 0V3.414L1.707 14.707A1 1 0 0 1 1 15"/>
                                </svg>
                            </button>
                            <div class="hero__card-info">
                                <h5>Find Cozy Comfort While Exploring Truly Beautiful Classic Books</h5>
                                <div class="hero__card-badge">
                                    <svg viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M17.692 20c-.162 0-.325-.046-.467-.136l-1.937-1.24-1.937 1.24c-.266.169-.605.183-.882.03s-.45-.443-.45-.76v-3.941H4.23c-.583 0-1.057.474-1.057 1.057s.474 1.058 1.057 1.058h5.289c.478 0 .865.388.865.865s-.388.865-.865.865H4.23C2.693 18.998 1.442 17.747 1.442 16.21V2.788C1.442 1.251 2.693 0 4.23 0h12.885c.796 0 1.442.647 1.442 1.442v12.61c0 .047-.003.092-.011.137.008.045.011.09.011.137v4.807c0 .317-.172.607-.45.76-.13.071-.273.106-.416.106z"/>
                                    </svg>
                                    125 Books
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero__col hero__col--mid">
                    <div class="hero__card hero__card--stretch">
                        <img src="/images/home-hero-image-2.webp" alt="Warm inspiring recipes" class="hero__card-img">
                        <div class="hero__card-overlay">
                            <button class="hero__card-arrow" aria-label="View">
                                <svg viewBox="0 0 15 15" aria-hidden="true">
                                    <path d="M1 15a1 1 0 0 1-.707-1.707L11.586 2H1.52a1 1 0 0 1 0-2h12.483q.202.002.379.075a1 1 0 0 1 .542.543 1 1 0 0 1 .076.38V13.48a1 1 0 1 1-2 0V3.414L1.707 14.707A1 1 0 0 1 1 15"/>
                                </svg>
                            </button>
                            <div class="hero__card-info">
                                <h5>Discover Warm and Inspiring Recipes to Enjoy</h5>
                                <div class="hero__card-badge">
                                    <svg viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M17.692 20c-.162 0-.325-.046-.467-.136l-1.937-1.24-1.937 1.24c-.266.169-.605.183-.882.03s-.45-.443-.45-.76v-3.941H4.23c-.583 0-1.057.474-1.057 1.057s.474 1.058 1.057 1.058h5.289c.478 0 .865.388.865.865s-.388.865-.865.865H4.23C2.693 18.998 1.442 17.747 1.442 16.21V2.788C1.442 1.251 2.693 0 4.23 0h12.885c.796 0 1.442.647 1.442 1.442v12.61c0 .047-.003.092-.011.137.008.045.011.09.011.137v4.807c0 .317-.172.607-.45.76-.13.071-.273.106-.416.106z"/>
                                    </svg>
                                    83 Books
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hero__newsletter">
                        <p>Save <strong>20%</strong> Off Only This Holiday Season</p>
                        <form class="hero__newsletter-form" @submit.prevent>
                            <input type="email" placeholder="Your email *" aria-label="Email address" required>
                            <button type="submit">Enroll</button>
                        </form>
                    </div>
                </div>

                <div class="hero__col hero__col--right">
                    <div class="hero__card hero__card--stretch">
                        <img src="/images/home-hero-image-3.webp" alt="Creative home ideas books" class="hero__card-img">
                        <div class="hero__card-overlay">
                            <button class="hero__card-arrow" aria-label="View">
                                <svg viewBox="0 0 15 15" aria-hidden="true">
                                    <path d="M1 15a1 1 0 0 1-.707-1.707L11.586 2H1.52a1 1 0 0 1 0-2h12.483q.202.002.379.075a1 1 0 0 1 .542.543 1 1 0 0 1 .076.38V13.48a1 1 0 1 1-2 0V3.414L1.707 14.707A1 1 0 0 1 1 15"/>
                                </svg>
                            </button>
                            <div class="hero__card-info">
                                <h5>Explore Creative Home Ideas Inside These Books</h5>
                                <div class="hero__card-badge">
                                    <svg viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M17.692 20c-.162 0-.325-.046-.467-.136l-1.937-1.24-1.937 1.24c-.266.169-.605.183-.882.03s-.45-.443-.45-.76v-3.941H4.23c-.583 0-1.057.474-1.057 1.057s.474 1.058 1.057 1.058h5.289c.478 0 .865.388.865.865s-.388.865-.865.865H4.23C2.693 18.998 1.442 17.747 1.442 16.21V2.788C1.442 1.251 2.693 0 4.23 0h12.885c.796 0 1.442.647 1.442 1.442v12.61c0 .047-.003.092-.011.137.008.045.011.09.011.137v4.807c0 .317-.172.607-.45.76-.13.071-.273.106-.416.106z"/>
                                    </svg>
                                    47 Books
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style lang="scss" scoped>
.hero {
    background: $color-lightest;
    padding: 50px 0;

    &__top {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        align-items: end;
        margin-bottom: 24px;
    }

    &__title {
        font-size: clamp(28px, 3vw, 46px);
        font-weight: 700;
        color: $color-dark;
        line-height: 1.15;
    }

    &__desc {
        font-size: 15px;
        color: $color-gray;
        line-height: 1.7;
    }

    &__bottom {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 16px;
        align-items: stretch;
        min-height: 500px;
    }

    &__col {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    &__buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        flex-shrink: 0;
    }

    &__btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 13px 22px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.2s, color 0.2s, border-color 0.2s;

        svg {
            width: 12px;
            height: 12px;
            fill: currentColor;
            flex-shrink: 0;
        }

        &--primary {
            background: $color-primary;
            color: $color-white;

            &:hover {
                background: darken($color-primary, 8%);
            }
        }

        &--outline {
            background: transparent;
            color: $color-dark;
            border: 1.5px solid $color-dark;

            &:hover {
                background: $color-dark;
                color: $color-white;
            }
        }
    }

    &__card {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        min-height: 200px;

        &--stretch {
            flex: 1;
        }
    }

    &__card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        position: absolute;
        inset: 0;
        transition: transform 0.4s ease;

        .hero__card:hover & {
            transform: scale(1.05);
        }
    }

    &__card-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 16px;
        background: linear-gradient(0deg, rgba(0, 0, 0, 0.6) 0%, transparent 55%);
    }

    &__card-arrow {
        align-self: flex-end;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: $color-white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s, transform 0.2s;
        flex-shrink: 0;

        svg {
            width: 12px;
            height: 12px;
            fill: $color-dark;
        }

        &:hover {
            background: $color-primary;

            svg {
                fill: $color-white;
            }
        }

        .hero__card:hover & {
            transform: rotate(45deg);
        }
    }

    &__card-info {
        color: $color-white;

        h5 {
            font-size: 13px;
            font-weight: 600;
            color: $color-white;
            line-height: 1.4;
            margin-bottom: 10px;
            max-width: 90%;
        }
    }

    &__card-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: $color-lightest;
        color: $color-dark;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;

        svg {
            width: 14px;
            height: 14px;
            fill: $color-dark;
            flex-shrink: 0;
        }
    }

    &__newsletter {
        background: $color-primary;
        color: $color-white;
        border-radius: 10px;
        padding: 24px 18px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 16px;
        flex-shrink: 0;

        p {
            font-size: 15px;
            line-height: 1.5;

            strong {
                font-weight: 700;
            }
        }
    }

    &__newsletter-form {
        display: flex;
        flex-direction: column;
        gap: 8px;

        input {
            border: none;
            outline: none;
            border-radius: 30px;
            padding: 10px 16px;
            font-size: 14px;
            font-family: $font-body;

            &::placeholder {
                color: $color-gray;
            }
        }

        button {
            border-radius: 30px;
            padding: 10px 16px;
            background: $color-dark;
            color: $color-white;
            font-size: 14px;
            font-weight: 600;
            font-family: $font-body;
            cursor: pointer;
            transition: background 0.2s;

            &:hover {
                background: lighten($color-dark, 15%);
            }
        }
    }
}
</style>
