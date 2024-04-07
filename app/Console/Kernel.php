<?php

namespace App\Console;

use App\Models\KhuyenMai;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            // Cập nhật trạng thái "Kết thúc"
            KhuyenMai::where('ngay_ket_thuc', '<', Carbon::now())->update(['trang_thai' => 2]);

            // Cập nhật trạng thái "Đang áp dụng"
            KhuyenMai::where('ngay_bat_dau', '<=', Carbon::now())
                ->where('ngay_ket_thuc', '>=', Carbon::now())
                ->update(['trang_thai' => 1]);
            // Chưa áp dụng
            KhuyenMai::where('ngay_bat_dau', '>', Carbon::now())
                ->whereNotNull('ngay_bat_dau')
                ->update(['trang_thai' => 0]);
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
