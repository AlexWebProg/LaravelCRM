@extends('manager.client.edit.layout')

@section('pageStyle')
    @parent
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/client/edit/stat.css') }}">
@endsection

@section('action_type_section')
    <div class="row">
        <div class="col-12">
            <div class="timeline">
                @foreach ($client->stat as $idx => $stat)
                    @if(empty($idx) || $stat->created_text !== $client->stat[$idx - 1]->created_text)
                        <div class="time-label">
                            <span class="bg-lightblue">{{ $stat->created_text }}</span>
                        </div>
                    @endif

                    <div>
                        <i class="fa {{ $stat->stat_icon }}"></i>
                        <div class="timeline-item">
                            <span class="time">{{ $stat->created_str }}</span>
                            <h3 class="timeline-header no-border">{{ $stat->action }}</h3>
                        </div>
                    </div>
                @endforeach
                <div>
                    <i class="fa fa-clock-o bg-gray" aria-hidden="false"></i>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
    @parent
@endsection
