@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Settings</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('settings.create') !!}">Add New</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    {!! Form::open(['route' => 'settings.save']) !!}
                            <!-- APP Name Field -->
                            <div class="form-group">
                                <div class="input-group">
                                    <!-- if string long to show in label show as tooltip -->
                                    <span class="input-group-addon">
                                        {!! 'APP Name :' !!}
                                    </span>
                                    {!! Form::text("configs[app_name]", settings('app_name', null), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <!-- if string long to show in label show as tooltip -->
                                    <span class="input-group-addon">
                                        {!! 'API KEY :' !!}
                                    </span>
                                    {!! Form::text("configs[api_key]", settings('api_key', null), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <!-- if string long to show in label show as tooltip -->
                                    <span class="input-group-addon">
                                        {!! 'Project ID :' !!}
                                    </span>
                                    {!! Form::text("configs[project_id]", settings('project_id', null), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        </div>

                    {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
