@extends('layouts.app')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <section class="content-header" style="margin-bottom:30px;">
        <h1 class="pull-left">{!! $project->project !!}</h1>
        <div class="pull-right">
            <a href="#" class='btn btn-success' data-toggle="modal"
               data-target="#uploadSamples" data-method='POST'>
                <i class="glyphicon glyphicon-plus"></i> upload samples
            </a>
            <a href="#" class='btn btn-success' data-toggle="modal"
               data-target="#logicModal" data-method='POST'>
                <i class="glyphicon glyphicon-plus"></i> Logic
            </a>
            <a href="{!! route('projects.export', [$project->id]) !!}"
               class="btn btn-info">{!! trans('messages.export_project') !!}</a>
            <a href="{!! route('projects.sort', [$project->id]) !!}"
               class="btn btn-info">{!! trans('messages.sort_project') !!}</a>
            @if($project->status != 'published' || Auth::user()->role->level > 8)
                {!! Form::open(['route' => ['projects.dbcreate', $project->id], 'method' => 'post', 'class' => 'btn']) !!}
                @if($project->status == 'modified')
                    {!! Form::button('<i class="fa fa-list-alt"></i> '.trans('messages.rebuild_form'), [
                        'type' => 'submit',
                        'class' => 'btn btn-danger',
                        'onclick' => 'return confirm("Are you sure?\n This will update live form table for data entry!\nSome serious changes are running.\nPlease do not run this frequently if data entry already live.")'
                    ]) !!}
                @else
                    {!! Form::button('<i class="fa fa-list-alt"></i> '.trans('messages.build_form'), [
                        'type' => 'submit',
                        'class' => 'btn btn-danger',
                        'onclick' => 'return confirm("Are you sure?\nThis will build actual form table for data entry!")'
                    ]) !!}
                @endif

                {!! Form::close() !!}
            @endif
        </div>
    </section>

    <section>
        <div class="content">
            <div class="clearfix"></div>
            @include('flash::message')
            @include('adminlte-templates::common.errors')
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
          <span class="pull-right">
            <!-- Rectangular switch -->
            <label>Training Mode</label>

            <label class="btn btn-secondary switch">
              <input id="training" type="checkbox" autocomplete="off" @if($project->training) checked @endif>
              <div class="slider"></div>
            </label>
            <i class="editProject fa fa-edit btn btn-primary" style="cursor: pointer;font-size: 20px;"></i>
          </span>

                        </div>
                    </div>
                    <div class="row">
                        {!! Form::model($project, ['route' => ['projects.update', $project->id], 'method' => 'patch', 'id' => 'project']) !!}

                        @include('projects.fields')

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            @if(!$project->sections->isEmpty())
                @foreach($project->sections as $section_key => $section)
                    @php
                        //section as css class name
                        $sectionClass = str_slug($section['sectionname'], $separator = "-");
                        $editing = true;
                    @endphp
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="panel-title">
                                {!! $section->sectionname !!}
                                <small> {!! (!empty($section->descriptions))?" | ".$section->descriptions:"" !!}</small>
                                <span class="pull-right"><a href="#" class='btn btn-success' data-toggle="modal"
                                                            data-target="#qModal"
                                                            data-qurl="{!! route('questions.store') !!}"
                                                            data-section="{!! $section->id !!}" data-method='POST'><i
                                                class="glyphicon glyphicon-plus"></i></a></span>
                            </div>
                        </div>
                        <div class="panel-body">
                            @include('projects.table_questions')
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
    @include('questions.modal')
@endsection
@section('css')
    <style type="text/css">
        .toggle {
            display: none;
        }
    </style>
