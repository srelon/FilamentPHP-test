<template>
    <section class="cats">
        <div class="container">
            <div class="cats__wrapper">
                <div
                    class="cats__track"
                    :style="{
                        transform: `translateX(${offset}%)`,
                        transition: animated ? 'transform 0.6s ease' : 'none',
                    }"
                >
                    <a
                        v-for="cat in categories"
                        :key="cat.name"
                        href="#"
                        class="cats__item"
                    >
                        <div class="cats__icon">
                            <img :src="cat.icon" :alt="cat.name" width="40" height="40">
                        </div>
                        <h5 class="cats__name">{{ cat.name }}</h5>
                        <span class="cats__count">{{ cat.count }} books</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'

const VISIBLE = 8

const categories = [
    {
        name: 'Art & Design',
        icon: '/images/category-icon-1.svg',
        count: 5,
    },
    {
        name: 'Self-help',
        icon: '/images/category-icon-6.svg',
        count: 4,
    },
    {
        name: 'Science',
        icon: '/images/category-icon-3.svg',
        count: 4,
    },
    {
        name: 'Romance',
        icon: '/images/category-icon-9.svg',
        count: 4,
    },
    {
        name: 'Novels',
        icon: '/images/category-icon-4.svg',
        count: 3,
    },
    {
        name: 'History',
        icon: '/images/category-icon-2.svg',
        count: 5,
    },
    {
        name: 'Fantasy',
        icon: '/images/category-icon-8.svg',
        count: 5,
    },
    {
        name: 'Cooking',
        icon: '/images/category-icon-5.svg',
        count: 5,
    },
    {
        name: 'Business',
        icon: '/images/category-icon-10.svg',
        count: 3,
    },
    {
        name: 'Adventure',
        icon: '/images/category-icon-7.svg',
        count: 3,
    },
]

const total = categories.length
const max_index = total - VISIBLE

const current = ref(0)
const animated = ref(true)

const offset = computed(() => -(current.value * 100) / total)

const track_width = `${(total / VISIBLE) * 100}%`
const item_width = `${100 / total}%`

let timer: ReturnType<typeof setInterval>

function advance() {
    if (current.value >= max_index) {
        animated.value = false
        current.value = 0
        nextTick(() => {
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    animated.value = true
                })
            })
        })
    } else {
        animated.value = true
        current.value++
    }
}

onMounted(() => {
    timer = setInterval(advance, 10000)
})

onUnmounted(() => {
    clearInterval(timer)
})
</script>

<style lang="scss" scoped>
.cats {
    padding: 40px 0;
    border-bottom: 1px solid $color-light;

    &__wrapper {
        overflow: hidden;
        width: 100%;
    }

    &__track {
        display: flex;
        width: v-bind(track_width);
    }

    &__item {
        width: v-bind(item_width);
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 16px 20px;
        border-right: 1px solid $color-light;
        transition: color 0.2s;
        color: $color-dark;

        &:last-child {
            border-right: none;
        }

        &:hover {
            color: $color-primary;

            .cats__icon {
                background: rgba($color-primary, 0.08);
            }
        }
    }

    &__icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: $color-lightest;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        transition: background 0.2s;

        img {
            width: 28px;
            height: 28px;
            object-fit: contain;
        }
    }

    &__name {
        font-size: 13px;
        font-weight: 600;
        color: inherit;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    &__count {
        font-size: 12px;
        color: $color-gray;
    }
}
</style>
