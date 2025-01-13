<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RedemptionTransactionApprovalNoti extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    protected $redemption_transaction_uuid;
    protected $document_no;
    protected $branchname;
    protected $installer_card_card_number;
    protected $total_points_redeemed;
    protected $total_cash_value;
    protected $status;
    protected $prepare_byname;
    public function __construct($transaction)
    {
        // Fetch the user who prepared the transaction
        $preparebyUuid = $transaction->prepare_by; // Single value, not a collection
        $prepareByUser = User::where('uuid', $preparebyUuid)->first();
        // Assign the user model to the `prepareby` attribute
        $transaction->prepareby = $prepareByUser;

        $approvedbyUuid = $transaction->approved_by; // Single value, not a collection
        $approvedByUser = User::where('uuid', $approvedbyUuid)->first();
        // Assign the user model to the `prepareby` attribute
        $transaction->approvedby = $approvedByUser;

        $paidbybyUuid = $transaction->paid_by; // Single value, not a collection
        $paidbyByUser = User::where('uuid', $paidbybyUuid)->first();
        // Assign the user model to the `prepareby` attribute
        $transaction->paidby = $paidbyByUser;


        $this->redemption_transaction_uuid = $transaction->uuid;
        $this->document_no = $transaction->document_no;
        $this->branchname = $transaction->branch->branch_name_eng;
        $this->installer_card_card_number = $transaction->installer_card_card_number;
        $this->total_points_redeemed = $transaction->total_points_redeemed;
        $this->total_cash_value = $transaction->total_cash_value;
        $this->status = $transaction->status;
        $this->prepare_byname = $transaction->prepareby->name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            "redemption_transaction_uuid" => $this->redemption_transaction_uuid,
            "document_no" => $this->document_no,
            "branchname" => $this->branchname,
            "installer_card_card_number" => $this->installer_card_card_number,
            "total_points_redeemed" => $this->total_points_redeemed,
            "total_cash_value" => $this->total_cash_value,
            "status" => $this->status,
            "prepare_byname" => $this->prepare_byname
        ];
    }
}
