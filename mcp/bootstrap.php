<?php
use Http\Discovery\Psr17Factory;
use Mcp\Capability\Registry\Container;
use Mcp\Server\Transport\StdioTransport;
use Mcp\Server\Transport\StreamableHttpTransport;
use Mcp\Server\Transport\TransportInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

require_once __DIR__.'/vendor/autoload.php';

set_exception_handler(static function (Throwable $t): never {
    logger()->critical('Uncaught exception: '.$t->getMessage(), ['exception' => $t]);

    exit(1);
});

/**
 * @return TransportInterface<int>|TransportInterface<ResponseInterface>
 */
function transport(): TransportInterface
{
    if ('cli' === \PHP_SAPI) {
        return new StdioTransport(logger: logger());
    }

    return new StreamableHttpTransport(
        (new Psr17Factory())->createServerRequestFromGlobals(),
        logger: logger(),
    );
}

function shutdown(ResponseInterface|int $result): never
{
    if ('cli' === \PHP_SAPI) {
        exit($result);
    }

    $sapiEmitterClass = 'Laminas\\HttpHandlerRunner\\Emitter\\SapiEmitter';
    if (class_exists($sapiEmitterClass)) {
        (new $sapiEmitterClass())->emit($result);
        exit(0);
    }

    http_response_code($result->getStatusCode());
    foreach ($result->getHeaders() as $name => $values) {
        header($name.': '.implode(', ', $values), false);
    }
    echo (string) $result->getBody();
    exit(0);
}

function logger(): LoggerInterface
{
    return new class extends AbstractLogger {
        public function log($level, string|Stringable $message, array $context = []): void
        {
            $debug = $_SERVER['DEBUG'] ?? false;

            if (!$debug && 'debug' === $level) {
                return;
            }

            $exception = $context['exception'] ?? null;
            unset($context['exception']);

            $logMessage = sprintf(
                "[%s] [%s] %s %s\n",
                date('Y-m-d H:i:s'),
                strtoupper($level),
                $message,
                ([] === $context || !$debug) ? '' : json_encode($context),
            );

            if ($exception instanceof Throwable) {
                $logMessage .= sprintf('> %s', $exception->getMessage())."\n";
            }

            if (($_SERVER['FILE_LOG'] ?? false) || !defined('STDERR')) {
                file_put_contents(__DIR__.'/mintmcp.log', $logMessage, \FILE_APPEND);
            } else {
                fwrite(\STDERR, $logMessage);
            }
        }
    };
}

function container(): Container
{
    $container = new Container();
    $container->set(LoggerInterface::class, logger());

    return $container;
}

/** Append one OAuth/MCP lifecycle event to mintmcp.log. No secrets. */
function logEvent(string $event, array $context = []): void
{
    $line = sprintf(
        "[%s] [EVENT] %s%s\n",
        date('Y-m-d H:i:s'),
        $event,
        empty($context) ? '' : ' '.json_encode(
            $context,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR
        ),
    );
    file_put_contents(__DIR__.'/mintmcp.log', $line, FILE_APPEND);
}

/** First token of User-Agent (e.g. "claude-code/2.1.119"). */
function shortUserAgent(): string
{
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if ($ua !== '' && preg_match('#^(\S+)#', $ua, $m)) {
        return $m[1];
    }
    return 'unknown';
}