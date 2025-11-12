<script setup lang="ts">
import type { Course } from '~/composables/useCourses'

const props = defineProps<{
  course: Course
  enrolled?: boolean
  showEnrollButton?: boolean
}>()

const emit = defineEmits<{
  enroll: [courseId: number]
}>()

// Difficulty badge color
const difficultyColor = computed(() => {
  switch (props.course.difficultyLevel) {
    case 'beginner':
      return 'green'
    case 'intermediate':
      return 'yellow'
    case 'advanced':
      return 'red'
    default:
      return 'gray'
  }
})

// Format duration
const formattedDuration = computed(() => {
  const hours = props.course.durationHours
  return hours === 1 ? '1 hour' : `${hours} hours`
})

// Handle enroll click
const handleEnroll = () => {
  emit('enroll', props.course.id)
}
</script>

<template>
  <UCard>
    <!-- Card Header -->
    <template #header>
      <div class="flex justify-between items-start">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
          {{ course.title }}
        </h3>
        <UBadge
          :color="difficultyColor"
          variant="subtle"
        >
          {{ course.difficultyLevel }}
        </UBadge>
      </div>
    </template>

    <!-- Card Body -->
    <div class="space-y-3">
      <!-- Description -->
      <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">
        {{ course.description }}
      </p>

      <!-- Course Details -->
      <div class="flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400">
        <div class="flex items-center gap-1">
          <UIcon name="i-heroicons-user" class="w-4 h-4" />
          <span>{{ course.instructor }}</span>
        </div>
        <div class="flex items-center gap-1">
          <UIcon name="i-heroicons-clock" class="w-4 h-4" />
          <span>{{ formattedDuration }}</span>
        </div>
        <div v-if="course.availableSlots !== null" class="flex items-center gap-1">
          <UIcon name="i-heroicons-users" class="w-4 h-4" />
          <span>{{ course.availableSlots }} slots left</span>
        </div>
      </div>

      <!-- Enrollment Status -->
      <div v-if="!course.isEnrollmentOpen" class="text-sm text-red-600 dark:text-red-400">
        Enrollment closed
      </div>
      <div v-else-if="enrolled" class="text-sm text-green-600 dark:text-green-400 flex items-center gap-1">
        <UIcon name="i-heroicons-check-circle" class="w-4 h-4" />
        <span>Already enrolled</span>
      </div>
    </div>

    <!-- Card Footer -->
    <template #footer>
      <div class="flex justify-between items-center">
        <div v-if="course.startDate" class="text-xs text-gray-500 dark:text-gray-400">
          Starts: {{ new Date(course.startDate).toLocaleDateString() }}
        </div>
        <div v-else class="text-xs text-gray-500 dark:text-gray-400">
          Self-paced
        </div>

        <UButton
          v-if="showEnrollButton && !enrolled && course.isEnrollmentOpen"
          color="primary"
          size="sm"
          @click="handleEnroll"
        >
          Enroll Now
        </UButton>
        <UButton
          v-else-if="enrolled"
          color="gray"
          size="sm"
          variant="soft"
          disabled
        >
          Enrolled
        </UButton>
      </div>
    </template>
  </UCard>
</template>
