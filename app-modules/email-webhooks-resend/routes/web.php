<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

$webhookPath = Config::string('services.email.webhooks.resend.webhook_path', '/emails/webhooks/resend');
$webhookName = Config::string('services.email.webhooks.resend.webhook_name', 'resend');

// Route::webhooks(
//     $webhookPath,
//     $webhookName,
// );
