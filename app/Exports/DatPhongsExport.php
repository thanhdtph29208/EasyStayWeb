<?php

namespace App\Exports;

use App\Models\DatPhong;
use App\Models\DatPhongDichVu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
class DatPhongsExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DatPhong::all();
    }
    /**
     * Returns headers for report
     * @return array
     */
    public function headings(): array {
        return [
            'Họ tên',
            'Loại phòng',
            'Phòng',
            'Đơn giá',
            'Số lượng phòng',
            'Số lượng người',
            'Thời gian đến',
            'Thời gian đi',
            'Dịch vụ',
            'Số lượng dịch vụ',
            'Tổng tiền',
            'Hình thức thanh toán',
            'Trạng thái',
            'Ghi chú',
            "Created",
            "Updated",
            "Deleted",
        ];
    }

    public function map($datPhong): array {
        $id = $datPhong->id;
        $tenPhongsArray = $datPhong->phongs()->pluck('ten_phong')->toArray();
        $tenLoaiPhongsArray = $datPhong->loaiPhongs()->pluck('ten')->toArray();
        $giaLoaiPhongsArray = $datPhong->loaiPhongs()->pluck('gia')->toArray();
        $soLuongLoaiPhongsArray = $datPhong->loaiPhongs()->pluck('so_luong_phong')->toArray();
        $tenDichVusArray = $datPhong->dichVus()->pluck('ten_dich_vu')->toArray();
        $soLuongDichVusArray =DatPhongDichVu::where('dat_phong_id',$id)->pluck('so_luong')->toArray();
        return [
            $datPhong->user->ten_nguoi_dung,
            $tenLoaiPhongsArray,
            $tenPhongsArray,
            $giaLoaiPhongsArray,
            $soLuongLoaiPhongsArray,
            $datPhong->so_luong_nguoi,
            $datPhong->thoi_gian_den,
            $datPhong->thoi_gian_di,
            $tenDichVusArray,
            $soLuongDichVusArray,
            $datPhong->tong_tien,
            $datPhong->payment,
            $datPhong->trang_thai,
            $datPhong->ghi_chu,
            $datPhong->created_at,
            $datPhong->updated_at,
            $datPhong->deleted_at,
        ];
    }
}
