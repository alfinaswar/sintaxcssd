<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MasterRs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = User::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('User.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-user-cog"></i></button></a>&nbsp;
                    <button type="button" class="btn btn-outline-danger btn-icon" onclick="deleteUser(event,' . $row->id . ')" ><i class="fa fa-user-alt-slash"></i></button>';
                    return $btn;
                })
                ->addColumn('role', function ($row) {
                    return $row->getRoleNames()->first();
                })
                ->rawColumns(['action', 'role'])
                ->make(true);
        }

        return view('User.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Role::all();
        $rs = MasterRs::all();

        return view('User.create', compact('role','rs'));
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
            'username' => 'required|unique:users,username',
            'role' => 'required',
            'password' => 'required',
            'kodeRS' => 'required',
        ], [
            'name.required' => 'Nama tidak boleh kosong',
            'username.required' => 'Username tidak boleh kosong',
            'username.unique' => 'Username telah terdaftar',
            'role.required' => 'Role tidak boleh kosong',
            'password.required' => 'Password file tidak boleh kosong',
            'kodeRS.required' => 'kode RS tidak boleh kosong',

        ]);
        $data = $request->all();
        //dd($data);
        $data['password'] = Hash::make($request->password);
        $query = User::create($data);
        $query->assignRole($request->role);

        return redirect()->route('User.index')->with('success', 'Data berhasil di tambahkan');
    }
    public function getrs(Request $request) {
        $item = [];
        $dataItem = DB::connection('mysql')->table('master_rs');
        if($request->has('q')) {
            $search = $request->q;
            $dataItem->where('nama', 'LIKE', "%$search%")->limit(5)
                ->get(['kodeRS', 'nama']);
            $item = $dataItem->pluck('nama', 'kodeRS');
        } else {
            $item = $dataItem->limit(5)->get(['kodeRS', 'nama'])->pluck('nama', 'kodeRS');
        }
        return response()->json($item);
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
        $user = User::find($id);
        $role = Role::all();
        return view('User.edit', compact('user', 'role'));
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
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        $data['username'] = $request->username;
        $data['name'] = $request->name;
        $data['rs'] = $request->rs;
        $data['role'] = $request->role;

        $query = User::find($id);
        $query->update($data);
        $query->syncRoles($request->role);

        return redirect()->route('User.index')->with('success', 'Data berhasil di ubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();

        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
    public function changePassword(Request $request)
    {
        return view('User.change-password');
    }

    public function changePasswordSave(Request $request)
    {

        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|confirmed|min:6|string'
        ]);
        $auth = Auth::user();
        // dd(!Hash::check($request->get('current_password'), $auth->password));
        // dd(!Hash::check('123456', '$2y$10$gKtFDQMdXogn0ojd0iQq0uBmRdn3m9FTn2cG6UNrlHdkBRx/CsNb6'));
        // The passwords matches
        if (!Hash::check($request->get('current_password'), $auth->password)) {
            return back()->with('error', "Current Password is Invalid");
        }

        // Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0) {
            return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        }

        $user =  User::find($auth->id);
        $user->password =  Hash::make($request->new_password);
        $user->save();
        Auth::logout();
        return redirect()->route('login')->with("success", "Password berhasil di ubah.");;
    }
}
