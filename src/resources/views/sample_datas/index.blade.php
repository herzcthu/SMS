@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Sample Datas</h1>
        <h1 class="pull-right">
            <!-- button data-toggle="modal" data-target="#translateModal" class="btn btn-primary" style="margin-top: -10px;margin-bottom: 5px; margin-right:5px;">{!! trans('messages.importTranslation') !!}</button-->

            <button data-toggle="modal" data-target="#importModal" class="btn btn-primary" style="margin-top: -10px;margin-bottom: 5px; margin-right:5px;">{!! trans('messages.import') !!}</button>

           <!-- a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('sampleDatas.create') !!}">{!! trans('messages.add_new') !!}</a-->
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('sample_datas.table')
            </div>
        </div>
    </div>
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    {!! Form::open(['route' => 'sample.import', 'id' => 'importModalForm', 'method' => 'POST', 'files' => true, 'class' => 'form-inline']) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="importModalLabel">Import Sample Data</h4>
      </div>

      <div class="modal-body">
      <h4> Your CSV file should include following columns (ID Code is mandatory)</h4>
      <table class="table table-striped">
          <tr>
              <th>ID Code</th>
              <th>State</th>
              <th>District</th>
              <th>Township</th>
              <th>Village_Tract</th>
              <th>Village</th>
              <th>Name</th>
              <th>N.R.C No.</th>
              <th>Gender</th>
              <th>Date of Birth</th>
              <th>Edu Background</th>
            </tr>
            <tr>
              <th>Father</th>
              <th>Mother</th>
              <th>Ethinicity</th>
              <th>Language</th>
              <th>Current occupation</th>
              <th>Phone no. (1)</th>
              <th>Phone no. (2)</th>
              <th>Address</th>
              <th>Bank Information</th>
              <th>Mobile Provider</th>
          </tr>
      </table>
      <h4> Data type and Data group will be used when building form and generating sample list for data entry.</h4>
        <div class="input-group">
            <span class="input-group-addon" id="datatype">Data Type: </span>
            {!! Form::select('type', ['enumerator' => 'Enumerator', 'spotchecker' => 'Spot Checker', 'location' => 'Location', 'voter' => 'Voter'], null, ['class' => 'form-control', 'aria-describedby' => 'datatype']) !!}
        </div>
         <div class="input-group">
            <span class="input-group-addon" id="dbgroup">Data Group: </span>
            {!! Form::select('dbgroup', ['1' => 'Group 1','2' => 'Group 2','3' => 'Group 3','4' => 'Group 4','5' => 'Group 5'], null, ['class' => 'form-control', 'aria-describedby' => 'dbgroup']) !!}
        </div>
        <div class="input-group">
            <span class="btn btn-primary btn-file" id="file">
                <label for="upload" id="file-label">Browse File</label>
                {!! Form::file('samplefile', ['aria-describedby' => 'file', 'id'=>'upload']); !!}
            </span>
        </div>
      </div>
      <div class="modal-footer">
        <span id="ajaxMesg" class="hidden"></span>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {!! Form::submit('import', ['class' => 'btn btn-primary', 'id' => 'importData']) !!}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

<div class="modal fade" id="translateModal" tabindex="-1" role="dialog" aria-labelledby="translateModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    {!! Form::open(['route' => 'sample.importTranslation', 'id' => 'translateModalForm', 'method' => 'POST', 'files' => true, 'class' => 'form-inline']) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="importModalLabel">Import Sample Data</h4>
      </div>

      <div class="modal-body">
      <h4> Your CSV file should include following columns (ID Code is mandatory)</h4>
      <table class="table table-striped">
          <tr>
              <th>ID Code</th>
              <th>State</th>
              <th>District</th>
              <th>Township</th>
              <th>Village_Tract</th>
              <th>Village</th>
              <th>Name</th>
              <th>N.R.C No.</th>
              <th>Gender</th>
              <th>Date of Birth</th>
              <th>Edu Background</th>
            </tr>
            <tr>
              <th>Father</th>
              <th>Mother</th>
              <th>Ethinicity</th>
              <th>Language</th>
              <th>Current occupation</th>
              <th>Phone no. (1)</th>
              <th>Phone no. (2)</th>
              <th>Address</th>
              <th>Bank Information</th>
              <th>Mobile Provider</th>
          </tr>
      </table>
      <h4> Data type and Data group will be used when building form and generating sample list for data entry.</h4>
        <div class="input-group">
            <span class="input-group-addon" id="datatype">Data Type: </span>
            {!! Form::select('type', ['enumerator' => 'Enumerator', 'spotchecker' => 'Spot Checker', 'location' => 'Location', 'voter' => 'Voter'], null, ['class' => 'form-control', 'aria-describedby' => 'datatype']) !!}
        </div>
         <div class="input-group">
            <span class="input-group-addon" id="dbgroup">Data Group: </span>
            {!! Form::select('dbgroup', ['1' => 'Group 1','2' => 'Group 2','3' => 'Group 3','4' => 'Group 4','5' => 'Group 5'], null, ['class' => 'form-control', 'aria-describedby' => 'dbgroup']) !!}
        </div>
        <div class="input-group">
            <span class="btn btn-primary btn-file" id="file">
                <label for="upload" id="translate-file-label">Browse File</label>
                {!! Form::file('samplefile', ['aria-describedby' => 'file', 'id'=>'transupload']); !!}
            </span>
        </div>
      </div>
      <div class="modal-footer">
        <span id="ajaxMesg" class="hidden"></span>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {!! Form::submit('import', ['class' => 'btn btn-primary', 'id' => 'importData']) !!}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
<!-- https://www.abeautifulsite.net/whipping-file-inputs-into-shape-with-bootstrap-3 -->
@push('before-head-end')
<style type="text/css">
label#file-label {
    margin-bottom: 0px !important;
}
.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
</style>
@endpush
@push('before-body-end')
<script type="text/javascript">
$(document).ready(function(){
    $('#importModal').on('shown.bs.modal', function(event) {
            $('#upload').on('change',function(e){
                fileName = e.target.value.split( '\\' ).pop();
                $('#file-label').html(fileName);
            });
    });

    $('#translateModal').on('shown.bs.modal', function(event) {
            $('#transupload').on('change',function(e){
                fileName = e.target.value.split( '\\' ).pop();
                $('#translate-file-label').html(fileName);
            });
    });
});
</script>
@endpush
