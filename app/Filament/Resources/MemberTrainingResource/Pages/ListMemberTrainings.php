<?php

namespace App\Filament\Resources\MemberTrainingResource\Pages;

use App\Filament\Resources\MemberTrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberTrainings extends ListRecords
{
    protected static string $resource = MemberTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Register Trainee'),
        ];
    }
}