<?php

class TraitNameResolver
{
    public const TRAIT_NAMESPACE = 'MintHCM\\Api\\EntityTraits\\';
    public const TRAIT_SUFFIX = 'Trait';

    public static function resolve(string $shortName): string
    {
        return $shortName . self::TRAIT_SUFFIX;
    }

    public static function resolveWithNamespace(string $shortName): string
    {
        return self::TRAIT_NAMESPACE . self::resolve($shortName);
    }
}
