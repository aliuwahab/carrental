<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingPaymentInstructions extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Booking $booking,
        public bool $isNewAccount = false
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $vehicle = $this->booking->vehicle;
        $whatsappNumber = config('app.whatsapp_number', '+1 (555) 123-4567');
        $mobileMoneyNumber = config('app.mobile_money_number', '+1 (555) 123-4567');
        
        $message = (new MailMessage)
            ->subject('Complete Your Booking Payment - ' . $this->booking->booking_code)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your booking has been created successfully!');

        if ($this->isNewAccount) {
            $message->line('📧 **An account has been created for you.** Check your email for login details.');
        }

        $message->line('**Booking Created Successfully!**')
            ->line('Your booking ID is: **' . $this->booking->booking_code . '**')
            ->line('')
            ->line('**Booking Details:**')
            ->line('Vehicle: ' . $vehicle->name)
            ->line('Start Date: ' . $this->booking->start_date->format('M d, Y'))
            ->line('End Date: ' . $this->booking->end_date->format('M d, Y'))
            ->line('Rental Days: ' . $this->booking->rental_days)
            ->line('**Payment Amount: $' . number_format($this->booking->total_amount, 2) . '**')
            ->line('')
            ->line('---')
            ->line('')
            ->line('**Payment Options:**')
            ->line('')
            ->line('**Mobile Money**')
            ->line('📱 Send **$' . number_format($this->booking->total_amount, 2) . '** to: ' . $mobileMoneyNumber)
            ->line('Reference: **' . $this->booking->booking_code . '**')
            ->line('')
            ->action('Complete Payment', route('booking.create', $vehicle->slug) . '?booking_id=' . $this->booking->id . '&step=3')
            ->line('')
            ->line('---')
            ->line('')
            ->line('**⚠️ Important Payment Instructions:**')
            ->line('• Use this booking code as your payment reference: **' . $this->booking->booking_code . '**')
            ->line('• Payment must be completed within **1 hour** to confirm your reservation')
            ->line('• After 1 hour, your booking will be automatically cancelled and the vehicle will be available for others')
            ->line('• You will receive a confirmation email once payment is verified')
            ->line('• Contact us via WhatsApp at ' . $whatsappNumber . ' if you have any payment issues')
            ->line('')
            ->action('View My Bookings', route('dashboard'))
            ->line('Thank you for choosing ' . config('app.name') . '!');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'vehicle_name' => $this->booking->vehicle->name,
            'total_amount' => $this->booking->total_amount,
            'status' => $this->booking->status,
            'expires_at' => $this->booking->expires_at?->toDateTimeString(),
            'is_new_account' => $this->isNewAccount,
            'message' => 'Please complete payment to confirm your reservation.',
        ];
    }
}
