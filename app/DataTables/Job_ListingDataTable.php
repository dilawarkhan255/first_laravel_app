<?php

namespace App\DataTables;

use App\Models\JobListing;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class Job_ListingDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('actions', function($job) {
                return view('jobs.datatables.actions', compact('job'));
            })
            ->addColumn('description', function($job) {
                return '<i class="fas fa-info-circle" onclick="showDescription(\''. addslashes($job->description) .'\')"></i>';
            })
            ->rawColumns(['actions', 'description']);
    }

    public function query(JobListing $model)
    {
        return $model->newQuery()->with('designation');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('jobTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0)
            ->responsive(true);
    }

    protected function getColumns()
    {
        return [
            Column::make('title')->title('Title'),
            Column::make('company')->title('Company'),
            Column::make('designation.name')->title('Designation'),
            Column::make('description')->title('Description')->addClass('text-center'),
            Column::make('location')->title('Location'),
            Column::make('status')->title('Status')->addClass('text-center'),
            Column::computed('actions')->title('Actions')->addClass('text-center')->exportable(false)->printable(false)->width(120),
        ];
    }
}
