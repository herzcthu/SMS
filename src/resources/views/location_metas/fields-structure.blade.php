<!-- Project Field -->
<div class="form-group col-sm-12">
    {!! Form::label('project_id', 'Project:') !!}
    {!! Form::label('project_id',$project->project) !!}
    {!! Form::hidden('project_id', $project->id) !!}
</div>

<!-- Default Location Code Field -->
<div class="form-group col-sm-12">
    <p class="bg-warning text-danger">Please set one field as primary. It will be used as id code for survey to store
        and filter by.</p>
    <p class="bg-warning text-danger">Remove unused fields</p>
    <p class="text-info">Save Column Info : This will save anything you change on this page</p>
    <p class="text-info">Update DB Schemas : This will create database table and columns for data to be imported</p>
    <p class="text-info">Import Data : Import data from uploaded file</p>
    <p class="text-info">Create and Import : This will do all three step above.</p>
</div>

<div class="form-group col-sm-12">
    <table class="table table-responsive">
        <tr>
            <th>Label</th>
            <th>DB Field Name</th>
            <th>Field Type</th>
            <th>Filter</th>
            <th>Data Type</th>
            <th>Show</th>
            <th>Export</th>
            <th>Field Action
                <i class=" fa fa-plus btn btn-sm btn-success btn-flat btn-green" onclick="addItem()"></i>
            </th>
        </tr>
        <tbody id="container" class="no-border-x no-border-y ui-sortable">

        @if($locationMetas->isEmpty())
            <tr class="item">
                <td>
                    {!! Form::text("label", null, ["class" => "form-control field_label field"]) !!}
                </td>
                <td>
                    {!! Form::text("field_name", null, ["class" => "form-control field_name field"]) !!}
                </td>
                <td>
                    {!! Form::text("field_type", 'primary', ["class" => "form-control field_type field", "readonly"]) !!}
                </td>
                <td>
                    {!! Form::select("data_type", ["location" => "Location", "observer1" => "Observer 1", "observer2" => "Observer 2", "sample" => "Sample Group"],'location', ["class" => "form-control data_type field"]) !!}
                </td>
                <td>
                    {!! Form::checkbox("show", 1, null,["class" => "magic-checkbox field_show field", "id" => "show"]) !!}
                    {!! Form::label("show", " ") !!}
                </td>
                <td>
                    {!! Form::checkbox("export", 1, null, ["class" => "magic-checkbox field_export field", "id" => "export"]) !!}
                    {!! Form::label("export", " ") !!}
                </td>
                <td>
                    Required <i class="fa fa-star text-danger"></i>
                </td>
            </tr>
            <tr class="item">
                <td>
                    {!! Form::text("label", null, ["class" => "form-control field_label field"]) !!}
                </td>
                <td>
                    {!! Form::text("field_name", null, ["class" => "form-control field_name field"]) !!}
                </td>
                <td>
                    {!! Form::select("field_type", ["code" => "Code","text" => "Text","textarea" => "Paragraph","integer" => "Number", "sbo_number" => "SBO Number", "phone" => "Phone"],'text', ["class" => "form-control field_type field"]) !!}
                </td>
                <td>
                    {!! Form::select("filter_type", ["" => "None", "typein" => "Type In","selectbox" => "Select Box"],null, ["class" => "form-control filter_type field"]) !!}
                </td>
                <td>
                    {!! Form::select("data_type", ["location" => "Location", "observer1" => "Observer 1", "observer2" => "Observer 2", "sample" => "Sample Group"],'location', ["class" => "form-control data_type field"]) !!}
                </td>
                <td>
                    {!! Form::checkbox("show", 1, null,["class" => "magic-checkbox field_show field", "id" => "show"]) !!}
                    {!! Form::label("show", " ") !!}
                </td>
                <td>
                    {!! Form::checkbox("export", 1, null, ["class" => "magic-checkbox field_export field", "id" => "export"]) !!}
                    {!! Form::label("export", " ") !!}
                </td>
                <td>
                    <i onclick="addItem()" class="add-new fa fa-plus btn btn-sm btn-success"
                       style="cursor: pointer;"></i>
                    <i onclick="removeItem(this)" class="remove fa fa-trash-o"
                       style="cursor: pointer;font-size: 20px;color: red;"></i>
                </td>
            </tr>
        @else
            @foreach($locationMetas as $k => $location)
                @if($location->field_name)
                    <tr class="item" style="display: table-row;">
                        <td>
                            {!! Form::text("label", ($location->label)?$location['label']:title_case($location->field_name), ["class" => "form-control field_label field"]) !!}
                        </td>
                        <td>
                            {!! Form::text("field_name", $location->field_name, ["class" => "form-control field_name field", "readonly"]) !!}
                        </td>
                        <td>

                            @if($location->field_type == 'primary')
                                {!! Form::text("field_type", 'primary', ["class" => "form-control field_type field", "readonly"]) !!}
                            @else
                                {!! Form::select("field_type", ["code" => "Code","text" => "Text","textarea" => "Paragraph","integer" => "Number", "sbo_number" => "SBO Number", "phone" => "Phone"], $location->field_type, ["class" => "form-control field_type field"]) !!}
                            @endif
                        </td>
                        <td>
                            @if($location->field_type == 'primary')
                                {!! Form::select("filter_type", ["typein" => "Type In"],'typein', ["class" => "form-control filter_type field", "readonly"]) !!}
                            @else
                                {!! Form::select("filter_type", ["" => "None", "typein" => "Type In","selectbox" => "Select Box"],$location->filter_type, ["class" => "form-control filter_type field"]) !!}
                            @endif
                        </td>
                        <td>
                            {!! Form::select("data_type", ["location" => "Location", "observer1" => "Observer 1", "observer2" => "Observer 2", "sample" => "Sample Group"],$location->data_type, ["class" => "form-control data_type field"]) !!}
                        </td>
                        <td>
                            @if($location->field_type == 'primary')
                                {!! Form::checkbox("show", 1, 1,["class" => "magic-checkbox field_show field", "id" => "show".$k, "onclick"=>"return false;"]) !!}
                                {!! Form::label("show".$k, " ") !!}
                            @else
                                {!! Form::checkbox("show", 1, $location->show_index,["class" => "magic-checkbox field_show field", "id" => "show".$k]) !!}
                                {!! Form::label("show".$k, " ") !!}
                            @endif
                        </td>
                        <td>
                            @if($location->field_type == 'primary')
                                {!! Form::checkbox("export", 1, 1, ["class" => "magic-checkbox field_export field", "id" => "export".$k, "onclick"=>"return false;"]) !!}
                                {!! Form::label("export".$k, " ") !!}
                            @else
                                {!! Form::checkbox("export", 1, $location->export, ["class" => "magic-checkbox field_export field", "id" => "export".$k]) !!}
                                {!! Form::label("export".$k, " ") !!}
                            @endif
                        </td>
                        <td>
                            <i onclick="addItem()" class="add-new fa fa-plus btn btn-sm btn-success"
                               style="cursor: pointer;"></i>
                            @if($location->field_type != 'primary')
                                <i onclick="removeItem(this)" class="remove fa fa-trash-o"
                                   style="cursor: pointer;font-size: 20px;color: red;"></i>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">

    {!! Form::submit('Save Column Info', ['class' => 'btn btn-primary','name'=> 'submit']) !!}
    {!! Form::submit('Update DB Schemas', ['class' => 'btn btn-primary','name'=> 'submit']) !!}
    {!! Form::submit('Import Data', ['class' => 'btn btn-primary','name'=> 'submit']) !!}
    {!! Form::submit('Create and Import', ['class' => 'btn btn-primary','name'=> 'submit']) !!}

    <a href="{!! route('location-metas.index') !!}" class="btn btn-default">Cancel</a>
