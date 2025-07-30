<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Metadata;

/**
 * @immutable
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */
final readonly class RequiresOperatingSystem extends Metadata
{
    /**
     * @var non-empty-string
     */
    private string $operatingSystem;

    /**
     * @param 0|1              $level
     * @param non-empty-string $operatingSystem
     */
    public function __construct(int $level, string $operatingSystem)
    {
        parent::__construct($level);

        $this->operatingSystem = $operatingSystem;
    }

    public function isRequiresOperatingSystem(): true
    {
        return true;
    }

    /**
     * @return non-empty-string
     */
    public function operatingSystem(): string
    {
        return $this->operatingSystem;
    }
}
