<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Jobs;
use App\Devices;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    /**
     * Show the device statuses.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devices = Devices::all();
        if (!$devices->count()) {
            $devices = [];
        }

        return view('devices/index', ['devices' => $devices]);
    }

      /**
       * Show the the new device form.
       *
       * @return \Illuminate\Http\Response
       */
      public function newDevice()
      {
          return view('devices/new');
      }

      /**
       * Store the device into the database.
       *
       * @return \Illuminate\Http\Response
       */
      public function postDevice(request $request)
      {
          $validation = Validator::make($request->all(),
              [
                  'name'   => 'required',
              ]
          );

          if ($validation->fails()) {
              return redirect::back()->withInput()->withErrors($validation);
          } else {
              $devices = new Devices();

              $devices->name = $request->name;
              $devices->save();

              session()->flash('msg', 'Your device has been created');

              return redirect('devices');
          }
      }

      /**
       * Show the the edit device form.
       *
       * @return \Illuminate\Http\Response
       */
      public function editDevice($deviceId)
      {
          $device = Devices::find($deviceId);

          return view('devices/edit', ['device' => $device]);
      }

      /**
       * Store the updated device into the database.
       *
       * @return \Illuminate\Http\Response
       */
      public function patchDevice(request $request)
      {
          $validation = Validator::make($request->all(),
              [
                  'name'   => 'required',
              ]
          );

          if ($validation->fails()) {
              return redirect::back()->withInput()->withErrors($validation);
          } else {
              $devices = Devices::find($request->id);

              $devices->name = $request->name;
              $devices->save();

              session()->flash('msg', 'Your Device has been updated');

              return redirect('devices');
          }
      }

    /**
     * Get device jobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $device = Auth::guard('api')->user();
        $job = Jobs::where('device', $device->id)
        ->where('status', 'pending')
        ->join('permissions', 'jobs.permission', 'permissions.id')
        ->select('jobs.*', 'permissions.limits')
        ->first();

        if ($job) {
            $options = app('request')->header('accept-charset') == 'utf-8' ? JSON_UNESCAPED_UNICODE : null;
            // Update last seen
            Devices::findOrFail($device->id)->touch();

            return json_encode($job, JSON_UNESCAPED_SLASHES);
        } else {
            // Update last seen
            Devices::findOrFail($device->id)->touch();

            return;
        }
    }

    /**
     * Post job result.
     *
     * @return \Illuminate\Http\Response
     */
    public function post(request $request)
    {
        /* Store the job progress */
        $job = Jobs::findOrFail($request->id);
        $job->status = $request->status;
        if ($request->result) {
            $job->result .= $request->result;
        }
        $job->save();

        /* Set the device status */
        $device = Devices::findOrFail($job->device);
        if ($request->status == 'running') {
            $device->status = 'running';
        } else {
            $device->status = 'idle';
        }
        $device->save();
    }
}
