<?php

namespace App\Policies;

use App\Models\User;

abstract class BaseRolePolicy
{
    protected function isAdmin(User $user): bool { return $user->role === 'admin'; }
    protected function isKaryawan(User $user): bool { return $user->role === 'karyawan'; }
    protected function isBidan(User $user): bool { return $user->role === 'bidan'; }
    protected function isPemilik(User $user): bool { return $user->role === 'pemilik'; }
    protected function isSupplier(User $user): bool { return $user->role === 'supplier'; }
    protected function isViewer(User $user): bool { return in_array($user->role, ['admin', 'pemilik'], true); }
    protected function isOperational(User $user): bool { return in_array($user->role, ['admin', 'karyawan'], true); }
}
