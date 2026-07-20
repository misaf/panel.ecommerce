<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraActivityLog\Tests\Fixtures\LoggableWidget;
use Misaf\VendraActivityLog\Tests\Fixtures\PlainWidget;

beforeEach(function (): void {
    if ( ! Schema::hasTable('activity_log_widgets')) {
        (require __DIR__ . '/../database/migrations/0001_01_01_000000_create_activity_log_widgets_table.php')->up();
    }

    makeCurrentTestTenant();

    config(['activitylog.enabled' => true]);
});

it('logs creation for a model that implements ShouldLogActivity', function (): void {
    $widget = LoggableWidget::create(['name' => 'Alpha', 'description' => 'first']);

    $activity = DB::table('activity_log')->latest('id')->first();

    expect($activity)->not->toBeNull()
        ->and($activity->event)->toBe('created')
        ->and($activity->description)->toBe('created')
        ->and($activity->subject_type)->toBe($widget->getMorphClass())
        ->and((int) $activity->subject_id)->toBe($widget->id);

    $properties = json_decode($activity->properties, true);

    expect($properties['attributes'])->toBe(['name' => 'Alpha', 'description' => 'first'])
        ->and($properties['attributes'])->not->toHaveKey('id');
});

it('logs updates with both old and new attribute values', function (): void {
    $widget = LoggableWidget::create(['name' => 'Alpha']);
    $widget->update(['name' => 'Beta']);

    $activity = DB::table('activity_log')->where('event', 'updated')->latest('id')->first();
    $properties = json_decode($activity->properties, true);

    expect($properties['attributes']['name'])->toBe('Beta')
        ->and($properties['old']['name'])->toBe('Alpha');
});

it('logs deletion', function (): void {
    $widget = LoggableWidget::create(['name' => 'Alpha']);
    $widget->delete();

    expect(DB::table('activity_log')->where('event', 'deleted')->exists())->toBeTrue();
});

it('does not log models that do not implement ShouldLogActivity', function (): void {
    PlainWidget::create(['name' => 'Quiet']);

    expect(DB::table('activity_log')->count())->toBe(0);
});

it('records nothing while activity logging is disabled', function (): void {
    config()->set('activitylog.enabled', false);

    LoggableWidget::create(['name' => 'Alpha']);

    expect(DB::table('activity_log')->count())->toBe(0);
});
