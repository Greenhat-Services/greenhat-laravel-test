<script setup lang="ts">
import type { CourseEnrollment } from '~/composables/useAuth'

const props = defineProps<{
  enrollment: CourseEnrollment
}>()

// Status badge color
const statusColor = computed(() => {
  switch (props.enrollment.status) {
    case 'enrolled':
      return 'blue'
    case 'in_progress':
      return 'yellow'
    case 'completed':
      return 'green'
    case 'dropped':
      return 'red'
    default:
      return 'gray'
  }
})

// Format status text
const statusText = computed(() => {
  return props.enrollment.status.split('_').map(word =>
    word.charAt(0).toUpperCase() + word.slice(1)
  ).join(' ')
})

// Format date
const formatDate = (dateString: string | null) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString()
}

// Progress color
const progressColor = computed(() => {
  const progress = props.enrollment.progressPercentage
  if (progress >= 75) return 'green'
  if (progress >= 50) return 'yellow'
  if (progress >= 25) return 'orange'
  return 'red'
})
</script>

<template>
  <UCard>
    <!-- Card Header -->
    <template #header>
      <div class="flex justify-between items-start">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ enrollment.course?.title || 'Course' }}
          </h3>
          <p v-if="enrollment.course?.instructor" class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Instructor: {{ enrollment.course.instructor }}
          </p>
        </div>
        <UBadge
          :color="statusColor"
          variant="subtle"
        >
          {{ statusText }}
        </UBadge>
      </div>
    </template>

    <!-- Card Body -->
    <div class="space-y-4">
      <!-- Course Description -->
      <p v-if="enrollment.course?.description" class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
        {{ enrollment.course.description }}
      </p>

      <!-- Progress Bar -->
      <div class="space-y-2">
        <div class="flex justify-between text-sm">
          <span class="text-gray-600 dark:text-gray-400">Progress</span>
          <span class="font-medium text-gray-900 dark:text-white">
            {{ enrollment.progressPercentage }}%
          </span>
        </div>
        <UProgress
          :value="enrollment.progressPercentage"
          :color="progressColor"
          size="md"
        />
      </div>

      <!-- Enrollment Details -->
      <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
          <span class="text-gray-500 dark:text-gray-400">Enrolled:</span>
          <p class="font-medium text-gray-900 dark:text-white">
            {{ formatDate(enrollment.enrolledAt) }}
          </p>
        </div>
        <div v-if="enrollment.completedAt">
          <span class="text-gray-500 dark:text-gray-400">Completed:</span>
          <p class="font-medium text-gray-900 dark:text-white">
            {{ formatDate(enrollment.completedAt) }}
          </p>
        </div>
        <div v-if="enrollment.course?.durationHours">
          <span class="text-gray-500 dark:text-gray-400">Duration:</span>
          <p class="font-medium text-gray-900 dark:text-white">
            {{ enrollment.course.durationHours }} hours
          </p>
        </div>
        <div v-if="enrollment.course?.difficultyLevel">
          <span class="text-gray-500 dark:text-gray-400">Difficulty:</span>
          <p class="font-medium text-gray-900 dark:text-white capitalize">
            {{ enrollment.course.difficultyLevel }}
          </p>
        </div>
      </div>

      <!-- Notes -->
      <div v-if="enrollment.notes" class="pt-3 border-t border-gray-200 dark:border-gray-700">
        <span class="text-sm text-gray-500 dark:text-gray-400">Notes:</span>
        <p class="text-sm text-gray-900 dark:text-white mt-1">
          {{ enrollment.notes }}
        </p>
      </div>
    </div>
  </UCard>
</template>
