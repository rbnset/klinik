<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
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
