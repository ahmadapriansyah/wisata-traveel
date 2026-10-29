<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WisataController extends Controller
{
    public function index()
    {
        try {
            $wisata = DB::table('paket_wisata')->get();
        } catch (\Exception $e) {
            $wisata = [];
        }

        return view('dashboard', compact('wisata'));
    }

    public function halamanLogin()
    {
        return view('login');
    }

    public function prosesLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = DB::table('users')
            ->where('email', $request->email)
            ->where('password', $request->password)
            ->first();

        if ($user) {
            Auth::loginUsingId($user->id);
            $request->session()->regenerate();

            return redirect()->intended($user->role === 'Admin' ? '/dashboard' : '/paket-wisata');
        }

        return back()->with('error', 'Login gagal! Email atau password salah.');
    }

    public function halamanRegister()
    {
        return view('register');
    }

    public function prosesRegister(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.unique'       => 'Email sudah terdaftar!',
            'password.confirmed' => 'Konfirmasi password tidak cocok!',
        ]);

        DB::table('users')->insert([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => $request->password,
            'role'       => 'Wisatawan',
            'created_at' => now(),
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function dashboard(): View
    {
        return view('dashboard');
    }

    public function tampilUsers(): View
    {
        return view('users', ['data' => DB::table('users')->get()]);
    }

    public function tampilPaket(): View
    {
        return view('paket_wisata', ['data' => DB::table('paket_wisata')->get()]);
    }

    public function tampilJadwal(): View
    {
        return view('jadwal_keberangkatan', ['data' => DB::table('jadwal_keberangkatan')->get()]);
    }

    public function tampilCustomTrip(): View
    {
        return view('custom_trip', ['data' => DB::table('custom_trip')->get()]);
    }

    public function tampilPeserta(): View
    {
        return view('peserta', ['data' => DB::table('peserta')->get()]);
    }

    public function tampilPemesanan(): View
    {
        $query = DB::table('pemesanan')
            ->leftJoin('paket_wisata', 'pemesanan.paket_wisata_id', '=', 'paket_wisata.id')
            ->select('pemesanan.*', 'paket_wisata.nama_paket', 'paket_wisata.foto');

        if (Auth::user()?->role !== 'Admin') {
            $query->where('pemesanan.user_id', Auth::id());
        }

        $data = $query->get();

        return view('pemesanan', compact('data'));
    }

    public function formPesan($id)
    {
        $paket = DB::table('paket_wisata')->where('id', $id)->first();
        return view('pesan_tiket', compact('paket'));
    }

    public function simpanPesan(Request $request)
    {
        DB::table('pemesanan')->insert([
            'kode_pemesanan'  => 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'user_id'         => Auth::id() ?? 1,
            'paket_wisata_id' => $request->paket_wisata_id,
            'jadwal_id'       => $request->jadwal_id ?? null,
            'tanggal_wisata'  => $request->tanggal_wisata,
            'jumlah_tiket'    => $request->jumlah_tiket,
            'total_harga'     => $request->total_harga,
            'total_bayar'     => $request->total_harga,
            'status'          => 'pending',
            'created_at'      => now(),
        ]);

        return redirect('/pemesanan')->with('success', 'Pemesanan berhasil disimpan!');
    }

    public function tampilPembayaran(): View
    {
        $pemesananIds = $this->getPemesananIds();

        try {
            $query = DB::table('pembayaran');
            if (Auth::user()?->role !== 'Admin') {
                $query->whereIn('pemesanan_id', $pemesananIds);
            }
            $data = $query->get();
        } catch (\Exception $e) {
            try {
                $query = DB::table('pembayaran');
                if (Auth::user()?->role !== 'Admin') {
                    $query->whereIn('id_pemesanan', $pemesananIds);
                }
                $data = $query->get();
            } catch (\Exception $ex) {
                $data = [];
            }
        }

        return view('pembayaran', ['data' => $data]);
    }

public function prosesBayar(Request $request)
{
    $request->validate([
        'pemesanan_id' => 'required',
        'jumlah_bayar' => 'required',
        'bukti_bayar'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // 1. Simpan file gambar
    $fileName = time() . '_' . $request->file('bukti_bayar')->getClientOriginalName();
    $request->file('bukti_bayar')->storeAs('public/bukti_pembayaran', $fileName);

    $pemesananId = (int) $request->pemesanan_id;
    $nominal     = $request->jumlah_bayar;

    // 2. Cek kolom database
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('pembayaran');

    $dataInsert = [];

    // Kolom ID Pemesanan
    if (in_array('pemesanan_id', $columns)) {
        $dataInsert['pemesanan_id'] = $pemesananId;
    } elseif (in_array('id_pemesanan', $columns)) {
        $dataInsert['id_pemesanan'] = $pemesananId;
    }

    // Kolom Nominal
    if (in_array('nominal', $columns)) {
        $dataInsert['nominal'] = $nominal;
    } elseif (in_array('jumlah_bayar', $columns)) {
        $dataInsert['jumlah_bayar'] = $nominal;
    } elseif (in_array('total_bayar', $columns)) {
        $dataInsert['total_bayar'] = $nominal;
    }

    // Kolom Bukti
    if (in_array('bukti_transfer', $columns)) {
        $dataInsert['bukti_transfer'] = $fileName;
    } elseif (in_array('bukti_bayar', $columns)) {
        $dataInsert['bukti_bayar'] = $fileName;
    } elseif (in_array('bukti', $columns)) {
        $dataInsert['bukti'] = $fileName;
    }

    // KUNCI PERBAIKAN: Kolom Metode Pembayaran
    if (in_array('metode_pembayaran', $columns)) {
        $dataInsert['metode_pembayaran'] = 'Transfer Bank';
    } elseif (in_array('metode', $columns)) {
        $dataInsert['metode'] = 'Transfer Bank';
    }

    // Status & Timestamp
    if (in_array('status', $columns)) {
        $dataInsert['status'] = 'Verifikasi';
    }
    if (in_array('created_at', $columns)) {
        $dataInsert['created_at'] = now();
    }

    // Insert ke database
    DB::table('pembayaran')->insert($dataInsert);

    // Update status di tabel pemesanan
    DB::table('pemesanan')
        ->where('id', $pemesananId)
        ->update(['status' => 'Verifikasi']);

    return redirect('/pembayaran')->with('success', 'Konfirmasi pembayaran berhasil dikirim!');
}
    public function verifikasiPembayaran(Request $request, $id)
    {
        DB::table('pembayaran')->where('id', $id)->update(['status' => 'Terverifikasi']);

        $pembayaran = DB::table('pembayaran')->where('id', $id)->first();
        if ($pembayaran) {
            $idPemesanan = $pembayaran->pemesanan_id ?? $pembayaran->id_pemesanan;
            
            DB::table('pemesanan')->where('id', $idPemesanan)->update(['status' => 'Lunas']);

            DB::table('tiket')->insert([
                'pemesanan_id' => $idPemesanan,
                'kode_tiket'   => 'TKT-' . rand(1000, 9999),
                'created_at'   => now(),
            ]);
        }

        return redirect('/pembayaran')->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    public function tampilTiket(): View
    {
        $pemesananIds = $this->getPemesananIds();

        try {
            $data = (Auth::user()?->role === 'Admin')
                ? DB::table('tiket')->get()
                : DB::table('tiket')->whereIn('pemesanan_id', $pemesananIds)->get();
        } catch (\Exception $e) {
            $data = (Auth::user()?->role === 'Admin')
                ? DB::table('tiket')->get()
                : DB::table('tiket')->whereIn('id_pemesanan', $pemesananIds)->get();
        }

        return view('tiket', ['data' => $data]);
    }

    public function tampilPengembalian(): View
    {
        $pemesananIds = $this->getPemesananIds();

        try {
            $data = (Auth::user()?->role === 'Admin')
                ? DB::table('pengembalian')->get()
                : DB::table('pengembalian')->whereIn('pemesanan_id', $pemesananIds)->get();
        } catch (\Exception $e) {
            $data = [];
        }

        return view('pengembalian', ['data' => $data]);
    }

    public function tampilLaporan(): View
    {
        return view('laporan', [
            'pemesanan'  => DB::table('pemesanan')->get(),
            'pembayaran' => DB::table('pembayaran')->get(),
        ]);
    }

    private function getPemesananIds(): array
    {
        try {
            return DB::table('pemesanan')->where('user_id', Auth::id())->pluck('id')->toArray();
        } catch (\Exception $e) {
            try {
                return DB::table('pemesanan')->where('id_user', Auth::id())->pluck('id')->toArray();
            } catch (\Exception $ex) {
                return [];
            }
        }
    }
}
