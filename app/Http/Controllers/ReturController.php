<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReturController extends Controller
{
    /**
     * Tampilkan daftar retur
     */
    public function index()
    {
        // Ambil data retur lengkap dari view
        $retur = DB::table('v_retur_lengkap')
            ->orderByDesc('idretur')
            ->get();

        // Ambil daftar penerimaan untuk dropdown (retur ke vendor)
        $penerimaan = DB::table('v_detail_penerimaan_header')
            ->orderByDesc('idpenerimaan')
            ->get();

        // ✅ Ambil daftar penjualan untuk dropdown (retur dari customer)
        $penjualan = DB::table('penjualan')
            ->join('user', 'penjualan.iduser', '=', 'user.iduser')
            ->select('penjualan.*', 'user.username')
            ->orderByDesc('idpenjualan')
            ->get();

        return view('retur.index', compact('retur', 'penerimaan', 'penjualan'));
    }

    /**
     * Simpan retur baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_retur' => 'required|in:penerimaan,penjualan', // ✅ Validasi jenis retur
            'items' => 'required|array|min:1',
            'items.*.idbarang' => 'required|integer|exists:barang,idbarang',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.alasan' => 'required|string|max:200',
            'items.*.iddetail_penerimaan' => 'nullable|integer|exists:detail_penerimaan,iddetail_penerimaan',
            'items.*.iddetail_penjualan' => 'nullable|integer|exists:detail_penjualan,iddetail_penjualan',
        ], [
            'jenis_retur.required' => 'Jenis retur wajib dipilih.',
            'items.required' => 'Tambahkan minimal 1 item untuk diretur.',
            'items.*.idbarang.required' => 'ID Barang wajib diisi.',
            'items.*.idbarang.exists' => 'Barang tidak ditemukan di database.',
            'items.*.jumlah.required' => 'Jumlah retur wajib diisi.',
            'items.*.jumlah.min' => 'Jumlah retur minimal 1.',
            'items.*.alasan.required' => 'Alasan retur wajib diisi.',
        ]);

        try {
            DB::beginTransaction();

            $idUser = Auth::user()->iduser;
            $jenisRetur = $request->jenis_retur;

            // ✅ Tentukan ID referensi berdasarkan jenis retur
            if ($jenisRetur === 'penerimaan') {
                $idReferensi = $request->idpenerimaan ?? null; // Retur ke vendor
                $tableName = 'detail_penerimaan';
            } else {
                $idReferensi = $request->idpenjualan ?? null; // Retur dari customer
                $tableName = 'detail_penjualan';
            }

            // ✅ Insert ke tabel retur (header)
            $idRetur = DB::table('retur')->insertGetId([
                'idpenerimaan' => $jenisRetur === 'penerimaan' ? $idReferensi : null,
                'iduser' => $idUser,
                'jenis_retur' => $jenisRetur, // ✅ Simpan jenis retur
                'status' => 'N', // N = New
                'created_at' => now()
            ]);

            // ✅ Insert detail retur
            foreach ($request->items as $item) {

                // ✅ Validasi jumlah tidak melebihi yang diterima/dijual
                if ($idReferensi) {
                    $jumlahAsli = DB::table($tableName)
                        ->where($jenisRetur === 'penerimaan' ? 'idpenerimaan' : 'idpenjualan', $idReferensi)
                        ->where('idbarang', $item['idbarang'])
                        ->value($jenisRetur === 'penerimaan' ? 'jumlah_terima' : 'jumlah');

                    if ($item['jumlah'] > $jumlahAsli) {
                        DB::rollBack();
                        return back()->with('error', "Jumlah retur melebihi jumlah " .
                            ($jenisRetur === 'penerimaan' ? 'penerimaan' : 'penjualan') .
                            " untuk barang ID: {$item['idbarang']}");
                    }
                }

                // Insert detail retur
                DB::table('detail_retur')->insert([
                    'idretur' => $idRetur,
                    'idbarang' => $item['idbarang'],
                    'jumlah' => $item['jumlah'],
                    'alasan' => $item['alasan'],
                    'iddetail_penerimaan' => $item['iddetail_penerimaan'] ?? null,
                    'created_at' => now()
                ]);
            }

            DB::commit();

            return redirect()->route('retur.index')
                ->with('success', "Retur #{$idRetur} berhasil disimpan! (Jenis: " .
                    ($jenisRetur === 'penerimaan' ? 'Retur ke Vendor' : 'Retur dari Customer') . ")");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail retur
     */
    public function show($id)
    {
        $retur = DB::table('v_retur_lengkap')
            ->where('idretur', $id)
            ->get();

        if ($retur->isEmpty()) {
            return redirect()->route('retur.index')
                ->with('error', 'Data retur tidak ditemukan.');
        }

        $header = $retur->first();

        return view('retur.show', compact('retur', 'header'));
    }

    /**
     * Update status retur
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:N,P,S',
        ]);

        try {
            DB::table('retur')
                ->where('idretur', $id)
                ->update(['status' => $request->status]);

            return redirect()->route('retur.index')
                ->with('success', 'Status retur berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    /**
     * Hapus retur
     */
    public function destroy($id)
    {
        try {
            DB::table('detail_retur')->where('idretur', $id)->delete();
            DB::table('retur')->where('idretur', $id)->delete();

            return redirect()->route('retur.index')
                ->with('success', 'Retur berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus retur: ' . $e->getMessage());
        }
    }

    public function getItemsPenerimaan($idpenerimaan)
    {
        $items = DB::table('detail_penerimaan')
            ->join('barang', 'detail_penerimaan.idbarang', '=', 'barang.idbarang')
            ->where('detail_penerimaan.idpenerimaan', $idpenerimaan)
            ->select('barang.nama as nama_barang', 'detail_penerimaan.jumlah')
            ->get();

        return response()->json($items);
    }

    public function getItemsPenjualan($idpenjualan)
    {
        $items = DB::table('detail_penjualan')
            ->join('barang', 'detail_penjualan.idbarang', '=', 'barang.idbarang')
            ->where('detail_penjualan.idpenjualan', $idpenjualan)
            ->select('barang.nama as nama_barang', 'detail_penjualan.jumlah')
            ->get();

        return response()->json($items);
    }
}
