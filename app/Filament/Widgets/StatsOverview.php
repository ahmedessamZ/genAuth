<?php

namespace App\Filament\Widgets;

use App\Enums\UserStatusEnum;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users',User::query()->count() )
                ->description(' ')->color('primary')
                ->descriptionIcon('heroicon-s-user-group', IconPosition::After),

            Stat::make('Active Users',User::query()->where('status',UserStatusEnum::ACTIVE)->count() )
                ->description(' ')->color('success')
                ->descriptionIcon('heroicon-m-user-plus', IconPosition::Before),

            Stat::make('Suspended Users',User::query()->where('status',UserStatusEnum::INACTIVE)->count() )
                ->description(' ')->color('danger')
                ->descriptionIcon('heroicon-m-user-minus', IconPosition::Before),

            Stat::make('Pending Users',User::query()->where('status',UserStatusEnum::PENDING)->count() )
                ->description(' ')->color('info')
                ->descriptionIcon('heroicon-m-user-minus', IconPosition::Before),
        ];
    }
}
