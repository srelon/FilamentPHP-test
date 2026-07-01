<template>
    <div class="base-radio-group">
        <label
            v-for="option in options"
            :key="option.value"
            class="base-radio-group__option"
            :class="{ 'base-radio-group__option--active': modelValue === option.value }"
        >
            <input
                type="radio"
                :name="name"
                :value="option.value"
                :checked="modelValue === option.value"
                @change="$emit('update:modelValue', option.value)"
            >
            {{ option.label }}
        </label>
    </div>
</template>

<script setup lang="ts" generic="T extends string">
interface Option<T> {
    value: T
    label: string
}

defineProps<{
    modelValue: T
    options: Option<T>[]
    name: string
}>()

defineEmits<{ 'update:modelValue': [value: T] }>()
</script>

<style lang="scss" scoped>
.base-radio-group {
    display: flex;
    gap: 12px;

    &__option {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 16px;
        border: 1.5px solid $color-light;
        border-radius: 6px;
        font-size: 14px;
        color: $color-dark;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;

        input {
            accent-color: $color-primary;
        }

        &--active {
            border-color: $color-primary;
            background: rgba($color-primary, 0.04);
        }
    }
}
</style>
