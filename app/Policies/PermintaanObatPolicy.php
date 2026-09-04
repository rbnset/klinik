<?php
namespace App\Policies;
use App\Models\PermintaanObat;
use App\Models\User;
class PermintaanObatPolicy extends BaseRolePolicy {
 public function viewAny(User $user): bool { return in_array($user->role, ['admin','karyawan','bidan','pemilik'], true); }
 public function view(User $user, PermintaanObat $record): bool {
   if ($this->isAdmin($user)||$this->isKaryawan($user)||$this->isPemilik($user)) return true;
   return $this->isBidan($user) && (int)$record->id_pengguna === (int)$user->id;
 }
 public function create(User $user): bool { return $this->isAdmin($user)||$this->isKaryawan($user)||$this->isBidan($user); }
 public function update(User $user, PermintaanObat $record): bool {
   if ($record->status !== 'pending') return false;
   return $this->isAdmin($user)||$this->isKaryawan($user)||($this->isBidan($user)&&(int)$record->id_pengguna===(int)$user->id);
 }
 public function delete(User $user, PermintaanObat $record): bool { return $this->update($user,$record); }
 public function restore(User $user, PermintaanObat $record): bool { return $this->isAdmin($user); }
 public function forceDelete(User $user, PermintaanObat $record): bool { return $this->isAdmin($user); }
}
