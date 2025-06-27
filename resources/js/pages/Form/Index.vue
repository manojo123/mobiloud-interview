<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
      <!-- Success Message -->
      <div v-if="success" class="mb-8 rounded-md bg-green-50 p-6">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-green-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <h3 class="text-lg font-medium text-green-800">Success!</h3>
            <p class="mt-2 text-sm text-green-700">{{ success }}</p>
          </div>
        </div>
      </div>

      <!-- Form Introduction -->
      <div class="rounded-lg bg-white p-8 shadow-lg">
        <div class="text-center">
          <h1 class="mb-4 text-3xl font-bold text-gray-900">Lead Capture Form</h1>
          <p class="mb-8 text-lg text-gray-600">
            Welcome! We're excited to learn more about your business and how we can help you.
            This form will take just a few minutes to complete.
          </p>

          <div class="mb-8">
            <h2 class="mb-4 text-xl font-semibold text-gray-800">What we'll collect:</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div class="flex items-center space-x-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                  <span class="text-sm font-medium text-blue-600">1</span>
                </div>
                <span class="text-gray-700">Basic contact information</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                  <span class="text-sm font-medium text-blue-600">2</span>
                </div>
                <span class="text-gray-700">Website type and details</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                  <span class="text-sm font-medium text-blue-600">3</span>
                </div>
                <span class="text-gray-700">Platform preferences</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                  <span class="text-sm font-medium text-blue-600">4</span>
                </div>
                <span class="text-gray-700">Review and submit</span>
              </div>
            </div>
          </div>

          <Link
            :href="route('form.step1')"
            class="inline-flex items-center rounded-md bg-blue-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            Get Started
            <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Props {
  success?: string;
}

defineProps<Props>();
</script>
