<template>
    <Card class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <div class="px-3 py-3">
            <h1 class="text-center text-3xl text-gray-700 dark:text-white font-semibold mb-4">Eksport rekomendacji chatu</h1>

            <form @submit.prevent="submitForm" class="space-y-4">
                <div class="relative">
                    <label for="start-date" class="block text-sm font-medium dark:text:white">Od:</label>
                    <input id="start-date" type="date" v-model="startDate" :max="today"  class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 dark:focus:ring-indigo-300 focus:border-indigo-500 dark:focus:border-indigo-300 sm:text-sm pl-10">
                </div>

                <div class="relative">
                    <label for="end-date" class="block text-sm font-medium dark:text:white">Do:</label>
                    <input id="end-date" type="date" v-model="endDate" :max="today"  class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 dark:focus:ring-indigo-300 focus:border-indigo-500 dark:focus:border-indigo-300 sm:text-sm pl-10">
                </div>

                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium dark:text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-400 dark:hover:bg-indigo-500 dark:focus:ring-indigo-300">
                    Eksport
                </button>
            </form>
        </div>
    </Card>
</template>

<script setup>
import { ref, defineProps, watch } from 'vue';
import axios from 'axios';

const { card } = defineProps(['card']);

const startDate = ref('');
const endDate = ref('');
const today = ref(new Date().toISOString().split('T')[0]);

watch(startDate, (newStartDate) => {
    if (newStartDate > endDate.value) {
        endDate.value = newStartDate;
    }
});

watch(endDate, (newEndDate) => {
    if (newEndDate < startDate.value) {
        startDate.value = newEndDate;
    }
});

const submitForm = () => {
    // If the start date is not set, set it to a very early date
    if (!startDate.value) {
        startDate.value = '1900-01-01';
    }

    // If the end date is not set, set it to today
    if (!endDate.value) {
        endDate.value = today.value;
    }
    console.log('startDate', startDate.value);
    console.log('endDate', endDate.value);
    axios.post('/export', { from: startDate.value, to: endDate.value }, { responseType: 'blob' })
        .then(response => {
            startDate.value = '';
            endDate.value = '';

            // Create a blob URL for the zip file response
            const url = window.URL.createObjectURL(new Blob([response.data]));

            // Create a link element to trigger the download
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'chat_responses.zip');

            // Append the link to the document body and trigger the download
            document.body.appendChild(link);
            link.click();

            // Cleanup: Remove the link element
            document.body.removeChild(link);
        })
        .catch(error => {
            console.error('Error exporting chat responses:', error);
        });

    console.log('startDate', startDate.value);
    console.log('endDate', endDate.value);
};

console.log('card', card.export);
</script>
