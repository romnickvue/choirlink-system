<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingActivities extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Upcoming Activities';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Activity::query()
                    ->where('status', 'Published')
                    ->whereDate('activity_date', '>=', today())
                    ->orderBy('activity_date')
                    ->orderBy('start_time')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Activity')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('activity_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('Start Time')
                    ->time('h:i A')
                    ->placeholder('Not specified'),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('End Time')
                    ->time('h:i A')
                    ->placeholder('Not specified'),

                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->placeholder('Not specified'),
            ])
            ->paginated(false)
            ->striped();
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No upcoming activities';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Published activities scheduled for today or later will appear here.';
    }
}
