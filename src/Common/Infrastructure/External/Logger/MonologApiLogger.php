<?php
declare(strict_types=1);

namespace CQRS\Common\Infrastructure\External\Logger;

use CQRS\Common\Domain\Contract\LoggerInterface;
use Psr\Log\LoggerInterface as MainLoggerInterface;

final readonly class MonologApiLogger implements LoggerInterface
{
    public function __construct(private MainLoggerInterface $apiLogger)
    {
    }

    public function info(string $message, array $context = []): void
    {
        $this->apiLogger->info($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->apiLogger->warning($message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->apiLogger->critical($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->apiLogger->error($message, $context);
    }
}