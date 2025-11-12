<script setup lang="ts">
import type { FormSubmitEvent } from '#ui/types'
import { z } from 'zod'

const router = useRouter()
const { submitRegistration, loading, error } = useEmployeeRegistration()

// Define validation schema
const schema = z.object({
  registrationCode: z.string().refine(val => val === 'ACME', {
    message: 'Registration code must be ACME'
  }),
  firstName: z.string().min(2, 'First name must be at least 2 characters').max(255),
  lastName: z.string().min(2, 'Last name must be at least 2 characters').max(255),
  email: z.string().email('Invalid email address'),
  role: z.string().min(1, 'Role is required').max(255),
  highestQualification: z.string().min(1, 'Highest qualification is required').max(255),
  desiredSalary: z.number().positive('Salary must be positive').optional().nullable(),
  note: z.string().max(5000, 'Note must not exceed 5000 characters').optional().nullable()
})

type Schema = z.output<typeof schema>

// Form state
const state = reactive({
  registrationCode: '',
  firstName: '',
  lastName: '',
  email: '',
  role: '',
  highestQualification: '',
  desiredSalary: null as number | null,
  note: null as string | null
})

// Handle form submission
async function onSubmit(event: FormSubmitEvent<Schema>) {
  try {
    const response = await submitRegistration(event.data)
    console.log('response', response)

    // Navigate to success page with data
    router.push({
      path: '/success',
      query: {
        name: response.user.name,
        email: response.user.email,
        role: response.role
      }
    })
  } catch (err) {
    // Error is handled by the composable
    console.error('Submission error:', err)
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
          Employee Registration
        </h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
          Welcome to Greenhat! Please fill out the form below to register as a new employee.
        </p>
      </div>

      <!-- Registration Form Card -->
      <UCard>
        <UForm
          :schema="schema"
          :state="state"
          class="space-y-6"
          @submit="onSubmit"
        >
          <!-- Registration Code -->
          <UFormGroup
            label="Registration Code"
            name="registrationCode"
            required
            help="Enter the registration code provided to you (ACME)"
          >
            <UInput
              v-model="state.registrationCode"
              placeholder="ACME"
              :disabled="loading"
            />
          </UFormGroup>

          <!-- Name Fields Row -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <UFormGroup
              label="First Name"
              name="firstName"
              required
            >
              <UInput
                v-model="state.firstName"
                placeholder="John"
                :disabled="loading"
              />
            </UFormGroup>

            <UFormGroup
              label="Last Name"
              name="lastName"
              required
            >
              <UInput
                v-model="state.lastName"
                placeholder="Doe"
                :disabled="loading"
              />
            </UFormGroup>
          </div>

          <!-- Email -->
          <UFormGroup
            label="Email Address"
            name="email"
            required
            help="You will receive a confirmation email at this address"
          >
            <UInput
              v-model="state.email"
              type="email"
              placeholder="john.doe@example.com"
              :disabled="loading"
            />
          </UFormGroup>

          <!-- Role -->
          <UFormGroup
            label="Role"
            name="role"
            required
            help="Your job title or position"
          >
            <UInput
              v-model="state.role"
              placeholder="Software Engineer"
              :disabled="loading"
            />
          </UFormGroup>

          <!-- Highest Qualification -->
          <UFormGroup
            label="Highest Qualification"
            name="highestQualification"
            required
            help="Your highest level of education"
          >
            <UInput
              v-model="state.highestQualification"
              placeholder="Bachelor's Degree in Computer Science"
              :disabled="loading"
            />
          </UFormGroup>

          <!-- Desired Salary -->
          <UFormGroup
            label="Desired Salary (Optional)"
            name="desiredSalary"
            help="Your expected annual salary in USD"
          >
            <UInput
              v-model.number="state.desiredSalary"
              type="number"
              placeholder="75000"
              :disabled="loading"
            />
          </UFormGroup>

          <!-- Note -->
          <UFormGroup
            label="Additional Notes (Optional)"
            name="note"
            help="Any additional information you'd like to share (max 5000 characters)"
          >
            <UTextarea
              v-model="state.note"
              :rows="4"
              placeholder="Tell us about your experience, interests, or anything else you'd like us to know..."
              :disabled="loading"
            />
          </UFormGroup>

          <!-- Server Error Display -->
          <UAlert
            v-if="error"
            color="red"
            variant="soft"
            :title="error.message"
            :close-button="{ icon: 'i-heroicons-x-mark-20-solid', color: 'red', variant: 'link' }"
          >
            <template v-if="error.errors" #description>
              <ul class="list-disc list-inside space-y-1">
                <li v-for="(messages, field) in error.errors" :key="field">
                  <span class="font-semibold">{{ field }}:</span> {{ messages.join(', ') }}
                </li>
              </ul>
            </template>
          </UAlert>

          <!-- Submit Button -->
          <div class="flex justify-end">
            <UButton
              type="submit"
              size="lg"
              :loading="loading"
              :disabled="loading"
            >
              {{ loading ? 'Registering...' : 'Register' }}
            </UButton>
          </div>
        </UForm>
      </UCard>

      <!-- Help Text -->
      <div class="mt-6 text-center">
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Need help? Contact <a href="mailto:hr@greenhat.com" class="text-primary-600 hover:text-primary-500">hr@greenhat.com</a>
        </p>
      </div>
    </div>
  </div>
</template>
