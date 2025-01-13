<?php

namespace App\Providers;

use Filament\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\ServiceProvider;

class GlobalFilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        CreateAction::configureUsing(
            fn(CreateAction $action) =>
            $action->icon('heroicon-o-plus')
        );

        ViewAction::configureUsing(
            fn(ViewAction $action) =>
            $action->button()
        );

        EditAction::configureUsing(
            fn(EditAction $action) =>
            $action->button()
        );

        DeleteAction::configureUsing(
            fn(DeleteAction $action) =>
            $action->button()
        );
    }
}
