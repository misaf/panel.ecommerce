<?php

declare(strict_types=1);

namespace Tests\Support;

use Misaf\DockerEngine\Contracts\Stream\Stream;

final class StringDockerStream implements Stream
{
    private int $offset = 0;

    public function __construct(private string $contents) {}

    public function read(int $length = 8192): string
    {
        $chunk = mb_substr($this->contents, $this->offset, $length, '8bit');
        $this->offset += mb_strlen($chunk, '8bit');

        return $chunk;
    }

    public function write(string $data): int
    {
        $this->contents .= $data;

        return mb_strlen($data, '8bit');
    }

    public function eof(): bool
    {
        return $this->offset >= mb_strlen($this->contents, '8bit');
    }

    public function close(): void
    {
        $this->offset = mb_strlen($this->contents, '8bit');
    }
}
