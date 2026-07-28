<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $p = new App\Models\PpdbSetting([
        'tahun_ajaran' => '2026/2027',
        'jalur' => 'Zonasi',
        'jadwal_buka' => '2026-01-01',
        'jadwal_tutup' => '2026-02-01',
        'kuota' => 10,
        'is_active' => true
    ]);
    $p->save();
    echo "SAVE OK\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
