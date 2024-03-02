<?php

declare(strict_types=1);

namespace App\Actions\Product;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final class InStockAction extends BulkAction
{
    use CanCustomizeProcess;

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('form.in_stock'));

        $this->modalHeading(fn(): string => __('filament-actions::delete.multiple.modal.heading', ['label' => $this->getPluralModelLabel()]));

        $this->modalSubmitActionLabel(__('filament-actions::delete.multiple.modal.actions.delete.label'));

        $this->successNotificationTitle(__('filament-actions::delete.multiple.notifications.deleted.title'));

        $this->color('warning');

        $this->icon(FilamentIcon::resolve('actions::delete-action') ?? 'heroicon-m-trash');

        $this->requiresConfirmation();

        $this->modalIcon(FilamentIcon::resolve('actions::delete-action.modal') ?? 'heroicon-o-trash');

        $this->action(function (): void {
            $this->process(static fn(Collection $records) => $records->each(fn(Model $record) => $record->update(['in_stock' => true])));

            $this->success();
        });

        $this->deselectRecordsAfterCompletion();

        $this->hidden(function (HasTable $livewire): bool {
            $trashedFilterState = $livewire->getTableFilterState(TrashedFilter::class) ?? [];

            if ( ! array_key_exists('value', $trashedFilterState)) {
                return false;
            }

            if ($trashedFilterState['value']) {
                return false;
            }

            return filled($trashedFilterState['value']);
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'inStock';
    }
}
