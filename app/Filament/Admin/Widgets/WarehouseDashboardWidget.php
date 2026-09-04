<?php
namespace App\Filament\Admin\Widgets;
use App\Filament\Admin\Resources\Obats\ObatResource; use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource; use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource; use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource; use App\Models\Obat; use App\Models\PembelianObat; use App\Models\PermintaanObat; use Filament\Widgets\Widget;
class WarehouseDashboardWidget extends Widget { protected string $view='filament.admin.widgets.warehouse-dashboard-widget'; protected static ?int $sort=-5; protected int|string|array $columnSpan='full'; public static function canView():bool{return auth()->user()?->role==='karyawan';}
 public function getStokKritis():int{return Obat::where('stok','<=',10)->count();}
 public function getPermintaanPending():int{return PermintaanObat::where('status','pending')->count();}
 public function getPoMenunggu():int{return PembelianObat::where('status','pending')->count();}
 public function getPoKonfirmasiHarga():int{return PembelianObat::where('status','menunggu_konfirmasi_gudang')->count();}
 public function getPenerimaanBerjalan():int{return PembelianObat::where('status','diproses')->whereHas('penerimaan_obat')->whereHas('detail_pembelian')->get()->filter(fn($po)=>$po->status_penerimaan!=='lengkap')->count();}
 public function url(string $resource):string{return match($resource){ 'obat'=>ObatResource::getUrl(), 'permintaan'=>PermintaanObatResource::getUrl(), 'po'=>PembelianObatResource::getUrl(), 'penerimaan'=>PenerimaanObatResource::getUrl(), default=>'#'};}
}
