<?php
namespace App\Policies\User\Shop;

use App\Entities\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the post.
     *
     * @param  \App\Entities\User\User $user
     * @return mixed
     */
    public function view(User $user)
    {
        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        if ($user->can('view-own-shop')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create posts.
     *
     * @param  \App\Entities\User\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        if ($user->can('create-shop')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  \App\Entities\User\User $user
     * @return mixed
     */
    public function update(User $user)
    {
        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        if ($user->can('edit-own-shop')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  \App\Entities\User\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        if ($user->can('delete-own-shop')) {
            return true;
        }

        return false;
    }
}
