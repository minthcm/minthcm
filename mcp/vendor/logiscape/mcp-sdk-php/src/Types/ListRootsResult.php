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
 * - ChatGPT o1 pro mode
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
 * Filename: Types/ListRootsResult.php
 */
 
declare(strict_types=1);

namespace Mcp\Types;

class ListRootsResult extends Result {
    /**
     * @param Root[] $roots
     */
    public function __construct(
        public readonly array $roots,
        ?Meta $_meta = null,
    ) {
        parent::__construct($_meta);
    }

    public function validate(): void {
        parent::validate();
        foreach ($this->roots as $root) {
            if (!$root instanceof Root) {
                throw new \InvalidArgumentException('Roots must be instances of Root');
            }
            $root->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = parent::jsonSerialize();
        $data['roots'] = $this->roots;
        return $data;
    }

    public static function fromResponseData(array $data): self {
        $meta = null;
        if (isset($data['_meta'])) {
            $metaData = $data['_meta'];
            unset($data['_meta']);
            $meta = new Meta();
            foreach ($metaData as $k => $v) {
                $meta->$k = $v;
            }
        }

        $rootsData = $data['roots'] ?? [];
        unset($data['roots']);

        $roots = [];
        foreach ($rootsData as $r) {
            if (!is_array($r)) {
                throw new \InvalidArgumentException('Invalid root data in ListRootsResult');
            }
            $roots[] = Root::fromArray($r);
        }

        $obj = new self($roots, $meta);

        // Extra fields
        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }
}