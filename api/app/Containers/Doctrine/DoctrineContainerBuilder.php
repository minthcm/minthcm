<?php


/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

namespace MintHCM\Api\Containers\Doctrine;

use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class DoctrineContainerBuilder extends ContainerBuilder
{
    public function __construct()
    {
        parent::__construct();

        $this->setupExtensions();
        $this->addSettings();
        $this->addDependencies();
    }

    protected function setupExtensions()
    {
        Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
    }

    protected function addSettings()
    {
        global $mint_config;

        $this->addDefinitions([
            'settings' => [
                'doctrine' => [
                    'dev_mode' => true,
                    'cache_path' => __DIR__ . '/../../../var/cache/doctrine',
                    'proxy_path' => __DIR__ . '/../../../var/cache/doctrine/orm/Proxies',
                    'entity_paths' => [__DIR__ . '/../../Entities/'],
                    'connection' => $mint_config['database'],
                ]
            ]
        ]);
    }

    protected function addDependencies()
    {
        $this->addDefinitions([
            EntityManagerInterface::class => function (ContainerInterface $c): EntityManager {
                $doctrineSettings = $c->get('settings')['doctrine'];
                $config = ORMSetup::createAnnotationMetadataConfiguration(
                    $doctrineSettings['entity_paths'],
                    $doctrineSettings['dev_mode'],
                    $doctrineSettings['proxy_path'],
                    new FilesystemAdapter('', 0, $doctrineSettings['cache_path'] ?? null)
                );
                $connection = DriverManager::getConnection($doctrineSettings['connection'], $config);
                return new EntityManager($connection, $config);
            }
        ]);
    }
}
