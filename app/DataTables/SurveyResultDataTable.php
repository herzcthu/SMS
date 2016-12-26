<?php

namespace App\DataTables;

use App\Models\Project;
use App\Models\SurveyResult;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class SurveyResultDataTable extends DataTable
{
    protected $project;

    protected $tableColumns;

    protected $tableBaseColumns;

    protected $sampleType;

    protected $joinMethod;
    /**
     * Project Setter
     * @param  App\Models\Project $project [Project Models from route]
     * @return $this ( App\DataTables\SurveyResultDataTable )
     */
    public function forProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * Columns Setter
     * @param array $columns [array of columns to use by datatables]
     * @return $this ( App\DataTables\SurveyResultDataTable )
     */
    public function setColumns($columns)
    {
        $this->tableColumns = $columns;
        return $this;
    }

    /**
     * Columns Setter
     * @param array $columns [array of columns to use by datatables]
     * @return $this ( App\DataTables\SurveyResultDataTable )
     */
    public function setBaseColumns($columns)
    {
        $this->tableBaseColumns = $columns;
        return $this;
    }

    /**
     * Survey type setter (country|region)
     * @param string $surveyType [country|region]
     * @return $this ( App\DataTables\SurveyResultDataTable )
     */
    public function setSampleType($sampleType)
    {
        $this->sampleType = $sampleType;
        return $this;
    }

    public function setJoinMethod($join)
    {
        $this->joinMethod = $join;
        return $this;
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $orderTable = str_plural($this->project->dblink);
        $orderBy = (isset($this->orderBy)) ? $orderTable . '.' . $this->orderBy : $orderTable . '.id';
        $order = (isset($this->order)) ? $this->order : 'asc';

        $table = $this->datatables
            ->eloquent($this->query());
        $table->addColumn('project_id', $this->project->id);

        $table->addColumn('action', 'projects.sample_datatables_actions');

        $table->orderColumn($orderBy, DB::raw('LENGTH(' . $orderBy . ')') . " $1");

        return $table->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        // $this->project->dblink = 'voter|location|enumerator'
        if (!empty($this->project->dblink) && class_exists('App\Models\\' . ucfirst($this->project->dblink))) {

            // set dblink model class
            $class = 'App\Models\\' . ucfirst($this->project->dblink);

            // create table name
            $table = str_plural($this->project->dblink);
            $orderBy = (isset($this->orderBy)) ? $table . '.' . $this->orderBy : $table . '.id';
            $order = (isset($this->order)) ? $this->order : 'asc';

            // dblink
            $type = $this->project->dblink;

            $joinMethod = (isset($this->joinMethod)) ? $this->joinMethod : 'join';

            // get dblink table base columns
            $tableColumnsArray = array_keys($this->tableBaseColumns);
            // modify column name to use in sql query TABLE.COLUMN format
            array_walk($tableColumnsArray, function (&$column, $index) use ($table) {
                $column = $table . '.' . $column;
            });
            // concat all columns with comma
            $dbLinkTableColumns = implode(', ', $tableColumnsArray);

            $project = $this->project;
            $childTable = $project->dbname;
            $sectionColumns = [];
            foreach ($project->sections as $k => $section) {
                $sectionColumns[] = 'section' . ($k + 1) . 'status';
            }

            $input_columns = '';
            // get all inputs for a project form by name key index
            $unique_inputs = $this->project->inputs->pluck('inputid')->unique();

            $unique_inputs = $unique_inputs->toArray();
            $columnsFromResults = array_merge($sectionColumns, $unique_inputs);
            // modify column name to use in sql query TABLE.COLUMN format
            array_walk($columnsFromResults, function (&$column, $index) use ($childTable) {
                $column = $childTable . '.' . $column;
            });

            $input_columns = implode(',', $columnsFromResults);
            //$count = sizeof($unique_inputs);
            // run query
            $query = $class::query()
                ->select(DB::raw($dbLinkTableColumns), DB::raw($input_columns));
            $query->{$joinMethod}($childTable, function ($join) use ($table, $class, $childTable) {
                $join->on($table . '.id', '=', $childTable . '.samplable_id')
                    ->where($childTable . '.samplable_type', '=', $class);
            });
        } else {
            $query = SurveyResult::query();
            $query->where('project_id', $this->project->id)->with('project');
        }

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        $table = $this->builder()
            ->columns($this->getColumns())
            ->ajax(['type' => 'POST', 'data' => '{"_method":"GET"}']);

        $table->addAction(['width' => '80px']);

        return $table->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        if (!empty($this->tableColumns) && is_array($this->tableColumns)) {
            return $this->tableColumns;
        } else {
            return [
                'inputid' => ['name' => 'inputid', 'data' => 'inputid', 'title' => 'No.'],
                'samplable_id' => ['name' => 'samplable_id', 'data' => 'samplable_id', 'title' => 'ID', 'defaultContent' => ''],
                'value' => ['name' => 'value', 'data' => 'value', 'title' => 'Value', 'defaultContent' => ''],
            ];
        }
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return $this->project->project . time();
    }

    /**
     * Get default builder parameters.
     *
     * @return array
     */
    protected function getBuilderParameters()
    {
        $columns = "1, 2, 3, 4, 5, 6";
        return [
            'dom' => 'Brtip',
            //'sServerMethod' => 'POST',
            'scrollX' => true,
            'buttons' => [
                'print',
                'reset',
                'reload',
                [
                    'extend' => 'collection',
                    'text' => '<i class="fa fa-download"></i> Export',
                    'buttons' => [
                        'exportPostCsv',
                        'exportPostExcel',
                        'exportPostPdf',
                    ],
                ],
                'colvis',
            ],
            'initComplete' => "function () {
                            this.api().columns([$columns]).every(function () {
                                var column = this;
                                var input = document.createElement(\"input\");
                                input.className = 'form-control input-sm';
                                input.style.width = '80px';
                                $(input).appendTo($(column.header()))
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                        }",
        ];
    }
}
