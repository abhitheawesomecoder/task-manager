<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function afterSave(): void
    {
        if ($this->record->done) {
             $this->record->done_date = \Carbon\Carbon::now();
             $this->record->save();
        }else{
            $this->record->done_date = null;
            $this->record->save();
        }

    }
}
