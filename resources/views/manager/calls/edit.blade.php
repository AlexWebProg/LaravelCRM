@extends('manager.layouts.main')

@section('pageName')Звонки за {{ $arDates['strDate'] }}@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Звонки за {{ $arDates['strDate'] }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('manager.calls.month', $arDates['intMonth']) }}">Аналитика звонков</a></li>
                        <li class="breadcrumb-item active">{{ $arDates['strDate'] }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form id="main_form" action="{{ route('manager.calls.update') }}" method="post" class="pb-5 form-horizontal">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Данные по звонкам</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="repair_full">Ремонт целиком</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('repair_full') is-invalid @enderror" name="repair_full" placeholder="Ремонт целиком"
                                                   value="{{ old('repair_full', $call?->repair_full) }}" id="repair_full">
                                            @error('repair_full')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="repair_partial">Частичный ремонт</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('repair_partial') is-invalid @enderror" name="repair_partial" placeholder="Частичный ремонт"
                                                   value="{{ old('repair_partial', $call?->repair_partial) }}" id="repair_partial">
                                            @error('repair_partial')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="advertising">Реклама</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('advertising') is-invalid @enderror" name="advertising" placeholder="Реклама"
                                                   value="{{ old('advertising', $call?->advertising) }}" id="advertising">
                                            @error('advertising')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="evening_calls">Звонки после 19:00</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('evening_calls') is-invalid @enderror" name="evening_calls" placeholder="Звонки после 19:00"
                                                   value="{{ old('evening_calls', $call?->evening_calls) }}" id="evening_calls">
                                            @error('evening_calls')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="day_total">Всего за день</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('day_total') is-invalid @enderror" name="day_total" placeholder="Всего за день"
                                                   value="{{ old('day_total', $call?->day_total) }}" id="day_total">
                                            @error('day_total')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="signed_up">Записались</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('signed_up') is-invalid @enderror" name="signed_up" placeholder="Записались"
                                                   value="{{ old('signed_up', $call?->signed_up) }}" id="signed_up">
                                            @error('signed_up')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="est_wo_dep">Сметы без выезда</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('est_wo_dep') is-invalid @enderror" name="est_wo_dep" placeholder="Сметы без выезда"
                                                   value="{{ old('est_wo_dep', $call?->est_wo_dep) }}" id="est_wo_dep">
                                            @error('est_wo_dep')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Откуда были звонки</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="from_youtube">YouTube</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('from_youtube') is-invalid @enderror" name="from_youtube" placeholder="YouTube"
                                                   value="{{ old('from_youtube', $call?->from_youtube) }}" id="from_youtube">
                                            @error('from_youtube')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="from_dzen">Дзен</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('from_dzen') is-invalid @enderror" name="from_dzen" placeholder="Дзен"
                                                   value="{{ old('from_dzen', $call?->from_dzen) }}" id="from_dzen">
                                            @error('from_dzen')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="from_rutube">RuTube</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('from_rutube') is-invalid @enderror" name="from_rutube" placeholder="RuTube"
                                                   value="{{ old('from_rutube', $call?->from_rutube) }}" id="from_rutube">
                                            @error('from_rutube')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="from_telegram">Telegram</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('from_telegram') is-invalid @enderror" name="from_telegram" placeholder="Telegram"
                                                   value="{{ old('from_telegram', $call?->from_telegram) }}" id="from_telegram">
                                            @error('from_telegram')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="from_tiktok">TikTok</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('from_tiktok') is-invalid @enderror" name="from_tiktok" placeholder="TikTok"
                                                   value="{{ old('from_tiktok', $call?->from_tiktok) }}" id="from_tiktok">
                                            @error('from_tiktok')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="from_vk">VK</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('from_vk') is-invalid @enderror" name="from_vk" placeholder="VK"
                                                   value="{{ old('from_vk', $call?->from_vk) }}" id="from_vk">
                                            @error('from_vk')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="from_site">Сайт</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('from_site') is-invalid @enderror" name="from_site" placeholder="Сайт"
                                                   value="{{ old('from_site', $call?->from_site) }}" id="from_site">
                                            @error('from_site')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="from_people">От знакомых</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('from_people') is-invalid @enderror" name="from_people" placeholder="От знакомых"
                                                   value="{{ old('from_people', $call?->from_people) }}" id="from_people">
                                            @error('from_people')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label text-right" for="from_other">Другой</label>
                                        <div class="col-6">
                                            <input type="number" min="0" class="form-control @error('from_other') is-invalid @enderror" name="from_other" placeholder="Другой"
                                                   value="{{ old('from_other', $call?->from_other) }}" id="from_other">
                                            @error('from_other')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="date" value="{{ $arDates['dbDate'] }}">
                    <input type="hidden" name="manager_id" value="{{ $call?->manager_id }}">
                    <input id="main_form_submit" type="submit" class="d-none">
                </form>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
