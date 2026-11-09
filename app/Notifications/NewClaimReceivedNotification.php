<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewClaimReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(public Claim $claim)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $item = $this->claim->item;
        $tipeLabel = $item->tipe === 'hilang' ? 'barang hilang' : 'barang temuan';

        return [
            'type'         => 'claim_received',
            'title'        => 'Klaim Masuk Baru',
            'message'      => "{$this->claim->claimer->name} mengajukan klaim untuk {$tipeLabel}: \"{$item->judul}\".",
            'claim_id'     => $this->claim->id,
            'item_id'      => $item->id,
            'item_title'   => $item->judul,
            'claimer_name' => $this->claim->claimer->name,
            'url'          => route('claims.incoming', $item->id),
            'icon'         => 'claim_in',
        ];
    }
}
