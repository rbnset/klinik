<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy extends BaseRolePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'karyawan', 'pemilik', 'supplier'], true);
    }

    public function view(User $user, Supplier $record): bool
    {
        if ($this->isAdmin($user) || $this->isKaryawan($user) || $this->isPemilik($user)) {
            return true;
        }

        return $this->isSupplier($user) && (int) $record->id_pengguna === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Supplier $record): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        return $this->isSupplier($user) && (int) $record->id_pengguna === (int) $user->id;
    }

    public function delete(User $user, Supplier $record): bool
    {
        // Supplier tidak boleh menghapus dirinya sendiri. Penghapusan hanya oleh admin.
        return $this->isAdmin($user);
    }

    public function restore(User $user, Supplier $record): bool
    {
        return $this->isAdmin($user);
    }

    public function forceDelete(User $user, Supplier $record): bool
    {
        return $this->isAdmin($user);
    }
}
