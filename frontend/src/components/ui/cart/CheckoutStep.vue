<template>
    <div class="checkout-step" :class="{ 'checkout-step--done': done && !active, 'checkout-step--disabled': !active && !done }">
        <div class="checkout-step__head">
            <div class="checkout-step__head-left">
                <span class="checkout-step__num" :class="{ 'checkout-step__num--done': done && !active }">
                    <svg v-if="done && !active" viewBox="0 0 12 10" aria-hidden="true">
                        <path d="M1 5l3.5 3.5L11 1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <template v-else>{{ step_number }}</template>
                </span>
                <h3 class="checkout-step__title">{{ title }}</h3>
            </div>
            <BaseButton v-if="done && !active" type="button" variant="text" @click="$emit('edit')">
                Edit
            </BaseButton>
        </div>

        <div v-if="active" class="checkout-step__body">
            <slot />
        </div>
        <div v-else-if="done" class="checkout-step__summary">
            <slot name="summary" />
        </div>
    </div>
</template>

<script setup lang="ts">
import BaseButton from '@/components/ui/base/BaseButton.vue'

interface Props {
    step_number: number
    title: string
    active: boolean
    done: boolean
}

defineProps<Props>()
defineEmits<{ edit: [] }>()
</script>

<style lang="scss" scoped>
.checkout-step {
    border: 1.5px solid $color-light;
    border-radius: 10px;
    padding: 20px 24px;
    transition: border-color 0.2s, opacity 0.2s;

    &--disabled {
        opacity: 0.5;
    }

    &__head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    &__head-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    &__num {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: $color-lighter;
        color: $color-dark;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        flex-shrink: 0;

        &--done {
            background: $color-primary;
            color: $color-white;
        }

        svg {
            width: 11px;
            height: 9px;
        }
    }

    &__title {
        font-size: 16px;
        font-weight: 700;
        color: $color-dark;
    }

    &__body {
        margin-top: 18px;
    }

    &__summary {
        margin-top: 14px;
        font-size: 14px;
        color: $color-gray;
        line-height: 1.6;
    }
}
</style>
