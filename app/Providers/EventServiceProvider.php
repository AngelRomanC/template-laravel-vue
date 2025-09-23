<?php

namespace App\Providers;

use App\Events\RecursamientoEvent;
use App\Listeners\RecursamientoListener;
use App\Events\RecursamientoEvent2;
use App\Listeners\RecursamientoListener2;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        RecursamientoEvent::class=>[
            RecursamientoListener::class,
        ],
        RecursamientoEvent2::class=>[
            RecursamientoListener2::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
