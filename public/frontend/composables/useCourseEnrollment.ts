export interface EnrollmentData {
  courseId: number
  notes?: string | null
}

export interface EnrollmentResponse {
  data: CourseEnrollment
  meta?: {
    message?: string
  }
}

export interface EnrollmentsResponse {
  data: CourseEnrollment[]
  meta?: {
    pagination?: {
      total: number
      count: number
      perPage: number
      currentPage: number
      totalPages: number
    }
  }
}

export const useCourseEnrollment = () => {
  const config = useRuntimeConfig()
  const { getToken, user } = useAuth()

  const loading = ref(false)
  const error = ref<ApiError | null>(null)
  const enrollments = ref<CourseEnrollment[]>([])

  // Enroll in course
  const enrollInCourse = async (data: EnrollmentData) => {
    const token = getToken()

    if (!token) {
      throw new Error('No authentication token found')
    }

    loading.value = true
    error.value = null

    try {
      const response = await $fetch<EnrollmentResponse | CourseEnrollment>(
        '/v1/course-enrollments',
        {
          method: 'POST',
          baseURL: config.public.apiBase,
          body: data,
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
          }
        }
      )

      // Handle both Fractal format (with data wrapper) and plain object
      let enrollment: CourseEnrollment
      if ('data' in response) {
        // Fractal response with data wrapper
        enrollment = response.data
      } else {
        // Plain enrollment object (no Fractal wrapper)
        enrollment = response
      }

      // Add to enrollments list
      enrollments.value.push(enrollment)

      return response
    } catch (err: any) {
      if (err.data) {
        error.value = {
          message: err.data.message || 'Failed to enroll in course',
          errors: err.data.errors || {}
        }
      } else {
        error.value = {
          message: err.message || 'An unexpected error occurred'
        }
      }

      throw err
    } finally {
      loading.value = false
    }
  }

  // Fetch enrollments
  const fetchEnrollments = async (filters: { employeeId?: number, courseId?: number, status?: string } = {}) => {
    const token = getToken()

    if (!token) {
      throw new Error('No authentication token found')
    }

    loading.value = true
    error.value = null

    try {
      // Build query parameters
      const params = new URLSearchParams()

      if (filters.employeeId) params.append('employee_id', filters.employeeId.toString())
      if (filters.courseId) params.append('course_id', filters.courseId.toString())
      if (filters.status) params.append('status', filters.status)

      const queryString = params.toString()
      const url = `/v1/course-enrollments${queryString ? `?${queryString}` : ''}`

      const response = await $fetch<EnrollmentsResponse | CourseEnrollment[]>(url, {
        method: 'GET',
        baseURL: config.public.apiBase,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`
        }
      })

      // Handle both Fractal format (with data wrapper) and plain array
      if (Array.isArray(response)) {
        // Plain array response (no Fractal wrapper)
        enrollments.value = response
      } else {
        // Fractal response with data wrapper
        enrollments.value = response.data
      }

      return response
    } catch (err: any) {
      if (err.data) {
        error.value = {
          message: err.data.message || 'Failed to fetch enrollments',
          errors: err.data.errors || {}
        }
      } else {
        error.value = {
          message: err.message || 'An unexpected error occurred'
        }
      }

      throw err
    } finally {
      loading.value = false
    }
  }

  // Check if user is enrolled in a course
  const isEnrolledIn = (courseId: number): boolean => {

    const result = enrollments.value.some(
      enrollment => {
        return enrollment.courseId === courseId &&
          ['enrolled', 'in_progress'].includes(enrollment.status)
      }
    )

    return result
  }

  // Get enrollment for a specific course
  const getEnrollment = (courseId: number): CourseEnrollment | undefined => {
    return enrollments.value.find(
      enrollment => enrollment.courseId === courseId
    )
  }

  return {
    loading: readonly(loading),
    error: readonly(error),
    enrollments: readonly(enrollments),
    enrollInCourse,
    fetchEnrollments,
    isEnrolledIn,
    getEnrollment
  }
}
