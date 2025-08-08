<?php

/**
 * Model Context Protocol SDK for PHP
 *
 * (c) 2024 Logiscape LLC <https://logiscape.com>
 *
 * Based on the Python SDK for the Model Context Protocol
 * https://github.com/modelcontextprotocol/python-sdk
 *
 * PHP conversion developed by:
 * - Josh Abbott
 * - Claude 3.5 Sonnet (Anthropic AI model)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    logiscape/mcp-sdk-php
 * @author     Josh Abbott <https://joshabbott.com>
 * @copyright  Logiscape LLC
 * @license    MIT License
 * @link       https://github.com/logiscape/mcp-sdk-php
 *
 * Filename: Types/AnnotatedTrait.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Trait to be used by classes that are Annotated
 */
trait AnnotatedTrait {
    public ?Annotations $annotations = null;

    public function setAnnotations(?Annotations $annotations): void {
        $this->annotations = $annotations;
    }

    protected function annotationsToJson(): array {
        return $this->annotations !== null ? ['annotations' => $this->annotations] : [];
    }

    protected function validateAnnotations(): void {
        if ($this->annotations !== null) {
            $this->annotations->validate();
        }
    }
}