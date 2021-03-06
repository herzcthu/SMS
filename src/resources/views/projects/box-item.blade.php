@php
    $response_filter =  '';
    if(!$project->locationMetas->isEmpty()) {
        if(!$project->locationMetas->where('filter_type', 'selectbox')->isEmpty()) {
            $response_filter = $project->locationMetas->where('filter_type', 'selectbox')->first()->field_name;
        }
    }
@endphp
<div class="box box-primary">
    <div class="box-body">
        <h4>{{ $project->project }}</h4>
        <div class="media">
            <div class="media-left">
                <a href="{{ route('projects.surveys.index', $project->id) }}">
                    <span style="background-color: #eee;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);text-align: center;font-family: 'Lobster';font-size: 5vw;">{{ $project->unique_code }}</span>
                </a>
            </div>
            <div class="media-body">
                <div class="clearfix">
                @if(in_array(Auth::user()->role->level,[3,4,5,6,7,8,9]))
                    @if($project->type == 'dynamic')
                        <a href="{{ route('projects.surveys.index', $project->id) }}"
                           class='btn btn-success btn-sm' style='margin-bottom: 5px;'>
                            <i class="glyphicon glyphicon-eye-open"></i> {!! trans('messages.list_incidents') !!}
                        </a>
                        <a href="{{ route('project.monitor', $project->id) }}" class='btn btn-default btn-sm' style='margin-bottom: 5px;'>
                            <i class="glyphicon glyphicon-eye-envelope"></i> Monitor Incidents
                        </a>
                    @else
                        <a href="{{ route('projects.surveys.index', $project->id) }}"
                           class='btn btn-success btn-sm' style='margin-bottom: 5px;'>
                            <i class="glyphicon glyphicon-eye-open"></i> {!! trans('messages.list_samples') !!}
                        </a>
                    @endif
                @endif
                @if(in_array(Auth::user()->role->level,[3,4,5,6,7,8,9]))
                        <a href="{{ route('projects.response.filter', [$project->id, $response_filter]) }}"
                           class='btn btn-default btn-sm' style='margin-bottom: 5px;'>
                            <i class="glyphicon glyphicon-equalizer"></i> {!! trans('messages.response') !!}
                        </a>
                @endif
                @if(in_array(Auth::user()->role->level,[8,9]))
                        <a href="{{ route('projects.edit', $project->id) }}" class='btn btn-default btn-sm'
                           style='margin-bottom: 5px;'>
                            <i class="glyphicon glyphicon-edit"></i> {!! trans('messages.edit') !!}
                        </a>
                    @endif
                    @if(in_array(Auth::user()->role->level,[3,7,8,9]))
                            <a href="{{ route('projects.channel.rates', [$project->id]) }}"
                               class='btn btn-default btn-sm' style='margin-bottom: 5px;'>
                                <i class="glyphicon glyphicon-equalizer"></i> {!! trans('messages.channel-rate') !!}
                            </a>
                        @if(config('sms.double_entry'))
                            <a href="{{ route('projects.response.filter', [$project->id, $response_filter, 'double']) }}"
                               class='btn btn-default btn-sm' style='margin-bottom: 5px;'>
                                <i class="glyphicon glyphicon-transfer"></i> {!! trans('messages.double_entry') !!}
                            </a>
                            <a href="{{ route('projects.response.double', [$project->id]) }}"
                               class='btn btn-default btn-sm' style='margin-bottom: 5px;'>
                                <i class="fa fa-balance-scale"></i> {!! trans('messages.check') !!}</i>
                            </a>
                        @endif
                    @endif
                    @if(in_array(Auth::user()->role->level,[7,8,9]))
                        <a href="{{ route('projects.analysis', $project->id) }}"
                           class='btn btn-default btn-sm' style='margin-bottom: 5px;'>
                            <i class="fa fa-pie-chart"></i> {!! trans('messages.analysis') !!}
                        </a>
                    @endif
                    @if(in_array(Auth::user()->role->level,[3,4,5,6,7,8,9]))
                        <a href="{{ route('projects.smslog', $project->id) }}" class='btn btn-default btn-sm' style='margin-bottom: 5px;'>
                        <i class="fa fa-envelope"></i> {!! trans('messages.smslog') !!}
                        </a>
                    @endif
                    @if(in_array(Auth::user()->role->level,[7,8,9]))
                        <a href="{{ route('location-metas.show-structure', $project->id) }}"
                           class='btn btn-default btn-sm' style='margin-bottom: 5px;'>
                            <i class="glyphicon glyphicon-edit"></i>Edit Sample Columns
                        </a>
                    @endif
                    @if(in_array(Auth::user()->role->level,[7,8,9]))
                        <a href="{{ route('sample-details.index', ['project_id' => $project->id]) }}"
                           class='btn btn-default btn-sm' style='margin-bottom: 5px;'>
                            <i class="glyphicon glyphicon-edit"></i>List Sample Data
                        </a>
                    @endif
                </div>
            </div>
            <!--div class="media-right">
                @if(Auth::user()->role->level >= 7)
                {!! Form::open(['route' => ['projects.destroy', $project->id], 'method' => 'delete', 'class' => 'from-inline']) !!}

                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
                    'type' => 'submit',
                    'class' => 'btn btn-danger',
                    'style' => 'height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);text-align: center;font-family: "Lobster";font-size: 2vw;',
                    'onclick' => "return confirm('".trans('messages.are_you_sure')."')"
                ]) !!}

                {!! Form::close() !!}
                @endif
            </div-->
        </div>
    </div>
</div>