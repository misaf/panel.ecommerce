# Vendra Application Image

This directory contains only the Laravel application image definition and its
runtime entrypoint. Infrastructure orchestration belongs to the standalone Go
[vendra-controller](https://github.com/misaf/vendra-controller) repository.

The published image contains FrankenPHP, production Composer dependencies, and
compiled frontend assets. On startup the entrypoint waits for its configured
database and Redis services, runs migrations, and warms application caches.

No Laravel container mounts `/var/run/docker.sock`, executes Docker Compose, or
renders storefront infrastructure. Laravel communicates with the controller's
authenticated `/v1/storefronts` provisioner endpoint through queued jobs.
