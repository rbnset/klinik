<?php
namespace App\Policies;
use App\Models\Pembayaran;
use App\Models\User;
class PembayaranPolicy extends BaseRolePolicy {
 public function viewAny(User $user): bool { return $this->isAdmin($user)||$this->isKaryawan($user)||$this->isPemilik($user); }
 public function view(User $user, Pembayaran $record): bool { return $this->viewAny($user); }
 public function create(User $user): bool { return $this->isAdmin($user)||$this->isKaryawan($user); }
 public function update(User $user, Pembayaran $record): bool { return $this->create($user); }
 public function delete(User $user, Pembayaran $record): bool { return $this->create($user); }
 public function restore(User $user, Pembayaran $record): bool { return $this->create($user); }
 public function forceDelete(User $user, Pembayaran $record): bool { return $this->isAdmin($user); }
}
