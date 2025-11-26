# Brevo Email Service Setup

This guide will help you configure Brevo (formerly Sendinblue) for sending transactional emails in the Car Rental application.

## What is Brevo?

Brevo is an all-in-one email marketing and transactional email service that provides:
- SMTP relay for sending emails
- Email templates and design tools
- Real-time analytics and tracking
- High deliverability rates
- Generous free tier (300 emails/day)

## Setup Instructions

### 1. Create a Brevo Account

1. Go to [https://www.brevo.com/](https://www.brevo.com/)
2. Click "Sign Up" and create a free account
3. Verify your email address

### 2. Get Your API Keys

#### SMTP Key (for sending emails)
1. Log in to your Brevo dashboard
2. Go to **Settings** → **SMTP & API**
3. Click on **SMTP** tab
4. Click **Generate a new SMTP key** or copy your existing key
5. Save this key securely

#### API Key (for advanced features - optional)
1. In the same **SMTP & API** section
2. Click on **API Keys** tab
3. Click **Generate a new API key**
4. Give it a name (e.g., "Car Rental App")
5. Save this key securely

### 3. Configure The Laravel Application

Update your `.env` file with the following settings:

```env
# Mail Configuration for Brevo
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your-brevo-email@example.com
MAIL_PASSWORD=your-smtp-key-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="Rental Ghana"

# Brevo Keys
BREVO_API_KEY=your-api-key-here
BREVO_SMTP_KEY=your-smtp-key-here

# Contact Information
APP_WHATSAPP_NUMBER="+233 XX XXX XXXX"
APP_MOBILE_MONEY_NUMBER="+233 XX XXX XXXX"
```

**Important Notes:**
- `MAIL_USERNAME` should be your Brevo account email
- `MAIL_PASSWORD` should be your SMTP key (not your Brevo account password)
- `MAIL_FROM_ADDRESS` should be a verified sender email in Brevo

### 4. Verify Your Sender Domain (Recommended)

For better deliverability:

1. Go to **Senders & IP** → **Domains**
2. Click **Add a domain**
3. Enter your domain name
4. Follow the DNS configuration instructions
5. Wait for verification (usually takes a few minutes)

### 5. Test Your Configuration

Run this command to test email sending:

```bash
php artisan tinker
```

Then execute:

```php
Mail::raw('Test email from Car Rental App', function ($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});
```
```env
MAIL_PORT=2525  # Alternative port
```

Or use TLS on port 465:

```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

## Support

- **Brevo Documentation:** [https://developers.brevo.com/](https://developers.brevo.com/)
- **Brevo Support:** Available in dashboard under Help
