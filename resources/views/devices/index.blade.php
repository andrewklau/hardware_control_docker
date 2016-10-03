@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Devices<span style="float: right;"><a href="{{ url('/devices/new') }}">New Device</a></span></div>

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
                          <th>Status</th>
                          <th>Last Update</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($devices as $device)
                        <tr>
                          <td>{{ $device->name }}</td>
                          <td style="text-align: center;">
                              @if ( $device->status == 'idle' )
                              <span class="fa fa-check"></span>
                              @elseif ( $device->status == 'running' )
                              <span class="fa fa-refresh fa-spin"></span>
                              @endif
                          </td>
                          <td>{{ $device->updated_at->toDayDateTimeString() }}</td>
                          <td><a href="/devices/edit/{{ $device->id }}">Edit</a></td>
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
