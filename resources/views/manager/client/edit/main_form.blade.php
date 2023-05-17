@extends('manager.client.edit.layout')

@section('pageStyle')
    @parent
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/client/edit/main_form.css') }}">
@endsection

@section('action_type_section')
    <form id="main_form" action="{{ route('manager.client.update', $client->id) }}" method="post">
        @csrf
        @method('patch')
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label>Адрес объекта</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" rows="3" name="address" placeholder="Адрес объекта" required="required" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>{{ old('address', $client->address) }}</textarea>
                    @error('address')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Имя заказчика</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Имя заказчика"
                           value="{{ old('name', $client->name) }}" required="required" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Телефон"
                           value="{{ old('phone', $client->phone_str) }}" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                    @error('phone')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email"
                           value="{{ old('email', $client->email) }}" required="required" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4 offset-md-1">
                <div class="form-group">
                    <label>Статус</label>
                    <select id="ob_status" class="form-control @error('ob_status') is-invalid @enderror" name="ob_status" @if (empty(auth()->user()->is_admin))disabled="disabled"@endif>
                        <option value="1"
                            {{ 1 === old('ob_status', $client->ob_status) ? ' selected' : '' }}>
                            В работе
                        </option>
                        <option value="2"
                            {{ 2 === old('ob_status', $client->ob_status) ? ' selected' : '' }}>
                            На гарантии
                        </option>
                        <option value="3"
                            {{ 3 === old('ob_status', $client->ob_status) ? ' selected' : '' }}>
                            Завершён
                        </option>
                    </select>
                    @error('ob_status')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @if ($client->ob_status !== 1) d-none @endif" id="in_process_block">
                    <label>Работа начата</label>
                    <select class="form-control @error('in_process') is-invalid @enderror" name="in_process" @if (empty(auth()->user()->is_admin))disabled="disabled"@endif>
                        <option value="1"
                            {{ 1 === old('in_process', $client->in_process) ? ' selected' : '' }}>
                            Да
                        </option>
                        <option value="0"
                            {{ 0 === old('in_process', $client->in_process) ? ' selected' : '' }}>
                            Нет
                        </option>
                    </select>
                </div>
                @error('in_process')
                <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-group @if ($client->ob_status !== 2) d-none @endif" id="warranty_end_block">
                    <label for="warranty_end">На гарантии до</label>
                    <input type="text" class="form-control datetimepicker-input @error('warranty_end') is-invalid @enderror" id="warranty_end" data-toggle="datetimepicker" data-target="#warranty_end" name="warranty_end" value="{{ old('warranty_end', $client->warranty_end_form) }}" placeholder="На гарантии до ..." @if (empty(auth()->user()->is_admin))readonly="readonly"@endif/>
                    @error('warranty_end')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Координаты</label>
                    <input type="text" class="form-control @error('coordinates') is-invalid @enderror" name="coordinates" placeholder="Координаты адреса на карте"
                           value="{{ old('coordinates', $client->coordinates) }}" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                    @error('coordinates')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                @if (empty($client->is_active) && !empty(auth()->user()->is_admin))
                <div class="w-100 client-delete-block">
                    <hr/>
                    <button type="submit" class="btn btn-danger confirm_object_delete w-100"><i class="fa fa-trash mr-2"></i> Удалить объект</button>
                </div>
                @endif
            </div>
        </div>
        <input type="hidden" name="id" value="{{ $client->id }}"/>
        <input id="main_form_submit" type="submit" class="d-none">
    </form>
    @if (empty($client->is_active) && !empty(auth()->user()->is_admin))
    <form action="{{ route('manager.client.delete', $client->id) }}" method="post">
        @csrf
        @method('DELETE')
        <input id="delete_form_submit" type="submit" class="d-none">
    </form>
    @endif
@endsection

@section('pageScript')
    @parent
    <script src="{{ assetVersioned('dist/js/pages/manager/client/edit/main_form.js') }}"></script>
@endsection
