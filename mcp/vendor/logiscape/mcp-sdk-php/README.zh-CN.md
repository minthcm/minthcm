# PHP 版本的模型上下文协议(MCP) SDK

[English](README.md) | 中文

这个包提供了[模型上下文协议(Model Context Protocol)](https://modelcontextprotocol.io)的 PHP 实现，使应用程序能够以标准化的方式为大语言模型(LLM)提供上下文。它将提供上下文的关注点与实际的 LLM 交互分离开来。

## 概述

这个 PHP SDK 实现了完整的 MCP 规范，可以轻松地：

- 构建能连接到任何 MCP 服务器的 MCP 客户端
- 创建暴露资源、提示和工具的 MCP 服务器
- 使用标准传输方式如 stdio 和 HTTP
- 处理所有 MCP 协议消息和生命周期事件

本 SDK 基于模型上下文协议的官方[Python SDK](https://github.com/modelcontextprotocol/python-sdk)开发。

这个 SDK 主要面向从事前沿 AI 集成解决方案的开发者。某些功能可能尚未完善，在生产环境使用前，实现应该由经验丰富的开发者进行彻底的测试和安全审查。

## 安装

您可以通过 composer 安装此包：

```bash
composer require logiscape/mcp-sdk-php
```

### 系统要求

- PHP 8.1 或更高版本
- ext-curl
- ext-json
- ext-pcntl (可选，推荐在 CLI 环境中使用)
- monolog/monolog (可选，用于示例客户端/服务器的日志记录)

## 基本用法

### 创建 MCP 服务器

以下是创建提供提示功能的 MCP 服务器的完整示例：

```php
<?php

// 一个带有测试用提示列表的基本示例服务器

require 'vendor/autoload.php';

use Mcp\Server\Server;
use Mcp\Server\ServerRunner;
use Mcp\Types\Prompt;
use Mcp\Types\PromptArgument;
use Mcp\Types\PromptMessage;
use Mcp\Types\ListPromptsResult;
use Mcp\Types\TextContent;
use Mcp\Types\Role;
use Mcp\Types\GetPromptResult;
use Mcp\Types\GetPromptRequestParams;

// 创建服务器实例
$server = new Server('example-server');

// 注册提示处理器
$server->registerHandler('prompts/list', function($params) {
    $prompt = new Prompt(
        name: 'example-prompt',
        description: '示例提示模板',
        arguments: [
            new PromptArgument(
                name: 'arg1',
                description: '示例参数',
                required: true
            )
        ]
    );
    return new ListPromptsResult([$prompt]);
});

$server->registerHandler('prompts/get', function(GetPromptRequestParams $params) {
    $name = $params->name;
    $arguments = $params->arguments;

    if ($name !== 'example-prompt') {
        throw new \InvalidArgumentException("未知提示: {$name}");
    }

    // 安全获取参数值
    $argValue = $arguments ? $arguments->arg1 : 'none';

    $prompt = new Prompt(
        name: 'example-prompt',
        description: '示例提示模板',
        arguments: [
            new PromptArgument(
                name: 'arg1',
                description: '示例参数',
                required: true
            )
        ]
    );

    return new GetPromptResult(
        messages: [
            new PromptMessage(
                role: Role::USER,
                content: new TextContent(
                    text: "示例提示文本，参数值为: $argValue"
                )
            )
        ],
        description: '示例提示'
    );
});

// 创建初始化选项并运行服务器
$initOptions = $server->createInitializationOptions();
$runner = new ServerRunner($server, $initOptions);
$runner->run();
```

将此代码保存为 `example_server.php`

### 创建 MCP 客户端

以下是如何创建连接到示例服务器的客户端：

```php
<?php

// 一个连接到example_server.php并输出提示的基本示例客户端

require 'vendor/autoload.php';

use Mcp\Client\Client;
use Mcp\Client\Transport\StdioServerParameters;
use Mcp\Types\TextContent;

// 创建stdio连接的服务器参数
$serverParams = new StdioServerParameters(
    command: 'php',  // 可执行文件
    args: ['example_server.php'],  // 服务器文件路径
    env: null  // 可选环境变量
);

// 创建客户端实例
$client = new Client();

try {
    echo("开始连接\n");
    // 使用stdio传输连接到服务器
    $session = $client->connect(
        commandOrUrl: $serverParams->getCommand(),
        args: $serverParams->getArgs(),
        env: $serverParams->getEnv()
    );

    echo("开始获取可用提示\n");
    // 列出可用提示
    $promptsResult = $session->listPrompts();

    // 输出提示列表
    if (!empty($promptsResult->prompts)) {
        echo "可用提示：\n";
        foreach ($promptsResult->prompts as $prompt) {
            echo "  - 名称: " . $prompt->name . "\n";
            echo "    描述: " . $prompt->description . "\n";
            echo "    参数:\n";
            if (!empty($prompt->arguments)) {
                foreach ($prompt->arguments as $argument) {
                    echo "      - " . $argument->name . " (" . ($argument->required ? "必需" : "可选") . "): " . $argument->description . "\n";
                }
            } else {
                echo "      (无)\n";
            }
        }
    } else {
        echo "没有可用提示。\n";
    }

} catch (\Exception $e) {
    echo "错误: " . $e->getMessage() . "\n";
    exit(1);
} finally {
    // 关闭服务器连接
    if (isset($client)) {
        $client->close();
    }
}
```

将此代码保存为 `example_client.php` 并运行：

```bash
php example_client.php
```

## 进阶示例

"examples" 目录包含了使用 STDIO 和 HTTP 传输方式的额外客户端和服务器示例。所有示例都设计为在安装 SDK 的同一目录中运行。

一些示例使用 monolog 进行日志记录，可以通过 composer 安装：

```bash
composer require monolog/monolog
```

## MCP Web 客户端

"webclient"目录包含了一个用于测试 MCP 服务器的基于 Web 的应用程序。它旨在展示一个能在典型 Web 托管环境中运行的 MCP 客户端。

### 设置 Web 客户端

要设置 Web 客户端，请将"webclient"目录的内容上传到 Web 目录（例如 cPanel 服务器上的 public_html）。确保通过运行本 README 安装部分中的 Composer 命令在同一目录中安装 MCP SDK for PHP。

### 使用 Web 客户端

Web 客户端上传到 Web 目录后，导航到 index.php 打开界面。要连接到包含的 MCP 测试服务器，在 Command 字段中输入`php`，在 Arguments 字段中输入`test_server.php`，然后点击`Connect to Server`。该界面允许您测试提示、工具和资源。还有一个调试面板，允许您查看客户端和服务器之间发送的 JSON-RPC 消息。

### Web 客户端注意事项和限制

此 MCP Web 客户端旨在供开发者测试 MCP 服务器，不建议在未经额外安全性、错误处理和资源管理测试的情况下将其作为公共 Web 界面使用。

虽然 MCP 通常作为有状态的会话协议实现，但典型的基于 PHP 的 Web 托管环境限制了长时间运行的进程。为了最大化兼容性，MCP Web 客户端将为每个请求初始化客户端和服务器之间的新连接，并在请求完成后关闭该连接。

## OAuth 授权（测试中）

HTTP 传输支持可选的 OAuth 2.1 授权。更多信息请见 [OAuth 认证示例](examples/server_auth/README.md)。

## 文档

有关模型上下文协议的详细信息，请访问[官方文档](https://modelcontextprotocol.io)。

## 2025-03-26 实现

我们目前正在实现 MCP 规范的 2025-03-26 版本。

### 已完成任务

- 实现协议版本协商
- 创建新规范功能的类
- 添加对 JSON-RPC 批处理的支持
- 实现 HTTP 传输

### 可测试功能

* 实现基于 OAuth 2.1 的服务端授权框架

### 待办事项

- 实现客户端 OAuth 授权框架
- 探索在 PHP 环境中支持 SSE 的可行性

## 致谢

这个 PHP SDK 由以下人员开发：

- [Josh Abbott](https://joshabbott.com)
- Claude 3.5 Sonnet (Anthropic AI 模型)
- Claude Opus 4

Josh Abbott 使用 OpenAI ChatGPT o1 专业版与 OpenAI Codex 进行了额外的调试与重构。

基于模型上下文协议的原始[Python SDK](https://github.com/modelcontextprotocol/python-sdk)开发。

## 许可证

MIT 许可证 (MIT)。更多信息请查看[许可证文件](LICENSE)。
