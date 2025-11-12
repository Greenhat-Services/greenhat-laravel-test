export default defineNuxtRouteMiddleware((to, from) => {
  const { isAuthenticated } = useAuth()

  // If not authenticated and not going to login page, redirect to login
  if (!isAuthenticated.value && to.path !== '/auth/login') {
    return navigateTo('/auth/login')
  }

  // If authenticated and trying to access login page, redirect to courses
  if (isAuthenticated.value && to.path === '/auth/login') {
    return navigateTo('/courses')
  }
})
