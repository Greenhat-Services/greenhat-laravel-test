<script setup lang="ts">
const route = useRoute()
const router = useRouter()

// Get data from query parameters
const name = computed(() => route.query.name as string || 'there')
const email = computed(() => route.query.email as string || '')
const role = computed(() => route.query.role as string || '')

// Redirect to home if no data
onMounted(() => {
  if (!route.query.name || !route.query.email) {
    router.push('/')
  }
})

const handleNewRegistration = () => {
  router.push('/')
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
      <UCard>
        <!-- Success Icon -->
        <div class="flex justify-center mb-6">
          <div class="rounded-full bg-green-100 dark:bg-green-900 p-6">
            <Icon name="i-heroicons-check-circle" class="w-16 h-16 text-green-600 dark:text-green-400" />
          </div>
        </div>

        <!-- Success Message -->
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            Registration Successful!
          </h1>
          <p class="text-lg text-gray-600 dark:text-gray-400">
            Welcome to the Greenhat team, {{ name }}!
          </p>
        </div>

        <!-- Registration Details -->
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Registration Details
          </h2>

          <div class="space-y-3">
            <div class="flex items-start">
              <Icon name="i-heroicons-user" class="w-5 h-5 text-gray-400 mr-3 mt-0.5" />
              <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</p>
                <p class="text-base text-gray-900 dark:text-white">{{ name }}</p>
              </div>
            </div>

            <div class="flex items-start">
              <Icon name="i-heroicons-envelope" class="w-5 h-5 text-gray-400 mr-3 mt-0.5" />
              <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                <p class="text-base text-gray-900 dark:text-white">{{ email }}</p>
              </div>
            </div>

            <div class="flex items-start">
              <Icon name="i-heroicons-briefcase" class="w-5 h-5 text-gray-400 mr-3 mt-0.5" />
              <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</p>
                <p class="text-base text-gray-900 dark:text-white">{{ role }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Next Steps Alert -->
        <UAlert
          color="blue"
          variant="soft"
          title="What's Next?"
          icon="i-heroicons-information-circle"
        >
          <template #description>
            <div class="space-y-2 text-sm">
              <p>✅ A confirmation email has been sent to <strong>{{ email }}</strong></p>
              <p>✅ Our HR team will review your registration</p>
              <p>✅ You will receive further instructions via email within 2-3 business days</p>
            </div>
          </template>
        </UAlert>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-center gap-4">
          <UButton
            color="primary"
            variant="outline"
            @click="handleNewRegistration"
          >
            Register Another Employee
          </UButton>
        </div>
      </UCard>

      <!-- Contact Info -->
      <div class="mt-6 text-center">
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Questions? Contact us at <a href="mailto:hr@greenhat.com" class="text-primary-600 hover:text-primary-500">hr@greenhat.com</a>
        </p>
      </div>
    </div>
  </div>
</template>
