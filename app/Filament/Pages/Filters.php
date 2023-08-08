<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\User;
use App\Models\Filter;
use App\Models\FilterUser;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use Filament\Pages\Concerns;
use Filament\Pages\Actions\Action;
use Filament\Forms\ComponentContainer;
use Filament\Notifications\Notification;
use Filament\Pages\Contracts\HasFormActions;

class Filters extends Page implements HasFormActions
{
    use Concerns\HasFormActions;

    protected static string $settings;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.filters';

    public $data;

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

         $settings_with_value = Filter::with(['values' => function($q){
            $q->where('filter_user.user_id', auth()->user()->id);
        }])->get();

        $settings = array();
        
        foreach ($settings_with_value as $setting) {
              if($setting->values->isEmpty())
                $settings[$setting->name] = null;      
              else
                $settings[$setting->name] = intval($setting->values[0]->payload);      
        }
        $data = $this->mutateFormDataBeforeFill($settings);

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    public function save(): void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('afterValidate');

        $data = $this->mutateFormDataBeforeSave($data);

        $this->callHook('beforeSave');

        $settingnames = array_keys($data);

        foreach ($settingnames as $settingname) {
            $filter = Filter::where('name',$settingname)->first();
            $update = FilterUser::updateOrCreate(['user_id' => auth()->user()->id,'filter_id' => $filter->id],['payload' => $data[$settingname]]);
        }

        $this->callHook('afterSave');

        $this->getSavedNotification()?->send();

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl);
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function getSavedNotification(): ?Notification
    {
        $title = $this->getSavedNotificationTitle();

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($title);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return $this->getSavedNotificationMessage() ?? 'Successfully Saved';
    }

    /**
     * @deprecated Use `getSavedNotificationTitle()` instead.
     */
    protected function getSavedNotificationMessage(): ?string
    {
        return null;
    }

    protected function callHook(string $hook): void
    {
        if (! method_exists($this, $hook)) {
            return;
        }

        $this->{$hook}();
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('user_id')
            ->label('User')
            ->options(User::all()->pluck('name', 'id'))
            ->searchable()
        ];
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->columns(2)
                ->inlineLabel(config('filament.layout.forms.have_inline_labels')),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return null;
    }
}
