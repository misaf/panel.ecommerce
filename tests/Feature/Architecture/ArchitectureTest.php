<?php

declare(strict_types=1);

use App\Http\Controllers\Controller;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Http\FormRequest;

arch('globals')
    ->expect(['dd', 'ddd', 'dump', 'var_dump'])
    ->not->toBeUsed();

arch('ENV')
    ->expect('env')
    ->not->toBeUsed();

// arch('App')
// ->expect('App')
// ->toUseStrictTypes();

arch('Config')
    ->expect('Config')
    ->toUseStrictTypes();

arch('Database')
    ->expect('Database')
    ->toUseStrictTypes();

arch('Lang')
    ->expect('Lang')
    ->toUseStrictTypes();

arch('Routes')
    ->expect('Routes')
    ->toUseStrictTypes();

arch('Tests')
    ->expect('Tests')
    ->toUseStrictTypes();

// arch('App\Console toBeFinal')
//     ->expect('App\Console')
//     ->toBeFinal();

// arch('App\Console toBeClasses')
//     ->expect('App\Console')
//     ->toBeClasses();

arch('App\Filament toBeFinal')
    ->expect('App\Filament')
    ->toBeFinal();

arch('App\Filament toBeClasses')
    ->expect('App\Filament')
    ->toBeClasses();

arch('App\Http\Controllers\Livewire toBeFinal')
    ->expect('App\Http\Controllers\Livewire')
    ->toBeFinal();

arch('App\Models toBeClasses')
    ->expect('App\Models')
    ->toBeClasses();

arch('App\Traits toBeTraits')
    ->expect('App\Traits')
    ->toBeTraits();

arch('App\Contracts toBeInterface')
    ->expect('App\Contracts')
    ->toBeInterface();

arch('App\DataTransferObjects toBeClasses')
    ->expect('App\DataTransferObjects')
    ->toBeClasses();

arch('App\DataTransferObjects toBeFinal')
    ->expect('App\DataTransferObjects')
    ->toBeFinal();

arch('App\Enums toBeEnums')
    ->expect('App\Enums')
    ->toBeEnums();

arch('App\Observers toHaveSuffix Observer')
    ->expect('App\Observers')
    ->toHaveSuffix('Observer');

arch('App\Observers toBeClasses')
    ->expect('App\Observers')
    ->toBeClasses();

arch('App\Observers toBeFinal')
    ->expect('App\Observers')
    ->toBeFinal();

arch('App\Observers toImplement Illuminate\Contracts\Queue\ShouldQueue')
    ->expect('App\Observers')
    ->toImplement(ShouldQueue::class);

// arch('App\Console\Commands toBeClasses')
//     ->expect('App\Console\Commands')
//     ->toBeClasses();

// arch('App\Console\Commands toBeFinal')
//     ->expect('App\Console\Commands')
//     ->toBeFinal();

// arch('App\Console\Commands toExtend \Illuminate\Console\Command')
//     ->expect('App\Console\Commands')
//     ->toExtend(Command::class);

arch('App\Http\Controllers toHaveSuffix Controller')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller');

// arch('App\Http\Controllers toBeClasses')
//     ->expect('App\Http\Controllers')
//     ->toBeClasses();

arch('App\Http\Controllers toBeFinal')
    ->expect('App\Http\Controllers')
    ->toBeFinal()
    ->ignoring('App\Http\Controllers\Controller');

arch('App\Http\Controllers toExtend \App\Http\Controllers\Controller')
    ->expect('App\Http\Controllers')
    ->toExtend(Controller::class)
    ->ignoring('App\Http\Controllers\Controller');

arch('App\Http\Controllers\Panel\Admin toHaveConstructor')
    ->expect('App\Http\Controllers\Panel\Admin')
    ->toHaveConstructor();

arch('App\Http\Controllers\Panel\User toHaveConstructor')
    ->expect('App\Http\Controllers\Panel\User')
    ->toHaveConstructor();

arch('App\Casts toHaveSuffix Cast')
    ->expect('App\Casts')
    ->toHaveSuffix('Cast');

