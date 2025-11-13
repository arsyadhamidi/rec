<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\CutiDokter;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Spesialis;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $dokters = Dokter::join('spesialis', 'dokter.spesialis_id', 'spesialis.id')
            ->select([
                'dokter.id',
                'dokter.slug',
                'dokter.nm_dokter',
                'dokter.tmp_lahir',
                'dokter.tgl_lahir',
                'dokter.jk',
                'dokter.alamat',
                'dokter.telp_dokter',
                'dokter.tentang',
                'dokter.pendidikan',
                'dokter.keahlian',
                'dokter.foto_dokter',
                'dokter.is_deleted',
                'spesialis.nama_spesialis',
            ])
            ->where('dokter.is_deleted', '1')->orderBy('dokter.id', 'asc')->get();


        $beritas = Berita::join('kategori_berita', 'berita.kategori_berita_id', 'kategori_berita.id')
            ->select([
                'berita.id',
                'berita.judul',
                'berita.slug',
                'berita.isi_berita',
                'berita.ringkasan',
                'berita.gambar_berita',
                'berita.tgl_berita',
                'berita.status',
                'berita.is_deleted',
                'kategori_berita.nm_kategori',
            ])
            ->where('berita.is_deleted', '1')
            ->where('berita.status', '1')
            ->orderBy('berita.id', 'desc')
            ->get(3);

        return view('landing.main.index', [
            'dokters' => $dokters,
            'beritas' => $beritas,
        ]);
    }

    public function profilsingkat()
    {
        return view('landing.tentang-kami.profil-singkat.index');
    }

    public function sejarahrumahsakit()
    {
        return view('landing.tentang-kami.sejarah.index');
    }

    public function visimisi()
    {
        return view('landing.tentang-kami.visi-misi.index');
    }

    public function strukturorganisasi()
    {
        return view('landing.tentang-kami.organisasi.index');
    }

    public function dokterspesialis()
    {
        $dokters = Dokter::join('spesialis', 'dokter.spesialis_id', 'spesialis.id')
            ->select([
                'dokter.id',
                'dokter.slug',
                'dokter.nm_dokter',
                'dokter.tmp_lahir',
                'dokter.tgl_lahir',
                'dokter.jk',
                'dokter.alamat',
                'dokter.telp_dokter',
                'dokter.tentang',
                'dokter.pendidikan',
                'dokter.keahlian',
                'dokter.foto_dokter',
                'dokter.is_deleted',
                'spesialis.nama_spesialis',
            ])
            ->where('dokter.is_deleted', '1')
            ->orderBy('id', 'asc')
            ->get();

        $spesialis = Spesialis::where('is_deleted', '1')->orderBy('id', 'desc')->get();
        return view('landing.dokter-medis.dokter.index', [
            'dokters' => $dokters,
            'spesialis' => $spesialis,
        ]);
    }

    public function profildokter($id)
    {
        $dokters = Dokter::join('spesialis', 'dokter.spesialis_id', 'spesialis.id')
            ->select([
                'dokter.id',
                'dokter.slug',
                'dokter.nm_dokter',
                'dokter.tmp_lahir',
                'dokter.tgl_lahir',
                'dokter.jk',
                'dokter.alamat',
                'dokter.telp_dokter',
                'dokter.tentang',
                'dokter.pendidikan',
                'dokter.keahlian',
                'dokter.foto_dokter',
                'dokter.is_deleted',
                'spesialis.nama_spesialis',
            ])
            ->where('dokter.slug', $id)
            ->where('dokter.is_deleted', '1')
            ->firstOrFail();

        $jadwals = JadwalDokter::where('dokter_id', $dokters->id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->get();

        // Ambil cuti dokter (jika sedang atau pernah cuti)
        $cuti = CutiDokter::where('dokter_id', $dokters->id)
            ->where('is_deleted', '1')
            ->orderByDesc('id')
            ->first();

        return view('landing.dokter-medis.dokter.profile', compact('dokters', 'jadwals', 'cuti'));
    }

    public function tenagamedis()
    {
        return view('landing.dokter-medis.medis.index');
    }

    public function layananunggulan()
    {
        return view('landing.layanan.layanan-unggulan.index');
    }

    public function layananumum()
    {
        return view('landing.layanan.layanan-umum.index');
    }

    public function ruangoperasi()
    {
        return view('landing.fasilitas.ruang-operasi');
    }

    public function berita()
    {
        $beritas = Berita::join('kategori_berita', 'berita.kategori_berita_id', 'kategori_berita.id')
            ->select([
                'berita.id',
                'berita.judul',
                'berita.slug',
                'berita.isi_berita',
                'berita.ringkasan',
                'berita.gambar_berita',
                'berita.tgl_berita',
                'berita.status',
                'berita.is_deleted',
                'kategori_berita.nm_kategori',
            ])
            ->where('berita.is_deleted', '1')
            ->where('berita.status', '1')
            ->orderBy('berita.id', 'desc')
            ->paginate(9);

        return view('landing.berita.index', [
            'beritas' => $beritas,
        ]);
    }

    public function showberita($id)
    {
        $details = Berita::where('slug', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();

        $beritas = Berita::join('kategori_berita', 'berita.kategori_berita_id', 'kategori_berita.id')
            ->select([
                'berita.id',
                'berita.judul',
                'berita.slug',
                'berita.isi_berita',
                'berita.ringkasan',
                'berita.gambar_berita',
                'berita.tgl_berita',
                'berita.status',
                'berita.is_deleted',
                'kategori_berita.nm_kategori',
            ])
            ->where('berita.is_deleted', '1')
            ->where('berita.status', '1')
            ->orderBy('berita.id', 'desc')
            ->get(5);

        $dokters = Dokter::join('spesialis', 'dokter.spesialis_id', 'spesialis.id')
            ->select([
                'dokter.id',
                'dokter.nm_dokter',
                'dokter.tmp_lahir',
                'dokter.tgl_lahir',
                'dokter.jk',
                'dokter.alamat',
                'dokter.telp_dokter',
                'dokter.tentang',
                'dokter.pendidikan',
                'dokter.keahlian',
                'dokter.foto_dokter',
                'dokter.is_deleted',
                'spesialis.nama_spesialis',
            ])
            ->where('dokter.is_deleted', '1')->orderBy('dokter.id', 'asc')->get();
        return view('landing.berita.detail', [
            'details' => $details,
            'beritas' => $beritas,
            'dokters' => $dokters,
        ]);
    }

    public function tatatertib()
    {
        return view('landing.informasi.tata-tertib.index');
    }

    public function kerjasamaasuransi()
    {
        return view('landing.informasi.asuransi.index');
    }

    public function kontak()
    {
        return view('landing.kontak.index');
    }
}
