<?php
namespace Database\Seeders;
use App\Models\DetailPembelianObat;
use App\Models\DetailPenerimaanObat;
use App\Models\Obat;
use App\Models\Pembayaran;
use App\Models\PembelianObat;
use App\Models\PenerimaanObat;
use App\Models\RiwayatStok;
use App\Services\StokObatService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
class PengadaanBarangSeeder extends Seeder
{
 public function run(): void {
  $purchases=[
   [30,1,[[1,80,3500,80],[2,60,6000,60]],'lunas'],
   [20,2,[[1,100,3800,70],[4,50,15000,50]],'sebagian'],
   [10,3,[[1,120,3650,120],[2,70,6200,50],[6,60,5200,60]],'belum'],
  ];
  foreach($purchases as [$daysAgo,$supplierId,$items,$payState]){
   $tgl=Carbon::now()->subDays($daysAgo); $po=PembelianObat::create(['id_supplier'=>$supplierId,'id_pengguna'=>2,'tanggal_pesan'=>$tgl,'status'=>'selesai']);
   $details=[]; foreach($items as [$obatId,$qty,$harga,$terima]) $details[] = DetailPembelianObat::create(['id_pembelian_obat'=>$po->id,'id_obat'=>$obatId,'jumlah_pesan'=>$qty,'harga_satuan'=>$harga]);
   $penerimaan=PenerimaanObat::create(['id_pembelian_obat'=>$po->id,'nomor_faktur'=>'INV-SUP-'.str_pad((string)$po->id,4,'0',STR_PAD_LEFT),'tanggal_terima'=>$tgl->copy()->addDays(2)]);
   foreach($details as $i=>$detail){ $qty=$items[$i][3]; if($qty<=0) continue; DetailPenerimaanObat::create(['id_penerimaan_obat'=>$penerimaan->id,'id_detail_pembelian'=>$detail->id,'jumlah_diterima'=>$qty]); }
   app(StokObatService::class)->postingPenerimaan($penerimaan);
   $total=(int)$po->total_pesanan; $bayar=$payState==='lunas'?$total:($payState==='sebagian'?intdiv($total,2):0);
   if($bayar>0) Pembayaran::create(['id_pembelian_obat'=>$po->id,'tanggal_bayar'=>$tgl->copy()->addDays(3),'metode_pembayaran'=>'transfer','total_bayar'=>$bayar]);
  }
 }
}
