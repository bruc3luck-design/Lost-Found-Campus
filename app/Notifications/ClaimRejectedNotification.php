<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClaimRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public Claim $claim, public ?string $reason = null)
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
        $reasonText = $this->reason ? " Catatan: {$this->reason}" : '';

        return [
            'type'       => 'claim_rejected',
            'title'      => 'Klaim Ditolak',
            'message'    => "Pengajuan klaim Anda untuk \"{$item->judul}\" belum disetujui.{$reasonText}",
            'claim_id'   => $this->claim->id,
            'item_id'    => $item->id,
            'item_title' => $item->judul,
            'url'        => route('my.history', ['tab' => 'klaim']),
            'icon'       => 'x_circle',
        ];
    }
}
