<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;
use Spatie\QueueableAction\QueueableAction;

final class SyncModelStatus
{
    // use QueueableAction;

    public function __construct(public Model $model)
    {
        // Prepare the action for execution, leveraging constructor injection.
    }

    public function execute(): void
    {
        $this->model->setStatus('status-name');
    }
}
