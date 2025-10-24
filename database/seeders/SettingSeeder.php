<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Contact Settings
            [
                'key' => 'whatsapp_contact',
                'value' => '+1 (555) 123-4567',
                'type' => 'phone',
                'group' => 'contact',
                'description' => 'WhatsApp contact number for customer support',
            ],
            [
                'key' => 'contact_email',
                'value' => 'support@quickrental.com',
                'type' => 'email',
                'group' => 'contact',
                'description' => 'Email address for customer support',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1 (555) 123-4567',
                'type' => 'phone',
                'group' => 'contact',
                'description' => 'Phone number for customer support',
            ],

            // Payment Settings
            [
                'key' => 'paypal_link',
                'value' => 'https://paypal.me/quickrental',
                'type' => 'url',
                'group' => 'payment',
                'description' => 'PayPal payment link',
            ],
            [
                'key' => 'mobile_money_number',
                'value' => '+1 (555) 123-4567',
                'type' => 'phone',
                'group' => 'payment',
                'description' => 'Mobile money payment number',
            ],
            [
                'key' => 'payment_timeout_hours',
                'value' => '1',
                'type' => 'text',
                'group' => 'payment',
                'description' => 'Payment timeout in hours',
            ],

            // Confirmation Settings
            [
                'key' => 'confirmation_rules',
                'value' => 'Driver Requirements: We will provide a professional driver for your rental. Valid driver\'s license required for the provided driver. Minimum age: 21 years for the driver. Clean driving record preferred for the driver. Customer Responsibility: You are responsible for ensuring the driver does not consume alcohol or any prohibited substances during the trip. Any violation of this policy may result in immediate termination of the rental agreement.',
                'type' => 'textarea',
                'group' => 'confirmation',
                'description' => 'Terms and conditions for booking confirmation',
            ],
            [
                'key' => 'booking_confirmation_message',
                'value' => 'Your booking has been confirmed! You will receive a confirmation email shortly.',
                'type' => 'textarea',
                'group' => 'confirmation',
                'description' => 'Message shown when booking is confirmed',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::set(
                $setting['key'],
                $setting['value'],
                $setting['type'],
                $setting['group'],
                $setting['description']
            );
        }
    }
}
