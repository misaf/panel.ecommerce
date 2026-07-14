<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Panels
    |--------------------------------------------------------------------------
    |
    | Here you may specify the Filament panels that the tag administration UI
    | is registered on. You may provide a single panel ID or an array of IDs
    | to mount the module across multiple panels.
    |
    | Supported: "admin" (string), ["admin", "vendor"] (array)
    |
    */

    'panels' => ['admin'],

    /*
    |--------------------------------------------------------------------------
    | Navigation Group
    |--------------------------------------------------------------------------
    |
    | This value determines the sidebar navigation group that contains the tag
    | resource. It may be a translation key or a literal label. When empty,
    | the module's default content group is used.
    |
    */

    'navigation_group' => 'vendra-support::navigation.groups.Content',

];
