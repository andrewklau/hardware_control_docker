<?php

namespace App\Http\Controllers;

use Auth;
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

            return json_encode($job, JSON_UNESCAPED_SLASHES);
        } else {
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
