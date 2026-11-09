<?php

namespace App\Filament\Resources\PrkGroupResource\Pages;

use App\Filament\Resources\PrkGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrkGroup extends EditRecord
{
    protected static string $resource = PrkGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}