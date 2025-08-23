<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Đăng ký command dọn dẹp tất cả các item đã xóa mềm
Artisan::command('trashed:cleanup-all {--type=all} {--days=30}', function () {
    $this->call(\App\Console\Commands\CleanupTrashedItems::class, [
        '--type' => $this->option('type'),
        '--days' => $this->option('days')
    ]);
})->purpose('Tự động xóa vĩnh viễn tất cả các item đã bị xóa mềm sau 30 ngày');

// Đăng ký command dọn dẹp banner đã xóa mềm (alias cho backward compatibility)
Artisan::command('banners:cleanup-trashed', function () {
    $this->call(\App\Console\Commands\CleanupTrashedItems::class, ['--type' => 'banners']);
})->purpose('Tự động xóa vĩnh viễn banner đã bị xóa mềm sau 30 ngày');
