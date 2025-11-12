export interface RegistrationData {
  registrationCode: string
  firstName: string
  lastName: string
  email: string
  role: string
  highestQualification: string
  desiredSalary?: number | null
  note?: string | null
}

export interface RegistrationResponse {
  data: {
    id: number
    role: string
    highestQualification: string
    desiredSalary: number | null
    note: string | null
    confirmationEmailSentAt: string | null
    createdAt: string
    user: {
      id: number
      email: string
      firstName: string
      lastName: string
      name: string
      createdAt: string
      updatedAt: string
    }
  }
  meta: {
    success: boolean
    message: string
  }
}

export interface ApiError {
  message: string
  errors?: Record<string, string[]>
}

export const useEmployeeRegistration = () => {
  const config = useRuntimeConfig()
  const loading = ref(false)
  const error = ref<ApiError | null>(null)
  const success = ref(false)
  const responseData = ref<RegistrationResponse | null>(null)

  const submitRegistration = async (data: RegistrationData) => {
    loading.value = true
    error.value = null
    success.value = false
    responseData.value = null

    try {
      const response = await $fetch<RegistrationResponse>(
        '/v1/employee-registration',
        {
          method: 'POST',
          baseURL: config.public.apiBase,
          body: data,
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        }
      )

      success.value = true
      responseData.value = response
      return response
    } catch (err: any) {
      // Handle validation errors
      if (err.data) {
        error.value = {
          message: err.data.message || 'Registration failed',
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

  const resetState = () => {
    loading.value = false
    error.value = null
    success.value = false
    responseData.value = null
  }

  return {
    loading: readonly(loading),
    error: readonly(error),
    success: readonly(success),
    responseData: readonly(responseData),
    submitRegistration,
    resetState
  }
}
