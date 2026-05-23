<?php

use Api\V8\BeanDecorator\BeanManager;
use Api\V8\Helper\ModuleListProvider;
use Api\V8\Helper\VarDefHelper;
use Api\V8\JsonApi\Helper\AttributeObjectHelper;
use Api\V8\JsonApi\Helper\PaginationObjectHelper;
use Api\V8\JsonApi\Helper\RelationshipObjectHelper;
use Api\V8\Service;
use Psr\Container\ContainerInterface as Container;
use Api\Core\Loader\CustomLoader;

return CustomLoader::mergeCustomArray([
    Service\ListViewSearchService::class => function (Container $container) {
        return new Service\ListViewSearchService(
            $container->get(BeanManager::class),
            $container->get(VarDefHelper::class)
        );
    },
    Service\UserPreferencesService::class => function (Container $container) {
        return new Service\UserPreferencesService(
            $container->get(BeanManager::class)
        );
    },
    Service\UserService::class => function (Container $container) {
        return new Service\UserService(
            $container->get(BeanManager::class),
            $container->get(AttributeObjectHelper::class),
            $container->get(RelationshipObjectHelper::class)
        );
    },
    Service\MetaService::class => function (Container $container) {
        return new Service\MetaService(
            $container->get(BeanManager::class),
            $container->get(ModuleListProvider::class),
            $container->get(VarDefHelper::class)
        );
    },
    Service\ListViewService::class => function (Container $container) {
        return new Service\ListViewService(
            $container->get(BeanManager::class),
            $container->get(AttributeObjectHelper::class),
            $container->get(RelationshipObjectHelper::class),
            $container->get(PaginationObjectHelper::class),
            $container->get(VarDefHelper::class)
        );
    },
    Service\ModuleService::class => function (Container $container) {
        return new Service\ModuleService(
            $container->get(BeanManager::class),
            $container->get(AttributeObjectHelper::class),
            $container->get(RelationshipObjectHelper::class),
            $container->get(PaginationObjectHelper::class)
        );
    },
    Service\LogoutService::class => function (Container $container) {
        return new Service\LogoutService(
            $container->get(BeanManager::class)
        );
    },
    Service\RelationshipService::class => function (Container $container) {
        return new Service\RelationshipService(
            $container->get(BeanManager::class),
            $container->get(AttributeObjectHelper::class),
            $container->get(PaginationObjectHelper::class)
        );
    },
    Service\MonthInfoService::class => function (Container $container) {
        return new Service\MonthInfoService(
            $container->get(BeanManager::class),
            $container->get(AttributeObjectHelper::class),
            $container->get(RelationshipObjectHelper::class)
        );
    },
    Service\FileService::class => function (Container $container) {
        return new Service\FileService(
            $container->get(BeanManager::class),
            $container->get(AttributeObjectHelper::class),
            $container->get(RelationshipObjectHelper::class)
        );
    },
], basename(__FILE__));
