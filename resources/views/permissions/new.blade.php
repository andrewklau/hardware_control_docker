@extends('layouts.app') @section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">New Permission</div>

                <div class="panel-body">
                    @if(Session::has('msg'))
                    <div class="alert alert-info">
                        {{Session::get('msg')}}
                    </div>
                    @endif @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="col-md-12">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('permissions/new') }}">
                            {{ csrf_field() }}
                            <div class="form-group ">
                                <label class="default-label">Name</label>
                                <input type="text" name="name" data-validation="required" class="form-control" value="{{ old('name') }}">
                            </div>

                            <div class="form-group ">
                                <label class="default-label">Limits <small>CSV format</small></label>
                                <input type="text" name="limits" data-validation="required" class="form-control" value="{{ old('limits') }}">
                            </div>

                            <hr>
                            <input type="submit" value="Save" class="btn btn-primary btn-block btn-lg">
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
