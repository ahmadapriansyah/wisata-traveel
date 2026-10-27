<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WisataController extends Controller
{

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
        $data = (Auth::user()?->role === 'Admin')
            ? DB::table('pemesanan')->get()
            : DB::table('pemesanan')->whereIn('id', $this->getPemesananIds())->get();

        return view('pemesanan', ['data' => $data]);
    }

    public function prosesPesan(Request $request)
    {
        $request->validate([
            'id_paket' => 'required',
            'jumlah'   => 'required|numeric|min:1',
        ]);

        try {
            DB::table('pemesanan')->insert([
                'user_id'    => Auth::id(),
                'paket_id'   => $request->id_paket,
                'jumlah_pax' => $request->jumlah,
                'status'     => 'Menunggu Pembayaran',
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            DB::table('pemesanan')->insert([
                'id_user'    => Auth::id(),
                'id_paket'   => $request->id_paket,
                'jumlah_pax' => $request->jumlah,
                'status'     => 'Menunggu Pembayaran',
                'created_at' => now(),
            ]);
        }

        return redirect('/pembayaran')->with('success', 'Pemesanan berhasil! Silakan lakukan pembayaran.');
    }

    public function tampilPembayaran(): View
    {
        $data = (Auth::user()?->role === 'Admin')
            ? DB::table('pembayaran')->get()
            : DB::table('pembayaran')->whereIn('id_pemesanan', $this->getPemesananIds())->get();

        return view('pembayaran', ['data' => $data]);
    }

    public function prosesBayar(Request $request)
    {
        $request->validate([
            'id_pemesanan' => 'required',
            'jumlah_bayar' => 'required|numeric',
            'bukti_bayar'  => 'required',
        ]);

        DB::table('pembayaran')->insert([
            'id_pemesanan' => $request->id_pemesanan,
            'jumlah_bayar' => $request->jumlah_bayar,
            'bukti_bayar'  => $request->bukti_bayar,
            'status'       => 'Verifikasi',
            'created_at'   => now(),
        ]);

        return redirect('/pembayaran')->with('success', 'Pembayaran berhasil dikirim! Menunggu verifikasi admin.');
    }

    public function verifikasiPembayaran(Request $request, $id)
    {
        DB::table('pembayaran')->where('id', $id)->update(['status' => 'Terverifikasi']);

        $pembayaran = DB::table('pembayaran')->where('id', $id)->first();
        if ($pembayaran) {
            DB::table('pemesanan')->where('id', $pembayaran->id_pemesanan)->update(['status' => 'Lunas']);

            DB::table('tiket')->insert([
                'id_pemesanan' => $pembayaran->id_pemesanan,
                'kode_tiket'   => 'TKT-' . rand(1000, 9999),
                'created_at'   => now(),
            ]);
        }

        return redirect('/pembayaran')->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    public function tampilTiket(): View
    {
        $data = (Auth::user()?->role === 'Admin')
            ? DB::table('tiket')->get()
            : DB::table('tiket')->whereIn('id_pemesanan', $this->getPemesananIds())->get();

        return view('tiket', ['data' => $data]);
    }

    public function tampilPengembalian(): View
    {
        try {
            $data = (Auth::user()?->role === 'Admin')
                ? DB::table('pengembalian')->get()
                : DB::table('pengembalian')->whereIn('id_pemesanan', $this->getPemesananIds())->get();
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