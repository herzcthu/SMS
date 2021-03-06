@php
    if(isset($sample)) {
        $parties = explode(',', $sample->data->parties);
    } else {
        $parties = explode(',', $project->parties); //to remove later
    }
@endphp
<div class="row">
    <div class="col-sm-12 ">
        <div class='fade in' id="ballot-error">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <table class="table table-bordered table-responsive" style="vertical-align:middle">
            <tr valign="bottom">
                <th rowspan="2" height="12">
                    <p>{!! trans('ballots.serial') !!}</p>
                </th>
                <th rowspan="2">
                    <p style="margin-bottom: 0in"><br/>

                    </p>
                    <p>{!! trans('ballots.candidate') !!}</p>
                </th>
                <th rowspan="2">
                    <p>{!! trans('ballots.party') !!}</p>
                </th>
                <th colspan="2">
                    <p>{!! trans('ballots.votes_cast') !!}</p>
                </th>
            </tr>
            <tr valign="bottom">
                <th>
                    <p>{!! trans('ballots.in_numbers') !!}</p>
                </th>
                <th>
                    <p>{!! trans('ballots.in_words') !!}</p>
                </th>
            </tr>
            @foreach($parties as $party)
                <tr valign="top">
                    <td>
                    </td>
                    <td>

                    </td>
                    <td>
                        <p>{!! $party !!}</p>
                    </td>
                    <td>
                        {!! Form::number("result[ballot][".trim($party)."][advanced]", (isset($results))?Kanaung\Facades\Converter::convert($results['section'.$section->sort]->{trim($party).'_advanced'},'unicode','zawgyi'):null, ['class' => 'form-control input-sm party-advanced']) !!}
                    </td>
                    <td>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" width="100%" height="89" valign="top">
                    <p>{!! trans('ballots.witnesses') !!}</p>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-sm-4">
        <table class="table table-bordered table-responsive">
            <tr>
                <th colspan="2">{!! trans('ballots.remarks') !!}</th>
            </tr>

            <tr>
                <td>
                    <p>{!! trans('ballots.ballots_issued') !!}</p>
                </td>
                <td class="col-sm-5">
                    {!! Form::number("result[ballot_remark][rem1]", (isset($results))?Kanaung\Facades\Converter::convert($results['section'.$section->sort]->rem1,'unicode','zawgyi'):null, ['class' => 'form-control input-sm remarks', 'id' => 'rem1']) !!}
                </td>
            <tr>
            <tr>
                <td>
                    <p>{!! trans('ballots.ballots_received') !!}</p>
                </td>
                <td class="col-sm-5">
                    {!! Form::number("result[ballot_remark][rem2]", (isset($results))?Kanaung\Facades\Converter::convert($results['section'.$section->sort]->rem2,'unicode','zawgyi'):null, ['class' => 'form-control input-sm remarks', 'id' => 'rem2']) !!}
                </td>
            <tr>
            <tr>
                <td>
                    <p>{!! trans('ballots.valid_advanced') !!}</p>
                </td>
                <td class="col-sm-5">
                    {!! Form::number("result[ballot_remark][rem3]", (isset($results))?Kanaung\Facades\Converter::convert($results['section'.$section->sort]->rem3,'unicode','zawgyi'):null, ['class' => 'form-control input-sm remarks', 'id' => 'rem3']) !!}
                </td>
            <tr>
            <tr>
                <td>
                    <p>{!! trans('ballots.invalid_advanced') !!}</p>
                </td>
                <td class="col-sm-5">
                    {!! Form::number("result[ballot_remark][rem4]", (isset($results))?Kanaung\Facades\Converter::convert($results['section'.$section->sort]->rem4,'unicode','zawgyi'):null, ['class' => 'form-control input-sm remarks', 'id' => 'rem4']) !!}
                </td>
            <tr>
            <tr>
                <td>
                    <p>{!! trans('ballots.missing_advanced') !!}</p>
                </td>
                <td class="col-sm-5">
                    {!! Form::number("result[ballot_remark][rem5]", (isset($results))?Kanaung\Facades\Converter::convert($results['section'.$section->sort]->rem5,'unicode','zawgyi'):null, ['class' => 'form-control input-sm remarks', 'id' => 'rem5']) !!}
                </td>
            <tr>

        </table>
    </div>
</div>


