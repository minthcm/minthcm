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
 * Filename: Types/CreateMessageRequest.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Request to create a message via sampling
 */
class CreateMessageRequest extends Request {
    /**
     * @param SamplingMessage[] $messages
     */
    public function __construct(
        public readonly array $messages,
        public readonly int $maxTokens,
        public ?array $stopSequences = null, // string[]
        public ?string $systemPrompt = null,
        public ?float $temperature = null,
        public ?Meta $metadata = null,
        public ?ModelPreferences $modelPreferences = null,
        public ?string $includeContext = null,
    ) {
        parent::__construct('sampling/createMessage');
    }

    public function validate(): void {
        parent::validate();
        if (empty($this->messages)) {
            throw new \InvalidArgumentException('Messages array cannot be empty');
        }
        foreach ($this->messages as $message) {
            if (!$message instanceof SamplingMessage) {
                throw new \InvalidArgumentException('Messages must be instances of SamplingMessage');
            }
            $message->validate();
        }
        if ($this->maxTokens <= 0) {
            throw new \InvalidArgumentException('Max tokens must be greater than 0');
        }
        if ($this->includeContext !== null && !in_array($this->includeContext, ['allServers', 'none', 'thisServer'])) {
            throw new \InvalidArgumentException('Invalid includeContext value');
        }
        if ($this->modelPreferences !== null) {
            $this->modelPreferences->validate();
        }
        // metadata is a Meta object, it's allowed arbitrary fields
        if ($this->metadata !== null) {
            $this->metadata->validate();
        }
    }
}