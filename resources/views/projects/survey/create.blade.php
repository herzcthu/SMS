@extends('layouts.app')
@push('before-head-end')
<script type="text/javascript">
window.url="{!! route('projects.surveys.save', ['project' => $project->id, 'sample' => $sample->id]) !!}"
</script>
@endpush
@push('after-body-start')
<!--a class="btn btn-primary pull-right btn-float btn-float-up save" style="display:inline;margin-right:15px;" href="#" data-id="survey-form">{{ trans('messages.saveall') }}</a>
           <a class="pull-right btn-float btn-float-bottom btn-float-to-up" style="display:inline;font-size: 40px;" href="#"><i class="fa fa-arrow-circle-up"></i></a-->
@endpush
@section('content')
<form autocomplete="off">
    <section class="content-header">

    @if($project->status != 'published')
            <div class="alert alert-warning">
                Project modified. Rebuild to show new changes in this form.
            </div>
        @endif
        <h1 class="pull-left">{!! Form::label('name', $project->project) !!}</h1>

        <!--h1 class="pull-right">
            <a class="btn btn-default pull-right" style="display:inline;margin-top: -10px;margin-bottom: 5" href="{!! route('projects.surveys.index', $project->id) !!}" data-id="survey-form"> {{ trans('messages.back') }}</a>
           <a class="btn btn-primary pull-right save" style="display:inline;margin-top: -10px;margin-bottom: 5" href="#" data-id="survey-form"> {{ trans('messages.saveall') }}</a>
        </h1-->
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')

        <div class="clearfix"></div>

        @include('projects.survey.info_table')

        <div id="survey-form">
        @foreach($project->sections as $section_key => $section)
        @php
            //section as css class name
            $sectionClass = str_slug($section->sectionname, $separator = "-");
            $section_num = $section->sort + 1;

            if( isset($results) && !empty($results['section'.$section->sort]) ) {
                $section_status = $results['section'.$section->sort]->{'section'.$section->sort.'status'};
                if( $section_status == 0) {
                    $section_status = 'danger';
                    $icon = 'remove';
                } else if($section_status  == 1) {
                    $section_status = 'success';
                    $icon = 'ok';
                } else if($section_status  == 2) {
                    $section_status = 'warning';
                    $icon = 'ban-circle';
                } else if($section_status  == 3) {
                    $section_status = 'info';
                    $icon = 'alert';
                } else {
                    $section_status = 'danger';
                    $icon = 'remove';
                }
            } else {
                $section_status = 'primary';
                $icon = 'question';
            }

        @endphp
        <div class="panel panel-{{ $section_status }}" id="{!! $sectionClass !!}">
            <div class="panel-heading">
                <div class="panel-title">
                    {!! $section->sectionname !!} <small> {!! (!empty($section->descriptions))?" | ".$section->descriptions:"" !!}</small>

                    @if( isset($results) )
                        <span class="pull-right">
                        <span class="badge">
                            <span class="glyphicon glyphicon-{{ $icon }}"></span>
                        </span>
                        </span>
                    @else
                        <span class="pull-right">
                        <span class="badge">
                            <span class="glyphicon glyphicon-remove text-danger"></span>
                        </span>
                        </span>
                    @endif
                </div>
            </div>
            <div class="panel-body">

                @include('projects.show_fields')

                <h1 class="pull-right">
                   <a class="btn btn-sm btn-info pull-right save" data-section="{!! $section->id !!}" data-id="{!! $sectionClass !!}" style="display:inline;margin-top: -10px;margin-bottom: 5" href="#"> {{ trans('messages.savesection') }}</a>
                </h1>
            </div>
        </div>
        @endforeach
        </div>
    </div>
</form>
@endsection

<!-- copy from https://getflywheel.com/layout/add-sticky-back-top-button-website/ -->
@section('css')
<style>


</style>
@endsection

@push('before-body-end')
<!-- Modal -->
  <div class="modal fade" id="alert" role="dialog">
    <div class="modal-dialog modal-sm">

      <!-- Modal content-->
      <div class="modal-content">

        <div class="modal-body">
          <p id="submitted">Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
