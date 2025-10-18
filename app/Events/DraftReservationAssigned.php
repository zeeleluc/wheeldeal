<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DraftReservationAssigned
{
    use Dispatchable;
    use SerializesModels;

    public User $user;
    public int $draftId;

    public function __construct(User $user, int $draftId)
    {
        $this->user = $user;
        $this->draftId = $draftId;
    }
}
