<?php

namespace Apiato\Core\Loaders;

use Apiato\Core\Foundation\Facades\Apiato;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\SplFileInfo;

class RoutesLoader
{
    public function __invoke($router): void
    {
        if (! $this->shouldRegisterRoutes()) {
            return;
        }

        $allContainerPaths = Apiato::getAllContainerPaths();

        foreach ($allContainerPaths as $containerPath) {
            $this->loadApiContainerRoutes($containerPath);
            $this->loadWebContainerRoutes($containerPath);
        }
    }

    private function shouldRegisterRoutes(): bool
    {
        if (app()->routesAreCached()) {
            return false;
        }

        return true;
    }

    /**
     * Register the Containers API routes files.
     */
    private function loadApiContainerRoutes(string $containerPath): void
    {
        $apiRoutesPath = $this->getRoutePathsForUI($containerPath, 'API');

        if (File::isDirectory($apiRoutesPath)) {
            $files = $this->getFilesSortedByName($apiRoutesPath);
            foreach ($files as $file) {
                $this->loadApiRoute($file);
            }
        }
    }

    private function getRoutePathsForUI(string $containerPath, string $ui): string
    {
        return $this->getUIPathForContainer($containerPath, $ui).DIRECTORY_SEPARATOR.'Routes';
    }

    private function getUIPathForContainer(string $containerPath, string $ui): string
    {
        return $containerPath.DIRECTORY_SEPARATOR.'UI'.DIRECTORY_SEPARATOR.$ui;
    }

    /**
     * @return array|SplFileInfo[]
     */
    private function getFilesSortedByName(string $apiRoutesPath): array
    {
        $files = File::allFiles($apiRoutesPath);
        $files = Arr::sort($files, function ($file) {
            return $file->getFilename();
        });

        return $files;
    }

    private function loadApiRoute(SplFileInfo $file): void
    {
        $routeGroupArray = $this->getApiRouteGroup($file);
        Route::group($routeGroupArray, function ($router) use ($file) {
            require $file->getPathname();
        });
    }

    public function getApiRouteGroup(SplFileInfo|string $endpointFileOrPrefixString): array
    {
        return [
            'middleware' => $this->getMiddlewares(),
            // If $endpointFileOrPrefixString is a string, use that string as prefix
            // else, if it is a file then get the version name from the file name, and use it as prefix
            'prefix' => is_string($endpointFileOrPrefixString) ? $endpointFileOrPrefixString : $this->getApiVersionPrefix($endpointFileOrPrefixString),
        ];
    }

    private function getMiddlewares(): array
    {
        return [
            'api',
        ];
    }

    private function getApiVersionPrefix(SplFileInfo $file): string
    {
        return Config::get('apiato.api.prefix').(Config::get('apiato.api.enable_version_prefix') ? $this->getRouteFileVersionFromFileName($file) : '');
    }

    private function getRouteFileVersionFromFileName(SplFileInfo $file): string|bool
    {
        $fileNameWithoutExtension = $this->getRouteFileNameWithoutExtension($file);

        $fileNameWithoutExtensionExploded = explode('.', $fileNameWithoutExtension);

        return end($fileNameWithoutExtensionExploded);
    }

    private function getRouteFileNameWithoutExtension(SplFileInfo $file): string
    {
        return pathinfo($file->getFilename(), PATHINFO_FILENAME);
    }

    /**
     * Register the Containers WEB routes files.
     */
    private function loadWebContainerRoutes($containerPath): void
    {
        $webRoutesPath = $this->getRoutePathsForUI($containerPath, 'WEB');

        if (File::isDirectory($webRoutesPath)) {
            $files = $this->getFilesSortedByName($webRoutesPath);
            foreach ($files as $file) {
                $this->loadWebRoute($file);
            }
        }
    }

    private function loadWebRoute(SplFileInfo $file): void
    {
        Route::group([
            'middleware' => ['web'],
        ], function ($router) use ($file) {
            require $file->getPathname();
        });
    }
}
