<?php
namespace App\Policies;
use App\Models\PenerimaanObat;
use App\Models\User;
class PenerimaanObatPolicy extends BaseRolePolicy {
 public function viewAny(User $user): bool { return in_array($user->role,['admin','karyawan','pemilik','supplier'],true); }
 public function view(User $user, PenerimaanObat $record): bool { if(in_array($user->role,['admin','karyawan','pemilik'],true)) return true; return $user->isSupplier() && (int)$record->pembelian_obat?->id_supplier === (int)$user->supplier?->id; }
 public function create(User $user): bool { return $this->isAdmin($user)||$this->isKaryawan($user); }
 public function update(User $user, PenerimaanObat $record): bool { return $this->create($user) && !$record->stok_diposting_at; }
 public function delete(User $user, PenerimaanObat $record): bool { return $this->create($user) && !$record->stok_diposting_at; }
 public function restore(User $user, PenerimaanObat $record): bool { return $this->create($user); }
 public function forceDelete(User $user, PenerimaanObat $record): bool { return $this->isAdmin($user); }
}
