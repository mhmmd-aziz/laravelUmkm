<?php

namespace App\Policies;

use App\Models\Produk;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProdukPolicy
{
    use HandlesAuthorization;



    public function update(User $user, Produk $produk): bool
    {
 
        return $user->toko && $user->toko->id === $produk->toko_id;
    }

  

    public function delete(User $user, Produk $produk): bool
    {
        
        return $user->toko && $user->toko->id === $produk->toko_id;
    }


}

