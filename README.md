# Telegram API Proxy

This PHP script serves as a simple proxy for Telegram API requests. It's useful for accessing Telegram in countries where access to Telegram servers is blocked. Ensure that your server is located outside of your country for effective use.

### Usage:
Simply replace `https://api.telegram.org/` with `https://your-domain/proxy-path/index.php/` in all API requests.
For example, `getWebhookInfo` would be transformed into:

```
https://www.example.com/telegram-proxy/index.php/bot123456789:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA/getWebhookInfo
```

Ensure that you replace `www.example.com` with your actual domain and adjust `telegram-proxy` to the correct path where the `index.php` file is hosted.