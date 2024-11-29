# Laravel AI Chat

## Installation

Install the package via Composer:

```bash
composer require dougkusanagi/laravel-ai-chat
```

Publish the configuration file and assets:

```bash
php artisan vendor:publish --tag=ai-chat-config
php artisan vendor:publish --tag=ai-chat-assets
php artisan vendor:publish --tag=ai-chat-views
```

Set your Gemini API key in your .env file:

```env
GEMINI_API_KEY=your_api_key_here
```

## Usage

First, include Alpine.js and the AI Chat JavaScript in your layout file (usually `app.blade.php`):

```blade
<!DOCTYPE html>
<html>
<head>
    <!-- ... other head elements ... -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Include Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Include AI Chat JavaScript -->
    <script src="{{ asset('vendor/ai-chat/js/ai-chat.js') }}"></script>
</head>
<body>
    <!-- Your content -->
    @yield('content')
</body>
</html>
```

Then, add the chat component where you want it to appear:

```blade
<x-ai-chat::ai-floating-chat />
```

The chat component will appear as a floating button in the bottom-right corner of your page. When clicked, it will expand into a full chat interface where users can interact with the AI assistant.

## Customization

If you want to customize the chat interface, you can publish the views:

```bash
php artisan vendor:publish --tag=ai-chat-views
```

This will copy the blade component to `resources/views/vendor/ai-chat/components/ai-floating-chat.blade.php` in your application, where you can modify it to match your needs.
