<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\MemberTraining;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ChoirStatistics extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Total member records
        $totalMembers = Member::count();

        // Gender totals
        $maleMembers = Member::where('gender', 'Male')->count();

        $femaleMembers = Member::where('gender', 'Female')->count();

        // Member status totals
        $activeMembers = Member::where('status', 'Active')->count();

        $inactiveMembers = Member::where('status', 'Inactive')->count();

        // Active trainee totals
        $activeTrainees = MemberTraining::where('status', 'Active')->count();

        $mangAawitTrainees = MemberTraining::where('status', 'Active')
            ->where('category', 'Mang-aawit')
            ->count();

        $organistaTrainees = MemberTraining::where('status', 'Active')
            ->where('category', 'Organista')
            ->count();

        return [

            Stat::make('Total Mang-aawit', $totalMembers)
                ->description(
                    "Male: {$maleMembers} | Female: {$femaleMembers}"
                )
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Active Mang-aawit', $activeMembers)
                ->description('Currently active members')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Inactive Mang-aawit', $inactiveMembers)
                ->description('Currently inactive members')
                ->descriptionIcon('heroicon-m-user-minus')
                ->color('danger'),

            Stat::make('Nagsasanay', $activeTrainees)
                ->description(
                    "Mang-aawit: {$mangAawitTrainees} | Organista: {$organistaTrainees}"
                )
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),

        ];
    }
}