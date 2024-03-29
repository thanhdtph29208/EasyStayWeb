<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\DatPhong;
use App\Models\Phong;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            // Cập nhật trạng thái của Phòng dựa trên thời gian kết thúc của Đặt Phòng
            $currentDateTime = Carbon::now();

            // Lấy tất cả các đặt phòng đã kết thúc
            $datPhongs = DatPhong::where('thoi_gian_di', '<=', $currentDateTime)
                                ->get();

            foreach ($datPhongs as $datPhong) {
                // Lấy thông tin Phòng
                $phong = Phong::find($datPhong->phong_id);

                // Cập nhật trạng thái của Phòng
                $phong->trang_thai = 1; // Ví dụ: cập nhật trạng thái thành 0 khi Đặt Phòng kết thúc

                // Lưu thay đổi vào cơ sở dữ liệu
                $phong->save();
            }
        })->everySecond(); // Chạy công việc này mỗi phút (hoặc có thể là thời gian cụ thể khác)
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
