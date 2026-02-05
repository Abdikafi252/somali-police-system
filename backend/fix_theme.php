<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();

if ($user) {
    // Check if settings exist, create if not
    if (!$user->settings) {
        $user->settings()->create(['theme' => 'light']);
    } else {
        $user->settings->update(['theme' => 'light']);
    }
    echo "Theme updated to light for user: " . $user->email;
} else {
    echo "No user found.";
}
