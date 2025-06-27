<template>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-medium text-white">4</div>
                            <div class="ml-2 text-sm font-medium text-gray-900">Review & Submit</div>
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

            <!-- Review Card -->
            <div class="rounded-lg bg-white p-8 shadow-lg">
                <!-- Step Heading -->
                <div class="mb-6">
                    <h1 class="mb-2 text-2xl font-bold text-gray-900">Review & Submit</h1>
                    <p class="text-gray-600">
                        Please review all the information you have entered below. If you need to make changes, use the Edit buttons to go back to the
                        relevant step. When you're ready, click Submit to complete your submission.
                    </p>
                </div>

                <!-- Review Information -->
                <div class="space-y-8">
                    <!-- Step 1 Review -->
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Basic Information</h2>
                            <button @click="goToStep(1)" class="text-sm text-blue-600 hover:underline">Edit</button>
                        </div>
                        <ul class="space-y-1 text-sm text-gray-700">
                            <li><span class="font-medium">Name:</span> {{ formData.step1.name }}</li>
                            <li><span class="font-medium">Email:</span> {{ formData.step1.email }}</li>
                            <li><span class="font-medium">Company Name:</span> {{ formData.step1.company_name }}</li>
                            <li v-if="formData.step1.website_url"><span class="font-medium">Website URL:</span> {{ formData.step1.website_url }}</li>
                        </ul>
                    </div>

                    <!-- Step 2 Review -->
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Website Details</h2>
                            <button @click="goToStep(2)" class="text-sm text-blue-600 hover:underline">Edit</button>
                        </div>
                        <ul class="space-y-1 text-sm text-gray-700">
                            <li><span class="font-medium">Website Type:</span> {{ websiteTypeLabel(formData.step2.website_type) }}</li>
                        </ul>
                    </div>

                    <!-- Step 3 Review -->
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Platform Selection</h2>
                            <button @click="goToStep(3)" class="text-sm text-blue-600 hover:underline">Edit</button>
                        </div>
                        <ul class="space-y-1 text-sm text-gray-700">
                            <li>
                                <span class="font-medium">Platform:</span> {{ platformLabel(formData.step2.website_type, formData.step3.platform) }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Submit Button -->
                <form @submit.prevent="submitForm" class="mt-10 flex justify-end">
                    <button
                        type="submit"
                        :disabled="loading"
                        class="rounded-md bg-blue-600 px-8 py-2 text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
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
                            Submitting...
                        </span>
                        <span v-else>Submit</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Props {
    step: number;
    totalSteps: number;
    formData: {
        step1: Record<string, any>;
        step2: Record<string, any>;
        step3: Record<string, any>;
    };
    errors?: Record<string, string>;
}

const props = withDefaults(defineProps<Props>(), {
    errors: () => ({}),
});

const loading = ref(false);

const websiteTypeLabels = {
    ecommerce: 'E-commerce',
    blog: 'Blog/Content Site',
    corporate: 'Corporate/Business Site',
    portfolio: 'Portfolio',
    other: 'Other',
};

const ecommercePlatforms = {
    shopify: 'Shopify',
    woocommerce: 'WooCommerce',
    bigcommerce: 'BigCommerce',
    magento: 'Magento',
    custom_solution: 'Custom Solution',
    other: 'Other',
};

const generalPlatforms = {
    wordpress: 'WordPress',
    squarespace: 'Squarespace',
    webflow: 'Webflow',
    custom_developed: 'Custom Developed',
    other: 'Other',
};

function websiteTypeLabel(type: string) {
    return websiteTypeLabels[type as keyof typeof websiteTypeLabels] || type;
}

function platformLabel(type: string, value: string) {
    if (type === 'ecommerce') {
        return ecommercePlatforms[value as keyof typeof ecommercePlatforms] || value;
    }
    return generalPlatforms[value as keyof typeof generalPlatforms] || value;
}

function goToStep(step: number) {
    router.get(route(`form.step${step}`));
}

async function submitForm() {
    loading.value = true;
    try {
        await router.post(route('form.submit'));
    } catch (error) {
        console.error('Submission error:', error);
    } finally {
        loading.value = false;
    }
}
</script>
