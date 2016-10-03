<?php

namespace App\Http\Controllers;

use App\Permissions;
use Validator;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    /**
     * Show the permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permissions::all();
        if (!$permissions->count()) {
            $permissions = [];
        }

        return view('permissions/index', ['permissions' => $permissions]);
    }

    /**
     * Show the the new permission form.
     *
     * @return \Illuminate\Http\Response
     */
    public function newPermission()
    {
        return view('permissions/new');
    }

    /**
     * Store the permission into the database.
     *
     * @return \Illuminate\Http\Response
     */
    public function postPermission(request $request)
    {
        $validation = Validator::make($request->all(),
            [
                'name'   => 'required',
                'limits' => 'required',
            ]
        );

        if ($validation->fails()) {
            return redirect::back()->withInput()->withErrors($validation);
        } else {
            $permissions = new Permissions();

            $permissions->name = $request->name;
            $permissions->limits = $request->limits;
            $permissions->save();

            session()->flash('msg', 'Your permission has been created');

            return redirect('permissions');
        }
    }

    /**
     * Show the the edit permission form.
     *
     * @return \Illuminate\Http\Response
     */
    public function editPermission($permissionId)
    {
        $permission = Permissions::find($permissionId);

        return view('permissions/edit', ['permission' => $permission]);
    }

    /**
     * Store the updated permission into the database.
     *
     * @return \Illuminate\Http\Response
     */
    public function patchPermission(request $request)
    {
        $validation = Validator::make($request->all(),
            [
                'name'   => 'required',
                'limits' => 'required',
            ]
        );

        if ($validation->fails()) {
            return redirect::back()->withInput()->withErrors($validation);
        } else {
            $permissions = Permissions::find($request->id);

            $permissions->name = $request->name;
            $permissions->limits = $request->limits;
            $permissions->save();

            session()->flash('msg', 'Your permission has been updated');

            return redirect('permissions');
        }
    }
}
