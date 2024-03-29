<?php

namespace App\Events;

use App\Models\DatPhong;
use App\Models\Phong;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DatPhongCreated
{
    use Dispatchable, SerializesModels;

    protected $phongIds;
    protected $newStatus;

    /**
     * Create a new event instance.
     *
     * @param array $phongIds
     * @param string $newStatus
     * @return void
     */
    public function __construct(array $phongIds, string $newStatus)
    {
        $this->phongIds = $phongIds;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the room ids.
     *
     * @return array
     */
    public function getPhongIds()
    {
        return $this->phongIds;
    }

    /**
     * Get the new status.
     *
     * @return string
     */
    public function getNewStatus()
    {
        return $this->newStatus;
    }
}
