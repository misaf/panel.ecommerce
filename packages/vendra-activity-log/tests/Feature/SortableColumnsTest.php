<?php

declare(strict_types=1);

use Misaf\VendraActivityLog\Filament\Clusters\Resources\Pages\ListActivityLogs;
use Misaf\VendraActivityLog\Models\ActivityLog;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();
});

it('sorts the activity logs table by every sortable column following the stored values', function (): void {
    activity('aaa-log')->log('first entry');
    activity('bbb-log')->log('second entry');

    $activityLogs = ActivityLog::query()->orderBy('id')->get()->all();

    expect(livewire(ListActivityLogs::class)->call('loadTable'))
        ->toSortByEverySortableColumn($activityLogs);
});
