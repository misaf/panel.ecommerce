<?php

declare(strict_types=1);

namespace Tests\Support;

use Closure;
use LogicException;
use Misaf\DockerEngine\Contracts\Transport;
use Misaf\DockerEngine\Transport\Request;
use Misaf\DockerEngine\Transport\Response;
use Misaf\DockerEngine\Transport\StreamResponse;

final class FakeDockerTransport implements Transport
{
    /** @var list<Request> */
    public array $requests = [];

    /** @param Closure(Request, bool): (Response|StreamResponse) $handler */
    public function __construct(private readonly Closure $handler) {}

    public function request(Request $request): Response
    {
        $this->requests[] = $request;
        $response = ($this->handler)($request, false);

        return $response instanceof Response
            ? $response
            : throw new LogicException('The Docker fake returned a stream response for a regular request.');
    }

    public function stream(Request $request): StreamResponse
    {
        $this->requests[] = $request;
        $response = ($this->handler)($request, true);

        return $response instanceof StreamResponse
            ? $response
            : throw new LogicException('The Docker fake returned a regular response for a streaming request.');
    }
}
