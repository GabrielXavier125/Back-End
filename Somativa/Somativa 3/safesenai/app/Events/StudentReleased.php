<?php

namespace App\Events;

use App\Models\EarlyRelease;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentReleased
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly EarlyRelease $release
    ) {}
}
