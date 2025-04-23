<?php

namespace App\Providers;

use App\Broadcasting\WhatsappChannel;
use App\Repositories\Interfaces\UploadsRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UploadsRepository;
use App\Repositories\UserRepository;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UploadsRepositoryInterface::class, UploadsRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('whatsapp', function () {
                return new WhatsappChannel();
            });
        });
    }
}
