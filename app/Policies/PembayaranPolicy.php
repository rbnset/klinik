<?php
namespace App\Policies;
use App\Models\Pembayaran; use App\Models\User;
class PembayaranPolicy extends BaseRolePolicy {
 public function viewAny(User $user): bool { return in_array($user->role,['admin','karyawan','pemilik','supplier'],true); }
 public function view(User $user,Pembayaran $record): bool { if(in_array($user->role,['admin','karyawan','pemilik'],true)) return true; return $user->isSupplier() && (int)$record->pembelian_obat?->id_supplier === (int)$user->supplier?->id; }
 public function create(User $user): bool { return $this->isAdmin($user)||$this->isKaryawan($user); }
 public function update(User $user,Pembayaran $record): bool { return $this->create($user) && $record->status !== 'disetujui_supplier'; }
 public function delete(User $user,Pembayaran $record): bool { return $this->create($user) && $record->status !== 'disetujui_supplier'; }
 public function restore(User $user,Pembayaran $record): bool { return $this->create($user); }
 public function forceDelete(User $user,Pembayaran $record): bool { return $this->isAdmin($user); }
 public function approveBySupplier(User $user,Pembayaran $record): bool { return $user->isSupplier() && (int)$record->pembelian_obat?->id_supplier === (int)$user->supplier?->id && $record->status === 'menunggu_supplier'; }
 public function rejectBySupplier(User $user,Pembayaran $record): bool { return $this->approveBySupplier($user,$record); }
}
