<template>
    <div class="delivery-step">
        <BaseRadioGroup
            v-model="local.method"
            name="delivery_method"
            :options="delivery_methods"
        />

        <div v-if="local.method === 'nova_poshta'" class="delivery-step__row">
            <BaseSelect v-model="local.city" label="City" @update:modelValue="local.warehouse = ''">
                <option value="" disabled>Select city</option>
                <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
            </BaseSelect>
            <BaseSelect v-model="local.warehouse" label="Branch" :disabled="!local.city">
                <option value="" disabled>Select branch</option>
                <option v-for="wh in warehouses" :key="wh" :value="wh">{{ wh }}</option>
            </BaseSelect>
        </div>

        <BaseButton type="button" :disabled="!is_valid" @click="on_continue">Continue</BaseButton>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import BaseButton from '@/components/ui/base/BaseButton.vue'
import BaseSelect from '@/components/ui/base/BaseSelect.vue'
import BaseRadioGroup from '@/components/ui/base/BaseRadioGroup.vue'
import { useWizardStep } from '@/composables/useWizardStep'

interface DeliveryData {
    method: 'pickup' | 'nova_poshta'
    city: string
    warehouse: string
}

const props = defineProps<{ initial_data: DeliveryData }>()

const emit = defineEmits<{
    change: [data: DeliveryData]
    complete: [data: DeliveryData]
}>()

const { local, is_valid, on_continue } = useWizardStep<DeliveryData>(
    props.initial_data,
    emit,
    (data) => data.method === 'pickup' || (data.city !== '' && data.warehouse !== '')
)

const delivery_methods: { value: DeliveryData['method']; label: string }[] = [
    {
        value: 'pickup',
        label: 'Pickup',
    },
    {
        value: 'nova_poshta',
        label: 'Nova Poshta',
    },
]

const cities = ['Kyiv', 'Lviv', 'Odesa', 'Kharkiv', 'Dnipro']

const warehouses_by_city: Record<string, string[]> = {
    'Kyiv': ['Branch #1, Khreshchatyk St, 22', 'Branch #5, Peremohy Ave, 10', 'Branch #12, Lisova St, 4'],
    'Lviv': ['Branch #2, Svobody Ave, 15', 'Branch #7, Horodotska St, 33'],
    'Odesa': ['Branch #3, Derybasivska St, 8', 'Branch #9, Shevchenko Ave, 21'],
    'Kharkiv': ['Branch #4, Svobody Sq, 5', 'Branch #11, Sumska St, 40'],
    'Dnipro': ['Branch #6, Yavornytskoho Ave, 18'],
}

const warehouses = computed(() => warehouses_by_city[local.city] ?? [])
</script>

<style lang="scss" scoped>
.delivery-step {
    display: flex;
    flex-direction: column;
    gap: 16px;

    &__row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
}
</style>