</div>

@push('before-body-end')
    <script type="text/javascript">
        var htmlStr = '<tr class="item">\n' +
            '        <td>\n' +
            '            {!! Form::text("label", null, ["class" => "form-control field_label field"]) !!}\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            {!! Form::text("field_name", null, ["class" => "form-control field_name field"]) !!}\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            {!! Form::select("field_type", ["text" => "Text","textarea" => "Paragraph","integer" => "Number","code" => "Code", "sbo_number" => "SBO Number","phone" => "Phone"],null, ["class" => "form-control field_type field"]) !!}\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            {!! Form::select("filter_type", ["" => "None", "typein" => "Type In","selectbox" => "Select Box"],null, ["class" => "form-control filter_type field"]) !!}\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            {!! Form::select("data_type", ["location" => "Location", "observer1" => "Observer 1", "observer2" => "Observer 2", "sample" => "Sample Group"],null, ["class" => "form-control data_type field"]) !!}\n' +
            '        </td>\n' +
            '        <td>\n' +
            '           {!! Form::checkbox("show", 1, null,["class" => "magic-checkbox field_show field", "id" => "show"]) !!}\n' +
            '           {!! Form::label("show", " ") !!}\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            {!! Form::checkbox("export", 1, null, ["class" => "magic-checkbox field_export field", "id" => "export"]) !!}\n' +
            '            {!! Form::label("export", " ") !!}\n' +
            '         </td>\n' +
            '            <i onclick="addItem()" class="add-new fa fa-plus btn btn-sm btn-success"\n' +
            '               style="cursor: pointer;"></i>\n' +
            '\n' +
            '            <i onclick="removeItem(this)" class="remove fa fa-trash-o"\n' +
            '               style="cursor: pointer;font-size: 20px;color: red;"></i>\n' +
            '        </td>\n' +
            '    </tr>';

        function addItem() {
            var item = $(htmlStr).clone();
            $("#container").append(item);
        }

        function removeItem(e) {
            e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
        }

        (function ($) {
            $(document).ready(function () {
                $('tbody').sortable({
                    cursor: 'move',
                    axis: 'y'
                });
                $("form").on("submit", function (e) {
                    $('.field_label').each(function (index, value) {
                        $(this).attr('name', 'fields[' + index + '][label]');
                    });
                    $('.field_name').each(function (index, value) {
                        $(this).attr('name', 'fields[' + index + '][field_name]');
                    });
                    $('.field_type').each(function (index, value) {
                        $(this).attr('name', 'fields[' + index + '][field_type]');
                    });
                    $('.filter_type').each(function (index, value) {
                        $(this).attr('name', 'fields[' + index + '][filter_type]');
                    });
                    $('.data_type').each(function (index, value) {
                        $(this).attr('name', 'fields[' + index + '][data_type]');
                    });
                    $('.field_show').each(function (index, value) {
                        $(this).attr('name', 'fields[' + index + '][show]');
                    });
                    $('.field_export').each(function (index, value) {
                        $(this).attr('name', 'fields[' + index + '][export]');
                    });
                    // console.log( $( this ).serializeArray() );
                    // e.preventDefault();
                });
            });
        })(jQuery);


    </script>
@endpush