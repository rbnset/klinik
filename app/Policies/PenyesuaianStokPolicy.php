<?php
namespace App\Policies;
use App\Models\PenyesuaianStok;
use App\Models\User;
class PenyesuaianStokPolicy extends BaseRolePolicy {
 public function viewAny(User $user): bool { return $this->isAdmin($user)||$this->isKaryawan($user)||$this->isPemilik($user); }
 public function view(User $user, PenyesuaianStok $record): bool { return $this->viewAny($user); }
 public function create(User $user): bool { return $this->isAdmin($user)||$this->isKaryawan($user); }
 public function update(User $user, PenyesuaianStok $record): bool { return $this->create($user) && !$record->stok_diposting_at; }
 public function delete(User $user, PenyesuaianStok $record): bool { return $this->create($user) && !$record->stok_diposting_at; }
 public function restore(User $user, PenyesuaianStok $record): bool { return $this->create($user); }
 public function forceDelete(User $user, PenyesuaianStok $record): bool { return $this->isAdmin($user); }
}
