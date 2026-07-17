<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rules\Unique;
use Misaf\VendraAffiliate\Enums\AffiliateStatusEnum;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraSupport\Support\TagIntegration;
use Misaf\VendraSupport\Support\TenantAwareness;

final class AffiliateForm
{
    public static function configure(Schema $schema): Schema
    {
        $components = [
            Select::make('user_id')
                ->columnSpan(['lg' => 1])
                ->label(__('vendra-affiliate::attributes.user'))
                ->preload()
                ->relationship('user', 'username')
                ->required()
                ->searchable()
                ->unique(
                    modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule)
                        ->withoutTrashed(),
                ),

            TextInput::make('code')
                ->columnSpan(['lg' => 1])
                ->disabledOn('edit')
                ->helperText(__('vendra-affiliate::attributes.code_helper_text'))
                ->label(__('vendra-affiliate::attributes.code'))
                ->maxLength(16)
                ->unique(
                    modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule)
                        ->withoutTrashed(),
                ),

            TextInput::make('commission_percent')
                ->columnSpan(['lg' => 1])
                ->default(fn(): int => Config::integer('vendra-affiliate.defaults.commission_percent', 20))
                ->integer()
                ->label(__('vendra-affiliate::attributes.commission_percent'))
                ->maxValue(100)
                ->minValue(0)
                ->required()
                ->suffix('%'),

            TextInput::make('signup_bounty')
                ->columnSpan(['lg' => 1])
                ->helperText(__('vendra-affiliate::attributes.signup_bounty_helper_text'))
                ->integer()
                ->label(__('vendra-affiliate::attributes.signup_bounty'))
                ->minValue(0),

            ToggleButtons::make('status')
                ->columnSpanFull()
                ->default(AffiliateStatusEnum::Active)
                ->grouped()
                ->label(__('vendra-affiliate::attributes.status'))
                ->options(AffiliateStatusEnum::class)
                ->required(),
        ];

        if (TagIntegration::isAvailable()) {
            $components[] = SpatieTagsInput::make('tags')
                ->columnSpanFull()
                ->label(__('vendra-support::attributes.tags'))
                ->type(Affiliate::TAG_TYPE);
        }

        return $schema
            ->components($components);
    }

}
