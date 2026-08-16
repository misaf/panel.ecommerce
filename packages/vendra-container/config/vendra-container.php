<?php

declare(strict_types=1);

return [
    /*
     | Which runtime the ContainerRuntime contract resolves to. Both speak the
     | same Engine API, so this selects an adapter and its default API version —
     | nothing above the container layer branches on it.
     |
     | Supported: "docker", "podman".
     */
    'runtime' => env('CONTAINER_RUNTIME', 'docker'),

    /*
     | Where that runtime listens. Empty means no runtime is configured: callers
     | record their intent and reconcile later rather than failing.
     |
     | Docker:            unix:///var/run/docker.sock
     | Podman (rootless): unix:///run/user/<uid>/podman/podman.sock
     | Podman (rootful):  unix:///run/podman/podman.sock
     |
     | A tcp:// or http(s):// endpoint works too. Prefer rootless Podman where
     | the choice is open: its socket is not root-equivalent on the host.
     */
    'endpoint' => env('CONTAINER_ENDPOINT', 'unix:///var/run/docker.sock'),

    /*
     | Engine API version. Empty negotiates the adapter's default, which differs
     | between Docker and Podman's compatibility socket.
     */
    'api_version' => env('CONTAINER_API_VERSION', ''),

    'timeout'      => (int) env('CONTAINER_TIMEOUT', 60),
    'pull_timeout' => (int) env('CONTAINER_PULL_TIMEOUT', 600),
];
