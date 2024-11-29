@once
    <style>
        [x-cloak] { display: none !important; }
    </style>
@endonce

@push('scripts')
    <script>
        if (typeof Alpine === 'undefined') {
            console.error('Alpine.js is not loaded. Please include Alpine.js before the ai-chat.js script.');
        }
    </script>
@endpush

<div x-data="aiChat" x-cloak class="fixed right-4 bottom-4 z-50">
    <!-- Chat Container -->
    <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="bg-white flex flex-col rounded-md border shadow-lg w-[380px]"
        :class="isMinimized ? 'h-[400px]' : 'h-[600px]'">
        <!-- Chat Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <div class="flex gap-2 items-start">
                <div class="flex justify-center items-center h-8 rounded-full min-w-8 bg-blue-100">
                    <svg class="w-4 h-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z" />
                    </svg>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900">AI Assistant</h3>
                    <p class="text-sm text-gray-500">
                        Ask about anything including data of application.
                    </p>
                </div>
            </div>

            <div class="flex gap-1">
                <button @click="isMinimized = !isMinimized"
                    class="flex justify-center items-center w-8 h-8 rounded-md hover:bg-gray-100 text-gray-600">
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
                    class="flex justify-center items-center w-8 h-8 rounded-md hover:bg-gray-100 text-gray-600">
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
        <div class="overflow-y-auto flex-1 p-4 space-y-4 bg-gray-50" id="chat-messages">
            <template x-for="message in messages" :key="message.id">
                <div class="flex gap-3 items-start">
                    <div class="flex justify-center items-center w-8 h-8 rounded-full"
                        :class="message.role === 'assistant' ? 'bg-blue-100' : 'bg-gray-200'">
                        <template x-if="message.role === 'assistant'">
                            <svg class="w-4 h-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 8V4H8" />
                                <rect width="16" height="12" x="4" y="8" rx="2" />
                                <path d="M2 14h2" />
                                <path d="M20 14h2" />
                                <path d="M15 13v2" />
                                <path d="M9 13v2" />
                            </svg>
                        </template>
                        <template x-if="message.role === 'user'">
                            <svg class="w-4 h-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </template>
                    </div>
                    <div class="flex-1 p-2 space-y-2 rounded bg-white border">
                        <div class="max-w-none prose prose-sm">
                            <p class="text-sm font-bold text-gray-900" x-text="message.content"></p>
                            <p class="mt-1 text-xs text-gray-500" x-text="message.timestamp"></p>
                        </div>
                    </div>
                </div>
            </template>
            <div x-show="isLoading" class="flex justify-center items-center py-4">
                <div class="w-6 h-6 rounded-full border-b-2 animate-spin border-blue-600"></div>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="p-4 border-t bg-white">
            <form @submit.prevent="sendMessage" class="flex gap-2">
                <input type="text" x-model="newMessage"
                    class="flex px-3 py-1 w-full h-9 text-sm bg-white rounded-md border shadow-sm transition-colors text-gray-900 border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50"
                    placeholder="Type your message..." :disabled="isLoading" />
                <button type="submit"
                    class="inline-flex justify-center items-center w-9 h-9 text-sm font-medium whitespace-nowrap rounded-md shadow transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 bg-blue-600 text-white hover:bg-blue-700"
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
            class="inline-flex justify-center items-center w-10 h-10 text-sm font-medium whitespace-nowrap rounded-md shadow transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 bg-blue-600 text-white hover:bg-blue-700">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z" />
            </svg>
        </button>
    </div>
</div>