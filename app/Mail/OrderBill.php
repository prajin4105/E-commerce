<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderBill extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdf;

    public function __construct(Order $order, $pdf = null)
    {
        $this->order = $order;
        $this->pdf = $pdf;
    }

    public function build()
    {
        $mail = $this->subject('Your Order Bill - #' . $this->order->order_number)
                    ->view('emails.order-bill');

        if ($this->pdf) {
            $mail->attachData(
                $this->pdf->output(),
                'order-bill-' . $this->order->order_number . '.pdf',
                ['mime' => 'application/pdf']
            );
        }

        return $mail;
    }
} 