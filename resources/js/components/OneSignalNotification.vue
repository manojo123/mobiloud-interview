<template>
  <!-- OneSignal Notification Toast -->
  <div
    v-if="showNotification"
    class="fixed top-4 right-4 z-50 max-w-sm rounded-lg bg-blue-600 p-4 text-white shadow-lg transition-all duration-300"
    :class="{ 'translate-x-0 opacity-100': showNotification, 'translate-x-full opacity-0': !showNotification }"
  >
    <div class="flex items-start">
      <div class="flex-shrink-0">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
        </svg>
      </div>
      <div class="ml-3 flex-1">
        <p class="text-sm font-medium">{{ notificationMessage }}</p>
        <p v-if="notificationData" class="mt-1 text-xs opacity-90">
          {{ notificationData.user_name }} from {{ notificationData.company_name }}
        </p>
      </div>
      <button
        @click="hideNotification"
        class="ml-4 flex-shrink-0 text-white hover:text-blue-200 focus:outline-none"
      >
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

// OneSignal notification state
const showNotification = ref(false);
const notificationMessage = ref('');
const notificationData = ref(null);

// Show notification toast
const showNotificationToast = (message: string, data: any = null) => {
  notificationMessage.value = message;
  notificationData.value = data;
  showNotification.value = true;

  // Auto-hide after 5 seconds
  setTimeout(() => {
    hideNotification();
  }, 5000);
};

// Hide notification toast
const hideNotification = () => {
  showNotification.value = false;
};

// Initialize OneSignal event listeners
const initializeOneSignal = () => {
  // Check if OneSignal is available
  if (typeof window !== 'undefined' && (window as any).OneSignal) {
    const OneSignal = (window as any).OneSignal;

    // Listen for notification received (when app is in foreground)
    OneSignal.on('notificationDisplay', (event: any) => {
      // Show notification for any received notification
      showNotificationToast(
        event.title || 'New notification received',
        event.data || {}
      );
    });

    // Listen for notification received (when app is in background)
    OneSignal.on('notificationReceived', (event: any) => {
      // Show notification for any received notification
      showNotificationToast(
        event.title || 'New notification received',
        event.data || {}
      );
    });

    // Listen for notification click
    OneSignal.on('notificationClick', (event: any) => {
      // Navigate to the form page if specified
      if (event.data && event.data.web_url) {
        window.location.href = event.data.web_url;
      }
    });

    // Check subscription status and request if needed
    OneSignal.isPushNotificationsEnabled().then((isEnabled: boolean) => {
      if (!isEnabled) {
        OneSignal.registerForPushNotifications();
      }
    });

    // Set external user ID for better tracking
    OneSignal.setExternalUserId('web_user_' + Date.now());
  }
};

// Initialize on mount
onMounted(() => {
  // Wait for OneSignal to be ready
  const checkOneSignal = () => {
    if (typeof window !== 'undefined' && (window as any).OneSignal) {
      initializeOneSignal();
    } else {
      setTimeout(checkOneSignal, 500);
    }
  };

  checkOneSignal();
});
</script>
