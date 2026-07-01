<template>
    <div class="base-select">
        <label v-if="label" class="base-select__label">{{ label }}</label>
        <div class="base-select__wrap">
            <select
                :value="modelValue"
                :disabled="disabled"
                class="base-select__field"
                :class="{ 'base-select__field--error': error }"
                @change="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
            >
                <slot />
            </select>
            <svg class="base-select__arrow" viewBox="0 0 10 6" aria-hidden="true">
                <path d="M1 1l4 4 4-4"/>
            </svg>
        </div>
        <span v-if="error" class="base-select__error">{{ error }}</span>
    </div>
</template>

<script setup lang="ts">
interface Props {
    modelValue: string
    label?: string
    disabled?: boolean
    error?: string
}

withDefaults(defineProps<Props>(), {
    disabled: false,
})

defineEmits<{ 'update:modelValue': [value: string] }>()
</script>

<style lang="scss" scoped>
.base-select {
    display: flex;
    flex-direction: column;
    gap: 6px;

    &__label {
        @include form-field-label;
    }

    &__wrap {
        position: relative;
    }

    &__field {
        @include form-field-base;
        width: 100%;
        padding-right: 36px;
        background: $color-white;
        appearance: none;
        cursor: pointer;

        &:disabled {
            opacity: 0.5;
            cursor: default;
        }
    }

    &__arrow {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 10px;
        height: 6px;
        pointer-events: none;

        path {
            fill: none;
            stroke: $color-gray;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
    }

    &__error {
        @include form-field-error-text;
    }
}
</style>
