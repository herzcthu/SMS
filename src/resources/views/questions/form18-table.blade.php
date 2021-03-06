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
                <th colspan="2" width="42%">
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
            @foreach($section->questions->groupBy('party') as $party => $questions)
                @if($party)
                    <tr valign="bottom">

                        @foreach($questions as $question)
                            <td class="serial"></td>
                            <td class="candidate"></td>
                            <td class="party">
                                {{ $party }}
                            </td>
                            @php
                                $element = $question->surveyInputs->first();
                            $options = [
                            'class' => $element->className.' form-control zawgyi '.$sectionClass,
                            'id' => $element->id,
                            'placeholder' => Kanaung\Facades\Converter::convert($element->label,'unicode','zawgyi'),
                            'aria-describedby'=> $element->id.'-addons',
                            'autocomplete' => 'off',
                            'style' => 'width: 150px'
                            ];
                            @endphp
                            @if($question->layout == 'innumber')
                                <td class="innumber">

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <span class="input-group-text" id="{{ $element->id.'-addons' }}">{{ $question->qnum }}</span>
                                        </div>
                                        {!! Form::input($element->type,"result[".$element->inputid."]", (isset($results) && !empty($results['section'.$section->sort]))?Kanaung\Facades\Converter::convert($results['section'.$section->sort]->{$element->inputid},'unicode','zawgyi'):null, $options) !!}
                                    </div>


                                </td>
                            @endif
                            <td class="in_words"></td>
                        @endforeach

                    </tr>
                @endif
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
            @foreach($section->questions as $question)
                @if($question->layout == 'remark')
                    @php
                        $element = $question->surveyInputs->first();
                    $options = [
                    'class' => $element->className.' form-control zawgyi '.$sectionClass,
                    'id' => $element->id,
                    'placeholder' => Kanaung\Facades\Converter::convert($element->label,'unicode','zawgyi'),
                    'aria-describedby'=> $element->id.'-addons',
                    'autocomplete' => 'off',
                    'style' => 'width: 150px'
                    ];
                    @endphp
                    <tr>
                        <td class="rem_label">{{ $question->question }}</td>
                        <td class="rem_value">
                            {!! Form::input($element->type,"result[".$element->inputid."]", (isset($results) && !empty($results['section'.$section->sort]))?Kanaung\Facades\Converter::convert($results['section'.$section->sort]->{$element->inputid},'unicode','zawgyi'):null, $options) !!}
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    </div>
</div>

@push('document-ready')


@endpush
