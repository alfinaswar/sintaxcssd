<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Models\DokumenIzinModel;
use App\Models\FileModel;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DokumenIzinModel::where(function ($query) {
                if (!auth()->user()->hasRole('Administrator')) {
                    $query->where('user_at', auth()->user()->username);
                }
            })->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '';
                    $btnlihat = '';
                    $btnupdate = '';
                    if (auth()->user()->can('edit-status')) {
                        $btnEdite = '<button type="button" class="btn btn-outline-success btn-icon" onclick="setIdIzin(' . $row->id . ')" data-toggle="modal" data-target="#modal-verifikasi"><i class="fa fa-pen-alt"></i></button>';
                    }
                    if (auth()->user()->can('lihat-status')) {
                        $btnlihat = '<button type="button" onclick="setShowData(event,' . $row->id . ')" class="btn btn-outline-danger btn-icon" data-toggle="modal" data-target="#modal-show"><i class="fa fa-folder-open"></i></button>';
                    }
                    if (auth()->user()->can('update-file')) {
                        $btnupdate = '<button type="button" onclick="setIzin(' . $row->id . ')"  class="btn btn-outline-brand btn-icon" data-toggle="modal" data-target="#modal-update-file"><i class="fa fa-file-word"></i></button>';
                    }
                    $btn = $btnEdite . '&nbsp;' . $btnlihat . '&nbsp;' . $btnupdate;
                    return $btn;
                })
                ->addColumn('status', function ($query) {
                    // $status = '';
                    if ($query->verifikasi == 2 && $query->tanggalSelesai) {
                        return $status = '<span class="badge badge-primary">Done</span>';
                    } elseif ($query->verifikasi == 1 && $query->tanggalProses) {
                        return $status = '<span class="badge badge-danger">Cancel</span>';
                    } elseif ($query->verifikasi == 0 && $query->tanggalProses) {
                        return $status = '<span class="badge badge-warning">Proses</span>';
                    } elseif ($query->tanggalProses == null) {
                        return $status = '<span class="badge badge-success">Open</span>';
                    }
                    // $status2 = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
                    // return $status;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('form-upload.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('form-upload.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadFileRequest $request)
    {
        $data = $request->all();
        $query = DokumenIzinModel::create($data);
        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $key => $file) {
                $path = $file->store('public/files');
                $name = $file->getClientOriginalName();
                $insert[$key]['name'] = $name;
                $insert[$key]['path'] = $path;
                $insert[$key]['dokIzinID'] = $query->id;
                $insert[$key]['jenisDokumen'] = 'file upload';
                $insert[$key]['status'] = 0;
                $insert[$key]['requester'] = $query->requester;
                $insert[$key]['created_at'] = now();
                $insert[$key]['updated_at'] = now();
            }
        }
        FileModel::insert($insert);

        return redirect()->route('index.index')->with('success', 'Data berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = DokumenIzinModel::find($id);
        $file = FileModel::where('dokIzinID', $id)->orderBy('id', 'desc')->get();
        $view = view('form-upload.show-data', compact('query', 'file'))->render();
        return response()->json(['view' => $view], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        // dd($request->all());
        $query = DokumenIzinModel::find($id);
        // if ($request->verifikasi) {
        $data['verifikasi'] = $request->verifikasi;
        // }
        if ($query->tanggalProses == null) {
            $data['tanggalProses'] = date('Y-m-d');
        }
        if ($request->verifikasi == 2) {
            $data['tanggalSelesai'] = date('Y-m-d');
        } else {
            $data['tanggalSelesai'] = null;
        }
        $data['comments'] = $request->keterangan;
        $data['Tanggalcomments'] = date('Y-m-d');
        $query->update($data);


        return response()->json('Berhasil');
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
    public function downloadFile($id)
    {
        $path = FileModel::where("id", $id)->first();
        return Storage::download($path->path, $path->name);
    }
    public function updateStatus(Request $request)
    {
        $validatedData = $request->validate([
            'files' => 'required',
            'files.*' => 'mimes:doc,docx|max:10248'
        ], [
            'files.required' => 'File tidak boleh kosong',
            'files.*.mimes' => 'Format dokumen doc,docx',
            'files.*.max' => 'Ukuran file maksimal 10MB'
        ]);

        $query = DokumenIzinModel::find($request->idDokIzin);
        // if ($request->verifikasi) {
        $data['verifikasi'] = $request->verifikasi;
        // }
        if ($query->tanggalProses == null) {
            $data['tanggalProses'] = date('Y-m-d');
        }
        if ($request->verifikasi == 2) {
            $data['tanggalSelesai'] = date('Y-m-d');
        } else {
            $data['tanggalSelesai'] = null;
        }
        $data['comments'] = $request->keterangan;
        $data['Tanggalcomments'] = date('Y-m-d');
        $query->update($data);

        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $key => $file) {
                $path = $file->store('public/files');
                $name = $file->getClientOriginalName();
                $insert[$key]['name'] = $name;
                $insert[$key]['path'] = $path;
                $insert[$key]['dokIzinID'] = $request->idDokIzin;
                $insert[$key]['jenisDokumen'] = 'file upload';
                $insert[$key]['status'] = 1;
                $insert[$key]['requester'] = auth()->user()->name;
                $insert[$key]['created_at'] = now();
                $insert[$key]['updated_at'] = now();
            }
        }
        FileModel::insert($insert);
        return redirect()->route('index.index')->with('success', 'Data berhasil di ubah');;
    }
    public function updateFile(Request $request)
    {
        $validatedData = $request->validate([
            'files' => 'required',
            'files.*' => 'mimes:doc,docx|max:10248'
        ], [
            'files.required' => 'File tidak boleh kosong',
            'files.*.mimes' => 'Format dokumen doc,docx',
            'files.*.max' => 'Ukuran file maksimal 10MB'
        ]);

        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $key => $file) {
                $path = $file->store('public/files');
                $name = $file->getClientOriginalName();
                $insert[$key]['name'] = $name;
                $insert[$key]['path'] = $path;
                $insert[$key]['dokIzinID'] = $request->idDokIzin;
                $insert[$key]['jenisDokumen'] = 'file upload';
                $insert[$key]['status'] = 1;
                $insert[$key]['requester'] = auth()->user()->name;
                $insert[$key]['created_at'] = now();
                $insert[$key]['updated_at'] = now();
            }
        }
        FileModel::insert($insert);
        return redirect()->route('index.index')->with('success', 'Data berhasil di ubah');;
    }
}
