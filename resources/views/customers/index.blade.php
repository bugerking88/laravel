@extends('layouts.header')

@section('content')
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
    <div class="text-right">
        <button type="button" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#createModal"> {{__('add')}} </button>
    </div>
    <br/>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{__('customers')}}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{__('id')}}</th>
                        <th>{{__('customer name')}}</th>
                        <th>{{__('phone number')}}</th>
                        <th>{{__('email')}}</th>
                        <th>{{__('actions')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $value)
                        <tr class="data-row">
                            <td>{{$value -> id}}</td>
                            <td class="name">{{$value -> name}}</td>
                            <td class="phone-number">{{$value -> phone_number}}</td>
                            <td class="email">{{$value -> email}}</td>
                            <td class="action-col">
                                <input type="button" class="page-btn btn-primary" id="edit-item" value="{{__('edit')}}" data-item-id ="{{$value -> id}}">
                                <form method="post" action="{{route('customers.destroy',$value -> id)}}" class="action-form">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                    <input type="submit" class="page-btn btn-danger delete" value ="{{__('delete')}}">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
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
                    <h5 class="modal-title" id="edit-modal-label">{{__('edit data')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="attachment-body-content">
                    <div class="form-group">
                        <label class="col-form-label" for="name">{{__('name')}}</label>
                        <input type="text" name="name" class="form-control" id="name" required autofocus>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="email">{{__('email')}}</label>
                        <input type="email" name="email" class="form-control" id="email">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="phone_number">{{__('phone number')}}</label>
                        <input type="text" name="phone_number" class="form-control" id="phone_number">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('save')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('close')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="create-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="create-form" class="form-horizontal" method="POST" action="{{route('customers.store')}}">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="create-modal-label">{{__('create data')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="attachment-body-content">
                    <div class="form-group">
                        <label class="col-form-label" for="new_name">{{__('customer name')}}</label>
                        <input type="text" name="new_name" class="form-control" id="new_name" required autofocus>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="new_phone_number">{{__('phone number')}}</label>
                        <input type="text" name="new_phone_number" class="form-control" id="new_phone_number">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="new_email">{{__('email')}}</label>
                        <input type="email" name="new_email" class="form-control" id="new_email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('save')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('close')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
     // Call the dataTables jQuery plugin
     $(document).ready(function() {
        // on modal show
        $('#edit-modal').on('show.bs.modal', function() {
            var el = $(".edit-item-trigger-clicked"); // See how its usefull right here?
            var row = el.closest(".data-row");

            // get the data
            var name = row.children(".name").text();
            var email = row.children(".email").text();
            var phone_number = row.children(".phone-number").text();
            var url = "{{route('customers.update','id')}}";
            url = url.replace('id',el.data('item-id'));
            // fill the data in the input fields
            $("#edit-form").attr("action",url);
            $("#phone_number").val(phone_number);
            $("#email").val(email);
            $("#name").val(name);
        })

    });
</script>
@endpush
@endsection

