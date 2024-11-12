<?php

namespace Apiato\Core\Loaders;

use Apiato\Core\Foundation\Facades\Apiato;

trait AutoLoaderTrait
{
    use AliasesLoaderTrait;
    use CommandsLoaderTrait;
    use ConfigsLoaderTrait;
    use HelpersLoaderTrait;
    use LocalizationLoaderTrait;
    use MigrationsLoaderTrait;
    use ProvidersLoaderTrait;
    use ViewsLoaderTrait;

    /**
     * To be used from the `boot` function of the main service provider.
     */
    public function runLoadersBoot(): void
    {
        $this->loadMigrationsFromShip();
        $this->loadLocalsFromShip();
        $this->loadViewsFromShip();
        $this->loadHelpersFromShip();
        $this->loadCommandsFromShip();
        $this->loadCommandsFromCore();

        // Iterate over all the containers folders and autoload most of the components
        foreach (Apiato::getAllContainerPaths() as $containerPath) {
            $this->loadMigrationsFromContainers($containerPath);
            $this->loadLocalsFromContainers($containerPath);
            $this->loadViewsFromContainers($containerPath);
            $this->loadHelpersFromContainers($containerPath);
            $this->loadCommandsFromContainers($containerPath);
        }
    }

    public function runLoaderRegister(): void
    {
        $this->loadConfigsFromShip();

        foreach (Apiato::getAllContainerPaths() as $containerPath) {
            $this->loadConfigsFromContainers($containerPath);
            $this->loadOnlyMainProvidersFromContainers($containerPath);
        }
    }
}
