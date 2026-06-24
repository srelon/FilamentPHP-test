<template>
    <div class="thumb-slider" :class="{ 'thumb-slider--dark': dark }">
        <button
            v-if="needs_nav"
            class="thumb-slider__btn"
            :disabled="offset === 0"
            @click="prev"
            aria-label="Previous"
        >
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
        </button>

        <div class="thumb-slider__viewport" :style="viewport_style">
            <div class="thumb-slider__track" :style="track_style">
                <button
                    v-for="(img, i) in images"
                    :key="i"
                    class="thumb-slider__item"
                    :class="{ 'thumb-slider__item--active': active === img }"
                    @click="emit('select', img)"
                    @mouseenter="emit('hover', img)"
                >
                    <img :src="img" :alt="alt">
                </button>
            </div>
        </div>

        <button
            v-if="needs_nav"
            class="thumb-slider__btn"
            :disabled="offset >= max_offset"
            @click="next"
            aria-label="Next"
        >
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
        </button>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'

interface Props {
    images: string[]
    active: string
    alt?: string
    visible?: number
    item_size?: number
    gap?: number
    dark?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    alt: '',
    visible: 4,
    item_size: 72,
    gap: 10,
    dark: false,
})

const emit = defineEmits<{
    select: [img: string]
    hover: [img: string]
}>()

const offset = ref(0)

const step = computed(() => props.item_size + props.gap)
const max_offset = computed(() => Math.max(0, props.images.length - props.visible))
const needs_nav = computed(() => props.images.length > props.visible)

const viewport_style = computed(() => ({
    width: `${props.visible * props.item_size + (props.visible - 1) * props.gap}px`,
}))

const track_style = computed(() => ({
    transform: `translateX(-${offset.value * step.value}px)`,
    gap: `${props.gap}px`,
}))

function prev() {
    offset.value = Math.max(0, offset.value - 1)
}

function next() {
    offset.value = Math.min(max_offset.value, offset.value + 1)
}

watch(() => props.active, () => {
    const i = props.images.indexOf(props.active)
    if (i < offset.value) offset.value = i
    if (i >= offset.value + props.visible) offset.value = i - props.visible + 1
})
</script>

<style lang="scss" scoped>
.thumb-slider {
    display: flex;
    align-items: center;
    gap: 8px;

    &__viewport {
        overflow: hidden;
        flex-shrink: 0;
    }

    &__track {
        display: flex;
        transition: transform 0.3s ease;
    }

    &__item {
        width: v-bind('`${props.item_size}px`');
        height: v-bind('`${props.item_size}px`');
        flex-shrink: 0;
        border-radius: 6px;
        overflow: hidden;
        border: 2px solid $color-light;
        cursor: pointer;
        padding: 0;
        transition: border-color 0.2s;

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        &--active,
        &:hover {
            border-color: $color-primary;
        }
    }

    &__btn {
        width: 28px;
        align-self: stretch;
        flex-shrink: 0;
        border-radius: 6px;
        background: $color-lightest;
        border: 1.5px solid $color-light;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;

        svg {
            width: 16px;
            height: 16px;

            path {
                fill: none;
                stroke: $color-dark;
                stroke-width: 2;
                stroke-linecap: round;
                stroke-linejoin: round;
            }
        }

        &:not(:disabled):hover {
            background: $color-primary;
            border-color: $color-primary;

            svg path {
                stroke: $color-white;
            }
        }

        &:disabled {
            opacity: 0.3;
            cursor: default;
        }
    }

    &--dark {
        .thumb-slider__item {
            border-color: rgba(255, 255, 255, 0.25);

            &--active,
            &:hover {
                border-color: $color-white;
            }
        }

        .thumb-slider__btn {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);

            svg path {
                stroke: $color-white;
            }

            &:not(:disabled):hover {
                background: rgba(255, 255, 255, 0.3);
                border-color: rgba(255, 255, 255, 0.4);
            }
        }
    }
}
</style>
