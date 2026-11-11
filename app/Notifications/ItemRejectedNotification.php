<?php

namespace App\Notifications;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ItemRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public Item $item, public ?string $alasan = null)
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
        $catatan   = filled($this->alasan) ? " Alasan: {$this->alasan}" : '';

        return [
            'type'       => 'item_rejected',
            'title'      => 'Laporan Anda Ditolak ⚠️',
            'message'    => "Laporan {$tipeLabel} barang \"{$this->item->judul}\" tidak dapat diterbitkan oleh admin.{$catatan}",
            'item_id'    => $this->item->id,
            'item_title' => $this->item->judul,
            'url'        => route('my.items'),
            'icon'       => 'x_circle',
        ];
    }
}
