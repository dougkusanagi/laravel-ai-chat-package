# Laravel AI Chat

## Installation

Install the package via Composer:

```bash
composer require dougkusanagi/laravel-ai-chat
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=ai-chat-config
```

Set your Gemini API key in your .env file:

```env
GEMINI_API_KEY=your_api_key_here
```

## Usage

The chat component will be automatically available in your blade views. Simply include it where you want the chat to appear:

```blade
<x-ai-chat::ai-floating-chat />
```

Make sure you have Alpine.js and Tailwind CSS installed in your application, as they are required for the chat interface to work properly.

Add the following to your layout file (usually `app.blade.php`) in the head section:

```blade
<meta name="csrf-token" content="{{ csrf_token() }}">
```

The chat component will appear as a floating button in the bottom-right corner of your page. When clicked, it will expand into a full chat interface where users can interact with the AI assistant.

## Customization

If you want to customize the chat interface, you can publish the views:

```bash
php artisan vendor:publish --tag=ai-chat-views
```

This will copy the blade component to `resources/views/vendor/ai-chat/components/ai-floating-chat.blade.php` in your application, where you can modify it to match your needs.
