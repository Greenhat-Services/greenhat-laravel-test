<script setup lang="ts">
import type { FormSubmitEvent } from '#ui/types'
import { z } from 'zod'

definePageMeta({
  layout: false, // No layout for login page
  middleware: [] // No auth middleware on login page
})

const router = useRouter()
const { login, loading, error } = useAuth()

// Define validation schema
const schema = z.object({
  email: z.string().email('Invalid email address'),
  password: z.string().min(6, 'Password must be at least 6 characters')
})

type Schema = z.output<typeof schema>

// Form state
const state = reactive({
  email: '',
  password: ''
})

// Handle form submission
async function onSubmit(event: FormSubmitEvent<Schema>) {
  try {
    await login(event.data)

    // Navigate to courses page on successful login
    router.push('/courses')
  } catch (err) {
    // Error is handled by the composable
    console.error('Login error:', err)
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
          Employee Portal
        </h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
          Sign in to access your courses
        </p>
      </div>

      <!-- Login Form Card -->
      <UCard>
        <UForm
          :schema="schema"
          :state="state"
          class="space-y-6"
          @submit="onSubmit"
        >
          <!-- Email -->
          <UFormGroup
            label="Email"
            name="email"
            required
          >
            <UInput
              v-model="state.email"
              type="email"
              placeholder="Enter your email"
              icon="i-heroicons-envelope"
              size="lg"
            />
          </UFormGroup>

          <!-- Password -->
          <UFormGroup
            label="Password"
            name="password"
            required
          >
            <UInput
              v-model="state.password"
              type="password"
              placeholder="Enter your password"
              icon="i-heroicons-lock-closed"
              size="lg"
            />
          </UFormGroup>

          <!-- Error Display -->
          <UAlert
            v-if="error"
            color="red"
            variant="subtle"
            :title="error.message"
            :description="error.errors ? Object.values(error.errors).flat().join(', ') : undefined"
          />

          <!-- Submit Button -->
          <UButton
            type="submit"
            color="primary"
            size="lg"
            block
            :loading="loading"
          >
            Sign In
          </UButton>
        </UForm>

        <!-- Registration Link -->
        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Don't have an account?
            <UButton
              to="/"
              variant="link"
              :padded="false"
            >
              Register here
            </UButton>
          </p>
        </div>
      </UCard>
    </div>
  </div>
</template>
