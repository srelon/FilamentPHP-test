<template>
    <div class="filter-group">
        <ul class="filter-group__list">
            <li v-for="item in items" :key="item.name" class="filter-group__item">
                <label class="filter-group__label">
                    <input
                        v-if="type === 'radio'"
                        type="radio"
                        :name="title"
                        :value="item.name"
                        v-model="selected"
                    >
                    <input
                        v-else
                        type="checkbox"
                        :value="item.name"
                        v-model="selected"
                    >
                    <span class="filter-group__check"></span>
                    <slot name="label" :item="item">{{ item.name }}</slot>
                </label>
                <span v-if="item.count !== undefined" class="filter-group__count">{{ item.count }}</span>
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

interface Item {
    name: string
    count?: number
}

interface Props {
    title: string
    items: Item[]
    type?: 'checkbox' | 'radio'
    modelValue?: string[] | string | null
}

const props = withDefaults(defineProps<Props>(), {
    type: 'checkbox',
    modelValue: () => [],
})

const emit = defineEmits<{
    'update:modelValue': [val: string[] | string | null]
}>()

const selected = ref<string[] | string | null>(
    props.type === 'radio' ? (props.modelValue as string ?? null) : (props.modelValue as string[] ?? [])
)

watch(selected, (val) => emit('update:modelValue', val))
watch(() => props.modelValue, (val) => { selected.value = val ?? (props.type === 'radio' ? null : []) })
</script>

<style lang="scss" scoped>
.filter-group {
    &__list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    &__item {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    &__label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-size: 14px;
        color: $color-dark;

        input {
            display: none;
        }

        input:checked + .filter-group__check {
            background: $color-primary;
            border-color: $color-primary;

            &::after {
                opacity: 1;
            }
        }
    }

    &__check {
        width: 16px;
        height: 16px;
        border: 1.5px solid $color-light;
        border-radius: 3px;
        flex-shrink: 0;
        position: relative;
        transition: background 0.15s, border-color 0.15s;

        &::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1px;
            width: 5px;
            height: 9px;
            border: 2px solid $color-white;
            border-left: none;
            border-top: none;
            transform: rotate(45deg);
            opacity: 0;
        }
    }

    &__count {
        font-size: 12px;
        color: $color-gray;
    }
}
</style>
