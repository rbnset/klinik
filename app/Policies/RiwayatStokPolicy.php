<?php
namespace App\Policies;
use App\Models\RiwayatStok;
use App\Models\User;
class RiwayatStokPolicy extends BaseRolePolicy {
 public function viewAny(User $user): bool { return $this->isAdmin($user)||$this->isKaryawan($user)||$this->isPemilik($user); }
 public function view(User $user, RiwayatStok $record): bool { return $this->viewAny($user); }
 public function create(User $user): bool { return false; }
 public function update(User $user, RiwayatStok $record): bool { return false; }
 public function delete(User $user, RiwayatStok $record): bool { return false; }
 public function restore(User $user, RiwayatStok $record): bool { return false; }
 public function forceDelete(User $user, RiwayatStok $record): bool { return false; }
}