@endsection
@section('scripts')
    @include('projects.logicmodal')
    @include('projects.upload_modal')
    <script type='text/javascript'>
        (function($) {
        var formData = {'fields': ''};
        var sortURL = '{!! route('questions.sort') !!}';
        var trainingUrl = '{!! route('projects.trainingmode', $project->id) !!}';

        $(document).ready(function () {

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });

            $('#training').change(function () {
                if ($(this).is(':checked')) {
                    var trainingmode = 1;
                } else {
                    var trainingmode = 0;
                }

                //send ajax request
                $.ajax({
                    url: trainingUrl,
                    type: 'POST',
                    data: {trainingmode: trainingmode},
                    success: function (data) {

                        if (data.success) {
                            $("#message").html('Sorted');
                            $("#message").addClass('text-green');
                        } else {
                            $("#message").html('Something wrong');
                            $("#message").addClass('text-red');
                        }
                    }

                });
            });

            $('tbody').sortable({
                cursor: 'move',
                axis: 'y',
                update: function (event, ui) {
                    var order = $(this).sortable("serialize");
                    var section = $(this).data('section');
                    order += '&section=' + section;

                    //send ajax request
                    $.ajax({
                        url: sortURL,
                        type: 'POST',
                        data: order,
                        success: function (data) {

                            if (data.success) {
                                $("#message").html('Sorted');
                                $("#message").addClass('text-green');
                            } else {
                                $("#message").html('Something wrong');
                                $("#message").addClass('text-red');
                            }
                        }

                    });
                }
            });

        });
        })(jQuery);

    </script>
@endsection

