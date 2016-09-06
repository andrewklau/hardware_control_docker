@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Permissions<span style="float: right;"><a href="{{ url('/permissions/new') }}">New Permission</a></span></div>

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

                    <table class="table">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Limits</th>
                          <th>Last Update</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($permissions as $permission)
                        <tr>
                          <td>{{ $permission->name }}</td>
                          <td>{{ $permission->limits }}
                          <td>{{ $permission->updated_at->toDayDateTimeString() }}</td>
                          <td><a href="/permissions/edit/{{ $permission->id }}">Edit</a></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
