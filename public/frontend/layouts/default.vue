<script setup lang="ts">
const { user, logout, isAuthenticated } = useAuth()
const route = useRoute()

// Navigation links
const links = computed(() => [
  {
    label: 'Course Catalog',
    icon: 'i-heroicons-academic-cap',
    to: '/courses',
    active: route.path === '/courses'
  },
  {
    label: 'My Courses',
    icon: 'i-heroicons-book-open',
    to: '/my-courses',
    active: route.path === '/my-courses'
  }
])

// User menu items
const userMenuItems = [
  [{
    label: user.value?.email || 'User',
    slot: 'account',
    disabled: true
  }],
  [{
    label: 'Logout',
    icon: 'i-heroicons-arrow-right-on-rectangle',
    click: logout
  }]
]
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <header v-if="isAuthenticated" class="bg-white dark:bg-gray-800 shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo/Title -->
          <div class="flex items-center">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
              Employee Portal
            </h1>
          </div>

          <!-- Navigation -->
          <nav class="hidden md:flex space-x-4">
            <UButton
              v-for="link in links"
              :key="link.to"
              :to="link.to"
              :variant="link.active ? 'solid' : 'ghost'"
              :icon="link.icon"
              size="md"
            >
              {{ link.label }}
            </UButton>
          </nav>

          <!-- User Menu -->
          <div class="flex items-center">
            <UDropdown
              :items="userMenuItems"
              :popper="{ placement: 'bottom-end' }"
            >
              <UButton
                variant="ghost"
                :icon="'i-heroicons-user-circle'"
                size="md"
              >
                {{ user?.firstName || 'Menu' }}
              </UButton>

              <template #account="{ item }">
                <div class="text-left">
                  <p class="truncate font-medium text-gray-900 dark:text-white">
                    {{ user?.name }}
                  </p>
                  <p class="truncate text-sm text-gray-500 dark:text-gray-400">
                    {{ item.label }}
                  </p>
                </div>
              </template>
            </UDropdown>
          </div>
        </div>

        <!-- Mobile Navigation -->
        <nav class="md:hidden pb-4 flex space-x-2">
          <UButton
            v-for="link in links"
            :key="link.to"
            :to="link.to"
            :variant="link.active ? 'solid' : 'ghost'"
            :icon="link.icon"
            size="sm"
          >
            {{ link.label }}
          </UButton>
        </nav>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <slot />
    </main>

    <!-- Footer -->
    <footer v-if="isAuthenticated" class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-auto">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
          &copy; {{ new Date().getFullYear() }} Greenhat Employee Portal. All rights reserved.
        </p>
      </div>
    </footer>
  </div>
</template>
