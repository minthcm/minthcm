<?php

namespace MintHCM\Data\ORM\Doctrine\MintEntity;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Data\ORM\Doctrine\MintTypes\MintTypeManager;

abstract class MintEntity
{
    use Traits\LegacyEntityTrait;

    public const LEGACY_ACTIONS = true;

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        if ($this->hasCustomEntity() && property_exists($this->custom_entity, $name)) {
            return $this->custom_entity->$name;
        }
    }

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = (new PropertiesManager($this->getEntityManager(), $this))->getConvertedToPHPValue($name, $value);
            return;
        }

        if ($this->hasCustomEntity() && property_exists($this->custom_entity, $name)) {
            $this->custom_entity->$name = $value;
            return;
        }

        $mint_bean = $this->getMintBean(false);
        if(isset($mint_bean->field_defs[$name])){
            $this->$name = $value;
            return;
        }
        
    }

    public function hasLegacyActions(): bool
    {
        return static::LEGACY_ACTIONS;
    }

    public function hasAccess(string $view, bool $is_owner = false, bool $in_group = false): bool
    {
        return $this->checkLegacyAccess($view, $is_owner ?: 'not_set', $in_group ?: 'not_set');
    }

    public function hasCustomEntity(): bool
    {
        return property_exists($this, 'custom_entity') && $this->custom_entity instanceof MintEntity;
    }

    public function isCustomEntity(): bool
    {
        return property_exists($this, 'main_entity') && $this->main_entity instanceof MintEntity;
    }

    public function getId(): ?string
    {
        return $this->id ?? null;
    }

    /**
     * QA: Add support for first_name and last_name sequence
     */
    public function getName(): ?string
    {
        if (property_exists($this, 'name')) {
            return $this->name;
        }
        if (property_exists($this, 'first_name') && property_exists($this, 'last_name')) {
            return trim($this->first_name . ' ' . $this->last_name);
        }

        return null;
    }

    public function getModuleName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Serialize the entity to an array or JSON string.
     *
     * @param bool $json Whether to return the result as a JSON string
     * @return array|string The serialized entity
     */
    public function getSerialized(bool $json = false): array|string
    {
        $serializer = new MintEntitySerializer($this->getEntityManager());
        $data = $serializer->serialize($this);
        return $json ? json_encode($data) : $data;
    }

    public function gerPropertyTypeName(string $property): ?string
    {
        return (new PropertiesManager($this->getEntityManager(), $this))->getPropertyType($property);
    }

    public function getRelatedPropertiesDefs(): array
    {
        return (new PropertiesManager($this->getEntityManager(), $this))->getRelatedPropertiesDefs();
    }

    public function getRelatedPropertiesNames(): array
    {
        return (new PropertiesManager($this->getEntityManager(), $this))->getRelatedPropertiesNames();
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        global $mint_app;
        return $mint_app->getContainer()->get(EntityManagerInterface::class);
    }
}   
