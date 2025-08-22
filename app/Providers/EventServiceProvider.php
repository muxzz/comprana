<?php

namespace App\Providers;

use App\Events\CreateOrderEvent;
use App\Listeners\GenerateInvoiceListener;
use App\Listeners\SendEmailOrderListener;
use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use App\Observers\ImageObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
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
        CreateOrderEvent::class => [
            GenerateInvoiceListener::class,
            SendEmailOrderListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Image::observe(ImageObserver::class);
        Product::observe(ProductObserver::class);
        User::observe(UserObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
