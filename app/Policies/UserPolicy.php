<?php
namespace App\Policies;
use App\Models\User;
class UserPolicy extends BaseRolePolicy {
 public function viewAny(User $user): bool { return $this->isAdmin($user); }
 public function view(User $user, User $record): bool { return $this->isAdmin($user); }
 public function create(User $user): bool { return $this->isAdmin($user); }
 public function update(User $user, User $record): bool { return $this->isAdmin($user); }
 public function delete(User $user, User $record): bool { return $this->isAdmin($user) && (int)$record->id !== (int)$user->id; }
 public function restore(User $user, User $record): bool { return $this->isAdmin($user); }
 public function forceDelete(User $user, User $record): bool { return $this->isAdmin($user) && (int)$record->id !== (int)$user->id; }
}
