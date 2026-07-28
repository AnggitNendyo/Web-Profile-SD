<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/admin/ppdb-setting/1', 'PUT', [
    'tahun_ajaran' => '2026/2027',
    'jalur' => 'Zonasi',
    'jadwal_buka' => '2026-01-01',
    'jadwal_tutup' => '2026-02-01',
    'kuota' => 10,
    'is_active' => true
]);

// Need to fake auth?
$user = App\Models\User::first();
if ($user) {
    $app['auth']->login($user);
}

$response = $app->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
