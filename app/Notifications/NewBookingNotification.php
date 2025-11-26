<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Booking $booking
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
        $user = $this->booking->user;
        
        return (new MailMessage)
            ->subject('New Booking Created - ' . $this->booking->booking_code)
            ->greeting('Hello Admin,')
            ->line('A new booking has been created and is awaiting payment confirmation.')
            ->line('**Booking Details:**')
            ->line('Booking ID: ' . $this->booking->booking_code)
            ->line('Customer: ' . $user->name . ' (' . $user->email . ')')
            ->line('Vehicle: ' . $vehicle->name)
            ->line('Start Date: ' . $this->booking->start_date->format('M d, Y'))
            ->line('End Date: ' . $this->booking->end_date->format('M d, Y'))
            ->line('Total Amount: $' . number_format($this->booking->total_amount, 2))
            ->line('Status: ' . ucfirst($this->booking->status))
            ->line('---')
            ->line('**Action Required:**')
            ->line('The customer has been notified to complete payment within 1 hour.')
            ->line('Please monitor for payment and confirm the booking once payment is verified.')
            ->action('View Booking', url('/admin/bookings/' . $this->booking->id))
            ->line('The booking will automatically expire if payment is not received within the time limit.');
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
            'customer_name' => $this->booking->user->name,
            'customer_email' => $this->booking->user->email,
            'vehicle_name' => $this->booking->vehicle->name,
            'total_amount' => $this->booking->total_amount,
            'status' => $this->booking->status,
            'start_date' => $this->booking->start_date->toDateString(),
            'end_date' => $this->booking->end_date->toDateString(),
        ];
    }
}
