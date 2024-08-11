<?php

namespace TheBachtiarz\Base\Providers;

class ConfigProvider
{
    public function __invoke(): void
    {
        $registerConfig = [];

        // ? Cors paths
        $corsPaths = config('cors.paths');
        $registerConfig[] = [
            'cors.paths' => array_merge(
                $corsPaths,
                ['admin/*'],
            ),
        ];

        // ? Logging
        $loggingChannels = config('logging.channels');
        $registerConfig[] = [
            'logging.channels' => array_merge(
                $loggingChannels,
                [
                    'application' => [
                        'driver' => 'single',
                        'path' => storage_path('logs/application.log'),
                    ],
                    'curl' => [
                        'driver' => 'single',
                        'path' => storage_path('logs/curl.log'),
                    ],
                    'developer' => [
                        'driver' => 'single',
                        'path' => storage_path('logs/developer.log'),
                    ],
                    'production' => [
                        'driver' => 'single',
                        'path' => storage_path('logs/production.log'),
                    ],
                    'error' => [
                        'driver' => 'single',
                        'level' => 'debug',
                        'path' => storage_path('logs/error.log'),
                    ],
                    'maintenance' => [
                        'driver' => 'single',
                        'path' => storage_path('logs/maintenance.log'),
                    ],
                ],
            ),
        ];

        foreach ($registerConfig as $key => $config) {
            config($config);
        }
    }
}
