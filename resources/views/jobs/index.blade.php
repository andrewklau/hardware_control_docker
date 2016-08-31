@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

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

                    <p>Your current jobs are:</p>

                    <table class="table">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Source</th>
                          <th>Task</th>
                          <th>Device</th>
                          <th>Status</th>
                          <th>Timestamp</th>
                          <th> </th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($jobs as $job)
                        <tr>
                          <td>{{ $job->name }}</td>
                          <td>{{ $job->source }}
                          <td>{{ $job->task }}
                          <td>{{ $job->device }}
                          <td style="text-align: center;">
                              @if ( $job->status == 'completed' )
                              <span class="fa fa-check"></span>
                              @elseif ( $job->status == 'running' )
                              <span class="fa fa-refresh fa-spin"></span>
                              @elseif ( $job->status == 'pending' )
                              <span class="fa fa-spinner fa-spin"></span>
                              @elseif ( $job->status == 'failed' )
                              <span class="fa fa-warning"></span>
                              @endif
                          </td>
                          <td>{{ $job->created_at->toDayDateTimeString() }}</td>
                          <td><a href="/jobs/view/{{ $job->id }}">View</a></td>
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
