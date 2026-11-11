<?php

namespace App\Notifications;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ItemApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public Item $item)
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
        $tipeLabel = $this->item->tipe === 'hilang' ? 'kehilangan' : 'penemuan';

        return [
            'type'       => 'item_approved',
            'title'      => 'Laporan Anda Disetujui! 🎉',
            'message'    => "Laporan {$tipeLabel} barang \"{$this->item->judul}\" telah diverifikasi oleh admin dan kini tayang di portal publik.",
            'item_id'    => $this->item->id,
            'item_title' => $this->item->judul,
            'url'        => route('items.show', $this->item),
            'icon'       => 'check_circle',
        ];
    }
}
