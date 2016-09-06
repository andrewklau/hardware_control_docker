@extends('layouts.app') @section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">New Job</div>

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
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('jobs/new') }}">
                            {{ csrf_field() }}
                            <div class="form-group ">
                                <label class="default-label">Name</label>
                                <input type="text" name="name" data-validation="required" class="form-control" value="{{ old('name') }}">
                            </div>

                            <div class="form-group ">
                                <label class="default-label">Source</label>
                                <input type="text" name="source" data-validation="required" class="form-control" value="{{ old('source') }}">
                            </div>

                            <div class="form-group ">
                                <label class="default-label">Task</label>
                                <input type="text" name="task" data-validation="required" class="form-control" value="{{ old('task') }}">
                            </div>

                            <div class="form-group ">
                                <label class="default-label">Device</label>
                                <select name="device" class="form-control selectpicker" data-validation="required">
                                    <option value="">--Choose Device --</option>
                                    @foreach ($devices as $device)
                                    <option value="{{ $device->id }}">{{ $device->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group ">
                                <label class="default-label">Permission</label>
                                <select name="permission" class="form-control selectpicker" data-validation="required">
                                    <option value="">--Choose Permission --</option>
                                    @foreach ($permissions as $permission)
                                    <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
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
