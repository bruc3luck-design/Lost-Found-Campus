<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use App\Notifications\ClaimApprovedNotification;
use App\Notifications\ClaimRejectedNotification;
use App\Notifications\NewClaimReceivedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $claimer;
    private Item $item;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->owner = User::where('username', '2281050030')->first();
        $this->claimer = User::where('username', '2281050031')->first();

        $cat = Category::first();
        $loc = Location::where('is_pickup_point', false)->first();

        $this->item = Item::create([
            'user_id'     => $this->owner->id,
            'category_id' => $cat->id,
            'location_id' => $loc->id,
            'tipe'        => 'hilang',
            'judul'       => 'Laptop Asus ROG Hilang',
            'deskripsi'   => 'Tertinggal di ruang baca lantai 2',
            'tanggal'     => now(),
            'status'      => 'hilang',
        ]);
    }

    public function test_authenticated_user_can_access_notifications_page(): void
    {
        $response = $this->actingAs($this->owner)->get(route('notifications.index'));

        $response->assertOk();
        $response->assertSee('Notifikasi');
        $response->assertSee('Belum Ada Notifikasi');
    }

    public function test_owner_receives_notification_when_claim_is_submitted(): void
    {
        Notification::fake();

        $this->actingAs($this->claimer)->post(route('claims.store', $this->item), [
            'bukti' => 'Saya menemukan laptop tersebut dan menyimpannya di tas.',
        ]);

        Notification::assertSentTo(
            $this->owner,
            NewClaimReceivedNotification::class,
            function ($notification) {
                return $notification->claim->item_id === $this->item->id;
            }
        );
    }

    public function test_claimer_receives_notification_when_claim_is_approved(): void
    {
        Notification::fake();

        $claim = Claim::create([
            'item_id'    => $this->item->id,
            'claimer_id' => $this->claimer->id,
            'bukti'      => 'Bukti nomor seri laptop cocok.',
            'status'     => 'menunggu',
        ]);

        $this->actingAs($this->owner)->post(route('claims.approve', $claim), [
            'catatan' => 'Bukti sesuai, terima kasih!',
        ]);

        Notification::assertSentTo(
            $this->claimer,
            ClaimApprovedNotification::class
        );
    }

    public function test_claimer_receives_notification_when_claim_is_rejected(): void
    {
        Notification::fake();

        $claim = Claim::create([
            'item_id'    => $this->item->id,
            'claimer_id' => $this->claimer->id,
            'bukti'      => 'Bukti tidak jelas.',
            'status'     => 'menunggu',
        ]);

        $this->actingAs($this->owner)->post(route('claims.reject', $claim), [
            'catatan' => 'Ciri-ciri fisik tidak cocok.',
        ]);

        Notification::assertSentTo(
            $this->claimer,
            ClaimRejectedNotification::class
        );
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $claim = Claim::create([
            'item_id'    => $this->item->id,
            'claimer_id' => $this->claimer->id,
            'bukti'      => 'Bukti sah.',
            'status'     => 'menunggu',
        ]);

        $this->owner->notify(new NewClaimReceivedNotification($claim));

        $this->assertEquals(1, $this->owner->unreadNotifications()->count());

        $response = $this->actingAs($this->owner)->post(route('notifications.markAllRead'));

        $response->assertRedirect();
        $this->assertEquals(0, $this->owner->fresh()->unreadNotifications()->count());
    }

    public function test_user_can_mark_single_notification_as_read_and_redirect(): void
    {
        $claim = Claim::create([
            'item_id'    => $this->item->id,
            'claimer_id' => $this->claimer->id,
            'bukti'      => 'Bukti sah.',
            'status'     => 'menunggu',
        ]);

        $this->owner->notify(new NewClaimReceivedNotification($claim));

        $notification = $this->owner->unreadNotifications()->first();
        $this->assertNotNull($notification);

        $response = $this->actingAs($this->owner)->get(route('notifications.read', $notification->id));

        $response->assertRedirect(route('claims.incoming', $this->item->id));
        $this->assertEquals(0, $this->owner->fresh()->unreadNotifications()->count());
    }
}
