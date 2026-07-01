<template>
    <PageBanner title="About Us" />

    <section class="about section">
        <div class="container">
            <div class="about__intro">
                <h2 class="about__quote">The Right Book In The Right Hands At The Right Time Can Change The World</h2>
                <div class="about__intro-side">
                    <p class="about__intro-desc">We are a passionate team of book lovers dedicated to connecting readers with stories that inspire, educate, and transform. Our curated collection spans every genre.</p>
                    <ul class="about__contact-list">
                        <li>
                            <svg viewBox="0 0 15 15" aria-hidden="true"><path d="M7.5 0A5.5 5.5 0 0 0 2 5.5C2 9.358 7.5 15 7.5 15S13 9.358 13 5.5A5.5 5.5 0 0 0 7.5 0m0 7.5a2 2 0 1 1 2-2 2 2 0 0 1-2 2"/></svg>
                            <span>27 Division St, New York, NY 10002</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 15 15" aria-hidden="true"><path d="M7.5.5A6.5 6.5 0 1 0 14 7 6.508 6.508 0 0 0 7.5.5m0 11.75A5.25 5.25 0 1 1 12.75 7 5.256 5.256 0 0 1 7.5 12.25M8.125 7V4.25a.625.625 0 0 0-1.25 0V7.5a.625.625 0 0 0 .625.625H10a.625.625 0 0 0 0-1.25z"/></svg>
                            <span>Mon–Sat: 9:00AM – 6:00PM</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 15 15" aria-hidden="true"><path d="M13.5 2h-12A1.5 1.5 0 0 0 0 3.5v8A1.5 1.5 0 0 0 1.5 13h12a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 13.5 2m0 1 .09.007L7.5 7.572 1.41 3.007A.5.5 0 0 1 1.5 3zM1 11.5v-7.8l6.22 4.573a.5.5 0 0 0 .56 0L14 3.7v7.8a.5.5 0 0 1-.5.5h-12a.5.5 0 0 1-.5-.5"/></svg>
                            <a href="mailto:hello@example.com">hello@example.com</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="team section">
        <div class="container">
            <h2 class="section__title">Meet The People Behind Our Bookstore</h2>
            <div class="team__grid">
                <div v-for="member in team" :key="member.name" class="team__card">
                    <div class="team__avatar" :style="{ background: member.color }">
                        {{ member.initials }}
                    </div>
                    <h3 class="team__name">{{ member.name }}</h3>
                    <span class="team__role">{{ member.role }}</span>
                    <p class="team__bio">{{ member.bio }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-cta">
        <div class="container">
            <div class="about-cta__inner">
                <div class="about-cta__left">
                    <h2 class="about-cta__title">Immerse Yourself In The Fascinating World Of Literature</h2>
                    <router-link to="/products" class="about-cta__btn">Explore Collection</router-link>
                </div>
                <div class="about-cta__right">
                    <p class="about-cta__sub">Join our community</p>
                    <p class="about-cta__count">58,000+ subscribers</p>
                    <form class="about-cta__form" @submit.prevent>
                        <input type="email" placeholder="Your email address" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="about-perks">
        <div class="container">
            <div class="about-perks__grid">
                <div v-for="perk in perks" :key="perk.title" class="about-perks__item">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="about-perks__icon">
                        <path :d="perk.icon"/>
                    </svg>
                    <div>
                        <h4 class="about-perks__title">{{ perk.title }}</h4>
                        <p class="about-perks__desc">{{ perk.desc }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import PageBanner from '@/components/ui/base/PageBanner.vue'

const team = [
    {
        name: 'Daniel Brooks',
        role: 'Founder & CEO',
        initials: 'DB',
        color: '#ff6310',
        bio: 'Passionate bibliophile with 15 years in publishing.',
    },
    {
        name: 'Emma Collins',
        role: 'Head of Curation',
        initials: 'EC',
        color: '#6b4fff',
        bio: 'Former librarian turned digital curator.',
    },
    {
        name: 'Michael Reed',
        role: 'Lead Developer',
        initials: 'MR',
        color: '#18b96e',
        bio: 'Builds the tech that powers your reading journey.',
    },
    {
        name: 'Oliver Grant',
        role: 'Marketing Director',
        initials: 'OG',
        color: '#e84393',
        bio: 'Storyteller at heart, strategist by trade.',
    },
    {
        name: 'Sophia Turner',
        role: 'Customer Success',
        initials: 'ST',
        color: '#f5a623',
        bio: 'Making sure every reader finds their next favorite book.',
    },
    {
        name: 'Ethan Walker',
        role: 'Content Editor',
        initials: 'EW',
        color: '#2196f3',
        bio: 'Reviews, edits, and helps authors shine.',
    },
    {
        name: 'Lucas Meyer',
        role: 'Logistics Manager',
        initials: 'LM',
        color: '#009688',
        bio: 'Ensures your order arrives safe and on time.',
    },
    {
        name: 'Noah Bennett',
        role: 'Design Lead',
        initials: 'NB',
        color: '#9c27b0',
        bio: 'Crafts the visual experience you love.',
    },
]

const perks = [
    {
        title: 'Free Delivery',
        desc: 'On all orders over $40',
        icon: 'M1 1h3l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L22 6H6M16 19a1 1 0 1 1-2 0 1 1 0 0 1 2 0M9 19a1 1 0 1 1-2 0 1 1 0 0 1 2 0',
    },
    {
        title: '24/7 Support',
        desc: 'Round-the-clock assistance',
        icon: 'M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z',
    },
    {
        title: 'Easy Returns',
        desc: '30-day hassle-free returns',
        icon: 'M1 4v6h6M23 20v-6h-6M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4-4.64 4.36A9 9 0 0 1 3.51 15',
    },
    {
        title: 'Secure Checkout',
        desc: 'SSL encrypted payments',
        icon: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
    },
]
</script>

<style lang="scss" scoped>
@use "sass:color";

.about {
    &__intro {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: start;
    }

    &__quote {
        font-size: clamp(20px, 2.2vw, 32px);
        font-weight: 700;
        color: $color-dark;
        line-height: 1.3;
    }

    &__intro-desc {
        font-size: 15px;
        color: $color-gray;
        line-height: 1.7;
        margin-bottom: 24px;
    }

    &__contact-list {
        display: flex;
        flex-direction: column;
        gap: 12px;

        li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 14px;
            color: $color-dark;
        }

        svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            margin-top: 2px;

            path {
                fill: $color-primary;
            }
        }

        a {
            color: $color-dark;

            &:hover {
                color: $color-primary;
            }
        }
    }
}

