<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\ApiResource;

/**
 * Argument-less input schema for the read-only catalog MCP listing tools.
 *
 * MCP requires a tool's input schema to be a JSON object, but a collection
 * resource serializes to an array. Listing tools therefore declare this empty
 * object as their input so the advertised schema stays MCP-compliant while the
 * tool lists every active record.
 */
final class CatalogListingInput {}
