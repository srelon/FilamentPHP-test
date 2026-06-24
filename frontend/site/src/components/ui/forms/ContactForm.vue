<template>
    <Form :validation-schema="schema" @submit="on_submit" class="contact__form">
        <div class="contact__row">
            <BaseInput name="first_name" label="First Name" placeholder="John" />
            <BaseInput name="last_name" label="Last Name" placeholder="Doe" />
        </div>

        <BaseInput name="email" label="Email Address" type="email" placeholder="john@example.com" />

        <Field name="subject" v-slot="{ field, errorMessage }">
            <div class="contact__field">
                <label>Subject</label>
                <div class="contact__select-wrap">
                    <select v-bind="field" :class="{ 'contact__select--error': errorMessage }">
                        <option value="" disabled>Select a subject</option>
                        <option value="order">Order & Shipping</option>
                        <option value="return">Returns & Refunds</option>
                        <option value="product">Product Question</option>
                        <option value="account">Account & Billing</option>
                        <option value="other">Other</option>
                    </select>
                    <svg class="contact__select-arrow" viewBox="0 0 10 6" aria-hidden="true">
                        <path d="M1 1l4 4 4-4"/>
                    </svg>
                </div>
                <span v-if="errorMessage" class="contact__error">{{ errorMessage }}</span>
            </div>
        </Field>

        <BaseInput name="message" label="Message" type="textarea" placeholder="Write your message here..." />

        <BaseButton type="submit" :disabled="is_loading" class="contact__submit">
            {{ is_loading ? 'Sending...' : 'Send Message' }}
        </BaseButton>
    </Form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Form, Field } from 'vee-validate'
import { object, string } from 'yup'
import BaseButton from '@/components/ui/base/BaseButton.vue'
import BaseInput from '@/components/ui/base/BaseInput.vue'

const is_loading = ref(false)

const schema = object({
    first_name: string().required('First name is required'),
    last_name: string().required('Last name is required'),
    email: string().required('Email is required').email('Enter a valid email'),
    subject: string().required('Please select a subject'),
    message: string().required('Message is required').min(10, 'Message must be at least 10 characters'),
})

async function on_submit(values: Record<string, string>) {
    is_loading.value = true
    try {
        await new Promise((resolve) => setTimeout(resolve, 1000))
        console.log('Form submitted:', values)
    } finally {
        is_loading.value = false
    }
}
</script>

<style lang="scss" scoped>
.contact {
    &__form {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    &__row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    &__field {
        display: flex;
        flex-direction: column;
        gap: 6px;

        label {
            font-size: 14px;
            font-weight: 600;
            color: $color-dark;
        }
    }

    &__select-wrap {
        position: relative;

        select {
            width: 100%;
            padding: 11px 36px 11px 14px;
            border: 1.5px solid $color-light;
            border-radius: 6px;
            font-size: 14px;
            font-family: $font-body;
            color: $color-dark;
            background: $color-white;
            outline: none;
            appearance: none;
            cursor: pointer;
            transition: border-color 0.2s;

            &:focus {
                border-color: $color-primary;
            }
        }
    }

    &__select-arrow {
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

    &__select--error {
        border-color: #e53e3e !important;

        &:focus {
            border-color: #e53e3e !important;
        }
    }

    &__error {
        font-size: 12px;
        color: #e53e3e;
    }

    &__submit {
        align-self: flex-start;
    }
}
</style>
