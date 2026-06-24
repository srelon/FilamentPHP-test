<template>
    <Teleport to="body">
        <div v-if="open" class="lightbox">
            <button class="lightbox__half lightbox__half--prev" @click="prev" aria-label="Previous">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <button class="lightbox__half lightbox__half--next" @click="next" aria-label="Next">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
            </button>

            <button class="lightbox__close" @click="emit('update:open', false)" aria-label="Close">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>

            <Transition name="lightbox-fade" mode="out-in">
                <img :key="modelValue" :src="modelValue" :alt="alt" class="lightbox__img">
            </Transition>

            <div class="lightbox__thumbs" @click.stop>
                <ThumbSlider
                    :images="images"
                    :active="modelValue"
                    :alt="alt"
                    :visible="8"
                    :item_size="64"
                    :dark="true"
                    @select="emit('update:modelValue', $event)"
                />
            </div>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { watch, onMounted, onUnmounted } from 'vue'
import ThumbSlider from '@/components/ui/product/ThumbSlider.vue'

interface Props {
    images: string[]
    modelValue: string
    open: boolean
    alt?: string
}

const props = withDefaults(defineProps<Props>(), {
    alt: '',
})

const emit = defineEmits<{
    'update:modelValue': [img: string]
    'update:open': [val: boolean]
}>()

function current_index() {
    return props.images.indexOf(props.modelValue)
}

function prev() {
    const i = current_index()
    emit('update:modelValue', props.images[i > 0 ? i - 1 : props.images.length - 1])
}

function next() {
    const i = current_index()
    emit('update:modelValue', props.images[i < props.images.length - 1 ? i + 1 : 0])
}

function on_keydown(e: KeyboardEvent) {
    if (!props.open) return
    if (e.key === 'Escape') emit('update:open', false)
    if (e.key === 'ArrowLeft') prev()
    if (e.key === 'ArrowRight') next()
}

watch(() => props.open, (val) => {
    document.body.style.overflow = val ? 'hidden' : ''
})

onMounted(() => window.addEventListener('keydown', on_keydown))
onUnmounted(() => window.removeEventListener('keydown', on_keydown))
</script>

<style lang="scss" scoped>
.lightbox {
    position: fixed;
    inset: 0;
    z-index: 1000;
    background: rgba(0, 0, 0, 0.85);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
    padding: 60px 40px 40px;

    &__half {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 50%;
        z-index: 1;
        cursor: pointer;
        display: flex;
        align-items: center;

        svg {
            width: 48px;
            height: 48px;
            opacity: 0;
            transition: opacity 0.2s;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.8));

            path {
                fill: none;
                stroke: $color-white;
                stroke-width: 1.5;
                stroke-linecap: round;
                stroke-linejoin: round;
            }
        }

        &:hover svg {
            opacity: 1;
        }

        &--prev {
            left: 0;
            justify-content: flex-start;
            padding-left: 24px;
        }

        &--next {
            right: 0;
            justify-content: flex-end;
            padding-right: 24px;
        }
    }

    &__close {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 3;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;

        svg {
            width: 20px;
            height: 20px;

            path {
                fill: none;
                stroke: $color-white;
                stroke-width: 2;
                stroke-linecap: round;
                stroke-linejoin: round;
            }
        }

        &:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    }

    &__img {
        position: relative;
        z-index: 2;
        pointer-events: none;
        max-width: 100%;
        max-height: calc(100vh - 180px);
        object-fit: contain;
        border-radius: 8px;
        display: block;
        user-select: none;
    }

    &__thumbs {
        position: relative;
        z-index: 3;
    }
}

.lightbox-fade {
    &-enter-active,
    &-leave-active {
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    &-enter-from {
        opacity: 0;
        transform: scale(0.97);
    }

    &-leave-to {
        opacity: 0;
        transform: scale(1.03);
    }
}
</style>
