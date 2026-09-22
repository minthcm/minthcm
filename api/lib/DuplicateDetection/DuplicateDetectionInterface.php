<?php

namespace MintHCM\Lib\DuplicateDetection;

interface DuplicateDetectionInterface
{
    public function getDuplicates(string $module, array $record_data): array;
}
