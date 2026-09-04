<?php
namespace App\Policies;
use App\Models\Obat;
use App\Models\User;
class ObatPolicy extends BaseRolePolicy {
 public function viewAny(User $user): bool { return in_array($user->role, ['admin','karyawan','bidan','pemilik'], true); }
 public function view(User $user, Obat $record): bool { return $this->viewAny($user); }
 public function create(User $user): bool { return $this->isAdmin($user)||$this->isKaryawan($user); }
 public function update(User $user, Obat $record): bool { return $this->create($user); }
 public function delete(User $user, Obat $record): bool { return $this->create($user); }
 public function restore(User $user, Obat $record): bool { return $this->create($user); }
 public function forceDelete(User $user, Obat $record): bool { return $this->isAdmin($user); }
}
