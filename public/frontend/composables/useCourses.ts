export interface Course {
  id: number
  title: string
  slug: string
  description: string
  instructor: string
  durationHours: number
  difficultyLevel: 'beginner' | 'intermediate' | 'advanced'
  status: 'draft' | 'published' | 'archived'
  maxEnrollments: number | null
  startDate: string | null
  endDate: string | null
  createdAt: string
  updatedAt: string
  isEnrollmentOpen: boolean
  availableSlots: number | null
  categories?: CourseCategory[]
}

export interface CourseCategory {
  id: number
  name: string
  slug: string
  description: string | null
}

export interface PaginationMeta {
  total: number
  count: number
  perPage: number
  currentPage: number
  totalPages: number
}

export interface CoursesResponse {
  data: Course[]
  meta?: {
    pagination?: PaginationMeta
  }
}

export interface CourseResponse {
  data: Course
}

export interface CourseFilters {
  search?: string
  category?: string | number
  difficulty?: string
  status?: string
  availableForEnrollment?: boolean
  page?: number
  perPage?: number
}

export const useCourses = () => {
  const config = useRuntimeConfig()
  const { getToken } = useAuth()

  const loading = ref(false)
  const error = ref<ApiError | null>(null)
  const courses = ref<Course[]>([])
  const currentCourse = ref<Course | null>(null)
  const pagination = ref<PaginationMeta | null>(null)

  // Fetch courses with filters
  const fetchCourses = async (filters: CourseFilters = {}) => {
    const token = getToken()

    if (!token) {
      throw new Error('No authentication token found')
    }

    loading.value = true
    error.value = null

    try {
      // Build query parameters
      const params = new URLSearchParams()

      if (filters.search) params.append('search', filters.search)
      if (filters.category) params.append('category', filters.category.toString())
      if (filters.difficulty) params.append('difficulty_level', filters.difficulty)
      if (filters.status) params.append('status', filters.status)
      if (filters.availableForEnrollment !== undefined) {
        params.append('available_for_enrollment', filters.availableForEnrollment ? '1' : '0')
      }
      if (filters.page) params.append('page', filters.page.toString())
      if (filters.perPage) params.append('per_page', filters.perPage.toString())

      const queryString = params.toString()
      const url = `/v1/courses${queryString ? `?${queryString}` : ''}`

      const response = await $fetch<CoursesResponse | Course[]>(url, {
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
        courses.value = response
        pagination.value = null
      } else {
        // Fractal response with data wrapper
        courses.value = response.data
        pagination.value = response.meta?.pagination || null
      }

      return response
    } catch (err: any) {
      if (err.data) {
        error.value = {
          message: err.data.message || 'Failed to fetch courses',
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

  // Fetch single course
  const fetchCourse = async (courseId: number) => {
    const token = getToken()

    if (!token) {
      throw new Error('No authentication token found')
    }

    loading.value = true
    error.value = null

    try {
      const response = await $fetch<CourseResponse | Course>(
        `/v1/courses/${courseId}`,
        {
          method: 'GET',
          baseURL: config.public.apiBase,
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
          }
        }
      )

      // Handle both Fractal format (with data wrapper) and plain object
      if ('data' in response) {
        // Fractal response with data wrapper
        currentCourse.value = response.data
        return response.data
      } else {
        // Plain course object (no Fractal wrapper)
        currentCourse.value = response
        return response
      }
    } catch (err: any) {
      if (err.data) {
        error.value = {
          message: err.data.message || 'Failed to fetch course',
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

  return {
    loading: readonly(loading),
    error: readonly(error),
    courses: readonly(courses),
    currentCourse: readonly(currentCourse),
    pagination: readonly(pagination),
    fetchCourses,
    fetchCourse
  }
}
