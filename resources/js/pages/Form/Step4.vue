<template>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white">
                            <span class="text-sm font-medium">4</span>
                        </div>
                        <span class="text-lg font-medium text-gray-900">Review & Submit</span>
                    </div>
                    <div class="text-sm text-gray-500">Step {{ step }} of {{ totalSteps }}</div>
                </div>
                <div class="mt-4 h-2 w-full rounded-full bg-gray-200">
                    <div class="h-2 rounded-full bg-blue-600" style="width: 100%"></div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="rounded-lg bg-white p-8 shadow-lg">
                <!-- Step Heading -->
                <div class="mb-6">
                    <h1 class="mb-2 text-2xl font-bold text-gray-900">Review Your Information</h1>
                    <p class="text-gray-600">
                        Please review all the information you've provided. You can go back to any step to make changes before submitting.
                    </p>
                </div>

                <!-- Form Summary -->
                <div class="mb-8 space-y-6">
                    <!-- Step 1 Summary -->
                    <div class="rounded-lg border border-gray-200 p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                            <Link
                                :href="route('form.step1')"
                                class="text-sm text-blue-600 hover:text-blue-800"
                            >
                                Edit
                            </Link>
                        </div>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Name</span>
                                <p class="text-gray-900">{{ formData.step1.name }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Email</span>
                                <p class="text-gray-900">{{ formData.step1.email }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Company Name</span>
                                <p class="text-gray-900">{{ formData.step1.company_name }}</p>
                            </div>
                            <div v-if="formData.step1.website_url">
                                <span class="text-sm font-medium text-gray-500">Website URL</span>
                                <p class="text-gray-900">{{ formData.step1.website_url }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 Summary -->
                    <div class="rounded-lg border border-gray-200 p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Website Details</h3>
                            <Link
                                :href="route('form.step2')"
                                class="text-sm text-blue-600 hover:text-blue-800"
                            >
                                Edit
                            </Link>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Website Type</span>
                            <p class="text-gray-900">{{ getWebsiteTypeLabel(formData.step2.website_type) }}</p>
                        </div>
                    </div>

                    <!-- Step 3 Summary -->
                    <div class="rounded-lg border border-gray-200 p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Platform Selection</h3>
                            <Link
                                :href="route('form.step3')"
                                class="text-sm text-blue-600 hover:text-blue-800"
                            >
                                Edit
                            </Link>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Platform</span>
                            <p class="text-gray-900">{{ getPlatformLabel(formData.step3.platform) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Notification Status -->
                <div v-if="notificationStatus" class="mb-6 rounded-md p-4" :class="notificationStatusClass">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg v-if="notificationStatus === 'sending'" class="h-5 w-5 animate-spin text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            <svg v-else-if="notificationStatus === 'success'" class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <svg v-else-if="notificationStatus === 'error'" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium" :class="notificationStatusTextClass">
                                {{ notificationStatusMessage }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-6">
                    <button
                        @click="submitForm"
                        :disabled="loading"
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
                            Submitting...
                        </span>
                        <span v-else>Submit Form</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// Props from the controller
interface Props {
    step: number;
    totalSteps: number;
    formData: {
        step1: {
            name: string;
            email: string;
            company_name: string;
            website_url?: string;
        };
        step2: {
            website_type: string;
        };
        step3: {
            platform: string;
        };
    };
}

const props = defineProps<Props>();

// Reactive state
const loading = ref(false);
const notificationStatus = ref<'sending' | 'success' | 'error' | null>(null);

// Computed properties
const notificationStatusClass = computed(() => {
    switch (notificationStatus.value) {
        case 'sending':
            return 'bg-blue-50';
        case 'success':
            return 'bg-green-50';
        case 'error':
            return 'bg-red-50';
        default:
            return '';
    }
});

const notificationStatusTextClass = computed(() => {
    switch (notificationStatus.value) {
        case 'sending':
            return 'text-blue-800';
        case 'success':
            return 'text-green-800';
        case 'error':
            return 'text-red-800';
        default:
            return '';
    }
});

const notificationStatusMessage = computed(() => {
    switch (notificationStatus.value) {
        case 'sending':
            return 'Sending notification to team...';
        case 'success':
            return 'Notification sent successfully!';
        case 'error':
            return 'Notification failed to send, but form was submitted.';
        default:
            return '';
    }
});

// Methods
const getWebsiteTypeLabel = (type: string): string => {
    const labels: Record<string, string> = {
        ecommerce: 'E-commerce',
        blog: 'Blog/Content Site',
        corporate: 'Corporate/Business Site',
        portfolio: 'Portfolio',
        other: 'Other',
    };
    return labels[type] || type;
};

const getPlatformLabel = (platform: string): string => {
    const labels: Record<string, string> = {
        shopify: 'Shopify',
        woocommerce: 'WooCommerce',
        bigcommerce: 'BigCommerce',
        magento: 'Magento',
        custom_solution: 'Custom Solution',
        wordpress: 'WordPress',
        squarespace: 'Squarespace',
        webflow: 'Webflow',
        custom_developed: 'Custom Developed',
        other: 'Other',
    };
    return labels[platform] || platform;
};

const submitForm = async () => {
    loading.value = true;
    notificationStatus.value = 'sending';

    // Log OneSignal request details
    console.log('🚀 OneSignal Integration - Form Submission Started');
    console.log('📊 Form Data:', props.formData);
    console.log('📱 OneSignal App ID:', window.OneSignal?.getAppId?.() || 'Not initialized');
    console.log('⏰ Submission Time:', new Date().toISOString());

    try {
        // Submit the form
        await router.post(route('form.submit'), {}, {
            onSuccess: (page) => {
                console.log('✅ Form submitted successfully');
                console.log('📨 OneSignal notification triggered');
                console.log('📄 Response:', page);

                notificationStatus.value = 'success';

                // Additional OneSignal logging
                if (window.OneSignal) {
                    console.log('🔔 OneSignal SDK Status:', {
                        isPushSupported: window.OneSignal.isPushSupported(),
                        isSubscribed: window.OneSignal.isSubscribed(),
                        getUserId: window.OneSignal.getUserId(),
                    });
                }
            },
            onError: (errors) => {
                console.error('❌ Form submission failed:', errors);
                notificationStatus.value = 'error';
            },
            onFinish: () => {
                loading.value = false;
            }
        });
    } catch (error) {
        console.error('💥 Unexpected error during form submission:', error);
        notificationStatus.value = 'error';
        loading.value = false;
    }
};
</script>
