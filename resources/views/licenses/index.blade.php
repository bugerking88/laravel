@extends('layouts.header')

@section('content')
@push('styles')
<link href="{{ asset('css/bootstrap4-toggle.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/jquery.toast.min.css') }}" rel="stylesheet">
@endpush
<div class="container-fluid">
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong> {{$errors->first()}} </strong>
        </div>
    @endif
    @if(Session::has('message'))
        <div class="alert alert-success" role="alert">
            {{Session::get('message')}}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{Session::get('error')}}
        </div>
    @endif
    <div class="text-right">
        <button type="button" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#createModal"> {{__('新增授權證書')}} </button>
    </div>
    <br/>
    <div class="card">
        <div class="card-header">{{ __('授權證書') }}</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="serverSideDataTable" width="100%" cellspacing="0" >
                    <thead>
                        <tr>
                            <th>{{__('ID')}}</th>
                            <th>{{__('座席數')}}</th>
                            <th>{{__('用戶')}}</th>
                            <th>{{__('狀態')}}</th>
                            <th>{{__('過期時間')}}</th>
                            <th class="d-none">{{__('網卡設備')}}</th>
                            <th class="d-none">{{__('系統資訊')}}</th>
                            <th>{{__('驗證時間')}}</th>
                            <th>{{__('驗證IP')}}</th>
                            <th>{{__('操作')}}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="edit-form" class="form-horizontal" method="POST" action="">
                {{ csrf_field() }}
                {{ method_field('patch') }}
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-modal-label">{{__('編輯')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="attachment-body-content">
                    <div class="form-group">
                        <span class="col-form-label">{{__('用戶')}} : </span>
                        <span class="col-form-label" id="customer_name"></span>
                    </div>
                    <div class="form-group">
                        <span class="col-form-label">{{__('網卡設備')}} : </span>
                        <span class="col-form-label" id="mac_address"></span>
                    </div>
                    <div class="form-group">
                        <span class="col-form-label">{{__('系統資訊')}} : </span>
                        <span class="col-form-label" id="linux_info"></span>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="agent">{{__('座席數')}}</label>
                        <input type="text" name="agent" class="form-control" id="agent" required autofocus>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="expire_date">{{__('過期時間')}}</label>
                        <input type="date" name="expire_date" class="form-control" id="expire_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('保存')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('關閉')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="create-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="create-form" class="form-horizontal" method="POST" action="{{route('system-licenses.store')}}">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="create-modal-label">{{__('創建時間')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="attachment-body-content">
                <div class="form-group">
                        <label class="col-form-label" for="new_customer_id">{{__('用戶')}}</label>
                        <select name="new_customer_id" id="new_customer_id" class="form-control">
                            @foreach($customers as $key => $value)
                                <option value="{{$key}}"> {{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="new_agent">{{__('座席數')}}</label>
                        <input type="number" name="new_agent" class="form-control" id="new_agent" required autofocus min="0" max="999">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="new_expire_date">{{__('過期時間')}}</label>
                        <input type="date" name="new_expire_date" class="form-control" id="new_expire_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('保存')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('關閉')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="upload-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="uploadForm" class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="upload-modal-label">{{__('上傳檔案')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="attachment-body-content">
                    <div class="form-group">
                        <label class="col-form-label" for="identity">{{__('上傳rowave.identity檔案')}}</label>
                        <input type="file" name="identity" class="form-control" id="identity">
                        <input type="hidden" id="upload_customer_id" name="upload_customer_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('保存')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('關閉')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    var csrftoken = '{{ csrf_token() }}';
</script>
<script src="{{ asset('js/bootstrap/bootstrap4-toggle.min.js') }}"></script>
<script src="{{ asset('js/jquery.toast.min.js') }}"></script>

<script src="{{ asset('js/license.js') }}"></script>
@endpush

@endsection