// Wait for Alpine and DOM to be ready
document.addEventListener('alpine:init', () => {
    Alpine.data('aiChat', () => ({
        init() {
            console.log('AI Chat initialized'); // Debug log
            this.isOpen = true;
            this.isMinimized = false;
            this.isLoading = false;
        },
        isOpen: true,
        isMinimized: false,
        isLoading: false,
        messages: [{
            id: 1,
            role: 'assistant',
            content: 'Welcome! How can we help you today?',
            timestamp: new Date().toLocaleString()
        }],
        newMessage: '',

        async sendMessage() {
            if (!this.newMessage.trim() || this.isLoading) return;

            const messageId = Date.now();
            this.messages.push({
                id: messageId,
                role: 'user',
                content: this.newMessage,
                timestamp: new Date().toLocaleString()
            });

            const message = this.newMessage;
            this.newMessage = '';
            this.isLoading = true;

            try {
                const response = await this.callAiChat(message);

                if (!response.message) {
                    throw new Error('Invalid response from server');
                }

                // Always show the first response
                this.addAssistantMessage(response);

                // Only make second call if there's a SQL query and it's not null
                if (response.query && response.query !== 'null') {
                    this.isLoading = true;
                    const secondResponse = await this.callAiChat(message, response.query);

                    if (!secondResponse.message) {
                        throw new Error('Invalid response from server');
                    }

                    this.addAssistantMessage(secondResponse);
                }
            } catch (error) {
                console.error('Error:', error);
                this.messages.push({
                    id: Date.now(),
                    role: 'assistant',
                    content: error.message ||
                        'Sorry, an error occurred while processing your request.',
                    timestamp: new Date().toLocaleString()
                });
            } finally {
                this.isLoading = false;
                this.$nextTick(() => {
                    const container = document.getElementById('chat-messages');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
            }
        },

        async callAiChat(message, query = null) {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (!token) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch('/api/ai-chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token.content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message,
                    query,
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Network response was not ok');
            }

            return data;
        },

        addAssistantMessage(response) {
            if (response && response.message) {
                this.messages.push({
                    id: Date.now(),
                    role: 'assistant',
                    content: response.message,
                    timestamp: new Date().toLocaleString()
                });
            }
        }
    }));
});