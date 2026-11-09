<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberTrainingResource\Pages;
use App\Models\Member;
use App\Models\MemberTraining;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;

class MemberTrainingResource extends Resource
{
    protected static ?string $model = MemberTraining::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Nagsasanay';

    protected static ?string $navigationGroup = 'Member Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Trainee';

    protected static ?string $pluralModelLabel = 'Nagsasanay';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Trainee Information')
                ->description('Select the existing member who will undergo training.')
                ->schema([

                    Forms\Components\Select::make('member_id')
                        ->label('Member')
                        ->relationship(
                            name: 'member',
                            titleAttribute: 'last_name'
                        )
                        ->getOptionLabelFromRecordUsing(
                            fn (Member $record) => $record->full_name
                        )
                        ->searchable([
                            'first_name',
                            'middle_name',
                            'last_name',
                        ])
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('category')
                        ->label('Training Category')
                        ->options([
                            'Mang-aawit' => 'Mang-aawit',
                            'Organista' => 'Organista',
                        ])
                        ->required(),

                    Forms\Components\DatePicker::make('training_start_date')
                        ->label('Training Start Date')
                        ->required()
                        ->default(now())
                        ->maxDate(now())
                        ->native(false),

                    Forms\Components\TextInput::make('trainer')
                        ->label('Trainer / Responsible Person')
                        ->placeholder('Enter trainer name')
                        ->maxLength(255),

                ])
                ->columns(2),

            Section::make('Training Progress')
                ->description('Monitor the trainee’s current training stage and status.')
                ->schema([

                    Forms\Components\Select::make('progress_stage')
                        ->label('Progress Stage')
                        ->options([
                            'Newly registered' => 'Newly registered',
                            'Ongoing' => 'Ongoing',
                            'For evaluation' => 'For evaluation',
                            'Completed' => 'Completed',
                            'Discontinued' => 'Discontinued',
                        ])
                        ->required()
                        ->default('Newly registered')
                        ->live(),

                    Forms\Components\Select::make('status')
                        ->label('Training Status')
                        ->options([
                            'Active' => 'Active',
                            'Inactive' => 'Inactive',
                        ])
                        ->required()
                        ->default('Active'),

                    Forms\Components\DatePicker::make('completion_date')
                        ->label('Completion Date')
                        ->native(false)
                        ->visible(
                            fn (Get $get): bool =>
                                $get('progress_stage') === 'Completed'
                        )
                        ->required(
                            fn (Get $get): bool =>
                                $get('progress_stage') === 'Completed'
                        ),

                ])
                ->columns(2),

            Section::make('Additional Information')
                ->schema([

                    Forms\Components\Textarea::make('remarks')
                        ->label('Remarks / Progress Notes')
                        ->placeholder('Record observations, progress, or other important information.')
                        ->rows(4)
                        ->maxLength(2000)
                        ->columnSpanFull(),

                ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('member.last_name')
                    ->label('Trainee Name')
                    ->formatStateUsing(
                        fn ($state, MemberTraining $record) =>
                            $record->member?->full_name ?? 'Unknown Member'
                    )
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('progress_stage')
                    ->label('Progress')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Newly registered' => 'gray',
                        'Ongoing' => 'info',
                        'For evaluation' => 'warning',
                        'Completed' => 'success',
                        'Discontinued' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Training Status')
                    ->badge()
                    ->color(fn (string $state): string =>
                        $state === 'Active' ? 'success' : 'danger'
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('training_start_date')
                    ->label('Start Date')
                    ->date('M d, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('trainer')
                    ->label('Trainer')
                    ->placeholder('Not assigned')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('completion_date')
                    ->label('Completion Date')
                    ->date('M d, Y')
                    ->placeholder('—')
                    ->toggleable(),

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('category')
                    ->label('Training Category')
                    ->options([
                        'Mang-aawit' => 'Mang-aawit',
                        'Organista' => 'Organista',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Training Status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ]),

                Tables\Filters\SelectFilter::make('progress_stage')
                    ->label('Progress Stage')
                    ->options([
                        'Newly registered' => 'Newly registered',
                        'Ongoing' => 'Ongoing',
                        'For evaluation' => 'For evaluation',
                        'Completed' => 'Completed',
                        'Discontinued' => 'Discontinued',
                    ]),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('training_start_date', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(10);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemberTrainings::route('/'),
            'create' => Pages\CreateMemberTraining::route('/create'),
            'edit' => Pages\EditMemberTraining::route('/{record}/edit'),
        ];
    }
}