<template>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-medium text-white">2</div>
                            <div class="ml-2 text-sm font-medium text-gray-900">Website Details</div>
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
                <!-- Step Heading -->
                <div class="mb-6">
                    <h1 class="mb-2 text-2xl font-bold text-gray-900">Website Details</h1>
                    <p class="text-gray-600">
                        Tell us about your website type. This helps us understand your business goals and provide the most relevant solutions for your
                        specific needs.
                    </p>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Website Type Field -->
                    <div>
                        <label class="mb-4 block text-sm font-medium text-gray-700"> Website Type <span class="text-red-500">*</span> </label>

                        <div class="space-y-3">
                            <div v-for="(label, value) in websiteTypes" :key="value" class="relative flex items-start">
                                <div class="flex h-5 items-center">
                                    <input
                                        :id="value"
                                        v-model="form.website_type"
                                        :value="value"
                                        type="radio"
                                        required
                                        class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500"
                                        :class="{ 'border-red-500': errors.website_type || clientErrors.website_type }"
                                    />
                                </div>
                                <div class="ml-3 text-sm">
                                    <label :for="value" class="cursor-pointer font-medium text-gray-700">
                                        {{ label }}
                                    </label>
                                    <p v-if="getWebsiteTypeDescription(value)" class="text-gray-500">
                                        {{ getWebsiteTypeDescription(value) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <p v-if="errors.website_type" class="mt-2 text-sm text-red-600">{{ errors.website_type }}</p>
                        <p v-else-if="clientErrors.website_type" class="mt-2 text-sm text-red-600">{{ clientErrors.website_type }}</p>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between pt-6">
                        <button
                            type="button"
                            @click="goBack"
                            class="rounded-md border border-gray-300 bg-white px-6 py-2 text-gray-700 transition-colors hover:bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                        >
                            Back
                        </button>

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

// Props from the controller
interface Props {
    step: number;
    totalSteps: number;
    errors?: Record<string, string>;
    websiteTypes: Record<string, string>;
}

const props = withDefaults(defineProps<Props>(), {
    errors: () => ({}),
    websiteTypes: () => ({}),
});

// Form data
const form = reactive({
    website_type: '',
});

// Loading state
const loading = ref(false);

// Client-side validation errors
const clientErrors = reactive({
    website_type: '',
});

// Website type descriptions
const websiteTypeDescriptions = {
    ecommerce: 'Online store for selling products or services',
    blog: 'Content-focused website for articles, news, or personal sharing',
    corporate: 'Professional business website for company information and services',
    portfolio: 'Showcase website for displaying work, projects, or creative content',
    other: 'Other type of website not listed above',
};

// Get description for website type
const getWebsiteTypeDescription = (type: string): string => {
    return websiteTypeDescriptions[type as keyof typeof websiteTypeDescriptions] || '';
};

// Validation functions
const validateWebsiteType = (value: string): string => {
    if (!value) return 'Please select a website type.';
    if (!Object.keys(props.websiteTypes).includes(value)) return 'Please select a valid website type.';
    return '';
};

// Field validation
const validateField = (field: keyof typeof form) => {
    const value = form[field];

    switch (field) {
        case 'website_type':
            clientErrors.website_type = validateWebsiteType(value);
            break;
    }
};

// Validate all fields
const validateAllFields = (): boolean => {
    validateField('website_type');

    return !Object.values(clientErrors).some((error) => error !== '');
};

// Check if form is valid
const isFormValid = computed(() => {
    return form.website_type && validateWebsiteType(form.website_type) === '';
});

// Go back to previous step
const goBack = () => {
    router.get(route('form.step1'));
};

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
        await router.post(route('form.step2.store'), form);
    } catch (error) {
        console.error('Form submission error:', error);
    } finally {
    loading.value = false;
    }
};
</script>

<style scoped>
input[type='radio'] {
    color: black;
}
</style>
