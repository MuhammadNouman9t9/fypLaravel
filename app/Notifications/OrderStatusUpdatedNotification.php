<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification
{
    public function __construct(
        public Order $order,
        public string $oldStatus,
        public string $newStatus
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
        $statusMessages = [
            'pending' => 'Your order is pending and awaiting confirmation.',
            'processing' => 'Your order is being processed and will be shipped soon.',
            'shipped' => 'Your order has been shipped! You can track it using the tracking number below.',
            'delivered' => 'Your order has been delivered! We hope you enjoy your purchase.',
            'cancelled' => 'Your order has been cancelled. If you have any questions, please contact our support team.',
        ];

        $message = (new MailMessage)
            ->subject('Order Status Update - '.$this->order->order_number)
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your order status has been updated.')
            ->line('**Order Number:** '.$this->order->order_number)
            ->line('**Previous Status:** '.ucfirst($this->oldStatus))
            ->line('**New Status:** '.ucfirst($this->newStatus))
            ->line($statusMessages[$this->newStatus] ?? 'Your order status has been updated.');

        // Add tracking information if order is shipped
        if ($this->newStatus === 'shipped' && $this->order->shipments->isNotEmpty()) {
            $shipment = $this->order->shipments->first();
            $message->line('**Tracking Number:** '.$shipment->tracking_number);
            if ($shipment->expected_delivery_at) {
                $message->line('**Expected Delivery:** '.$shipment->expected_delivery_at->format('M d, Y'));
            }
        }

        $message->action('View Order Details', route('orders.show', $this->order))
            ->line('Thank you for shopping with us!')
            ->salutation('Regards, '.config('app.name'));

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
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => "Your order #{$this->order->order_number} status has been updated from ".ucfirst($this->oldStatus).' to '.ucfirst($this->newStatus).'.',
        ];
    }
}
