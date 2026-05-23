<?php

namespace MintHCM\Data\ORM\Doctrine\MintTypes;

use Doctrine\DBAL\Types\Type;

class MintTypeManager
{
    const TYPE_LOCATION = [
        'MintHCM\\Data\\ORM\\Doctrine\\MintTypes\\Types\\' => 'data/ORM/Doctrine/MintTypes/Types/',
        'MintHCM\\Custom\\Data\\ORM\\Doctrine\\MintTypes\\Types\\' => 'custom/data/ORM/Doctrine/MintTypes/Types/',
    ];

    public static function registerTypes(): void
    {
        /** @var Type*/
        foreach (self::getTypes() as $type) {
            if (!Type::hasType($type->getName())) {
                Type::addType($type->getName(), $type::class);
            } else {
                Type::overrideType($type->getName(), $type::class);
            }
        }
    }

    public static function hasType(string $type_name): bool
    {
        return Type::hasType($type_name);
    }

    public static function getType(string $type_name): ?Type
    {
        if (!self::hasType($type_name)) {
            return null;
        }
        return Type::getType($type_name);
    }

    public static function isMintType(Type|string $type): bool
    {
        if (is_string($type)) {
            $type = self::getType($type);
            if ($type === null) {
                return false;
            }
        }
        return $type instanceof MintTypeInterface;
    }

    /** @return MintTypeInterface[] */
    protected static function getTypes(): array
    {
        $types = [];
        foreach (self::TYPE_LOCATION as $namespace => $dir) {
            if (is_dir($dir)) {
                $type_class = self::getClassesFromDir($dir);
                foreach ($type_class as $class) {
                    $full_class_name = $namespace . $class;
                    if (!class_exists($full_class_name)) {
                        continue;
                    }
                    $type = new $full_class_name();
                    if (self::isTypeClass($type::class)) {
                        $types[] = $type;
                    }
                }
            }
        }

        return $types;
    }

    protected static function isTypeClass(string $class_name): bool
    {
        return is_subclass_of($class_name, Type::class);
    }

    protected static function getClassesFromDir(string $dir): array
    {
        $classes = [];
        $files = scandir($dir);
        foreach ($files as $file) {
            if (is_file($dir . '/' . $file) && substr($file, -4) === '.php') {
                $classes[] = substr($file, 0, -4);
            }
        }
        return $classes;
    }
}
