<script setup lang="ts">
definePageMeta({
  middleware: ['auth']
})

const { user, fetchUser } = useAuth()
const { enrollments, fetchEnrollments, loading, error } = useCourseEnrollment()

// Filter state
const statusFilter = ref<string>('all')

// Status options
const statusOptions = [
  { label: 'All Courses', value: 'all' },
  { label: 'Enrolled', value: 'enrolled' },
  { label: 'In Progress', value: 'in_progress' },
  { label: 'Completed', value: 'completed' }
]

// Fetch enrollments on mount
onMounted(async () => {
  try {
    // Fetch user with enrollments
    await fetchUser('employee.enrollments.course')

    // Also fetch enrollments separately for filtering
    if (user.value?.employee?.id) {
      await fetchEnrollments({ employeeId: user.value.employee.id })
    }
  } catch (err) {
    console.error('Failed to fetch enrollments:', err)
  }
})

// Filtered enrollments
const filteredEnrollments = computed(() => {
  if (!enrollments.value) return []

  if (statusFilter.value === 'all') {
    return enrollments.value
  }

  return enrollments.value.filter(
    enrollment => enrollment.status === statusFilter.value
  )
})

// Stats
const stats = computed(() => {
  const all = enrollments.value || []
  return {
    total: all.length,
    enrolled: all.filter(e => e.status === 'enrolled').length,
    inProgress: all.filter(e => e.status === 'in_progress').length,
    completed: all.filter(e => e.status === 'completed').length,
    averageProgress: all.length > 0
      ? Math.round(all.reduce((sum, e) => sum + e.progressPercentage, 0) / all.length)
      : 0
  }
})
</script>

<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div>
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
        My Courses
      </h1>
      <p class="mt-2 text-gray-600 dark:text-gray-400">
        Track your course enrollments and progress
      </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <UCard>
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Courses</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ stats.total }}
            </p>
          </div>
          <UIcon name="i-heroicons-academic-cap" class="w-8 h-8 text-blue-500" />
        </div>
      </UCard>

      <UCard>
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">In Progress</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ stats.inProgress }}
            </p>
          </div>
          <UIcon name="i-heroicons-clock" class="w-8 h-8 text-yellow-500" />
        </div>
      </UCard>

      <UCard>
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Completed</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ stats.completed }}
            </p>
          </div>
          <UIcon name="i-heroicons-check-circle" class="w-8 h-8 text-green-500" />
        </div>
      </UCard>

      <UCard>
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Avg Progress</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ stats.averageProgress }}%
            </p>
          </div>
          <UIcon name="i-heroicons-chart-bar" class="w-8 h-8 text-purple-500" />
        </div>
      </UCard>
    </div>

    <!-- Filter -->
    <UCard>
      <div class="flex items-center justify-between">
        <UFormGroup label="Filter by status" class="w-full max-w-xs">
          <USelect
            v-model="statusFilter"
            :options="statusOptions"
            option-attribute="label"
            value-attribute="value"
          />
        </UFormGroup>

        <UButton
          to="/courses"
          color="primary"
          icon="i-heroicons-plus"
        >
          Browse Courses
        </UButton>
      </div>
    </UCard>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center py-12">
      <UIcon name="i-heroicons-arrow-path" class="w-8 h-8 animate-spin text-primary" />
    </div>

    <!-- Error State -->
    <UAlert
      v-else-if="error"
      color="red"
      variant="subtle"
      :title="error.message"
    />

    <!-- Empty State -->
    <UCard v-else-if="!filteredEnrollments || filteredEnrollments.length === 0">
      <div class="text-center py-12">
        <UIcon name="i-heroicons-book-open" class="w-16 h-16 mx-auto text-gray-400 mb-4" />
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
          No enrollments found
        </h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4">
          You haven't enrolled in any courses yet
        </p>
        <UButton
          to="/courses"
          color="primary"
        >
          Browse Course Catalog
        </UButton>
      </div>
    </UCard>

    <!-- Enrollments Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <EnrollmentCard
        v-for="enrollment in filteredEnrollments"
        :key="enrollment.id"
        :enrollment="enrollment"
      />
    </div>
  </div>
</template>
