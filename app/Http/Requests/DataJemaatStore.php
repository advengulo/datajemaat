<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataJemaatStore extends FormRequest
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
            'jemaat_nama' => 'required',
            'jemaat_gelar_depan' => 'nullable',
            'jemaat_gelar_belakang' => 'nullable',
            'jemaat_tempat_lahir' => 'required',
            'jemaat_tanggal_lahir' => 'required',
            'jemaat_jenis_kelamin' => 'required',
            'jemaat_status_perkawinan' => 'required',
            // 'jemaat_tanggal_perkawinan' => request('jemaat_tanggal_perkawinan'),
            // 'jemaat_tanggal_baptis' => 'required',
            // 'jemaat_tanggal_sidi' => request('jemaat_tanggal_sidi'),            
            'jemaat_tanggal_bergabung' => 'nullable',
            'id_pendidikan_akhir' => 'required',
            'id_lingkungan' => 'required',
            // 'jemaat_alamat_rumah' => request('jemaat_alamat_rumah'),
            // 'jemaat_nomor_hp' => request('jemaat_nomor_hp'),
            // 'jemaat_email' => 'email',
            'id_parent' => 'nullable',
            'id_pekerjaan' =>'required',
            'jemaat_status_dikeluarga' => 'required',
            // 'jemaat_golongan_darah' => request('jemaat_golongan_darah'),
            'id_parent' => 'required_without:jemaat_kk_status',
        ];
    }

    public function messages()
    {
        return[
            'jemaat_nama.required' => 'Harap isi Nama Jemaat',
            'jemaat_tempat_lahir.required' => 'Tempat lahir harus di isi',
            'jemaat_jenis_kelamin.required' => 'Pilih jenis kelamin',
            'jemaat_tanggal_lahir.required' => 'Tanggal lahir harus di isi',
            // 'jemaat_tanggal_baptis.required' => 'Tanggal baptis jemaat harus di isi',
            'jemaat_status_perkawinan.required' => 'Pilih Status Perkawinan',
            'id_lingkungan.required' => 'Nomor Lingkungan wajib di isi',
            'id_pendidikan_akhir.required' => 'Pilih Pendidikan Akhir',
            'id_pekerjaan.required' => 'Pilih pekerjaan',
            'jemaat_status_dikeluarga.required' => 'Pilih salah satu status dikeluarga',
            'id_parent.required_without' => 'Pilih kepala keluarga',
        ];

    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Only check for lingkungan admins
            if (auth()->user()->isLingkunganAdmin()) {
                $lingkunganId = $this->input('id_lingkungan');

                // Verify user has access to the submitted lingkungan
                if (!auth()->user()->hasLingkunganAccess($lingkunganId)) {
                    $validator->errors()->add(
                        'id_lingkungan',
                        'Anda tidak memiliki akses untuk menambahkan jemaat ke lingkungan ini.'
                    );
                }
            }
        });
    }
}
