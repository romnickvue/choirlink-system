<?php

namespace App\Filament\Resources\PrkGroupResource\Pages;

use App\Filament\Resources\PrkGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrkGroups extends ListRecords
{
    protected static string $resource = PrkGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}