.team {
    background: $color-lightest;

    &__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-top: 40px;
    }

    &__card {
        background: $color-white;
        border-radius: 12px;
        padding: 28px 20px;
        text-align: center;
        border: 1px solid $color-light;
        transition: box-shadow 0.2s;

        &:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }
    }

    &__avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
        color: $color-white;
        margin: 0 auto 16px;
        font-family: $font-heading;
    }

    &__name {
        font-size: 16px;
        font-weight: 700;
        color: $color-dark;
        margin-bottom: 4px;
    }

    &__role {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: $color-primary;
        display: block;
        margin-bottom: 12px;
    }

    &__bio {
        font-size: 13px;
        color: $color-gray;
        line-height: 1.6;
    }
}

.about-cta {
    background: $color-dark;
    padding: 60px 0;

    &__inner {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }

    &__title {
        font-size: clamp(20px, 2vw, 30px);
        color: $color-white;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 24px;
    }

    &__btn {
        display: inline-flex;
        align-items: center;
        padding: 13px 28px;
        background: $color-primary;
        color: $color-white;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        transition: background 0.2s;

        &:hover {
            background: color.adjust($color-primary, $lightness: -8%);
        }
    }

    &__sub {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: 4px;
    }

    &__count {
        font-size: 28px;
        font-weight: 700;
        color: $color-primary;
        margin-bottom: 20px;
    }

    &__form {
        display: flex;
        gap: 8px;

        input {
            flex: 1;
            padding: 12px 16px;
            border-radius: 6px;
            border: none;
            font-size: 14px;
            font-family: $font-body;
            outline: none;
        }

        button {
            padding: 12px 24px;
            background: $color-primary;
            color: $color-white;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            font-family: $font-body;
            cursor: pointer;
            transition: background 0.2s;
            white-space: nowrap;

            &:hover {
                background: color.adjust($color-primary, $lightness: -8%);
            }
        }
    }
}

.about-perks {
    padding: 40px 0;
    background: $color-lightest;

    &__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }

    &__item {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    &__icon {
        width: 40px;
        height: 40px;
        flex-shrink: 0;

        path {
            fill: none;
            stroke: $color-primary;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
    }

    &__title {
        font-size: 15px;
        font-weight: 700;
        color: $color-dark;
        margin-bottom: 2px;
    }

    &__desc {
        font-size: 13px;
        color: $color-gray;
    }
}
</style>
