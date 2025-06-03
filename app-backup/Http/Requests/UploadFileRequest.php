<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dokumen' => 'required',
            'namaDokumen' => 'required',
            'priority' => 'required',
            'jenisFile' => 'required',
            'files' => 'required',
            'files.*' => 'mimes:doc,docx|max:10248'
        ];
    }
    public function messages()
    {
        return [
            'dokumen.required' => 'Nomor dokumen tidak boleh kosong',
            'namaDokumen.required' => 'Nama dokumen tidak boleh kosong',
            'priority.required' => 'Prioritas tidak boleh kosong',
            'jenisFile.required' => 'Jenis file tidak boleh kosong',
            'files.required' => 'File tidak boleh kosong',
            'files.*.mimes' => 'Format dokumen doc,docx',
            'files.*.max' => 'Ukuran file maksimal 10MB'
        ];
    }
}
