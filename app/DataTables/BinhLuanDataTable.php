<?php

namespace App\DataTables;

use App\Models\comments;
use http\Client\Request;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BinhLuanDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'binhluan.action')

            ->addColumn('Tên người dùng', function ($query) {
                return  $query->user->ten_nguoi_dung;
            })
            ->addColumn('Tên Bài viết', function ($query) {
                return  $query->bai_viet->tieu_de;
            })
            ->addColumn('Nội dung', function ($query) {
                return  $query->content;
            })
            ->addColumn('Thời gian tạo', function ($query) {
                return  $query->created_at;
            })

            ->addColumn('action', function ($query) {



                $deleteBtn = "<a href='" . route('admin.binh_luan_bai_viet.delete', $query->id) . "' class='btn btn-danger delete-item ms-2'>
                <i class='bi bi-archive'></i>
                </a>";


                return "<div class='d-flex'>"  . $deleteBtn . "</div>";
            })


            ->rawColumns(['Tên người dùng', 'Tên Bài viết', 'action','Tên người dùng'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(comments $model): QueryBuilder
    {
        return $model->with(['user','bai_viet'])->where('bai_viet_id', $this->id)->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('binhluan-table')
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
            Column::make('Tên người dùng'),
            Column::make('Tên Bài viết'),
            Column::make('Nội dung'),
             Column::make('Thời gian tạo'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'BinhLuan_' . date('YmdHis');
    }
}