<style type="text/css">
.zawgyi {
    font-family: "Zawgyi-One" !important;
}
.invalid {
    border: 1px solid red;
}
.hf-warning {
  color:red;
}
.modal {
  text-align: center;
  padding: 0!important;
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}
</style>
<script type='text/javascript'>
    (function($) {

    if($(".none").is(':checked')) {
          $(".none:checked").closest('.row').children().children().children().children('input').each(function(i, obj) {
             obj.disabled = true;
            });
            $(".none").prop("disabled", false);
        }
    $("input.none").change(function(e){
        if($(this).is(':checked')) {
            $(this).closest('.row').children().children().children().children('input').each(function(i, obj) {
             obj.disabled = true;
            });
            $(this).prop("disabled", false);
        } else {
            $(this).closest('.row').children().children().children().children('input').each(function(i, obj) {
            if(obj.type != 'text') {
             obj.checked = false;
             obj.disabled = false;
            }
            });
        }
    });

        $('.save').click(function(event){
            event.preventDefault();
            $('.loading').removeClass("hidden");

            var id = $(this).data('id');

            var section_id = $(this).data('section');

            //$('#'+id).find(":input").filter(function(){ return !this.value; }).attr("disabled", "disabled");
            var info_data = $('.info').serializeArray();

            var section_data = $('#'+id+' :input').serializeArray();

            section_data.push({name: 'samplable_type', value: $('#sample').val()},{name: 'section_id', value: section_id});

            var ajaxData = $.merge(info_data, section_data);

            request = sendAjax(url,ajaxData)

            //console.log(request);

            request.done(function( msg ) {
                $('#submitted').html(msg.message);

                $('#alert').modal('show');
            });

            request.fail(function( jqXHR, textStatus ) {
                $('#submitted').html(jqXHR.responseJSON.message);

                $('#alert').modal('show');

            });

            request.always(function(){
                setTimeout(function(){
                $('.loading').addClass("hidden");
                }, 400);
            });
            $('#alert').on('hidden.bs.modal', function () {
                if(id == 'survey-form') {
                    window.location.href = "{{ route('projects.surveys.index', $project->id) }}";
                } else {
                    window.location.reload();
                }
            })
            $('#'+id).find(":input").filter(function(){ return !this.value; }).removeAttr("disabled");

        });



        $(':input').on('keyup change',function(){
            var input = $(this)[0];
            var parent = $(this).parent();
            var validity = input.checkValidity();
            //console.log(validity);
            if(validity) {
                $(this).removeClass('invalid');
            } else {
                $(this).addClass('invalid');
            }
            input.reportValidity();
        });
        $("input[type=radio]").click(function() {
            // Get the storedValue
            var previousValue = $(this).data('selected');
            // if previousValue = true then
            //     Step 1: toggle radio button check mark.
            //     Step 2: save data-StoredValue as false to indicate radio button is unchecked.
            if (previousValue) {
                $(this).prop('checked', !previousValue);
                $(this).data('selected', !previousValue);
            }
            // If previousValue is other than true
            //    Step 1: save data-StoredValue as true to for currently checked radio button.
            //    Step 2: save data-StoredValue as false for all non-checked radio buttons.
            else{
                $(this).data('selected', true);
                $("input[type=radio]:not(:checked)").data("selected", false);
            }
        });
    })(jQuery);
    </script>
@endpush

@if(Auth::user()->role->role_name == 'doublechecker')

@push('document-ready')
$(":input").on('change keyup', function(e){
    var cssid = $(this).attr('id');
    var cssclass = $(this).data('class');
    if($(this).val() != $(this).data('origin')) {
        $('.'+cssclass).addClass('hide');
        $('.'+cssid).removeClass('hide');
        //console.log('data not match ' + cssid + cssclass);
    } else {
        $('.'+cssclass).addClass('hide');
    }
    e.preventDefault();
});


$("input[type=radio]").click(function() {
    // Get the storedValue
    var previousValue = $(this).data('selected');
    // if previousValue = true then
    //     Step 1: toggle radio button check mark.
    //     Step 2: save data-StoredValue as false to indicate radio button is unchecked.
    if (previousValue) {
    $(this).prop('checked', !previousValue);
    $(this).data('selected', !previousValue);
    }
    // If previousValue is other than true
    //    Step 1: save data-StoredValue as true to for currently checked radio button.
    //    Step 2: save data-StoredValue as false for all non-checked radio buttons.
    else{
    $(this).data('selected', true);
    $("input[type=radio]:not(:checked)").data("selected", false);
    }
});

@endpush
@endif
