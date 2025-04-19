<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Item;
use App\Models\User;

class TransactionCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $buyer;

    public function __construct(Item $item, User $buyer)
    {
        $this->item = $item;
        $this->buyer = $buyer;
    }

    public function build()
    {
        return $this->subject('取引が完了しました')
                    ->view('emails.transaction_completed');
    }
}
