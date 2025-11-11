<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Alamat;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// --- TAMBAHAN BARU ---
use Midtrans\Config;
use Midtrans\Snap;
// --- BATAS TAMBAHAN ---


class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout DAN membuat Snap Token.
     */
    public function index(): View|RedirectResponse
    {
        // 1. Cek Keranjang & Alamat
        if (Cart::isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan belanja dulu.');
        }
        $user = Auth::user();
        $alamats = Alamat::where('user_id', $user->id)->orderBy('label_alamat', 'asc')->get();
        if ($alamats->isEmpty()) {
            return redirect()->route('profile.edit')->with('error', 'Anda harus menambahkan alamat pengiriman terlebih dahulu sebelum checkout.');
        }
        
        $cartItems = Cart::getContent()->sortBy('name');
        $total = Cart::getTotal();

        // 2. Konfigurasi Midtrans
        // (Pastikan .env dan config/midtrans.php sudah benar)
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // 3. Buat Detail Item untuk Midtrans
        $midtransItems = [];
        foreach ($cartItems as $item) {
            $midtransItems[] = [
                'id'       => $item->id,
                'price'    => $item->price,
                'quantity' => $item->quantity,
                'name'     => $item->name,
            ];
        }
        
        // TODO: Tambahkan Ongkir (jika sudah ada)
        // $midtransItems[] = [
        //     'id'       => 'ONGKIR',
        //     'price'    => 15000, 
        //     'quantity' => 1,
        //     'name'     => 'Ongkos Kirim',
        // ];
        // $total += 15000;

        // 4. Buat Parameter Transaksi
        $orderId = 'INV-USER-' . $user->id . '-' . Carbon::now()->timestamp;
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'item_details' => $midtransItems,
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $alamats->first()->nomor_telepon, // Ambil dari alamat pertama (default)
                // 'billing_address' => ... (opsional)
                // 'shipping_address' => ... (opsional)
            ],
        ];

        // 5. Dapatkan Snap Token
        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            \Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Gagal memulai sesi pembayaran. Silakan coba lagi.');
        }

        // 6. Kirim ke View
        return view('checkout.index', compact('alamats', 'cartItems', 'total', 'snapToken'));
    }


    /**
     * Memproses callback/redirect SETELAH pembayaran Midtrans.
     * Ini menggantikan fungsi store() yang lama.
     */
    public function process(Request $request): RedirectResponse
    {
        // 1. Validasi input (dari redirect Midtrans)
        $request->validate([
            'order_id' => 'required|string',
            'status_code' => 'required|string',
            'transaction_id' => 'required|string',
            'alamat_id' => 'required|integer|exists:alamats,id', // <-- Ambil alamat_id
        ]);
        
        // 2. Otorisasi alamat (tambahan keamanan)
        $alamat = Alamat::find($request->alamat_id);
        if ($alamat->user_id !== Auth::id()) {
            return redirect()->route('cart.index')->with('error', 'Alamat tidak valid.');
        }
        $alamatJson = $alamat->toJson();

        // 3. Cek keranjang
        if (Cart::isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Sesi checkout Anda telah berakhir (Keranjang kosong).');
        }
        
        // 4. Cek status pembayaran (hanya 'capture' atau 'settlement' atau 'pending' yang kita proses)
        $statusCode = $request->status_code;
        $orderId = $request->order_id;
        
        // Jika GAGAL atau DITUTUP (status code 201 = pending)
        // 200 = sukses
        // 201 = pending
        // 202 = challenge
        // 407 = failure
        if ($statusCode == '202' || $statusCode == '407') {
            return redirect()->route('cart.index')->with('error', 'Pembayaran Anda gagal atau dibatalkan.');
        }
        
        // Jika Sukses (200) atau Pending (201)
        // Kita akan membuat pesanan.
        $statusTransaksi = ($statusCode == '200') ? 'diproses' : 'menunggu_pembayaran';
        $metodePembayaran = 'midtrans'; // Ganti dari 'bank_transfer'
        
        $cartItems = Cart::getContent();
        $user = Auth::user();
        $itemsPerToko = $cartItems->groupBy('attributes.toko_id');
        $transaksiBaruDibuat = []; // Untuk halaman sukses

        // 5. Mulai Database Transaction (SAMA SEPERTI LAMA)
        try {
            DB::beginTransaction();

            foreach ($itemsPerToko as $toko_id => $items) {
                
                $totalHargaPerToko = $items->sum(fn($item) => $item->getPriceSum());

                // Buat Invoice ID baru yang SAMA dengan order_id Midtrans, tapi unik per toko
                $invoiceId = $orderId . '-T' . $toko_id;

                $transaksi = Transaksi::create([
                    'user_id' => $user->id,
                    'toko_id' => $toko_id,
                    'invoice_id' => $invoiceId, // Gunakan order_id + toko_id
                    'total_harga' => $totalHargaPerToko,
                    'ongkir' => 0, // TODO: Tambah ongkir
                    'total_bayar' => $totalHargaPerToko, // TODO: Tambah ongkir
                    'status_transaksi' => $statusTransaksi, 
                    'alamat_pengiriman' => $alamatJson,
                    'metode_pembayaran' => $metodePembayaran,
                    'catatan_penjual' => 'Midtrans TX ID: ' . $request->transaction_id, // Simpan ID Midtrans
                ]);

                foreach ($items as $item) {
                    $produk = Produk::lockForUpdate()->find($item->id);
                    if (!$produk || $produk->stok < $item->quantity) {
                        throw new \Exception('Stok produk ' . $item->name . ' tidak mencukupi.');
                    }
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $produk->id,
                        'toko_id' => $toko_id, // Denormalisasi
                        'jumlah' => $item->quantity,
                        'harga_satuan' => $item->price,
                        'subtotal' => $item->getPriceSum(),
                    ]);
                    $produk->stok -= $item->quantity;
                    $produk->save();
                }
                
                // Kirim ID invoice, BUKAN semua data transaksi
                $transaksiBaruDibuat[] = $invoiceId; 
            }
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal membuat transaksi (Midtrans): ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }

        // 6. Kosongkan keranjang
        Cart::clear();

        // 7. Redirect ke halaman sukses
        // Kirim daftar Invoice ID yang baru dibuat
        return redirect()->route('pembeli.checkout.success')->with('invoice_ids', $transaksiBaruDibuat);
    }

    /**
     * Menampilkan halaman pesanan berhasil.
     */
    public function success(Request $request): View|RedirectResponse
    {
        $invoice_ids = $request->session()->get('invoice_ids');

        if (!$invoice_ids) {
            return redirect()->route('pembeli.pesanan.index');
        }

        // Tampilkan view sukses
        return view('checkout.success', compact('invoice_ids'));
    }
}