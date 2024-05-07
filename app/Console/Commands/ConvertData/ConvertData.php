<?php

declare(strict_types=1);

namespace App\Console\Commands\ConvertData;

use App\Console\Commands\ConvertData\Converter\BlogDataConverter;
use App\Console\Commands\ConvertData\Converter\ProductDataConverter;
use App\Console\Commands\ConvertData\Retriever\BlogDataRetriever;
use App\Console\Commands\ConvertData\Retriever\ProductDataRetriever;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

final class ConvertData extends Command
{
    protected $description = 'Converts data from the old version to the new version';

    protected $signature = 'app:convert-data';

    public function handle(): int
    {
        $dataType = $this->choice(
            question: 'Select the type of data to convert',
            choices: ['Blog', 'Product', 'All'],
            attempts: 3,
        );

        match($dataType) {
            'Blog'    => (new BlogDataConverter(storage: Storage::disk('public'), dataRetriever: new BlogDataRetriever()))->migrate(),
            'Product' => (new ProductDataConverter(storage: Storage::disk('public'), dataRetriever: new ProductDataRetriever()))->migrate(),
        };

        return Command::SUCCESS;
    }
}
