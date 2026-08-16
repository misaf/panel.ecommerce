<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Transport for the Docker Engine HTTP API.
 *
 * This is the only class in the package that knows about sockets, URLs, and
 * status codes; the runtimes above it work in value objects. It is shared rather
 * than duplicated per runtime because Podman's compatibility socket serves the
 * same API — a second copy of this would drift from the first for no reason.
 *
 * Nothing here sniffs which runtime answers. The differences that exist are the
 * endpoint and the API version, both configuration.
 */
final class EngineApiClient
{
    /**
     * @param string $endpoint unix:///path/to.sock, tcp://host:port, or an http(s) URL
     */
    public function __construct(
        private readonly string $endpoint,
        private readonly string $apiVersion = 'v1.43',
        private readonly int $timeout = 60,
    ) {}

    public function endpoint(): string
    {
        return $this->endpoint;
    }

    public function apiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * @param array<string, string> $query
     */
    public function get(string $path, array $query = [], ?int $timeout = null): Response
    {
        return $this->request($timeout)->get($this->url($path, $query));
    }

    /**
     * @param array<string, string> $query
     * @param array<string, mixed>  $payload
     */
    public function post(string $path, array $query = [], ?array $payload = null, ?int $timeout = null): Response
    {
        $request = $this->request($timeout);

        if (null === $payload) {
            return $request->post($this->url($path, $query));
        }

        return $request->asJson()->post($this->url($path, $query), $payload);
    }

    /**
     * @param array<string, string> $query
     */
    public function delete(string $path, array $query = [], ?int $timeout = null): Response
    {
        return $this->request($timeout)->delete($this->url($path, $query));
    }

    /**
     * A request against the unversioned root.
     *
     * Only `/_ping` uses it, to separate "the socket is dead" from "this API
     * version is unsupported" — a distinction an operator fixes in two different
     * places.
     */
    public function getUnversioned(string $path): Response
    {
        return $this->request()->get($this->base() . $path);
    }

    /**
     * A decoded body, or null when the object does not exist.
     *
     * @return array<array-key, mixed>|null
     */
    public function decode(Response $response): ?array
    {
        if ($response->failed()) {
            return null;
        }

        $body = $response->json();

        return is_array($body) ? $body : null;
    }

    /**
     * The runtime's own explanation, falling back to the raw body.
     */
    public function reason(Response $response): string
    {
        $message = $response->json('message');

        return is_string($message) && '' !== $message
            ? $message
            : Str::limit($response->body(), 300, '');
    }

    /**
     * @param array<string, string> $query
     */
    private function url(string $path, array $query = []): string
    {
        $url = $this->base() . '/' . mb_trim($this->apiVersion, '/') . $path;

        return [] === $query ? $url : $url . '?' . http_build_query($query);
    }

    /**
     * The HTTP host for the request.
     *
     * Over a unix socket the host is ignored by curl but still has to be a valid
     * URL, so a placeholder stands in for it.
     */
    private function base(): string
    {
        if (Str::startsWith($this->endpoint, 'unix://')) {
            return 'http://container-runtime';
        }

        if (Str::startsWith($this->endpoint, 'tcp://')) {
            return 'http://' . Str::after($this->endpoint, 'tcp://');
        }

        return mb_rtrim($this->endpoint, '/');
    }

    private function request(?int $timeout = null): PendingRequest
    {
        $request = Http::acceptJson()
            ->connectTimeout(5)
            ->timeout($timeout ?? $this->timeout);

        if (Str::startsWith($this->endpoint, 'unix://')) {
            $request = $request->withOptions([
                'curl' => [CURLOPT_UNIX_SOCKET_PATH => Str::after($this->endpoint, 'unix://')],
            ]);
        }

        return $request;
    }
}
