<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\Symfony\Symfony61\Rector\Class_\CommandPropertyToAttributeRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;

return RectorConfig::configure()
    ->withPaths([
//        __DIR__ . '/assets',
//        __DIR__ . '/config',
//        __DIR__ . '/public',
        __DIR__ . '/src',
//        __DIR__ . '/tests',
    ])
    ->withSets([
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        DoctrineSetList::GEDMO_ANNOTATIONS_TO_ATTRIBUTES,

    ])
    ->withConfiguredRule(
        \Rector\Php80\Rector\Class_\AnnotationToAttributeRector::class,
        [
            new \Rector\Php80\ValueObject\AnnotationToAttribute('ApiPlatform\Metadata\ApiFilter'),
            new \Rector\Php80\ValueObject\AnnotationToAttribute('ApiPlatform\Metadata\ApiResource'),
            new \Rector\Php80\ValueObject\AnnotationToAttribute('ApiPlatform\Metadata\ApiProperty'),
            new \Rector\Php80\ValueObject\AnnotationToAttribute('ApiPlatform\Metadata\ApiSubresource'),
        ])

    // uncomment to reach your current PHP version
    // ->withPhpSets()
    ->withRules([
        CommandPropertyToAttributeRector::class,
        InlineConstructorDefaultToPropertyRector::class,
        AddVoidReturnTypeWhereNoReturnRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class
    ]);
