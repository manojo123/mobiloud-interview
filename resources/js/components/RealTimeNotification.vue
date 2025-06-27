<template>
  <!-- Real-time Notification Toast -->
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

// Real-time notification state
const showNotification = ref(false);
const notificationMessage = ref('');
const notificationData = ref(null);
const lastSeenId = ref('');
let notificationCheckInterval: number | null = null;

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

// Check for new notifications
const checkForNotifications = async () => {
  try {
    const response = await fetch(`/notifications/latest?last_seen_id=${lastSeenId.value}`);
    const data = await response.json();

    if (data.type === 'new_registration' && data.id !== lastSeenId.value) {
      lastSeenId.value = data.id;
      showNotificationToast(data.message, data.data);
      console.log('New notification received:', data);
    }
  } catch (error) {
    console.error('Error checking for notifications:', error);
  }
};

// Initialize real-time notifications
onMounted(() => {
  // Check for notifications every 2 seconds
  notificationCheckInterval = setInterval(checkForNotifications, 2000);

  // Initial check
  checkForNotifications();
});

// Cleanup on unmount
onUnmounted(() => {
  if (notificationCheckInterval) {
    clearInterval(notificationCheckInterval);
  }
});
</script>
