<?php

namespace App\DataTables;

use App\Models\ChiTietDatPhong;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ChiTietDatPhongDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'chitietdatphong.action')
            ->addColumn('dat_phong_id', function($query){
                return $query->dat_phong->id;
            })
            ->addColumn('dich_vu_id', function($query){
                return $query->dich_vu->ten_dich_vu;
            })
            ->addColumn('so_luong_nguoi', function($query){
                return $query->dat_phong->so_luong_nguoi;
            })
            ->addColumn('so_luong_phong', function($query){
                return $query->dat_phong->so_luong_phong;
            })
            ->addColumn('thoi_gian_den', function($query){
                return $query->dat_phong->thoi_gian_den;
            })
            ->addColumn('thoi_gian_di', function($query){
                return $query->dat_phong->thoi_gian_di;
            })
            ->addColumn('payment', function($query){
                return $query->dat_phong->payment;
            })
            ->addColumn('ghi_chu', function($query){
                return $query->dat_phong->ghi_chu;
            })
            ->rawColumns(['phong_id','dich_vu_id','so_luong_nguoi','so_luong_phong','thoi_gian_den','thoi_gian_di','payment','ghi_chu'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ChiTietDatPhong $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('chitietdatphong-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        // Button::make('excel'),
                        // Button::make('csv'),
                        // Button::make('pdf'),
                        // Button::make('print'),
                        // Button::make('reset'),
                        // Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('dat_phong_id'),
            Column::make('dich_vu_id'),
            Column::make('so_luong_nguoi'),
            Column::make('so_luong_phong'),
            Column::make('thoi_gian_den'),
            Column::make('thoi_gian_di'),
            Column::make('payment'),
            Column::make('thanh_tien'),
            Column::make('invoice'),
            Column::make('ghi_chu'),
            // Column::computed('action')
            // ->exportable(false)
            // ->printable(false)
            // ->width(128)
            // ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ChiTietDatPhong_' . date('YmdHis');
    }
}
