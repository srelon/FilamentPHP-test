import { reactive, computed, watch } from 'vue'

interface WizardStepEmits<T> {
    (event: 'change', data: T): void
    (event: 'complete', data: T): void
}

export function useWizardStep<T extends object>(
    initial_data: T,
    emit: WizardStepEmits<T>,
    validator?: (data: T) => boolean
) {
    const local = reactive({ ...initial_data }) as T

    watch(local, (vals) => emit('change', { ...vals } as T), { deep: true })

    const is_valid = computed(() => (validator ? validator(local) : true))

    function on_continue() {
        if (is_valid.value) emit('complete', { ...local } as T)
    }

    return { local, is_valid, on_continue }
}