arch('App\Casts toBeClasses')
    ->expect('App\Casts')
    ->toBeClasses();

arch('App\Casts toBeFinal')
    ->expect('App\Casts')
    ->toBeFinal();

arch('App\Policies toHaveSuffix Policy')
    ->expect('App\Policies')
    ->toHaveSuffix('Policy');

arch('App\Policies toBeClasses')
    ->expect('App\Policies')
    ->toBeClasses();

arch('App\Policies toBeFinal')
    ->expect('App\Policies')
    ->toBeFinal();

arch('App\Providers toHaveSuffix Provider')
    ->expect('App\Providers')
    ->toHaveSuffix('Provider');

arch('App\Providers toBeClasses')
    ->expect('App\Providers')
    ->toBeClasses();

arch('App\Providers toBeFinal')
    ->expect('App\Providers')
    ->toBeFinal();

arch('App\Services toHaveSuffix Service')
    ->expect('App\Services')
    ->toHaveSuffix('Service');

arch('App\Services toBeClasses')
    ->expect('App\Services')
    ->toBeClasses();

arch('App\Services toBeFinal')
    ->expect('App\Services')
    ->toBeFinal();

arch('App\Http\Requests toHaveSuffix Request')
    ->expect('App\Http\Requests')
    ->toHaveSuffix('Request');

arch('App\Http\Requests toBeClasses')
    ->expect('App\Http\Requests')
    ->toBeClasses();

arch('App\Http\Requests toBeFinal')
    ->expect('App\Http\Requests')
    ->toBeFinal();

arch('App\Http\Requests toExtend \Illuminate\Foundation\Http\FormRequest')
    ->expect('App\Http\Requests')
    ->toExtend(FormRequest::class);

arch('App\Jobs toHaveSuffix Job')
    ->expect('App\Jobs')
    ->toHaveSuffix('Job');

arch('App\Jobs toBeClasses')
    ->expect('App\Jobs')
    ->toBeClasses();

arch('App\Jobs toBeFinal')
    ->expect('App\Jobs')
    ->toBeFinal();

arch('App\Mail toHaveSuffix Mail')
    ->expect('App\Mail')
    ->toHaveSuffix('Mail');

arch('App\Mail toBeClasses')
    ->expect('App\Mail')
    ->toBeClasses();

arch('App\Mail toBeFinal')
    ->expect('App\Mail')
    ->toBeFinal();

arch('Database\Factories toHaveSuffix Factory')
    ->expect('Database\Factories')
    ->toHaveSuffix('Factory');

arch('Database\Factories toBeClasses')
    ->expect('Database\Factories')
    ->toBeClasses();

arch('Database\Factories toBeFinal')
    ->expect('Database\Factories')
    ->toBeFinal();

arch('Database\Factories toExtend \Illuminate\Database\Eloquent\Factories\Factory')
    ->expect('Database\Factories')
    ->toExtend(Factory::class);

arch('Database\Migrations toHaveSuffix _table')
    ->expect('Database\Migrations')
    ->toHaveSuffix('_table');

arch('Database\Migrations toBeClasses')
    ->expect('Database\Migrations')
    ->toBeClasses();

arch('Database\Migrations toExtend \Illuminate\Database\Migrations\Migration')
    ->expect('Database\Migrations')
    ->toExtend(Migration::class);

arch('Database\Seeders toHaveSuffix Seeder')
    ->expect('Database\Seeders')
    ->toHaveSuffix('Seeder');

arch('Database\Seeders toBeClasses')
    ->expect('Database\Seeders')
    ->toBeClasses();

arch('Database\Seeders toBeFinal')
    ->expect('Database\Seeders')
    ->toBeFinal();

arch('Database\Seeders toExtend \Illuminate\Database\Seeder')
    ->expect('Database\Seeders')
    ->toExtend(Seeder::class);

arch('App\Listeners toBeClasses')
    ->expect('App\Listeners')
    ->toBeClasses();

arch('App\Listeners toBeFinal')
    ->expect('App\Listeners')
    ->toBeFinal();
