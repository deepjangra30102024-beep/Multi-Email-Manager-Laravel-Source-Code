<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $account = App\Models\EmailAccount::first();
    if (!$account) {
        echo "No account found\n";
        exit;
    }
    $service = new App\Services\GoogleGmailService($account);
    $emails = $service->getLatestEmails(1);
    dump($emails);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
