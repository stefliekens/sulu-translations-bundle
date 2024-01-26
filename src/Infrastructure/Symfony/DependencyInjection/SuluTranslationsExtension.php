<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SuluTranslationsExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasExtension('doctrine')) {
            $container->prependExtensionConfig(
                'doctrine',
                [
                    'orm' => [
                        'mappings' => [
                            'SuluTranslationsBundle' => [
                                'type' => 'attribute',
                                'dir' => __DIR__.'/../../../Domain/Model',
                                'prefix' => 'Tailr\SuluTranslationsBundle\Domain\Model',
                                'alias' => 'SuluTranslationsBundle',
                            ],
                        ],
                    ],
                ]
            );
        }

        if ($container->hasExtension('sulu_admin')) {
            $container->prependExtensionConfig(
                'sulu_admin',
                [
                    'lists' => [
                        'directories' => [
                            __DIR__.'/../../../../config/lists',
                        ],
                    ],
                    'forms' => [
                        'directories' => [
                            __DIR__.'/../../../../config/forms',
                        ],
                    ],
                    'resources' => [
                        'tailr_translations' => [
                            'routes' => [
                                'list' => 'tailr.translations_list',
                                'detail' => 'tailr.translations_fetch',
                            ],
                        ],
                    ],
                ]
            );
        }
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../../../config/services'));
        $loader->load('commands.yaml');
        $loader->load('controllers.yaml');
        $loader->load('repositories.yaml');
        $loader->load('serializers.yaml');
        $loader->load('services.yaml');
        $loader->load('translation-provider.yaml');
    }
}
