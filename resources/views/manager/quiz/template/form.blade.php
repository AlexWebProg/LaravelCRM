@extends('manager.layouts.main')

@section('pageName'){{ !empty($template?->name) ? $template->name : 'Добавление шаблона опроса' }}@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/quiz/template/form.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">{{ !empty($template?->name) ? $template->name : 'Добавление шаблона опроса' }}</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('manager.quiz.template.index') }}">Шаблоны опросов</a></li>
                    <li class="breadcrumb-item active">{{ !empty($template?->name) ? $template->name : 'Добавление шаблона' }}</li>
                </ol>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid pb-5">
                <form id="main_form" action="{{ route('manager.quiz.template.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $template?->id }}">
                    <input id="main_form_submit" type="submit" class="d-none">
                    <div class="row">
                        <div class="col-md-4">
                            @if (!empty($template?->surveys->count()))
                                <div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <p><i class="icon fa fa-info-circle"></i>Этот шаблон нельзя отредактировать, так как он используется в уже запущенных опросах.</p>
                                    Но Вы можете удалить его. На результаты опросов это не повлияет.
                                </div>
                            @endif
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Общая информация</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Название</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Название шаблона"
                                               value="{{ old('name', $template?->name) }}" required="required">
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Вводная фраза</label>
                                        <textarea class="form-control @error('intro') is-invalid @enderror" rows="3" name="intro" placeholder="Предложение заказчику принять участие в опросе">{{ old('intro', !empty($template?->intro) ? $template->intro : 'Пожалуйста, примите участие в опросе:') }}</textarea>
                                        @error('intro')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Вопросы</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="questions">
                                        @if (!empty(old('q_id')) && count(old('q_id')))
                                            @foreach(old('q_id') as $k => $v)
                                                <div class="question position-relative">
                                                    <div class="question_buttons">
                                                        <i class="fa fa-trash-o cursor-pointer delete_question text-danger mr-2" title="Удалить вопрос" aria-hidden="true"></i>
                                                        <i class="fa fa-bars cursor-pointer sortable_handle text-gray" title="Для изменения сортировки кликните и перемещайте вверх или вниз" aria-hidden="true"></i>
                                                    </div>
                                                    <input type="hidden" name="q_id[]" value="{{ $v }}">
                                                    <div class="form-group">
                                                        <label>Текст вопроса</label>
                                                        <textarea class="form-control @error('question.'.$k.'.text') is-invalid @enderror" rows="3" name="q_text[]" placeholder="Текст вопроса" required="required">{{ old('q_text')[$k] }}</textarea>
                                                        @error('question.'.$k.'.text')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="rating_block mb-3">
                                                        <div class="form-check">
                                                            <input type="hidden" name="q_rating_enabled[]" value="0" @if (!empty(old('q_rating_enabled')[$k])) disabled @endif>
                                                            <label class="form-check-label cursor-pointer">
                                                                <input class="form-check-input cursor-pointer rating_enabled" name="q_rating_enabled[]" type="checkbox" value="1" @if (!empty(old('q_rating_enabled')[$k])) checked @endif>
                                                                Возможность поставить оценку
                                                            </label>
                                                            @error('question.'.$k.'.rating_enabled')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="rating_values" @if (empty(old('q_rating_enabled')[$k])) style="display:none;" @endif>
                                                            <div class="form-inline ml-4 mt-2 mb-0">
                                                                <div class="form-group">
                                                                    <label class="ml-3 ml-sm-0 mr-sm-3 font-weight-normal">от</label>
                                                                    <input type="number" min="1" max="100" class="form-control" name="q_rating_from[]" placeholder="от" value="1" readonly="readonly">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="mx-3 font-weight-normal">до</label>
                                                                    <input type="number" min="1" max="100" class="form-control @error('question.'.$k.'.rating_to') is-invalid @enderror" name="q_rating_to[]" placeholder="до" value="{{ old('q_rating_to')[$k], 5 }}">
                                                                </div>
                                                            </div>
                                                            <small class="text-muted d-block ml-4 mb-2"><i class="fa fa-info-circle mr-1"></i>Не рек<span class="d-none d-sm-inline">омендуется</span> использовать оценки более 5</small>
                                                            @error('question.'.$k.'.rating_to')
                                                            <div class="ml-4 text-danger">{{ $message }}</div>
                                                            @enderror
                                                            <div class="form-check ml-4 mb-4">
                                                                <input type="hidden" name="q_rating_required[]" value="0" @if (!empty(old('q_rating_required')[$k])) disabled @endif>
                                                                <label class="form-check-label cursor-pointer">
                                                                    <input class="form-check-input cursor-pointer rating_required" name="q_rating_required[]" type="checkbox" value="1" @if (!empty(old('q_rating_required')[$k])) checked @endif>
                                                                    Оценка обязательна для заполнения
                                                                </label>
                                                                @error('question.'.$k.'.rating_required')
                                                                <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="comment_block">
                                                        <div class="form-check">
                                                            <input type="hidden" name="q_comment_enabled[]" value="0" @if (!empty(old('q_comment_enabled')[$k])) disabled @endif>
                                                            <label class="form-check-label cursor-pointer">
                                                                <input class="form-check-input cursor-pointer comment_enabled" name="q_comment_enabled[]" type="checkbox" value="1" @if (!empty(old('q_comment_enabled')[$k])) checked @endif>
                                                                Возможность добавить комментарий
                                                            </label>
                                                            @error('question.'.$k.'.comment_enabled')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="comment_values" @if (empty(old('q_comment_enabled')[$k])) style="display:none;" @endif>
                                                            <div class="form-check ml-4 mt-2">
                                                                <input type="hidden" name="q_comment_required[]" value="0" @if (!empty(old('q_comment_required')[$k])) disabled @endif>
                                                                <label class="form-check-label cursor-pointer">
                                                                    <input class="form-check-input cursor-pointer comment_required" name="q_comment_required[]" type="checkbox" value="1" @if (!empty(old('q_comment_required')[$k])) checked @endif>
                                                                    Комментарий обязателен для заполнения
                                                                </label>
                                                            </div>
                                                            @error('question.'.$k.'.comment_required')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <hr class="question_hr"/>
                                                </div>
                                            @endforeach
                                        @elseif (!empty($template?->questions) && count($template?->questions))
                                            @foreach($template->questions as $question)
                                                <div class="question position-relative">
                                                    <div class="question_buttons">
                                                        <i class="fa fa-trash-o cursor-pointer delete_question text-danger mr-2" title="Удалить вопрос" aria-hidden="true"></i>
                                                        <i class="fa fa-bars cursor-pointer sortable_handle text-gray" title="Для изменения сортировки кликните и перемещайте вверх или вниз" aria-hidden="true"></i>
                                                    </div>
                                                    <input type="hidden" name="q_id[]" value="{{ $question->id }}">
                                                    <div class="form-group">
                                                        <label>Текст вопроса</label>
                                                        <textarea class="form-control" rows="3" name="q_text[]" placeholder="Текст вопроса" required="required">{{ $question->text }}</textarea>
                                                    </div>
                                                    <div class="rating_block mb-3">
                                                        <div class="form-check">
                                                            <input type="hidden" name="q_rating_enabled[]" value="0" @if (!empty($question->rating_enabled)) disabled @endif>
                                                            <label class="form-check-label cursor-pointer">
                                                                <input class="form-check-input cursor-pointer rating_enabled" name="q_rating_enabled[]" type="checkbox" value="1" @if (!empty($question->rating_enabled)) checked @endif>
                                                                Возможность поставить оценку
                                                            </label>
                                                        </div>
                                                        <div class="rating_values" @if (empty($question->rating_enabled)) style="display:none;" @endif>
                                                            <div class="form-inline ml-4 mt-2 mb-0">
                                                                <div class="form-group">
                                                                    <label class="ml-3 ml-sm-0 mr-sm-3 font-weight-normal">от</label>
                                                                    <input type="number" min="1" max="100" class="form-control" name="q_rating_from[]" placeholder="от" value="1" readonly="readonly">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="mx-3 font-weight-normal">до</label>
                                                                    <input type="number" min="1" max="100" class="form-control" name="q_rating_to[]" placeholder="до" value="{{ $question->rating_to }}">
                                                                </div>
                                                            </div>
                                                            <small class="text-muted d-block ml-4 mb-2"><i class="fa fa-info-circle mr-1"></i>Не рек<span class="d-none d-sm-inline">омендуется</span> использовать оценки более 5</small>
                                                            <div class="form-check ml-4 mb-4">
                                                                <input type="hidden" name="q_rating_required[]" value="0" @if (!empty($question->rating_required)) disabled @endif>
                                                                <label class="form-check-label cursor-pointer">
                                                                    <input class="form-check-input cursor-pointer rating_required" name="q_rating_required[]" type="checkbox" value="1" @if (!empty($question->rating_required)) checked @endif>
                                                                    Оценка обязательна для заполнения
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="comment_block">
                                                        <div class="form-check">
                                                            <input type="hidden" name="q_comment_enabled[]" value="0" @if (!empty($question->comment_enabled)) disabled @endif>
                                                            <label class="form-check-label cursor-pointer">
                                                                <input class="form-check-input cursor-pointer comment_enabled" name="q_comment_enabled[]" type="checkbox" value="1" @if (!empty($question->comment_enabled)) checked @endif>
                                                                Возможность добавить комментарий
                                                            </label>
                                                        </div>
                                                        <div class="comment_values" @if (empty($question->comment_enabled)) style="display:none;" @endif>
                                                            <div class="form-check ml-4 mt-2">
                                                                <input type="hidden" name="q_comment_required[]" value="0" @if (!empty($question->comment_required)) disabled @endif>
                                                                <label class="form-check-label cursor-pointer">
                                                                    <input class="form-check-input cursor-pointer comment_required" name="q_comment_required[]" type="checkbox" value="1" @if (!empty($question->comment_required)) checked @endif>
                                                                    Комментарий обязателен для заполнения
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="question_hr"/>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button id="add_question" class="btn btn-info"><i class="fa fa-plus mr-2" aria-hidden="true"></i> Добавить вопрос</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                @if (!empty($template?->id))
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
                            <div class="card-footer row">
                                <a href="{{ route('manager.quiz.survey.create',$template->id) }}" class="btn btn-success col-6"><i class="fa fa-play mr-2"></i>Провести опрос</a>
                                <form action="{{ route('manager.quiz.template.delete', $template->id) }}" method="post" class="col-6">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger confirm_template_delete w-100"><i class="fa fa-trash mr-2"></i>Удалить шаблон</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div id="question_template" class="d-none">
        <div class="question position-relative">
            <div class="question_buttons">
                <i class="fa fa-trash-o cursor-pointer delete_question text-danger mr-2" title="Удалить вопрос" aria-hidden="true"></i>
                <i class="fa fa-bars cursor-pointer sortable_handle text-gray" title="Для изменения сортировки кликните и перемещайте вверх или вниз" aria-hidden="true"></i>
            </div>
            <input type="hidden" name="q_id[]" value="0">
            <div class="form-group">
                <label>Текст вопроса</label>
                <textarea class="form-control" rows="3" name="q_text[]" placeholder="Текст вопроса" required="required"></textarea>
            </div>
            <div class="rating_block mb-3">
                <div class="form-check">
                    <input type="hidden" name="q_rating_enabled[]" value="0" disabled>
                    <label class="form-check-label cursor-pointer">
                        <input class="form-check-input cursor-pointer rating_enabled" name="q_rating_enabled[]" type="checkbox" value="1" checked>
                        Возможность поставить оценку
                    </label>
                </div>
                <div class="rating_values">
                    <div class="form-inline ml-4 mt-2 mb-0">
                        <div class="form-group">
                            <label class="ml-3 ml-sm-0 mr-sm-3 font-weight-normal">от</label>
                            <input type="number" min="1" max="100" class="form-control" name="q_rating_from[]" placeholder="от" value="1" readonly="readonly">
                        </div>
                        <div class="form-group">
                            <label class="mx-3 font-weight-normal">до</label>
                            <input type="number" min="1" max="100" class="form-control" name="q_rating_to[]" placeholder="до" value="5">
                        </div>
                    </div>
                    <small class="text-muted d-block ml-4 mb-2"><i class="fa fa-info-circle mr-1"></i>Не рек<span class="d-none d-sm-inline">омендуется</span> использовать оценки более 5</small>
                    <div class="form-check ml-4 mb-4">
                        <input type="hidden" name="q_rating_required[]" value="0" disabled>
                        <label class="form-check-label cursor-pointer">
                            <input class="form-check-input cursor-pointer rating_required" name="q_rating_required[]" type="checkbox" value="1" checked>
                            Оценка обязательна для заполнения
                        </label>
                    </div>
                </div>
            </div>
            <div class="comment_block">
                <div class="form-check">
                    <input type="hidden" name="q_comment_enabled[]" value="0">
                    <label class="form-check-label cursor-pointer">
                        <input class="form-check-input cursor-pointer comment_enabled" name="q_comment_enabled[]" type="checkbox" value="1">
                        Возможность добавить комментарий
                    </label>
                </div>
                <div class="comment_values" style="display:none;">
                    <div class="form-check ml-4 mt-2">
                        <input type="hidden" name="q_comment_required[]" value="0">
                        <label class="form-check-label cursor-pointer">
                            <input class="form-check-input cursor-pointer comment_required" name="q_comment_required[]" type="checkbox" value="1">
                            Комментарий обязателен для заполнения
                        </label>
                    </div>
                </div>
            </div>
            <hr class="question_hr"/>
        </div>
    </div>

@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/manager/quiz/template/form.js') }}"></script>
@endsection
