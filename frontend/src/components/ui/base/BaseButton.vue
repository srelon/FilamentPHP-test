<template>
    <button class="base-btn" :class="`base-btn--${variant}`" :type="type" :disabled="disabled">
        <slot />
    </button>
</template>

<script setup lang="ts">
interface Props {
    variant?: 'primary' | 'outline' | 'text'
    type?: 'button' | 'submit' | 'reset'
    disabled?: boolean
}

withDefaults(defineProps<Props>(), {
    variant: 'primary',
    type: 'button',
    disabled: false,
})
</script>

<style lang="scss" scoped>
@use "sass:color";

.base-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 13px 24px;
    border-radius: 6px;
    font-size: 15px;
    font-weight: 600;
    font-family: $font-body;
    cursor: pointer;
    transition: background 0.2s, color 0.2s, border-color 0.2s;

    &--primary {
        background: $color-primary;
        color: $color-white;
        border: none;

        &:hover {
            background: color.adjust($color-primary, $lightness: -8%);
        }
    }

    &--outline {
        background: transparent;
        color: $color-dark;
        border: 1.5px solid $color-light;

        &:hover {
            background: $color-primary;
            color: $color-white;
            border-color: $color-primary;
        }
    }

    &--text {
        background: none;
        border: none;
        padding: 0;
        color: $color-primary;
        font-size: 13px;

        &:hover {
            color: color.adjust($color-primary, $lightness: -10%);
        }
    }

    &:disabled {
        opacity: 0.5;
        cursor: default;
        pointer-events: none;
    }
}
</style>
