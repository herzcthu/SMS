@extends('layouts.app')
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@php
    $select_filters = $project->locationMetas->where('filter_type', 'selectbox')->pluck('field_name');

@endphp
@section('content')
    <section class="content-header">
        <h1 class="pull-left">Projects Sample Response Rate</h1>
        <span class="pull-right">
        <label>Response rate by:
            <select autocomplete="off" id="responseBy" class="form-control input-md">
               @foreach($select_filters as $filter)

                   <option value="{!! route('projects.response.filter', [$project->id, $filter]) !!}" @if($data['filters']['type'] === $filter) selected="selected" @endif>{!! trans('samples.'.$filter) !!}</option>

               @endforeach
           </select>
        </label>
        </span>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('projects.table')
            </div>
        </div>
    </div>
@endsection

@push('document-ready')
    $('#responseBy').on('change', function(e){
        var filterurl = $(this).val();
        console.log(filterurl);
        window.location.href = filterurl;
    });
@endpush
