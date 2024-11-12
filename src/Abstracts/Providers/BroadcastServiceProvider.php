<?php

namespace Apiato\Core\Abstracts\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider as LaravelBroadcastServiceProvider;

abstract class BroadcastServiceProvider extends LaravelBroadcastServiceProvider
{
    public function boot()
    {
        Broadcast::routes();

        require app_path('Ship/Broadcasts/channels.php');
    }
}