@section('formbuilder')
    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
        $('#qModal').on('shown.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var formData = button.data('answers')
            var qid = button.data('qid') // Extract info from data-* attributes
            var qnum = button.data('qnum')
            var question = button.data('question')
            var double = button.data('double')
            var optional = button.data('optional')
            var report = button.data('report')
            var sort = button.data('sort')
            var section = button.data('section')
            var layout = button.data('layout')
            var actionurl = button.data('qurl')
            var method = button.data('method')
            var observation = button.data('observation')
            var party = button.data('party')

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)

            if (observation) {
                $.each(observation, function (key, value) {
                    modal.find("input[name='observation_type[" + key + "]']").prop('checked', true)
                });
            } else {
                modal.find("input.observation_type").prop('checked', false)
            }

            if (party) {
                $.each(party, function (key, value) {
                    modal.find("input[name='party[" + key + "]']").val(value);
                });
            }

            modal.find("input[name='qnum']").val(qnum)
            modal.find("input[name='question']").val(question)
            modal.find("input[name='double_entry']").prop('checked', double)
            modal.find("input[name='optional']").prop('checked', optional)
            modal.find("input[name='report']").prop('checked', report)
            if (sort) {
                modal.find("input[name='sort']").val(sort)
            }
            modal.find("input[name='section']").val(section)
            modal.find("select[name='layout']").val(layout)
            modal.find("input[name='_method']").val(method)
            $('#qModalLabel').text(question);
            let fbEditor = $(document.getElementById('fb-editor'));

            let fields = [
                {
                    label: 'Checkbox',
                    attrs: {
                        type: 'check'
                    },
                    icon: '🌟'
                },
                {
                    label: 'Radio',
                    attrs: {
                        type: 'single'
                    },
                    icon: '🌟'
                }
            ];
            let templates = {
                check: function (fieldData) {
                    return {
                        field: '<input type="checkbox" class="magic-pre-checkbox" id="' + fieldData.name + '">'
                    };
                },
                single: function (fieldData) {
                    return {
                        field: '<input type="radio" class="magic-pre-radio"  id="' + fieldData.name + '">'
                    };
                },

            };

            let options = {
                showActionButtons: false, // defaults: true
                editOnAdd: true,
                stickyControls: true,
                dataType: 'json',
                controlOrder: [
                    'checkbox',
                    'checkbox-group',
                    'radio-group',
                    'text',
                    'date',
                    'number',
                    'textarea'
                ],

                disableFields: ['autocomplete', 'button', 'header', 'file', 'paragraph', 'hidden'],

                typeUserAttrs: {
                    text: {
                        skip: {
                            label: 'Skip',
                            type: 'text',
                            name: 'skip',
                            placeholder: 'Space seperated list of Question Number'
                        },
                        goto: {
                            label: 'Go to',
                            type: 'text',
                            name: 'goto',
                            placeholder: 'Single Question Number'
                        },
                        optional: {
                            label: 'Optional',
                            type: 'checkbox',
                            name: 'optional'
                        },
                        other: {
                            label: 'Show Other Textbox',
                            type: 'checkbox',
                            name: 'other'
                        }
                    },
                    date: {
                        skip: {
                            label: 'Skip',
                            type: 'text',
                            name: 'skip',
                            placeholder: 'Space seperated list of Question Number'
                        },
                        goto: {
                            label: 'Go to',
                            type: 'text',
                            name: 'goto',
                            placeholder: 'Single Question Number'
                        },
                        optional: {
                            label: 'Optional',
                            type: 'checkbox',
                            name: 'optional'
                        },
                        other: {
                            label: 'Show Other Textbox',
                            type: 'checkbox',
                            name: 'other'
                        }
                    },
                    number: {
                        skip: {
                            label: 'Skip',
                            type: 'text',
                            name: 'skip',
                            placeholder: 'Space seperated list of Question Number'
                        },
                        goto: {
                            label: 'Go to',
                            type: 'text',
                            name: 'goto',
                            placeholder: 'Single Question Number'
                        },
                        optional: {
                            label: 'Optional',
                            type: 'checkbox',
                            name: 'optional'
                        },
                        other: {
                            label: 'Show Other Textbox',
                            type: 'checkbox',
                            name: 'other'
                        }
                    },
                    check: {
                        skip: {
                            label: 'Skip',
                            type: 'text',
                            name: 'skip',
                            placeholder: 'Space seperated list of Question Number'
                        },
                        goto: {
                            label: 'Go to',
                            type: 'text',
                            name: 'goto',
                            placeholder: 'Single Question Number'
                        },
                        optional: {
                            label: 'Optional',
                            type: 'checkbox',
                            name: 'optional'
                        },
                        value: {
                            type: 'number',
                            placeholder: 'Only number allow'
                        },
                        other: {
                            label: 'Show Other Textbox',
                            type: 'checkbox',
                            name: 'other'
                        }
                    },
                    single: {
                        skip: {
                            label: 'Skip',
                            type: 'text',
                            name: 'skip',
                            placeholder: 'Space seperated list of Question Number'
                        },
                        goto: {
                            label: 'Go to',
                            type: 'text',
                            name: 'goto',
                            placeholder: 'Single Question Number'
                        },
                        optional: {
                            label: 'Optional',
                            type: 'checkbox',
                            name: 'optional'
                        },
                        value: {
                            type: 'number',
                            placeholder: 'Only number allow'
                        },
                        other: {
                            label: 'Show Other Textbox',
                            type: 'checkbox',
                            name: 'other'
                        }
                    },
                    'radio-group': {
                        optional: {
                            label: 'Optional',
                            type: 'checkbox',
                            name: 'optional'
                        }
                    },
                    textarea: {
                        optional: {
                            label: 'Optional',
                            type: 'checkbox',
                            name: 'optional'
                        }
                    }
                },
                disabledAttrs: [
                    'name',
                    'access',
                    'description'
                ],
                defaultFields: formData,
                fields,
                templates
            };

            var formBuilder = fbEditor.formBuilder(options);

            $('#saveQuest').on('click', function (e) {
                e.preventDefault();
                var payload;
                var message;
                payload = formBuilder.formData;
                modal.find('input[name="raw_ans"]').val(payload)
                $.ajax({
                    url: actionurl,
                    type: 'POST',
                    cache: false,
                    data: $("#qModalForm").serialize(),
                    success: function (data) {
                        button.attr('data-answers', data.data.answers);
                        $('#ajaxMesg').text(data.message).addClass('text-success').removeClass('hidden').fadeOut(1400);

                    },
                    error: function (data) {
                        if (data.status == '401')
                            message = "Your session has expired. You need to log in again!"
                        else
                            message = data.message

                        $('#ajaxMesg').text(message).addClass('text-danger').removeClass('hidden').fadeOut(1400);
                    },
                    complete: function () {
                        window.beforeunload = function () {
                            return void 0;
                        }
                        resetForm($("#qModalForm"))
                        setTimeout(function () {
                            window.location.reload();
                        }, 1800);
                    }
                });
                return false;
            });

        }).on('hidden.bs.modal', function () {
            $("#fb-editor").empty()
        })
        });
        })(jQuery);
    </script>
@endsection
@push('before-body-end')
    <style type="text/css">
        .invalid {
            border: 1px solid red;
        }

        .hf-warning {
            color: red;
        }

        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            display: none;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
    <script type="text/javascript">
        (function ($) {
            $('form.translation').submit(function (e) {
                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (data) {
                        alert('OK. Translation saved!');
                    }
                });
                e.preventDefault();
            });
            $(':input').on('keyup change', function () {
                var input = $(this)[0];
                var parent = $(this).parent();
                var validity = input.checkValidity();

                if (validity) {
                    $(this).removeClass('invalid');
                } else {
                    $(this).addClass('invalid');
                }
                input.reportValidity();
            });
        })(jQuery);
    </script>
@endpush
