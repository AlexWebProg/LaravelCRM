@extends('manager.layouts.main')

@section('pageName')Проведение опроса@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Проведение опроса</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid pb-5">
                <form id="main_form" action="{{ route('manager.quiz.survey.store') }}" method="post">
                    @csrf
                    @method('put')
                    <input id="main_form_submit" type="submit" class="d-none">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Шаблон опроса</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Шаблон опроса</label>
                                        <select id="template_id" class="form-control @error('template_id') is-invalid @enderror" name="template_id">
                                            <option value="0">Выберите шаблон опроса ...</option>
                                            @foreach ($templates as $template)
                                                <option value="{{ $template->id }}"
                                                    {{ $template->id == old('template_id',$template_id) ? ' selected' : '' }}>
                                                    {{ $template->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('template_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @foreach ($templates as $template)
                                    <div class="callout callout-info bg-light template d-none" id="t_{{$template->id}}">
                                        {{ $template->intro }}
                                        @foreach ($template->questions as $question)
                                            <hr/>
                                            <p><b>{{ $question->text }}</b></p>
                                            <p>- Возможность поставить оценку: {{ empty($question->rating_enabled) ? 'нет' : 'от ' . $question->rating_from . ' до ' . $question->rating_to }}@if(!empty($question->rating_required)), обязательна для заполнения@endif</p>
                                            <p>- Возможность добавить комментарий: {{ empty($question->comment_enabled) ? 'нет' : 'да' }}@if(!empty($question->comment_required)), обязателен для заполнения@endif</p>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Объекты</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @error('client')
                                    <div class="text-danger mb-3">{{ $message }}</div>
                                    @enderror
                                    <div class="form-group text-truncate">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="all_clients" name="all_clients" value="1" @if(old('all_clients')) checked @endif>
                                            <label for="all_clients" class="font-weight-normal">
                                                <span class="ml-1">Выбрать все</span>
                                            </label>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group text-truncate">
                                        <div class="icheck-primary d-inline">
                                            <input class="client_group_check" type="checkbox" id="to_in_process_1" name="to_in_process_1" value="1" @if(old('to_in_process_1')) checked @endif>
                                            <label for="to_in_process_1" class="font-weight-normal">
                                                <span class="ml-1">Работа начата</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group text-truncate">
                                        <div class="icheck-primary d-inline">
                                            <input class="client_group_check" type="checkbox" id="to_in_process_0" name="to_in_process_0" value="1" @if(old('to_in_process_0')) checked @endif>
                                            <label for="to_in_process_0" class="font-weight-normal">
                                                <span class="ml-1">Работа не начата</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group text-truncate">
                                        <div class="icheck-primary d-inline">
                                            <input class="client_group_check" type="checkbox" id="to_ob_status_2" name="to_ob_status_2" value="1" @if(old('to_ob_status_2')) checked @endif>
                                            <label for="to_ob_status_2" class="font-weight-normal">
                                                <span class="ml-1">Объекты на гарантии</span>
                                            </label>
                                        </div>
                                    </div>
                                    <hr/>
                                    @foreach ($clients as $client)
                                        <div class="form-group text-truncate">
                                            <div class="icheck-primary d-inline">
                                                <input class="client_check @if($client->ob_status === 2) ob_status_2 @elseif($client->in_process === 1) in_process_1 @else in_process_0 @endif" type="checkbox" id="client_{{ $client->id }}" name="client[{{ $client->id }}]" value="{{ $client->id }}" @if(old('client.'.$client->id) || old('all_clients')) checked @endif>
                                                <label for="client_{{ $client->id }}" class="font-weight-normal">
                                                    <span class="ml-1">{{ $client->address }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/manager/quiz/survey/form.js') }}"></script>
@endsection
