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
 * Filename: Types/PaginatedRequest.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Base class for all paginated requests
 */
abstract class PaginatedRequest extends Request {
    public function __construct(
        string $method,
        ?string $cursor = null,
    ) {
        // Instead of passing cursor directly to Request, we create PaginatedRequestParams
        // and pass as params.
        parent::__construct($method, new PaginatedRequestParams($cursor));
    }

    public function validate(): void {
        parent::validate();
        if ($this->params !== null && $this->params instanceof PaginatedRequestParams) {
            $this->params->validate();
        }
    }
}