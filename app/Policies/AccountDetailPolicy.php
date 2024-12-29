<?php

namespace App\Policies;

use App\Models\AccountDetail;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AccountDetailPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can view the model.
    //  */
    // public function view(User $user, AccountDetail $accountDetail): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can create models.
    //  */
    // public function create(User $user): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can update the model.
    //  */
    // public function update(User $user, AccountDetail $accountDetail): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can delete the model.
    //  */
    // public function delete(User $user, AccountDetail $accountDetail): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can restore the model.
    //  */
    // public function restore(User $user, AccountDetail $accountDetail): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, AccountDetail $accountDetail): bool
    // {
    //     //
    // }

    public function update(User $user, AccountDetail $accountDetail)

        {
            return $user->id === $accountDetail->user_id;
        }

    public function delete(User $user, AccountDetail $accountDetail)

        {
            return $user->id === $accountDetail->user_id;
        }

}
