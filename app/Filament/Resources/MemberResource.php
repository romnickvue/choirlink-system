<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Mang-aawit';

    protected static ?string $navigationGroup = 'Member Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Mang-aawit';

    protected static ?string $pluralModelLabel = 'Mang-aawit';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('middle_name')
                            ->label('Middle Name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('suffix')
                            ->label('Suffix')
                            ->placeholder('Jr., Sr., III, etc.')
                            ->maxLength(50),

                        Forms\Components\Select::make('gender')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                            ])
                            ->required(),

                        Forms\Components\DatePicker::make('birthday')
                            ->label('Date of Birth')
                            ->required()
                            ->maxDate(now())
                            ->native(false),

                        Forms\Components\TextInput::make('contact_number')
                            ->label('Contact Number')
                            ->tel()
                            ->maxLength(30),
                    ])
                    ->columns(2),

                Section::make('Choir Information')
                    ->schema([
                        Forms\Components\Select::make('prk_group_id')
                            ->label('Prk.-Grp.')
                            ->relationship('prkGroup', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Forms\Components\Select::make('tungkulin')
                            ->label('Tungkulin')
                            ->options([
                                'Mang-aawit' => 'Mang-aawit',
                                'Organista' => 'Organista',
                            ])
                            ->required()
                            ->default('Mang-aawit'),

                        Forms\Components\Select::make('status')
                            ->label('Membership Status')
                            ->options([
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                            ])
                            ->required()
                            ->default('Active'),

                        Forms\Components\DatePicker::make('date_registered')
                            ->label('Date Registered')
                            ->required()
                            ->default(now())
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make('Profile Photo')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_photo')
                            ->label('Upload Photo')
                            ->image()
                            ->disk('public')
                            ->directory('members/photos')
                            ->maxSize(2048)
                            ->imageEditor()
                            ->nullable(),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('remarks')
                            ->label('Remarks')
                            ->rows(4)
                            ->maxLength(2000),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Personal Information')
                    ->schema([
                        ImageEntry::make('profile_photo')
                            ->label('Profile Photo')
                            ->disk('public')
                            ->circular(),

                        TextEntry::make('full_name')
                            ->label('Full Name')
                            ->weight('bold'),

                        TextEntry::make('gender')
                            ->label('Gender'),

                        TextEntry::make('birthday')
                            ->label('Date of Birth')
                            ->date('F d, Y'),

                        TextEntry::make('age')
                            ->label('Age')
                            ->state(fn (Member $record) => $record->age),

                        TextEntry::make('contact_number')
                            ->label('Contact Number')
                            ->placeholder('Not provided'),
                    ])
                    ->columns(2),

                InfoSection::make('Choir Information')
                    ->schema([
                        TextEntry::make('prkGroup.name')
                            ->label('Prk.-Grp.')
                            ->placeholder('Unassigned'),

                        TextEntry::make('tungkulin')
                            ->label('Tungkulin')
                            ->badge(),

                        TextEntry::make('status')
                            ->label('Membership Status')
                            ->badge()
                            ->color(fn (string $state): string =>
                                $state === 'Active' ? 'success' : 'danger'
                            ),

                        TextEntry::make('date_registered')
                            ->label('Date Registered')
                            ->date('F d, Y'),
                    ])
                    ->columns(2),

                InfoSection::make('Additional Information')
                    ->schema([
                        TextEntry::make('remarks')
                            ->label('Remarks')
                            ->placeholder('No remarks recorded')
                            ->columnSpanFull(),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->label('Photo')
                    ->disk('public')
                    ->circular(),

                Tables\Columns\TextColumn::make('last_name')
                    ->label('Full Name')
                    ->formatStateUsing(fn (Member $record) => $record->full_name)
                    ->searchable([
                        'first_name',
                        'middle_name',
                        'last_name',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('age')
                    ->label('Age')
                    ->state(fn (Member $record) => $record->age)
                    ->sortable(false),

                Tables\Columns\TextColumn::make('prkGroup.name')
                    ->label('Prk.-Grp.')
                    ->placeholder('Unassigned')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tungkulin')
                    ->label('Tungkulin')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string =>
                        $state === 'Active' ? 'success' : 'danger'
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('date_registered')
                    ->label('Date Registered')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ]),

                Tables\Filters\SelectFilter::make('prk_group_id')
                    ->label('Prk.-Grp.')
                    ->relationship('prkGroup', 'name'),

                Tables\Filters\SelectFilter::make('tungkulin')
                    ->options([
                        'Mang-aawit' => 'Mang-aawit',
                        'Organista' => 'Organista',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('last_name')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(10);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
