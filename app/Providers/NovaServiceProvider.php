<?php

namespace App\Providers;

use App\Nova\ChatResponse;
use App\Nova\Dashboards\QAndA;
use App\Nova\SystemMessage;
use App\Nova\User;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Nova\Dashboards\Main;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Middleware\ServeNova;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Outl1ne\NovaSettings\NovaSettings;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->app->make(Kernel::class)
            ->appendToMiddlewarePriority(ServeNova::class);

        Nova::booted(function () {
            $this->app->setLocale('pl');
        });

        Nova::serving(function () {
            $this->app->setLocale('pl');
        });

        NovaSettings::addSettingsFields([
            Text::make('Email kontaktowy', 'contact_email'),
            Text::make('Telefon kontaktowy', 'contact_phone'),
            Text::make('Email działu sprzedaży', 'sales_email'),
        ]);

        Nova::mainMenu(function (Request $request) {
           return [
               MenuSection::dashboard(Main::class)->icon('chart-bar'),

               MenuSection::make(__('gptService'), [
                   MenuItem::resource(ChatResponse::class),
                   MenuItem::resource(SystemMessage::class)
               ])->collapsable(),

               MenuSection::dashboard(QAndA::class)->icon('question-mark-circle'),

               MenuItem::make(__('novaSettings.navigationItemTitle'), 'nova-settings'),

               MenuItem::resource(User::class),
           ];
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new Main,
            new QAndA(),
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new NovaSettings(),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
