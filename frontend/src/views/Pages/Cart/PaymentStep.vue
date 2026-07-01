<template>
    <div class="payment-step">
        <BaseRadioGroup
            v-model="local.method"
            name="payment_method"
            :options="payment_methods"
        />

        <BaseButton type="button" @click="on_continue">Continue</BaseButton>
    </div>
</template>

<script setup lang="ts">
import BaseButton from '@/components/ui/base/BaseButton.vue'
import BaseRadioGroup from '@/components/ui/base/BaseRadioGroup.vue'
import { useWizardStep } from '@/composables/useWizardStep'

interface PaymentData {
    method: 'card' | 'cash'
}

const props = defineProps<{ initial_data: PaymentData }>()

const emit = defineEmits<{
    change: [data: PaymentData]
    complete: [data: PaymentData]
}>()

const { local, on_continue } = useWizardStep<PaymentData>(props.initial_data, emit)

const payment_methods: { value: PaymentData['method']; label: string }[] = [
    {
        value: 'card',
        label: 'Pay by Card Online',
    },
    {
        value: 'cash',
        label: 'Cash on Delivery',
    },
]
</script>

<style lang="scss" scoped>
.payment-step {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
</style>
