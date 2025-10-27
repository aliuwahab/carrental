<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

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
        return (new MailMessage)
            ->subject('Booking Confirmed - ' . $this->booking->booking_code)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your booking has been confirmed and payment has been verified.')
            ->line('Booking Details:')
            ->line('Booking ID: ' . $this->booking->booking_code)
            ->line('Vehicle: ' . $this->booking->vehicle->name)
            ->line('Start Date: ' . $this->booking->start_date->format('M d, Y'))
            ->line('End Date: ' . $this->booking->end_date->format('M d, Y'))
            ->line('Total Amount: $' . number_format($this->booking->total_amount, 2))
            ->action('View Booking', route('dashboard'))
            ->line('Thank you for choosing Rental Ghana!');
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
            'start_date' => $this->booking->start_date->format('M d, Y'),
            'end_date' => $this->booking->end_date->format('M d, Y'),
            'total_amount' => $this->booking->total_amount,
            'message' => 'Your booking has been confirmed and payment has been verified.',
        ];
    }
}
