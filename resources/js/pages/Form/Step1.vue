<template>
    <div class="min-h-screen bg-gray-50 py-12">
        <!-- OneSignal Notification Component -->
        <OneSignalNotification />

        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-medium text-white">1</div>
                            <div class="ml-2 text-sm font-medium text-gray-900">Basic Information</div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">Step {{ step }} of {{ totalSteps }}</div>
                </div>
                <div class="mt-4">
                    <div class="h-2 rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-blue-600" :style="{ width: `${(step / totalSteps) * 100}%` }"></div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="rounded-lg bg-white p-8 shadow-lg">
                <!-- Success Message -->
                <div v-if="success" class="mb-6 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ success }}</p>
                        </div>
                    </div>
                </div>

                <!-- General Error Message -->
                <div v-if="errors.general" class="mb-6 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ errors.general }}</p>
                        </div>
                    </div>
                </div>

                <!-- Step Heading -->
                <div class="mb-6">
                    <h1 class="mb-2 text-2xl font-bold text-gray-900">Basic Information</h1>
                    <p class="text-gray-600">
                        Please provide your basic contact information. This helps us understand your business needs and get in touch with you
                        regarding our services.
                    </p>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700"> Full Name <span class="text-red-500">*</span> </label>
                        <input
                            id="name"
                            v-model="form.name"
                            @blur="validateField('name')"
                            type="text"
                            required
                            minlength="3"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            :class="{ 'border-red-500': errors.name || clientErrors.name }"
                            placeholder="Enter your full name (minimum 3 characters)"
                        />
                        <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                        <p v-else-if="clientErrors.name" class="mt-1 text-sm text-red-600">{{ clientErrors.name }}</p>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            @blur="validateField('email')"
                            type="email"
                            required
                            class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            :class="{ 'border-red-500': errors.email || clientErrors.email }"
                            placeholder="Enter your email address"
                        />
                        <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                        <p v-else-if="clientErrors.email" class="mt-1 text-sm text-red-600">{{ clientErrors.email }}</p>
                    </div>

                    <!-- Company Name Field -->
                    <div>
                        <label for="company_name" class="mb-2 block text-sm font-medium text-gray-700">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="company_name"
                            v-model="form.company_name"
                            @blur="validateField('company_name')"
                            type="text"
                            required
                            minlength="3"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            :class="{ 'border-red-500': errors.company_name || clientErrors.company_name }"
                            placeholder="Enter your company name (minimum 3 characters)"
                        />
                        <p v-if="errors.company_name" class="mt-1 text-sm text-red-600">{{ errors.company_name }}</p>
                        <p v-else-if="clientErrors.company_name" class="mt-1 text-sm text-red-600">{{ clientErrors.company_name }}</p>
                    </div>

                    <!-- Website URL Field -->
                    <div>
                        <label for="website_url" class="mb-2 block text-sm font-medium text-gray-700">
                            Website URL <span class="text-gray-400">(Optional)</span>
                        </label>
                        <input
                            id="website_url"
                            v-model="form.website_url"
                            @blur="validateField('website_url')"
                            type="url"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            :class="{ 'border-red-500': errors.website_url || clientErrors.website_url }"
                            placeholder="https://your-website.com"
                        />
                        <p v-if="errors.website_url" class="mt-1 text-sm text-red-600">{{ errors.website_url }}</p>
                        <p v-else-if="clientErrors.website_url" class="mt-1 text-sm text-red-600">{{ clientErrors.website_url }}</p>
                        <p class="mt-1 text-sm text-gray-500">
                            If you have a website, please provide the URL so we can better understand your business.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6">
                        <button
                            type="submit"
                            :disabled="loading || !isFormValid"
                            class="rounded-md bg-blue-600 px-6 py-2 text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <span v-if="loading" class="flex items-center">
                                <svg
                                    class="mr-3 -ml-1 h-5 w-5 animate-spin text-white"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                Processing...
                            </span>
                            <span v-else>Continue to Next Step</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';
import OneSignalNotification from '@/components/OneSignalNotification.vue';

// Props from the controller
interface Props {
    step: number;
    totalSteps: number;
    errors?: Record<string, string>;
    success?: string;
    old?: Record<string, any>;
}

const props = withDefaults(defineProps<Props>(), {
    errors: () => ({}),
    success: '',
    old: () => ({}),
});

// Form data
const form = reactive({
    name: props.old.name || '',
    email: props.old.email || '',
    company_name: props.old.company_name || '',
    website_url: props.old.website_url || '',
});

// Loading state
const loading = ref(false);

// Client-side validation errors
const clientErrors = reactive({
    name: '',
    email: '',
    company_name: '',
    website_url: '',
});

// Validation functions
const validateName = (value: string): string => {
    if (!value) return 'The name field is required.';
    if (value.length < 3) return 'The name must be at least 3 characters.';
    return '';
};

const validateEmail = (value: string): string => {
    if (!value) return 'The email field is required.';
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) return 'Please enter a valid email address.';
    return '';
};

const validateCompanyName = (value: string): string => {
    if (!value) return 'The company name field is required.';
    if (value.length < 3) return 'The company name must be at least 3 characters.';
    return '';
};

const validateWebsiteUrl = (value: string): string => {
    if (!value) return ''; // Optional field
    try {
        new URL(value);
        return '';
    } catch {
        return 'Please enter a valid URL.';
    }
};

// Field validation
const validateField = (field: keyof typeof form) => {
    const value = form[field];

    switch (field) {
        case 'name':
            clientErrors.name = validateName(value);
            break;
        case 'email':
            clientErrors.email = validateEmail(value);
            break;
        case 'company_name':
            clientErrors.company_name = validateCompanyName(value);
            break;
        case 'website_url':
            clientErrors.website_url = validateWebsiteUrl(value);
            break;
    }
};

// Validate all fields
const validateAllFields = (): boolean => {
    validateField('name');
    validateField('email');
    validateField('company_name');
    validateField('website_url');

    return !Object.values(clientErrors).some((error) => error !== '');
};

// Check if form is valid
const isFormValid = computed(() => {
    return (
        form.name.length >= 3 &&
        form.email &&
        form.company_name.length >= 3 &&
        validateEmail(form.email) === '' &&
        (form.website_url === '' || validateWebsiteUrl(form.website_url) === '')
    );
});

// Form submission
const submitForm = async () => {
    // Clear previous client errors
    Object.keys(clientErrors).forEach((key) => {
        clientErrors[key as keyof typeof clientErrors] = '';
    });

    // Validate all fields
    if (!validateAllFields()) {
        return;
    }

    loading.value = true;

    try {
        await router.post(route('form.step1.store'), form);
    } catch (error) {
        console.error('Form submission error:', error);
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
input {
    color: black;
}
</style>
