<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension(
        'survos_location',
        [
            'db' => 'location.db',
            'user_provider' => 'App\Entity\User',
            'bar' => ['survos_location.ipsum', 'survos_location.lorem'],
            'integer_foo' => 50,
            'integer_bar' => 2
        ]
    );
};
