<?php

declare(strict_types=1);

namespace Misaf\VendraProduct\Filament\Clusters\Resources\Products\Actions;

use Filament\Actions\ReplicateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Misaf\VendraProduct\Filament\Clusters\Resources\Products\ProductResource;
use Misaf\VendraProduct\Models\Product;
use Misaf\VendraProduct\Models\ProductPrice;
use Misaf\VendraSupport\Support\TenantAwareness;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class DuplicateProductAction extends ReplicateAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('vendra-product::actions.duplicate'));

        $this->modalHeading(__('vendra-product::actions.duplicate_product'));

        $this->modalSubmitActionLabel(__('vendra-product::actions.duplicate'));

        $this->successNotificationTitle(__('vendra-product::messages.product_duplicated'));

        $this->authorize('replicate');

        $this->requiresConfirmation();

        $this->excludeAttributes(['position', 'token']);

        $this->mutateRecordDataUsing(fn(array $data): array => $this->mutateReplicaData($data));

        $this->after(function (Product $record, Product $replica): void {
            $this->duplicateRelations($record, $replica);
        });

        $this->successRedirectUrl(fn(Product $replica): string => ProductResource::getUrl('edit', ['record' => $replica]));
    }

    /**
     * @param  array<array-key, mixed>  $data
     * @return array<array-key, mixed>
     */
    private function mutateReplicaData(array $data): array
    {
        $transformed = [];

        if (isset($data['name'])) {
            $name = $this->duplicateTranslations($data['name'], ' Copy');
            $transformed['name'] = $this->ensureUniqueTranslatedValue('name', $name, ' Copy');
        }

        if (isset($data['slug'])) {
            $slug = $this->duplicateTranslations($data['slug'], '-copy', slug: true);
            $transformed['slug'] = $this->ensureUniqueTranslatedValue('slug', $slug, '-copy', slug: true);
        }

        return $transformed;
    }

    /**
     * @param  array<string, string>  $translations
     * @return array<string, string>
     */
    private function ensureUniqueTranslatedValue(string $column, array $translations, string $suffix, bool $slug = false): array
    {
        if ([] === $translations) {
            return $translations;
        }

        $baseTranslations = $translations;
        $counter = 1;

        while ($this->translatedValueExists($column, $translations)) {
            $counter++;
            $translations = [];

            foreach ($baseTranslations as $locale => $value) {
                if ($slug) {
                    $translations[$locale] = Str::slug("{$value}-{$counter}");
                } else {
                    $translations[$locale] = "{$value} {$counter}";
                }
            }
        }

        return $translations;
    }

    /**
     * @param  array<string, string>  $values
     */
    private function translatedValueExists(string $column, array $values): bool
    {
        $query = Product::withTrashed();

        if (TenantAwareness::enabled()) {
            $query->where('tenant_id', TenantAwareness::currentId());
        }

        $originalRecord = $this->getRecord();
        if (null !== $originalRecord && $originalRecord->exists) {
            $query->whereKeyNot($originalRecord->getKey());
        }

        $query->where(function (Builder $query) use ($column, $values): void {
            foreach ($values as $locale => $value) {
                $query->orWhere("{$column}->{$locale}", $value);
            }
        });

        return $query->exists();
    }

    /**
     * @return array<string, string>
     */
    private function duplicateTranslations(mixed $translations, string $suffix, bool $slug = false): array
    {
        if ( ! is_array($translations)) {
            return [];
        }

        $duplicatedTranslations = [];

        foreach ($translations as $locale => $translation) {
            if ( ! is_string($locale) || ! is_string($translation) || '' === $translation) {
                continue;
            }

            $duplicatedTranslations[$locale] = $slug
                ? Str::slug($translation . $suffix)
                : $translation . $suffix;
        }

        return $duplicatedTranslations;
    }

    private function duplicateRelations(Product $record, Product $replica): void
    {
        $this->duplicatePrices($record, $replica);
        $this->duplicateMedia($record, $replica);
    }

    private function duplicatePrices(Product $record, Product $replica): void
    {
        $record->productPrices()
            ->get()
            ->each(fn(ProductPrice $productPrice): ProductPrice => $replica->productPrices()->create([
                'currency_code' => $productPrice->currency_code,
                'price'         => (int) $productPrice->price->getAmount(),
            ]));
    }

    private function duplicateMedia(Product $record, Product $replica): void
    {
        $record->media()
            ->where('collection_name', 'products')
            ->get()
            ->each(fn(Media $media): Media => $media->copy($replica, $media->collection_name, $media->disk));
    }
}
