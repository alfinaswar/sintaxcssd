<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenghapusanAset extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'penghapusan_asets';
    protected $guarded = ['id'];

    /**
     * Versi alur approval.
     * 1 = alur lama (Sign1 = Manager, Sign2 = SMI, Sign3 = file ttd Karu)
     * 2 = alur 5 tingkat (SMI -> JangMed -> YanMed -> Keu -> Direktur)
     * 3 = alur 6 tingkat (SMI -> Karu -> PenunjangMed -> YanMed -> Keu -> Direktur)
     */
    const VERSI_LAMA = 1;
    const VERSI_5_TAHAP = 2;
    const VERSI_6_TAHAP = 3;

    /**
     * Definisi 6 tahap tanda tangan berurutan.
     * Approval lewat link tanpa login: tidak ada pengecekan role, hanya urutan tahap.
     *
     * key      : dipakai untuk route dan Status
     * sign     : kolom path file tanda tangan
     * acc      : kolom status approval (HARUS PERSIS, jangan menebak dari ucfirst($key))
     * acc_oleh : kolom nama penanda tangan
     * status   : nilai kolom Status saat tahap ini menunggu
     */
    const TAHAP = [
        'smi' => [
            'sign' => 'Sign1',
            'acc' => 'AccSmi',
            'acc_oleh' => 'AccSmiOleh',
            'label' => 'SMI',
            'jabatan' => 'SMI',
            'status' => 'smi',
        ],
        'karu' => [
            'sign' => 'Sign2',
            'acc' => 'AccKaru',
            'acc_oleh' => 'AccKaruOleh',
            'label' => 'Karu/Kanit Ruangan',
            'jabatan' => 'Karu/Kanit Ruangan',
            'status' => 'karu',
        ],
        'penunjangmed' => [
            'sign' => 'Sign3',
            'acc' => 'AccPenunjangMed',
            'acc_oleh' => 'AccPenunjangMedOleh',
            'label' => 'Kadiv Penunjang Medis',
            'jabatan' => 'Kadiv Penunjang Medis',
            'status' => 'penunjangmed',
        ],
        'yanmed' => [
            'sign' => 'Sign4',
            'acc' => 'AccYanMed',
            'acc_oleh' => 'AccYanMedOleh',
            'label' => 'Kadiv Pelayanan Medis',
            'jabatan' => 'Kadiv Pelayanan Medis',
            'status' => 'yanmed',
        ],
        'keu' => [
            'sign' => 'Sign5',
            'acc' => 'AccKeu',
            'acc_oleh' => 'AccKeuOleh',
            'label' => 'Kadiv Keuangan',
            'jabatan' => 'Kadiv Keuangan',
            'status' => 'keu',
        ],
        'direktur' => [
            'sign' => 'Sign6',
            'acc' => 'AccDirektur',
            'acc_oleh' => 'AccDirekturOleh',
            'label' => 'Direktur',
            'jabatan' => 'Direktur',
            'status' => 'direktur',
        ],
    ];

    public function getDepartemen()
    {
        return $this->belongsTo(MasterDepartemenModel::class, 'Departemen', 'id');
    }
    public function getDiajukan()
    {
        return $this->belongsTo(user::class, 'DiajukanOleh', 'id');
    }
    /**
     * Alur lama: Sign1 = Manager penunjang.
     */
    public function getManager()
    {
        return $this->belongsTo(user::class, 'Sign1', 'id');
    }
    /**
     * Alur lama: Sign2 = SMI.
     */
    public function getSmi()
    {
        return $this->belongsTo(user::class, 'Sign2', 'id');
    }
    public function getRs()
    {
        return $this->belongsTo(MasterRs::class, 'KodeRS', 'kodeRS');
    }
    public function getDetail()
    {
        return $this->hasMany(PenghapusanAsetDetail::class, 'idPenghapusan', 'id');
    }
    public function getGudang()
    {
        return $this->belongsTo(MasterGudang::class, 'NamaGudang', 'id');
    }

    /* ---------------------------------------------------------------------
     | Helper alur approval
     | ------------------------------------------------------------------ */

    /**
     * Apakah baris ini memakai alur berurutan 6 tahap.
     *
     * @return bool
     */
    public function isAlurBerurutan()
    {
        return (int) $this->VersiAlur >= self::VERSI_6_TAHAP;
    }

    /**
     * Daftar key tahap berurutan.
     *
     * @return array
     */
    public static function daftarTahap()
    {
        return array_keys(self::TAHAP);
    }

    /**
     * Konfigurasi satu tahap.
     *
     * @param  string  $tahap
     * @return array|null
     */
    public static function konfigurasiTahap($tahap)
    {
        return self::TAHAP[$tahap] ?? null;
    }

    /**
     * Key tahap berikutnya, atau null bila sudah tahap terakhir.
     *
     * @param  string|null  $tahap
     * @return string|null
     */
    public static function tahapSetelah($tahap)
    {
        $daftar = self::daftarTahap();
        $index = array_search($tahap, $daftar, true);

        if ($index === false) {
            return null;
        }

        return $daftar[$index + 1] ?? null;
    }

    /**
     * Tahap yang sedang menunggu tanda tangan.
     *
     * @return string|null
     */
    public function tahapAktif()
    {
        if (! $this->isAlurBerurutan()) {
            return null;
        }

        return array_key_exists((string) $this->Status, self::TAHAP) ? (string) $this->Status : null;
    }

    /**
     * Apakah pengajuan sedang menunggu tahap tertentu.
     *
     * @param  string  $tahap
     * @return bool
     */
    public function menungguTahap($tahap)
    {
        return $this->tahapAktif() === $tahap;
    }

    /**
     * Kolom Sign* yang menunggu tanda tangan.
     * Sign sebelumnya sudah terisi -> kolom Sign berikutnya yang aktif.
     *
     * @return string|null
     */
    public function signBerikutnya()
    {
        $tahap = $this->tahapAktif();

        return $tahap ? self::TAHAP[$tahap]['sign'] : null;
    }

    /**
     * Nomor urut tahap (1..6).
     *
     * @param  string  $tahap
     * @return int|null
     */
    public static function nomorTahap($tahap)
    {
        $index = array_search($tahap, self::daftarTahap(), true);

        return $index === false ? null : $index + 1;
    }

    /**
     * Nama kolom status approval sebuah tahap.
     *
     * @param  string  $tahap
     * @return string|null
     */
    public static function kolomAccTahap($tahap)
    {
        return self::TAHAP[$tahap]['acc'] ?? null;
    }

    /**
     * Nama kolom waktu approval sebuah tahap.
     *
     * @param  string  $tahap
     * @return string|null
     */
    public static function kolomAccPadaTahap($tahap)
    {
        $kolom = self::kolomAccTahap($tahap);

        return $kolom ? $kolom . 'Pada' : null;
    }

    /**
     * Nilai kolom Acc sebuah tahap.
     *
     * @param  string  $tahap
     * @return string|null
     */
    public function accTahap($tahap)
    {
        $kolom = self::kolomAccTahap($tahap);

        return $kolom ? ($this->{$kolom} ?? null) : null;
    }

    /**
     * Waktu approval sebuah tahap.
     *
     * @param  string  $tahap
     * @return string|null
     */
    public function accPadaTahap($tahap)
    {
        $kolom = self::kolomAccPadaTahap($tahap);

        return $kolom ? ($this->{$kolom} ?? null) : null;
    }

    /**
     * Path file tanda tangan sebuah tahap.
     *
     * @param  string  $tahap
     * @return string|null
     */
    public function signTahap($tahap)
    {
        $config = self::konfigurasiTahap($tahap);

        return $config ? ($this->{$config['sign']} ?? null) : null;
    }

    /**
     * Nama penanda tangan sebuah tahap (diketik approver, tanpa login).
     *
     * @param  string  $tahap
     * @return string|null
     */
    public function namaTahap($tahap)
    {
        $config = self::konfigurasiTahap($tahap);

        return $config ? ($this->{$config['acc_oleh']} ?? null) : null;
    }

    /**
     * Status setelah sebuah tahap ditandatangani.
     *
     * @param  string  $tahap
     * @return string
     */
    public static function statusSetelahTahap($tahap)
    {
        $berikutnya = self::tahapSetelah($tahap);

        return $berikutnya === null ? 'disetujui' : self::TAHAP[$berikutnya]['status'];
    }

    /**
     * URL approval tanpa login untuk sebuah tahap.
     * Cukup memakai id pengajuan, tanpa token.
     *
     * @param  string  $tahap
     * @return string
     */
    public function urlApproval($tahap)
    {
        return route('pa.approval.publik', [
            'id' => $this->getKey(),
            'tahap' => $tahap,
        ]);
    }

    /**
     * URL approval untuk tahap yang sedang menunggu.
     *
     * @return string|null
     */
    public function urlApprovalAktif()
    {
        $tahap = $this->tahapAktif();

        return $tahap ? $this->urlApproval($tahap) : null;
    }

    /**
     * Label status untuk badge.
     *
     * @return string
     */
    public function labelStatus()
    {
        $status = (string) $this->Status;

        if ($this->isAlurBerurutan() && ($config = self::konfigurasiTahap($status))) {
            return 'Menunggu ' . $config['label'];
        }

        switch ($status) {
            case 'disetujui':
                return 'Disetujui';
            case 'ditolak':
                return 'Ditolak';
            case 'proses':
                return 'Proses';
            case 'draft':
                return 'Draft';
            case 'pengajuan':
                return 'Pengajuan';
        }

        if ($config = self::konfigurasiTahap($status)) {
            return 'Menunggu ' . $config['label'];
        }

        return ucfirst($status);
    }

    /**
     * Kelas badge bootstrap untuk status.
     *
     * @return string
     */
    public function badgeStatus()
    {
        $status = (string) $this->Status;

        if ($status === 'disetujui') {
            return 'badge-success';
        }

        if ($status === 'ditolak') {
            return 'badge-danger';
        }

        if (array_key_exists($status, self::TAHAP)) {
            return 'badge-warning';
        }

        return $status === 'proses' ? 'badge-info' : 'badge-secondary';
    }
}
