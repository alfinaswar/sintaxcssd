<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('Role.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon"><i class="
                    flaticon2-gear"></i></button>&nbsp;</a>
                   ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::all();
        return view('Role.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Nama bagian tidak boleh kosong',
        ]);
        $query = Role::create(['name' => $request->name]);
        $query->syncPermissions($request->permission);

        return redirect()->route('Role.index')->with('success', 'Data berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::all();
        $roleHas = DB::table('role_has_permissions')->where('role_id', $id)
            ->pluck('permission_id', 'permission_id')
            ->all();
        return view('Role.update', compact('role', 'permission', 'roleHas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permission);

        return redirect()->route('Role.index')->with('success', 'Data berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
