<?php

namespace MintHCM\Data\ORM\Doctrine\MintEntity\Traits;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Data\BeanFactory;
use MintHCM\Data\MintBean;
use MintHCM\Utils\LegacyConnector;

trait LegacyEntityTrait
{
    /** @var MintBean */
    protected static $mint_bean;

    public function getMintBean(bool $fill_data = true): MintBean
    {
        if (empty(static::$mint_bean)) {
            static::$mint_bean = BeanFactory::getBean($this->getModuleName(), $this->getId());
            if (empty(static::$mint_bean->id)) {
                static::$mint_bean->new_with_id = true;
            }
        }

        if ($fill_data) {
            $this->fillLegacyBean();
        }

        return static::$mint_bean;
    }

    public function checkLegacyAccess(string $view, $is_owner = 'not_set', $in_group = 'not_set'): bool
    {
        $bean = $this->getMintBean();
        try {
            if ($bean->bean_implements('ACL')) {
                return $bean->ACLAccess($view, $is_owner, $in_group) === true;
            }
        } catch (\Exception $e) {
            return true;
        }
        
        return true;
    }

    public function legacySave(bool $check_notify = false): bool
    {
        try {
            $bean = $this->getMintBean(false);
            $this->fillLegacyBean();
            return !empty($bean->save($check_notify)) ? true : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function legacyDelete(): bool
    {
        try {
            $bean = $this->getMintBean(true);
            $bean->mark_deleted($bean->id);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function fillLegacyBean(): void
    {
        $bean = $this->getMintBean(false);

        /** @var \EntityCreatorManager */
        $entity_creator_manager = new LegacyConnector(
            'EntityCreatorManager', 
            'include/EntityCreator/EntityCreatorManager.php',
        );

        global $mint_app;
        $entity_manager = $mint_app->getContainer()->get(EntityManagerInterface::class);
        foreach ($bean->field_defs as $property => $defs) {
            if (empty($defs) || in_array($defs['type'], $entity_creator_manager::getSkipingFieldTypes())) {
                continue;
            }

            $is_entity_property = property_exists($this, $property);
            $is_custom_entity_property = $this->hasCustomEntity() && property_exists($this->custom_entity, $property);
            if (! $is_entity_property && ! $is_custom_entity_property) {
                continue;
            }

            $value = $this->$property;
            
            $meta = $entity_manager->getClassMetadata($is_entity_property ? static::class : get_class($this->custom_entity));
            if ($meta->hasField($property)) {
                $fieldType = $meta->getTypeOfField($property);
                $doctrineType = \Doctrine\DBAL\Types\Type::getType($fieldType);
                $value = $doctrineType->convertToDatabaseValue($value, $entity_manager->getConnection()->getDatabasePlatform());
            } else if (is_object($value) && property_exists($value, 'id')) {
                // Handle Doctrine relation objects - extract id property if object
                $value = $value->id;
            }

            $bean->$property = $value;
        }
    }

}
