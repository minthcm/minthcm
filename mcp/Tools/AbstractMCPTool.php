<?php

namespace MintMCP\Tools;

use ACLController;
use Mcp\Types\Tool;
use Mcp\Types\CallToolResult;
use Mcp\Types\TextContent;
use Mcp\Types\ToolInputSchema;
use MintMCP\Config\Config;
use MintMCP\Server\ControllerFactory;
use MintMCP\Tools\Exceptions\ModuleNotAllowedException;
use MintMCP\Server\Logger;
use Monolog\Logger as MonologLogger;

abstract class AbstractMCPTool
{
    /**
     * @var Config MCP configuration instance
     */
    public Config $config;

    /**
     * @var MonologLogger MCP logger instance
     */
    public MonologLogger $logger;
    /**
     * Initializes the configuration instance.
     */
    public function __construct()
    {
        $this->config = Config::getInstance();
        $this->logger = Logger::getLogger();
    }

    /**
     * Returns the unique name of the tool.
     *
     * @return string
     */
    abstract public function getName(): string;

    /**
     * Returns a human-readable description of the tool.
     *
     * @return string
     */
    abstract public function getDescription(): string;

    /**
     * Returns the input schema for the tool.
     *
     * @return ToolInputSchema
     */
    abstract public function getInputSchema(): ToolInputSchema;

    /**
     * Executes the tool's main logic.
     *
     * @param object $arguments Input arguments for the tool
     * @return CallToolResult
     */
    abstract public function execute(object $arguments): CallToolResult;

    /**
     * Creates a Tool object for MCP registration.
     *
     * @return Tool
     */
    public function createMCPTool(): Tool
    {
        $name = $this->getName();
        $description = $this->getDescription();
        $inputSchema = $this->getInputSchema();
        $tool = new Tool(
            name: $name,
            inputSchema: $inputSchema,
            description: $description
        );

        return $tool;
    }

    /**
     * Get an API Controller instance
     *
     * @param string $controllerClass The fully qualified class name of the controller
     * @return mixed The controller instance
     */
    protected function getController(string $controllerClass)
    {
        return ControllerFactory::getInstance()->createController($controllerClass);
    }

    /**
     * Checks user permissions for a module.
     *
     * Throws an exception if the user is not authenticated or does not have access.
     * Also enforces whitelist/blacklist configuration.
     *
     * @param string $module The module name
     * @param string $acl_action The ACL action (default: 'list')
     * @return bool True if access is granted
     * @throws \Exception|ModuleNotAllowedException
     */
    protected function checkPermissions(string $module, string $acl_action = 'list'): bool
    {
        global $current_user;

        if (!$current_user || !$current_user->id) {
            throw new \Exception('User not authenticated');
        }

        $blacklist = array_filter(array_map('trim', explode(',', $this->config->get('blacklist'))));

        // Blacklist check: if enabled and module is in blacklist, block access
        if (in_array($module, $blacklist, true)) {
            throw new ModuleNotAllowedException("Access to module '{$module}' is not allowed by blacklist.");
        }

        chdir('../legacy');
        if (!\ACLController::checkAccess($module, $acl_action, true, 'module', true)) {
            throw new ModuleNotAllowedException("Insufficient permissions for module: {$module}");
        }
        chdir('../mcp');

        return true;
    }

    /**
     * Helper to create a TextContent object.
     *
     * @param string $text
     * @return TextContent
     */
    protected function createTextContent(string $text): TextContent
    {
        $content = new TextContent($text);
        return $content;
    }

    /**
     * Helper to create a JSON content object.
     *
     * @param array $data
     * @return TextContent
     */
    protected function createJsonContent(array $data): TextContent
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $this->createTextContent($json);
    }

    /**
     * Helper to create a CallToolResult object.
     *
     * @param array $content
     * @return CallToolResult
     */
    protected function createResult(array $content): CallToolResult
    {
        $result = new CallToolResult($content);
        return $result;
    }

    /**
     * Returns a URL to the updated record (if possible).
     *
     * @param string $moduleName
     * @param string $id
     * @return string
     */
    protected function getRecordUrl(string $moduleName, string $id): string
    {
        global $sugar_config;
        if (isset($sugar_config['site_url'])) {
            $baseUrl = rtrim($sugar_config['site_url'], '/');
            return $baseUrl . '/#/modules/' . ucfirst($moduleName) . '/DetailView/' . $id;
        }
        return '';
    }
}
