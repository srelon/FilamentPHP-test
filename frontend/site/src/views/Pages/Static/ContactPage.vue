<template>
    <PageBanner title="Contact Us" />

    <section class="contact section">
        <div class="container">
            <div class="contact__grid">
                <div class="contact__form-wrap">
                    <h2 class="contact__heading">Get In Touch With Us</h2>
                    <p class="contact__sub">Have a question or need help finding the perfect book? Our team is here for you.</p>
                    <ContactForm />
                </div>

                <div class="contact__info">
                    <h2 class="contact__heading">Our Store Location</h2>
                    <ul class="contact__details">
                        <li v-for="item in contact_info" :key="item.label">
                            <svg viewBox="0 0 15 15" aria-hidden="true">
                                <path :d="item.icon" />
                            </svg>
                            <div>
                                <strong>{{ item.label }}</strong>
                                <a v-if="item.href" :href="item.href">{{ item.value }}</a>
                                <span v-else>{{ item.value }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="faq section">
        <div class="container">
            <h2 class="section__title">Helpful Answers To Your Questions</h2>
            <div class="faq__list">
                <div
                    v-for="(item, index) in faq"
                    :key="index"
                    class="faq__item"
                    :class="{ 'faq__item--open': open_index === index }"
                >
                    <button class="faq__question" @click="toggle(index)">
                        {{ item.question }}
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="faq__icon">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>
                    <div class="faq__answer">
                        <p>{{ item.answer }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import PageBanner from '@/components/ui/base/PageBanner.vue'
import ContactForm from '@/components/ui/forms/ContactForm.vue'

const open_index = ref<number | null>(0)

const contact_info = [
    {
        label: 'Address',
        value: '27 Division St, New York, NY 10002',
        href: null,
        icon: 'M7.5 0A5.5 5.5 0 0 0 2 5.5C2 9.358 7.5 15 7.5 15S13 9.358 13 5.5A5.5 5.5 0 0 0 7.5 0m0 7.5a2 2 0 1 1 2-2 2 2 0 0 1-2 2',
    },
    {
        label: 'Working Hours',
        value: 'Mon–Sat: 9:00AM – 6:00PM',
        href: null,
        icon: 'M7.5.5A6.5 6.5 0 1 0 14 7 6.508 6.508 0 0 0 7.5.5m0 11.75A5.25 5.25 0 1 1 12.75 7 5.256 5.256 0 0 1 7.5 12.25M8.125 7V4.25a.625.625 0 0 0-1.25 0V7.5a.625.625 0 0 0 .625.625H10a.625.625 0 0 0 0-1.25z',
    },
    {
        label: 'Email',
        value: 'hello@example.com',
        href: 'mailto:hello@example.com',
        icon: 'M13.5 2h-12A1.5 1.5 0 0 0 0 3.5v8A1.5 1.5 0 0 0 1.5 13h12a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 13.5 2m0 1 .09.007L7.5 7.572 1.41 3.007A.5.5 0 0 1 1.5 3zM1 11.5v-7.8l6.22 4.573a.5.5 0 0 0 .56 0L14 3.7v7.8a.5.5 0 0 1-.5.5h-12a.5.5 0 0 1-.5-.5',
    },
    {
        label: 'Phone',
        value: '578-393-4937',
        href: 'tel:5783934937',
        icon: 'M10.628 10.628a5 5 0 1 1-6.257-6.257 5 5 0 0 1 6.257 6.257m.707.707A6 6 0 0 1 4.92 4.92a6 6 0 0 1 6.415 6.415M13.5 14.207l-2.854-2.854.707-.707L14.207 13.5z',
    },
]

function toggle(index: number) {
    open_index.value = open_index.value === index ? null : index
}

const faq = [
    {
        question: 'How long does shipping take?',
        answer: 'Standard shipping takes 3–5 business days within the US. Expedited options are available at checkout for 1–2 day delivery.',
    },
    {
        question: 'Do you ship internationally?',
        answer: 'Yes, we ship to over 50 countries. International shipping typically takes 7–14 business days depending on your location.',
    },
    {
        question: 'What is your return policy?',
        answer: 'We accept returns within 30 days of purchase. Items must be in their original condition. Simply contact our support team to initiate a return.',
    },
    {
        question: 'What payment methods do you accept?',
        answer: 'We accept Visa, Mastercard, PayPal, and Apple Pay. All transactions are secured with SSL encryption.',
    },
    {
        question: 'How can I track my order?',
        answer: 'Once your order ships, you will receive a confirmation email with a tracking number. You can use it on our website or the carrier\'s site.',
    },
    {
        question: 'How do I reach customer support?',
        answer: 'You can reach us via email at hello@example.com or call 578-393-4937 during business hours (Mon–Sat 9AM–6PM).',
    },
]
</script>

<style lang="scss" scoped>
.contact {
    &__grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 60px;
        align-items: start;
    }

    &__heading {
        font-size: clamp(18px, 1.8vw, 26px);
        font-weight: 700;
        color: $color-dark;
        margin-bottom: 10px;
    }

    &__sub {
        font-size: 14px;
        color: $color-gray;
        line-height: 1.65;
        margin-bottom: 28px;
    }

    &__details {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-top: 24px;

        li {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            margin-top: 2px;

            path {
                fill: $color-primary;
            }
        }

        div {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        strong {
            font-size: 13px;
            font-weight: 700;
            color: $color-dark;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        span,
        a {
            font-size: 14px;
            color: $color-gray;
        }

        a:hover {
            color: $color-primary;
        }
    }
}

.faq {
    background: $color-lightest;

    &__list {
        max-width: 800px;
        margin: 40px auto 0;
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    &__item {
        border-bottom: 1px solid $color-light;

        &:first-child {
            border-top: 1px solid $color-light;
        }

        &--open {
            .faq__answer {
                max-height: 300px;
                opacity: 1;
                padding: 0 20px 20px;
            }

            .faq__icon {
                transform: rotate(180deg);
            }
        }
    }

    &__question {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px;
        text-align: left;
        font-size: 15px;
        font-weight: 600;
        color: $color-dark;
        font-family: $font-body;
        cursor: pointer;
        gap: 16px;
        transition: color 0.2s;

        &:hover {
            color: $color-primary;
        }
    }

    &__icon {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        transition: transform 0.25s;

        path {
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
    }

    &__answer {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, opacity 0.3s ease, padding 0.3s ease;
        padding: 0 20px;

        p {
            font-size: 14px;
            color: $color-gray;
            line-height: 1.7;
        }
    }
}
</style>
