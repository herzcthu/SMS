@php
    if($element->type == 'date') $element->className .= ' form-control date';

    $options = [
    'class' => $element->className.' form-control zawgyi '.$sectionClass,
    'id' => $element->id,
    'placeholder' => $element->label,
    'aria-describedby'=> $element->id.'-addons',
    'data-class'=>$element->inputid,
    'data-origin' => $origin_text,
    'autocomplete' => 'off'
    ];
    if($element->type == 'number') {
        $options['placeholder'] .= ' (number) ';
        if(isset($element->extras['min'])) $options['min'] = $element->extras['min'];
        if(isset($element->extras['max'])) $options['max'] = $element->extras['max'];
        if(isset($element->extras['step'])) $options['step'] = $element->extras['step'];

        if(isset($element->extras['min']) || isset($element->extras['max']))
            $options['placeholder'] .= $element->extras['min'].' - '. $element->extras['max'];
    }
@endphp
<div class="form-group">
    <div class="input-group">
        <!-- if string long to show in label show as tooltip -->
        <span class="input-group-addon" id="{{ $element->id }}-addons"
              @if(mb_strlen($element->label) > $wordcount) data-toggle="tooltip" data-placement="top"
              title="{!! $element->label !!}" @endif>
    		@if(mb_strlen($element->label) > $wordcount)
                {!! str_limit($element->label, $wordcount - 7 ) !!} <i class="fa fa-info-circle"></i>
            @else
                {!! $element->label !!}
            @endif
            @if($element->type == 'text' && in_array(strtolower($element->label), ['other', 'others']))
                @if($element->value != '') <span class="label label-primary badge">{!! $element->value !!}</span> @endif
            @endif
            @if($element->status != 'published') <span
                    class="label label-warning badge">{!! $element->status !!}</span> @endif
            <span class="hide label label-danger badge {!! $element->inputid .' '.$element->id!!}"><i class="fa"></i></span>
            </span>
        {!! Form::input($element->type,"result[".$element->inputid."]", (isset($double_results) && !empty($double_results['section'.$section->sort]))?Kanaung\Facades\Converter::convert($double_results['section'.$section->sort]->{$element->inputid},'unicode','zawgyi'):null, $options) !!}
    </div>
</div>
@if(!empty($element->skip) && !isset($editing))
    @push('document-ready')
        if($("input[name='result[{!! $element->inputid !!}]']").val() != "") {
        $("{!! $element->skip !!}").prop("disabled", true);
        } else {
        $("{!! $element->skip !!}").prop("disabled", false);
        }
        $("input[name='result[{!! $element->inputid !!}]']").on('focusout',function(){
        if($("input[name='result[{!! $element->inputid !!}]']").val() != "") {
        $("{!! $element->skip !!}").prop("disabled", true);
        @if(isset($element->extras['goto']))
            $("body, html").animate({
            scrollTop: $("{!! $element->extras['goto'] !!}").offset().top
            }, 600);
        @endif
        } else {
        $("{!! $element->skip !!}").prop("disabled", false);
        }
        });
    @endpush
@endif
