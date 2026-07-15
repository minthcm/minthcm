<?php
/* ===========================================================================
 * Copyright 2020-2021 Zindex Software
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\String\Exception;

use Throwable;

class InvalidStringException extends UnicodeException
{
    /**
     * @var string
     */
    protected string $string;

    /**
     * @var int
     */
    protected int $offset;

    /**
     * @param string $string
     * @param int $offset
     * @param Throwable|null $previous
     */
    public function __construct(string $string, int $offset = -1, ?Throwable $previous = null)
    {
        parent::__construct("Invalid UTF-8 string at offset {$offset}", 0, $previous);
        $this->string = $string;
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function string(): string
    {
        return $this->string;
    }

    /**
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }
}
