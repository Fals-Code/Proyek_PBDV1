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

        // Ambil daftar penerimaan untuk dropdown
        $penerimaan = DB::table('v_detail_penerimaan_header')
            ->orderByDesc('idpenerimaan')
            ->get();

        return view('retur.index', compact('retur', 'penerimaan'));
    }

    /**
     * Simpan retur baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.idbarang' => 'required|integer|exists:barang,idbarang',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.alasan' => 'required|string|max:200',
            'items.*.iddetail_penerimaan' => 'nullable|integer|exists:detail_penerimaan,iddetail_penerimaan',
        ], [
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
            $idPenerimaan = $request->idpenerimaan ?? null;

            // Format items untuk stored procedure
            $items = json_encode($request->items);

            // Panggil stored procedure
            DB::statement("CALL sp_add_retur(?, ?, ?, @out_id)", [
                $idPenerimaan,
                $idUser,
                $items
            ]);

            // Ambil output ID retur
            $result = DB::selectOne("SELECT @out_id AS idretur");
            $idretur = $result->idretur ?? null;

            DB::commit();

            if (!$idretur) {
                return back()->with('error', 'Gagal menyimpan retur. Silakan coba lagi.');
            }

            return redirect()->route('retur.index')
                ->with('success', "Retur #{$idretur} berhasil disimpan!");
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            // Handle error dari stored procedure
            if (str_contains($e->getMessage(), 'melebihi')) {
                return back()->with('error', 'Jumlah retur melebihi jumlah penerimaan!');
            }

            return back()->with('error', 'Kesalahan database: ' . $e->getMessage());
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

        // Ambil header (ambil data pertama untuk info umum)
        $header = $retur->first();

        return view('retur.show', compact('retur', 'header'));
    }

    /**
     * Update status retur (misal: dari Pending → Selesai)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:N,P,S', // N=New, P=Process, S=Selesai
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
     * Hapus retur (opsional, jika diperlukan)
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
}
