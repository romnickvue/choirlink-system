<?php

namespace App\Filament\Widgets;

use App\Models\Reminder;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestReminders extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest Reminders / Tagubilin';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Reminder::query()
                    ->where('status', 'Published')
                    ->where(function ($query) {
                        $query->whereNull('expiration_date')
                            ->orWhereDate('expiration_date', '>=', today());
                    })
                    ->orderByDesc('published_at')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Reminder')
                    ->weight('bold')
                    ->searchable()
                    ->description(function (Reminder $record): string {
                        return strip_tags($record->content);
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Normal' => 'success',
                        'Important' => 'warning',
                        'Urgent' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->placeholder('Not specified'),
            ])
            ->paginated(false)
            ->striped();
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No published reminders';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Published and unexpired reminders will appear here.';
    }
}
