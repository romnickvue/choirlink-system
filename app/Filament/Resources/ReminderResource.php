<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReminderResource\Pages;
use App\Models\Reminder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class ReminderResource extends Resource
{
    protected static ?string $model = Reminder::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Schedules & Activities';

    protected static ?string $navigationLabel = 'General Reminder / Tagubilin';

    protected static ?string $modelLabel = 'Reminder';

    protected static ?string $pluralModelLabel = 'General Reminders';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Reminder Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Reminder Title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('content')
                            ->label('Content / Tagubilin')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('priority')
                            ->options([
                                'Normal' => 'Normal',
                                'Important' => 'Important',
                                'Urgent' => 'Urgent',
                            ])
                            ->default('Normal')
                            ->required(),

                        Forms\Components\DatePicker::make('expiration_date')
                            ->label('Expiration Date')
                            ->native(false)
                            ->helperText('Optional. The reminder will no longer appear on the dashboard after this date.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Publication Settings')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'Draft' => 'Draft',
                                'Published' => 'Published',
                                'Archived' => 'Archived',
                            ])
                            ->default('Draft')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                $set(
                                    'published_at',
                                    $state === 'Published' ? now() : null
                                );
                            }),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Publication Date and Time')
                            ->seconds(false)
                            ->native(false)
                            ->visible(fn (Get $get): bool =>
                                $get('status') === 'Published'
                            ),
                    ])
                    ->columns(2),

                Forms\Components\Hidden::make('created_by')
                    ->default(fn () => auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Reminder')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Reminder $record): string =>
                        $record->priority . ' Priority'
                    ),

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Normal' => 'success',
                        'Important' => 'warning',
                        'Urgent' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Published' => 'success',
                        'Draft' => 'warning',
                        'Archived' => 'gray',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->placeholder('Not published'),

                Tables\Columns\TextColumn::make('expiration_date')
                    ->label('Expires')
                    ->date('M d, Y')
                    ->sortable()
                    ->placeholder('No expiration'),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->placeholder('Unknown'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Published' => 'Published',
                        'Archived' => 'Archived',
                    ]),

                SelectFilter::make('priority')
                    ->options([
                        'Normal' => 'Normal',
                        'Important' => 'Important',
                        'Urgent' => 'Urgent',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(10);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReminders::route('/'),
            'create' => Pages\CreateReminder::route('/create'),
            'edit' => Pages\EditReminder::route('/{record}/edit'),
        ];
    }
}
