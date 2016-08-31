<?php

namespace App\Http\Controllers;

use Auth;
use App\Jobs;
use App\Devices;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class JobController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $jobs = Jobs::where('user_id', $user_id)->get();

        return view('jobs/index', ['jobs' => $jobs]);
    }

    /**
     * Show the the new job form.
     *
     * @return \Illuminate\Http\Response
     */
    public function newJob()
    {
        $devices = Devices::all();
        if (!$devices->count()) {
            $devices = [];
        }

        return view('jobs/new', ['devices' => $devices]);
    }

    /**
     * Store the job into the database.
     *
     * @return \Illuminate\Http\Response
     */
    public function postJob(request $request)
    {
        $validation = Validator::make($request->all(),
            [
                'name' => 'required',
                'source' => 'required',
                'task' => 'required',
                'device' => 'required',
            ]
        );

        if ($validation->fails()) {
            return redirect::back()->withInput()->withErrors($validation);
        } else {
            $jobs = new Jobs();

            $jobs->user_id = Auth::user()->id;
            $jobs->name = $request->name;
            $jobs->source = $request->source;
            $jobs->task = $request->task;
            $jobs->device = $request->device;

            $jobs->save();

            session()->flash('msg', 'Your job has been queued for execution');

            return redirect('jobs');
        }
    }

    /**
     * View Specifc Job.
     *
     * @return \Illuminate\Http\Response
     */
    public function view($jobId)
    {
        $user_id = Auth::user()->id;
        $job = Jobs::where('user_id', $user_id)->where('id', $jobId)->first();

        return view('jobs/view', ['job' => $job]);
    }
}
