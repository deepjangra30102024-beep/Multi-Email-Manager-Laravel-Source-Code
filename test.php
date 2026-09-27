<?php

use App\Models\EmailAccount;
use App\Services\GoogleGmailService;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

try {
    $account = EmailAccount::first();
    if (! $account) {
        echo "No account found\n";
        exit;
    }
    $service = new GoogleGmailService($account);
    $emails = $service->getLatestEmails(1);
    dump($emails);
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
