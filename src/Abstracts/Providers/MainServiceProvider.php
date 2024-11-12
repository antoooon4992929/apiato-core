<?php

namespace Apiato\Core\Abstracts\Providers;

use Apiato\Core\Loaders\AliasesLoaderTrait;
use Apiato\Core\Loaders\ProvidersLoaderTrait;
use Illuminate\Support\ServiceProvider;

abstract class MainServiceProvider extends ServiceProvider
{
    use AliasesLoaderTrait;
    use ProvidersLoaderTrait;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->loadServiceProviders();
        $this->loadAliases();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
