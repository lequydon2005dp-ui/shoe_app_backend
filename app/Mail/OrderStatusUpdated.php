<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load('user');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Trạng thái đơn hàng #' . $this->order->id . ' đã cập nhật!',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.status_updated',
            with: ['order' => $this->order],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
