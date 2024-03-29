<?php

namespace App\Listeners;

use App\Events\DatPhongCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Phong;

class UpdateRoomStatus implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  ChangeRoomStatus  $event
     * @return void
     */

    public function handle(DatPhongCreated $event)
    {
        $phongIds = $event->getPhongIds();
        $newStatus = $event->getNewStatus();

        // Update room status
        Phong::whereIn('id', $phongIds)->update(['trang_thai' => $newStatus]);
    }
}
