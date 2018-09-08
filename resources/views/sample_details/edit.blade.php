@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Sample Details
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($sampleDetails, ['route' => ['sample-details.update', $sampleDetails->id], 'method' => 'patch']) !!}

                        @include('sample_details.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection