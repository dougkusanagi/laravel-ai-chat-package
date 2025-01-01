@once
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endonce

@push('scripts')
    <script>
        if (typeof Alpine === 'undefined') {
            console.error('Alpine.js is not loaded. Please include Alpine.js before the ai-chat.js script.');
        }
    </script>
@endpush
<div x-data="aiChat" x-cloak class="fixed z-50 bg-white right-4 bottom-4 dark:bg-background">
    <!-- Chat Container -->
    <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class=" flex flex-col rounded-md border border-gray-200 dark:border-gray-700 shadow-lg w-[380px]"
        :class="isMinimized ? 'h-[400px]' : 'h-[600px]'">
        <!-- Chat Header -->
        <div
            class="flex items-center justify-between p-4 bg-white border-b border-gray-200 dark:bg-background dark:border-gray-700">
            <div class="flex items-start gap-2">
                <div class="flex items-center justify-center h-8 bg-blue-100 rounded-full min-w-8 dark:bg-blue-900">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z" />
                    </svg>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">AI Assistant</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Ask about anything including data of application.
                    </p>
                </div>
            </div>

            <div class="flex gap-1">
                <button @click="isMinimized = !isMinimized"
                    class="flex items-center justify-center w-8 h-8 text-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-400">
                    <template x-if="!isMinimized">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 3v3a2 2 0 0 1-2 2H3" />
                            <path d="M21 8h-3a2 2 0 0 1-2-2V3" />
                            <path d="M3 16h3a2 2 0 0 1 2 2v3" />
                            <path d="M16 21v-3a2 2 0 0 1 2-2h3" />
                        </svg>
                    </template>
                    <template x-if="isMinimized">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h6v6" />
                            <path d="M9 21H3v-6" />
                            <path d="M21 3 9 15" />
                            <path d="M3 21 15 9" />
                        </svg>
                    </template>
                </button>
                <button @click="isOpen = false"
                    class="flex items-center justify-center w-8 h-8 text-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-400">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="flex-1 p-4 space-y-4 overflow-y-auto bg-gray-50 dark:bg-gray-900" id="chat-messages">
            <template x-for="message in messages" :key="message.id">
                <div class="flex items-start gap-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full"
                        :class="message.role === 'assistant' ? 'bg-blue-100 dark:bg-blue-900' : 'bg-gray-200 dark:bg-gray-700'">
                        <template x-if="message.role === 'assistant'">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 8V4H8" />
                                <rect width="16" height="12" x="4" y="8" rx="2" />
                                <path d="M2 14h2" />
                                <path d="M20 14h2" />
                                <path d="M15 13v2" />
                                <path d="M9 13v2" />
                            </svg>
                        </template>
                        <template x-if="message.role === 'user'">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </template>
                    </div>
                    <div
                        class="flex-1 p-2 space-y-2 bg-white border border-gray-200 rounded dark:bg-gray-800 dark:border-gray-700">
                        <div class="prose-sm prose max-w-none dark:prose-invert">
                            <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="message.content"></p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-text="message.timestamp"></p>
                        </div>
                    </div>
                </div>
            </template>
            <div x-show="isLoading" class="flex items-center justify-center py-4">
                <div class="w-6 h-6 border-b-2 border-blue-600 rounded-full animate-spin dark:border-blue-400"></div>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="p-4 bg-white border-t border-gray-200 dark:border-gray-700 dark:bg-gray-800">
            <form @submit.prevent="sendMessage" class="flex gap-2">
                <input type="text" x-model="newMessage"
                    class="flex w-full px-3 py-1 text-sm text-gray-900 transition-colors bg-white border border-gray-300 rounded-md shadow-sm h-9 dark:bg-gray-900 dark:text-white dark:border-gray-700 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-400 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
                    placeholder="Type your message..." :disabled="isLoading" />
                <button type="submit"
                    class="inline-flex items-center justify-center text-sm font-medium text-white transition-colors bg-blue-600 rounded-md shadow w-9 h-9 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 disabled:opacity-50 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600"
                    :disabled="isLoading">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m22 2-7 20-4-9-9-4Z" />
                        <path d="M22 2 11 13" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Chat Toggle Button -->
    <div x-show="!isOpen">
        <button @click="isOpen = true"
            class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-white transition-colors bg-blue-600 rounded-md shadow whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 disabled:opacity-50 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z" />
            </svg>
        </button>
    </div>
</div>
