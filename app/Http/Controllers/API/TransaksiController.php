<?php

namespace App\Http\Controllers\API;

use App\Events\TransaksiCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransaksiCollection;
use App\Http\Resources\TransaksiResource;
use App\Models\Depo;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Throwable;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $transaksis = Transaksi::query()
            ->with(['depo', 'kurir.user', 'barangs']);

        if ($user->role == 'Customer') {
            $transaksis = $transaksis->where('customer_id', $user->customer->id);
        } elseif ($user->role == 'Kurir') {
            $transaksis = $transaksis->where('kurir_id', $user->kurir->id);
        }

        $transaksis = $transaksis->orderBy('tanggal', 'desc')
            ->get();

        return new TransaksiCollection($transaksis);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $customer = Auth::user()->customer;
            $barangs = [];
            foreach ($request->cart as $cart) {
                $barangs[$cart['barang']['id']] = (int) $cart['jumlah'];
            }
            $depo = $this->findDepo($barangs, $request->lokasi);

            if ($depo) {
                DB::beginTransaction();

                $transaksi = $customer
                    ->transaksis()
                    ->create([
                        'depo_id' => $depo->id,
                        'customer_id' => $customer->id,
                        'tanggal' => now('Asia/Jakarta'),
                        'status' => 'Menunggu Pembayaran',
                        'lokasi_pengiriman' => new Point($request->lokasi['lat'], $request->lokasi['lng'])
                    ]);

                $transaksiDetails = [];

                foreach ($barangs as $barang => $jumlah) {
                    $transaksiDetails[$barang] = ['jumlah' => $jumlah];
                }

                $transaksi->barangs()->sync($transaksiDetails);
                $customer->barangs()->sync([]);

                DB::commit();

                $transaksi = $transaksi->load(['depo', 'barangs', 'customer.user']);
                TransaksiCreated::broadcast($transaksi);

                return TransaksiResource::make($transaksi);
            } else {
                return Response::json([
                    'status' => 'GAGAL',
                    'msg' => 'Barang tidak tersedia'
                ], 500);
            }
        } catch (Throwable $err) {
            Log::error($err->getMessage());
            abort(500, $err->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['barangs', 'depo', 'kurir.user']);

        return new TransaksiResource($transaksi);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $kurir = $transaksi->kurir;
        $ulasan = $request->ulasan;

        DB::beginTransaction();

        try {
            $transaksi->when(
                $ulasan,
                fn () => $transaksi->update([
                    'status' => $request->status,
                    'ulasan' => $ulasan
                ]),
                fn () => $transaksi->update([
                    'status' => $request->status
                ])
            );

            $trxKurir = $kurir->transaksis()
                ->where('status', 'Diproses')
                ->get();

            if ($trxKurir->count() === 0) {
                $kurir->update([
                    'status' => 'Idle'
                ]);
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return Response::json([
                'status' => 'GAGAL',
                'msg' => 'Terjadi kesalahan sistem. Silahkan coba lagi nanti'
            ], 500);
        }

        return Response::json([
            'status' => 'OK',
            'msg' => 'Transaksi selesai'
        ]);
    }

    public function addToKeranjang(Request $request)
    {
        $customer = Auth::user()->customer;

        try {
            DB::beginTransaction();

            $customer->barangs()->sync([
                $request->barang => ['jumlah' => $request->jumlah],
            ], false);

            DB::commit();
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            return Response::json([
                'status' => 'GAGAL',
                'msg' => 'Terjadi kesalahan sistem. Silahkan coba lagi nanti'
            ], 500);
        }

        return Response::json(['msg' => 'OK']);
    }

    // cari 1 depo terdekat yang stok pesanan mencukupi
    private function findDepo($barangs, $lokasi)
    {
        $ids = collect($barangs)
            ->map(fn ($barang, $key) => $key)
            ->values();
        $keranjangs = collect($barangs)
            ->map(fn ($jumlah, $id) => ['id' => $id, 'jumlah' => $jumlah])
            ->values();
        $listDepo = collect([]);
        $depos = Depo::query()
            ->with(['barangs' => fn ($q) => $q->whereIn('barang_id', $ids)->orderBy('barang_id', 'asc')])
            ->whereHas('barangs', fn ($q) => $q->whereIn('barang_id', $ids))
            ->get();

        foreach ($depos as $depo) {
            if ($depo->barangs->count() == $ids->count()) {
                $barangDepo = $depo->barangs;
                foreach ($keranjangs as $i => $keranjang) {
                    if ($barangDepo[$i]->pivot->stok >= $keranjang['jumlah']) {
                        $listDepo->add($depo);
                    } else {
                        continue;
                    }
                }
            } else {
                continue;
            }
        }

        foreach ($listDepo as $depo) {
            $lokasiDepo = $depo->lokasi;
            $distance = $this->haversineGreatCircleDistance(
                $lokasi['lat'],
                $lokasi['lng'],
                $lokasiDepo->latitude,
                $lokasiDepo->longitude
            );
            $depo['distance'] = $distance;
        }

        $nearest = $listDepo->sortBy(function ($depo) {
            return $depo->distance;
        });

        return $nearest->first();
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public function haversineGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo,
        $earthRadius = 6371000
    ) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    public function uploadBukti(Request $request, Transaksi $transaksi)
    {
        DB::beginTransaction();
        try {
            Storage::putFileAs('public', $request->file('bukti'), "bukti/{$transaksi->id}.jpeg");
            $transaksi->update([
                'bukti_pembayaran' => "bukti/{$transaksi->id}.jpeg",
                'status' => 'Menunggu Konfirmasi'
            ]);
            DB::commit();
        } catch (Throwable $err) {
            Log::error($err->getMessage());
            DB::rollBack();

            return Response::json([
                'status' => 'GAGAL',
                'msg' => $err->getMessage()
            ], 500);
        }

        return Response::json([
            'status' => 'OK',
            'msg' => 'Bukti pembayaran berhasil terkirim'
        ]);
    }
}
