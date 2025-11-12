export interface LoginCredentials {
  email: string
  password: string
}

export interface LoginResponse {
  accessToken: string
}

export interface UserResponse {
  data: {
    id: number
    email: string
    firstName: string
    lastName: string
    name: string
    createdAt: string
    updatedAt: string
    lastLoginAt: string | null
    employee?: {
      id: number
      userId: number
      role: string
      createdAt: string
      updatedAt: string
      enrollments?: CourseEnrollment[]
    }
  }
}

export interface CourseEnrollment {
  id: number
  courseId: number
  employeeId: number
  status: 'enrolled' | 'in_progress' | 'completed' | 'dropped'
  enrolledAt: string
  completedAt: string | null
  progressPercentage: number
  notes: string | null
  createdAt: string
  updatedAt: string
  course?: {
    id: number
    title: string
    slug: string
    description: string
    instructor: string
    durationHours: number
    difficultyLevel: string
    status: string
    maxEnrollments: number | null
    startDate: string | null
    endDate: string | null
  }
}

export interface ApiError {
  message: string
  errors?: Record<string, string[]>
}

export const useAuth = () => {
  const config = useRuntimeConfig()
  const router = useRouter()

  const loading = ref(false)
  const error = ref<ApiError | null>(null)
  const user = ref<UserResponse['data'] | null>(null)
  const isAuthenticated = computed(() => !!getToken())

  // Token management
  const getToken = () => {
    if (import.meta.client) {
      return localStorage.getItem('auth_token')
    }
    return null
  }

  const setToken = (token: string) => {
    if (import.meta.client) {
      localStorage.setItem('auth_token', token)
    }
  }

  const removeToken = () => {
    if (import.meta.client) {
      localStorage.removeItem('auth_token')
    }
  }

  // Login
  const login = async (credentials: LoginCredentials) => {
    loading.value = true
    error.value = null

    try {
      const response = await $fetch<LoginResponse>(
        '/v1/employee/authenticate',
        {
          method: 'POST',
          baseURL: config.public.apiBase,
          body: credentials,
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        }
      )

      console.log('response', response.accessToken)
      // Store token
      setToken(response.accessToken)

      return response
    } catch (err: any) {
      if (err.data) {
        error.value = {
          message: err.data.message || 'Login failed',
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

  // Logout
  const logout = () => {
    removeToken()
    user.value = null
    router.push('/auth/login')
  }

  // Fetch current user
  const fetchUser = async (includes: string = 'employee.enrollments') => {
    const token = getToken()

    if (!token) {
      throw new Error('No authentication token found')
    }

    loading.value = true
    error.value = null

    try {
      const response = await $fetch<UserResponse | UserResponse['data']>(
        `/v1/employee/user?include=${includes}`,
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
        user.value = response.data
        return response.data
      } else {
        // Plain user object (no Fractal wrapper)
        user.value = response
        return response
      }
    } catch (err: any) {
      if (err.status === 401) {
        // Token expired or invalid
        removeToken()
        user.value = null
        router.push('/auth/login')
      }

      if (err.data) {
        error.value = {
          message: err.data.message || 'Failed to fetch user',
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

  // Initialize user on mount if token exists
  const initAuth = async () => {
    if (isAuthenticated.value && !user.value) {
      try {
        await fetchUser()
      } catch (err) {
        // Silent fail, user will need to login again
        console.error('Failed to initialize auth:', err)
      }
    }
  }

  return {
    loading: readonly(loading),
    error: readonly(error),
    user: readonly(user),
    isAuthenticated,
    login,
    logout,
    fetchUser,
    initAuth,
    getToken
  }
}
