<template>
    <div class="base-input">
        <label v-if="label" class="base-input__label">{{ label }}</label>
        <textarea
            v-if="type === 'textarea'"
            v-model="value"
            :placeholder="placeholder"
            :rows="rows"
            class="base-input__field"
            :class="{ 'base-input__field--error': errorMessage }"
        ></textarea>
        <input
            v-else
            v-model="value"
            :type="type"
            :placeholder="placeholder"
            class="base-input__field"
            :class="{ 'base-input__field--error': errorMessage }"
        >
        <span v-if="errorMessage" class="base-input__error">{{ errorMessage }}</span>
    </div>
</template>

<script setup lang="ts">
import { useField } from 'vee-validate'

interface Props {
    name: string
    label?: string
    type?: string
    placeholder?: string
    rows?: number
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    rows: 5,
})

const { value, errorMessage } = useField<string>(() => props.name)
</script>

<style lang="scss" scoped>
.base-input {
    display: flex;
    flex-direction: column;
    gap: 6px;

    &__label {
        font-size: 14px;
        font-weight: 600;
        color: $color-dark;
    }

    &__field {
        padding: 11px 14px;
        border: 1.5px solid $color-light;
        border-radius: 6px;
        font-size: 14px;
        font-family: $font-body;
        color: $color-dark;
        outline: none;
        transition: border-color 0.2s;
        width: 100%;

        &:focus {
            border-color: $color-primary;
        }

        &::placeholder {
            color: $color-gray;
        }

        &--error {
            border-color: #e53e3e;

            &:focus {
                border-color: #e53e3e;
            }
        }
    }

    textarea {
        resize: vertical;
    }

    &__error {
        font-size: 12px;
        color: #e53e3e;
    }
}
</style>
