<?php

namespace App\Policies;

use App\Models\PembelianObat;
use App\Models\User;

class PembelianObatPolicy extends BaseRolePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'karyawan', 'pemilik', 'supplier'], true);
    }

    public function view(User $user, PembelianObat $record): bool
    {
        if ($this->isAdmin($user) || $this->isKaryawan($user) || $this->isPemilik($user)) return true;
        return $this->isSupplier($user) && (int) $record->id_supplier === (int) $user->supplier?->id;
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user) || $this->isKaryawan($user);
    }

    public function update(User $user, PembelianObat $record): bool
    {
        return $this->create($user)
            && $record->status === 'pending'
            && ! $record->supplier_dikonfirmasi_at
            && ! $record->penerimaan_obat()->exists()
            && ! $record->pembayaran()->exists();
    }

    public function delete(User $user, PembelianObat $record): bool
    {
        return $this->update($user, $record);
    }


    public function confirmPrice(User $user, PembelianObat $record): bool
    {
        return ($this->isAdmin($user) || $this->isKaryawan($user))
            && $record->status === 'menunggu_konfirmasi_gudang';
    }

    public function respondAsSupplier(User $user, PembelianObat $record): bool
    {
        return $this->isSupplier($user)
            && (int) $record->id_supplier === (int) $user->supplier?->id
            && $record->status === 'pending';
    }

    public function restore(User $user, PembelianObat $record): bool { return $this->create($user); }
    public function forceDelete(User $user, PembelianObat $record): bool { return $this->isAdmin($user); }
}
