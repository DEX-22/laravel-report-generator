<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('notifications.{id}', function ($id) {
    return $id === 1;
});
