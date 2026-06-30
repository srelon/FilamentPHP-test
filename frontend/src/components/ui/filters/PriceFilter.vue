<template>
    <div class="price-filter">
        <div class="price-filter__range">
            <div class="price-filter__track">
                <div class="price-filter__fill" :style="fill_style"></div>
                <input
                    type="range"
                    :value="price_min"
                    :min="min"
                    :max="max"
                    class="price-filter__input"
                    @input="on_slider_min"
                >
                <input
                    type="range"
                    :value="price_max"
                    :min="min"
                    :max="max"
                    class="price-filter__input"
                    @input="on_slider_max"
                >
            </div>
        </div>

        <div class="price-filter__inputs">
            <div class="price-filter__field">
                <span class="price-filter__sign">$</span>
                <input
                    type="number"
                    :value="price_min"
                    :min="min"
                    :max="price_max"
                    class="price-filter__num"
                    @change="on_min_change"
                >
            </div>
            <span class="price-filter__sep">—</span>
            <div class="price-filter__field">
                <span class="price-filter__sign">$</span>
                <input
                    type="number"
                    :value="price_max"
                    :min="price_min"
                    :max="max"
                    class="price-filter__num"
                    @change="on_max_change"
                >
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'

interface Props {
    min?: number
    max?: number
    model_min?: number
    model_max?: number
}

const props = withDefaults(defineProps<Props>(), {
    min: 0,
    max: 100,
})


const price_min = ref(props.model_min ?? props.min)
const price_max = ref(props.model_max ?? props.max)

watch(() => props.model_min, (v) => { if (v !== undefined && v !== price_min.value) price_min.value = v })
watch(() => props.model_max, (v) => { if (v !== undefined && v !== price_max.value) price_max.value = v })

const fill_style = computed(() => {
    const range = props.max - props.min
    const left = ((price_min.value - props.min) / range) * 100
    const right = ((props.max - price_max.value) / range) * 100
    return {
        left: `${left}%`,
        right: `${right}%`,
    }
})

function clamp(val: number, lo: number, hi: number) {
    return Math.min(Math.max(val, lo), hi)
}

function on_slider_min(e: Event) {
    price_min.value = clamp(Number((e.target as HTMLInputElement).value), props.min, price_max.value)
}

function on_slider_max(e: Event) {
    price_max.value = clamp(Number((e.target as HTMLInputElement).value), price_min.value, props.max)
}

function on_min_change(e: Event) {
    price_min.value = clamp(Number((e.target as HTMLInputElement).value), props.min, price_max.value)
    ;(e.target as HTMLInputElement).value = String(price_min.value)
}

function on_max_change(e: Event) {
    price_max.value = clamp(Number((e.target as HTMLInputElement).value), price_min.value, props.max)
    ;(e.target as HTMLInputElement).value = String(price_max.value)
}
</script>

<style lang="scss" scoped>
.price-filter {
    display: flex;
    flex-direction: column;
    gap: 16px;

    &__range {
        padding: 8px 0;
    }

    &__track {
        position: relative;
        height: 4px;
        background: $color-light;
        border-radius: 2px;
        margin: 8px 0;
    }

    &__fill {
        position: absolute;
        top: 0;
        bottom: 0;
        background: $color-primary;
        border-radius: 2px;
    }

    &__input {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 100%;
        height: 4px;
        margin: 0;
        background: transparent;
        appearance: none;
        pointer-events: none;

        &::-webkit-slider-thumb {
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: $color-white;
            border: 2px solid $color-primary;
            cursor: pointer;
            pointer-events: all;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
            transition: box-shadow 0.15s;

            &:hover {
                box-shadow: 0 0 0 4px rgba($color-primary, 0.15);
            }
        }

        &::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: $color-white;
            border: 2px solid $color-primary;
            cursor: pointer;
            pointer-events: all;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
        }
    }

    &__inputs {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    &__field {
        display: flex;
        align-items: center;
        gap: 4px;
        flex: 1;
        border: 1.5px solid $color-light;
        border-radius: 6px;
        padding: 6px 8px;
        transition: border-color 0.15s;

        &:focus-within {
            border-color: $color-primary;
        }
    }

    &__sign {
        font-size: 13px;
        color: $color-gray;
        flex-shrink: 0;
    }

    &__num {
        width: 100%;
        font-size: 13px;
        font-weight: 600;
        font-family: $font-body;
        color: $color-dark;
        outline: none;
        border: none;

        &::-webkit-inner-spin-button,
        &::-webkit-outer-spin-button {
            appearance: none;
        }
    }

    &__sep {
        font-size: 13px;
        color: $color-gray;
        flex-shrink: 0;
    }


}
</style>
