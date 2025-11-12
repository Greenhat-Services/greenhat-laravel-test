<script setup lang="ts">
definePageMeta({
  middleware: ['auth']
})

const { fetchCourses, courses, loading, error } = useCourses()
const { enrollInCourse, isEnrolledIn, fetchEnrollments, enrollments } = useCourseEnrollment()
const { user, fetchUser } = useAuth()

// Filter state
const filters = reactive({
  search: '',
  difficulty: '',
  availableForEnrollment: true
})

// Difficulty options
const difficultyOptions = [
  { label: 'All Levels', value: '' },
  { label: 'Beginner', value: 'beginner' },
  { label: 'Intermediate', value: 'intermediate' },
  { label: 'Advanced', value: 'advanced' }
]

// Enrollment status
const enrolling = ref(false)
const enrollSuccess = ref(false)
const enrollError = ref<string | null>(null)

// Fetch courses on mount
onMounted(async () => {
  try {
    // Fetch user data with enrollments first
    await fetchUser('employee.enrollments')

    // Fetch enrollments separately to populate the enrollments ref
    if (user.value?.employee?.id) {
      await fetchEnrollments({ employeeId: user.value.employee.id })
    }

    // Then fetch courses
    await fetchCourses({
      availableForEnrollment: filters.availableForEnrollment,
      status: 'published'
    })

  } catch (err) {
    console.error('Failed to fetch courses:', err)
  }
})

// Apply filters
const applyFilters = async () => {
  try {
    await fetchCourses({
      search: filters.search || undefined,
      difficulty: filters.difficulty || undefined,
      availableForEnrollment: filters.availableForEnrollment,
      status: 'published'
    })
  } catch (err) {
    console.error('Failed to apply filters:', err)
  }
}

// Watch filters and apply
watch(filters, () => {
  applyFilters()
})

// Handle enrollment
const handleEnroll = async (courseId: number) => {
  enrolling.value = true
  enrollSuccess.value = false
  enrollError.value = null

  try {
    await enrollInCourse({ courseId })
    enrollSuccess.value = true

    // Refresh enrollments to update UI
    if (user.value?.employee?.id) {
      await fetchEnrollments({ employeeId: user.value.employee.id })
    }

    // Show success message
    setTimeout(() => {
      enrollSuccess.value = false
    }, 3000)
  } catch (err: any) {
    console.error('Enrollment error:', err)
    enrollError.value = err.data?.message || 'Failed to enroll in course'
    setTimeout(() => {
      enrollError.value = null
    }, 5000)
  } finally {
    enrolling.value = false
  }
}

// Check if enrolled
const checkEnrolled = (courseId: number) => {
  const enrolled = isEnrolledIn(courseId)
  return enrolled
}
</script>

<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div>
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
        Course Catalog
      </h1>
      <p class="mt-2 text-gray-600 dark:text-gray-400">
        Browse and enroll in available courses
      </p>
    </div>

    <!-- Success Alert -->
    <UAlert
      v-if="enrollSuccess"
      color="green"
      variant="subtle"
      title="Successfully enrolled!"
      description="You have been enrolled in the course. Check 'My Courses' to view your enrollment."
    />

    <!-- Error Alert -->
    <UAlert
      v-if="enrollError"
      color="red"
      variant="subtle"
      title="Enrollment failed"
      :description="enrollError"
    />

    <!-- Filters -->
    <UCard>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search -->
        <UFormGroup label="Search">
          <UInput
            v-model="filters.search"
            icon="i-heroicons-magnifying-glass"
            placeholder="Search courses..."
          />
        </UFormGroup>

        <!-- Difficulty Filter -->
        <UFormGroup label="Difficulty Level">
          <USelect
            v-model="filters.difficulty"
            :options="difficultyOptions"
            option-attribute="label"
            value-attribute="value"
          />
        </UFormGroup>

        <!-- Available Only Toggle -->
        <UFormGroup label="Show Only">
          <UCheckbox
            v-model="filters.availableForEnrollment"
            label="Available for enrollment"
          />
        </UFormGroup>
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
    <UCard v-else-if="!courses || courses.length === 0">
      <div class="text-center py-12">
        <UIcon name="i-heroicons-academic-cap" class="w-16 h-16 mx-auto text-gray-400 mb-4" />
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
          No courses found
        </h3>
        <p class="text-gray-600 dark:text-gray-400">
          Try adjusting your filters to see more results
        </p>
      </div>
    </UCard>

    <!-- Courses Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <CourseCard
        v-for="course in courses"
        :key="course.id"
        :course="course"
        :enrolled="checkEnrolled(course.id)"
        :show-enroll-button="true"
        @enroll="handleEnroll"
      />
    </div>
  </div>
</template>
