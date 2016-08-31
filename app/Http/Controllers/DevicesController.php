<?php

namespace App\Http\Controllers;

use Auth;
use App\Jobs;
use Validator;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    /**
     * Get device jobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $device = Auth::guard('api')->user();
        $job = Jobs::where('device', $device->id)->first();

        if ($job->count()) {
            $options = app('request')->header('accept-charset') == 'utf-8' ? JSON_UNESCAPED_UNICODE : null;

            return json_encode($job, JSON_UNESCAPED_SLASHES);
        } else {
            return '0';
        }
    }

    /**
     * Post job result.
     *
     * @return \Illuminate\Http\Response
     */
    public function post(request $request)
    {
        $validation = Validator::make($request->all(),
            [
                'name' => 'required',
                'source' => 'required',
                'task' => 'required',
                'device' => 'required',
            ]
        );

        $job = Jobs::find($request->id);
        $job->status = $request->status;
        $job->result = $request->result ?: '';

        if ($job->save()) {
            return '0';
        } else {
            return '1';
        }
    }
}
