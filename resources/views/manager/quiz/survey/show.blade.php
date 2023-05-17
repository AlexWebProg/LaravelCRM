@extends('manager.layouts.main')

@section('pageName')Опрос по шаблону "{{ $survey->template->name }}" от {{ $survey->created_str }}@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/quiz/survey/show.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Опрос по шаблону "{{ $survey->template->name }}" от {{ $survey->created_str }}</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('manager.quiz.survey.index') }}">Проведённые опросы</a></li>
                    <li class="breadcrumb-item active">Опрос по шаблону "{{ $survey->template->name }}" от {{ $survey->created_str }}</li>
                </ol>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid pb-5">
                <div class="card card-primary">
                    <div class="card-body">
                        В опросе приняли участие <a href="javascript:void(0);" data-toggle="modal" data-target="#modal_client_list">{{ $survey->clients_completed->count() }} из {{ $survey->clients->count() }} заказчиков</a>
                        @foreach ($survey->template->questions as $question)
                            <hr/>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p><b>{{ $question->text }}</b></p>
                                    <p>- Возможность поставить оценку: {{ empty($question->rating_enabled) ? 'нет' : 'от ' . $question->rating_from . ' до ' . $question->rating_to }}@if(!empty($question->rating_required)), обязательна для заполнения@endif</p>
                                    <p>- Возможность добавить комментарий: {{ empty($question->comment_enabled) ? 'нет' : 'да' }}@if(!empty($question->comment_required)), обязателен для заполнения@endif</p>
                                </div>
                                <div class="col-lg-6">
                                    @if (!empty($question->rating_enabled))
                                        <canvas id="ratingChart_{{ $question->id }}" class="ratingChart"></canvas>
                                    @endif
                                    @if (!empty($question->comment_enabled))
                                        <p class="text-right"><i class="fa fa-comments-o mr-2" aria-hidden="true"></i>Всего комментариев: {{ $arAnswers[$question->id]['totalComments'] }}</p>
                                    @endif
                                    <p class="text-right"><button class="btn btn-info" data-toggle="modal" data-target="#modal_question{{$question->id}}_answers"><i class="fa fa-star-half-o mr-2" aria-hidden="true"></i>Подробности</button></p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @can('edit_quiz')
                <div class="row mt-5">
                    <div class="col-md-6 offset-md-6">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Функции</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-footer">
                                <form action="{{ route('manager.quiz.survey.delete', $survey->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger confirm_survey_delete w-100"><i class="fa fa-trash mr-2"></i>Удалить опрос</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div class="modal fade" id="modal_client_list">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Участники опроса</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @foreach ($survey->clients as $survey_client)
                        <p title="{{ empty($survey_client->completed_at) ? 'Ещё не ответил на опрос' : 'Ответил на опрос' }}" class="text-truncate">
                            <i class="fa fa-{{ empty($survey_client->completed_at) ? 'times text-secondary' : 'check text-success' }} mr-2" aria-hidden="true"></i>
                            <span class="answer_client_href" data-link="{{ route('manager.client.edit', $survey_client->client->id) }}">{{ $survey_client->client->address }}</span>
                        </p>
                    @endforeach
                </div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle-o mr-2" aria-hidden="true"></i>Закрыть окно</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    @foreach ($survey->template->questions as $question)
        <div class="modal fade" id="modal_question{{$question->id}}_answers">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-body pb-1">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <b>{{ $question->text }}</b>
                        @if (!empty($arAnswers[$question->id]['answers']))
                            <hr class="answer_hr"/>
                            <select class="form-control answers_sort">
                                <option value="created_desc">Сначала новые</option>
                                <option value="created_asc">Сначала старые</option>
                                <option value="rating_asc">Сначала с низкой оценкой</option>
                                <option value="rating_desc">Сначала с высокой оценкой</option>
                                <option value="address_asc">По адресу заказчика</option>
                            </select>
                            <div class="answers">
                                @foreach ($arAnswers[$question->id]['answers'] as $answer)
                                    <div class="answer" data-rating="{{ $answer->rating }}" data-address="{{ $answer->client->address }}" data-created="{{ strtotime($answer->created_at) }}">
                                        <hr class="answer_hr"/>
                                        <p class="text-truncate">
                                            @if (!empty($answer->rating))
                                                <span class="mr-2">{!! $answer->rating_star_with_empty($question->rating_to) !!}</span>
                                            @endif
                                            <span class="answer_client_href" data-link="{{ route('manager.client.edit', $answer->client->id) }}">{{ $answer->client->address }}</span>
                                            <small class="text-muted ml-2">{{ $answer->created_str }}</small>
                                        </p>
                                        @if (!empty($answer->comment))
                                            <p class="text_pre_line">{!! $answer->comment !!}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer text-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle-o mr-2" aria-hidden="true"></i>Закрыть окно</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    @endforeach

@endsection

@section('pageScript')
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart-js-3-9-1/chart.min.js') }}"></script>
    <script src="{{ assetVersioned('dist/js/pages/manager/quiz/survey/show.js') }}"></script>
    <script>
        $(document).ready(function () {
            @foreach ($arAnswers as $question_id => $arAnswer)
                @if (!empty($arAnswer['arAnswerRating']))
                    new Chart(document.getElementById("ratingChart_{{ $question_id }}"), {
                        type: 'bar',
                        data: {
                            labels: [
                                @foreach ($arAnswer['arAnswerRating'] as $rating => $answers_with_rating_qnt)
                                    '{{ $arAnswer['rating_label'][$rating] }}',
                                @endforeach
                            ],
                            datasets: [
                                {
                                    label: 'Поставили эту оценку',
                                    data: [
                                        @foreach ($arAnswer['arAnswerRating'] as $rating => $answers_with_rating_qnt)
                                            {{ $answers_with_rating_qnt }},
                                        @endforeach
                                    ],
                                    backgroundColor: '#17a2b8',
                                },
                                {
                                    label: 'Всего оценок поставили',
                                    data: [
                                        @foreach ($arAnswer['arAnswerRating'] as $rating => $answers_with_rating_qnt)
                                            {{ $arAnswer['totalAnswersWithRating'] }},
                                        @endforeach
                                    ],
                                    backgroundColor: '#f2f2f2',
                                }
                            ],
                        },
                        options: {
                            indexAxis: 'y',
                            elements: {
                                bar: {
                                    borderWidth: 0,
                                },
                            },
                            scales: {
                                x: {
                                    display: false,
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    stacked: true,
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false,
                                },
                                title: {
                                    display: true,
                                    text: 'Всего оценок: {{ $arAnswer['totalAnswersWithRating'] }}, средняя: {{ $arAnswer['averageRating'] }}'
                                },
                            },
                        }
                    });
                @endif
            @endforeach
        });
    </script>
@endsection
