<?php
namespace App\Policies;
use App\Models\PenerimaanObat;
use App\Models\User;
class PenerimaanObatPolicy extends BaseRolePolicy {
 public function viewAny(User $user): bool { return $this->isAdmin($user)||$this->isKaryawan($user)||$this->isPemilik($user); }
 public function view(User $user, PenerimaanObat $record): bool { return $this->viewAny($user); }
 public function create(User $user): bool { return $this->isAdmin($user)||$this->isKaryawan($user); }
 public function update(User $user, PenerimaanObat $record): bool { return $this->create($user) && !$record->stok_diposting_at; }
 public function delete(User $user, PenerimaanObat $record): bool { return $this->create($user) && !$record->stok_diposting_at; }
 public function restore(User $user, PenerimaanObat $record): bool { return $this->create($user); }
 public function forceDelete(User $user, PenerimaanObat $record): bool { return $this->isAdmin($user); }
}