@push('document-ready')
    var rem1 = parseInt($('#rem1').val(), 10);
    var rem2 = parseInt($('#rem2').val(), 10);
    var rem3 = parseInt($('#rem3').val(), 10);
    var rem4 = parseInt($('#rem4').val(), 10);
    var rem5 = parseInt($('#rem5').val(), 10);


    var party_advanced = 0;
    $('.party-advanced').each(function() {
    var each_advanced = parseInt($(this).val(),10);
    if(each_advanced){
    party_advanced += each_advanced;
    }
    });
    var error = false;

    if(party_advanced > rem3){
    error = true;
    $('#log10').remove();
    $('#ballot-error').append('
    <div id="log10">{{ trans('ballots.log10') }}<br></div>');
    }
    if(rem1 && rem2 && rem5 && (rem1 != (rem2 + rem5) ) ) {
    error = true;
    $('#log11').remove();
    $('#ballot-error').append('
    <div id="log11">{{ trans('ballots.log11') }}<br></div>');
    }
    if( rem2 && rem3 && rem4 && ( rem2 != (rem3 + rem4)) ) {
    error = true;
    $('#log9').remove();
    $('#ballot-error').append('
    <div id="log9">{{ trans('ballots.log9') }}<br></div>' );
    }

    if(error && !$('#ballot-error').is(':empty')) {
    $('#ballot-error').addClass('alert alert-danger ');
    error = false;
    } else {
    $('#ballot-error').removeClass('alert alert-danger ').html('');
    }

    $('.remarks').on('keyup', function(e){
    var rem1 = parseInt($('#rem1').val(), 10);
    var rem2 = parseInt($('#rem2').val(), 10);
    var rem3 = parseInt($('#rem3').val(), 10);
    var rem4 = parseInt($('#rem4').val(), 10);
    var rem5 = parseInt($('#rem5').val(), 10);


    var party_advanced = 0;
    $('.party-advanced').each(function() {
    var each_advanced = parseInt($(this).val(),10);
    if(each_advanced){
    party_advanced += each_advanced;
    }
    });

    if(party_advanced > rem3){
    error = true;
    $('#log10').remove();
    $('#ballot-error').append('
    <div id="log10">{{ trans('ballots.log10') }}<br></div>');
    } else {
    $('#log10').remove();
    }
    if(rem1 && rem2 && rem5 && (rem1 != (rem2 + rem5) ) ){
    error = true;
    $('#log11').remove();
    $('#ballot-error').append('
    <div id="log11">{{ trans('ballots.log11') }}<br></div>');
    } else {
    $('#log11').remove();
    }

    if( rem2 && rem3 && rem4 && ( rem2 != (rem3 + rem4)) ) {
    error = true;
    $('#log9').remove();
    $('#ballot-error').append('
    <div id="log9">{{ trans('ballots.log9') }}<br></div>' );
    } else {
    $('#log9').remove();
    }

    if(error && !$('#ballot-error').is(':empty')) {
    $('#ballot-error').addClass('alert alert-danger ');
    error = false;
    } else {
    $('#ballot-error').removeClass('alert alert-danger ').html('');
    }

    });


    $('.party-advanced').on('keyup', function(e){
    var rem1 = parseInt($('#rem1').val(), 10);
    var rem2 = parseInt($('#rem2').val(), 10);
    var rem3 = parseInt($('#rem3').val(), 10);
    var rem4 = parseInt($('#rem4').val(), 10);
    var rem5 = parseInt($('#rem5').val(), 10);


    var party_advanced = 0;
    $('.party-advanced').each(function() {
    var each_advanced = parseInt($(this).val(),10);
    if(each_advanced){
    party_advanced += each_advanced;
    }
    });


    if(party_advanced > rem3){
    error = true;
    $('#log10').remove();
    $('#ballot-error').append('
    <div id="log10">{{ trans('ballots.log10') }}<br></div>');
    } else {
    $('#log10').remove();
    }
    if(rem1 && rem2 && rem5 && (rem1 != (rem2 + rem5) ) ){
    error = true;
    $('#log11').remove();
    $('#ballot-error').append('
    <div id="log11">{{ trans('ballots.log11') }}<br></div>');
    } else {
    $('#log11').remove();
    }

    if( rem2 && rem3 && rem4 && ( rem2 != (rem3 + rem4)) ) {
    error = true;
    $('#log9').remove();
    $('#ballot-error').append('
    <div id="log9">{{ trans('ballots.log9') }}<br></div>' );
    } else {
    $('#log9').remove();
    }

    if(error && !$('#ballot-error').is(':empty')) {
    $('#ballot-error').addClass('alert alert-danger ');
    error = false;
    } else {
    $('#ballot-error').removeClass('alert alert-danger ').html('');
    }

    });



@endpush
