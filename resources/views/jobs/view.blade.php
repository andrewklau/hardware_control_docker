@extends('layouts.app') @section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Job #{{ $job->id }}</div>

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
                            <div class="form-group ">
                                <label class="default-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $job->name }}" disabled>
                            </div>

                            <div class="form-group ">
                                <label class="default-label">Source</label>
                                <input type="text" name="source" class="form-control" value="{{ $job->source }}" disabled>
                            </div>

                            <div class="form-group ">
                                <label class="default-label">Task</label>
                                <input type="text" name="task" class="form-control" value="{{ $job->task }}" disabled>
                            </div>

                            <div class="form-group ">
                                <label class="default-label">Date</label>
                                <input type="text" name="date" class="form-control" value="{{ $job->created_at->toDayDateTimeString() }}" disabled>
                            </div>

                            <div class="form-group ">
                                <label class="default-label">Device</label>
                                <select name="device" class="form-control selectpicker" disabled>
                                    <option value="">{{ $job->device }}</option>
                                </select>
                            </div>

                            <div class="form-group ">
                                <label class="default-label">Status</label>
                                <input type="text" name="status" class="form-control" value="{{ $job->status }}" disabled>
                            </div>

                            <div class="form-group ">
                                <label class="default-label">Result</label>
                                <textarea type="text" name="result" class="form-control" disabled>{{ $job->result }}</textarea>
                            </div>

                            <hr>

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
