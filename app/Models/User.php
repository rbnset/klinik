<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->role !== 'supplier') {
            return true;
        }

        // Akun supplier hasil pengajuan hanya dapat masuk setelah disetujui admin.
        // Akun supplier lama yang belum memiliki record supplier tetap kompatibel.
        return $this->supplier?->status_pengajuan !== 'menunggu'
            && $this->supplier?->status_pengajuan !== 'ditolak';
    }

    // Relasi yang terkait dengan User
    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id_pengguna');
    }
    public function pembelian_obat()
    {
        return $this->hasMany(PembelianObat::class, 'id_pengguna');
    }
    public function permintaan_obat()
    {
        return $this->hasMany(PermintaanObat::class, 'id_pengguna');
    }
    public function penyesuaian_stok()
    {
        return $this->hasMany(PenyesuaianStok::class, 'id_pengguna');
    }
}
