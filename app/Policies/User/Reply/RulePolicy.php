<?php
namespace App\Policies\User\Reply;

use Log;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the post.
     *
     * @param  \App\Models\User\User $user
     * @return mixed
     */
    public function view(User $user)
    {
        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        if ($user->can('view rules')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create posts.
     *
     * @param  \App\Models\User\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        Log::debug(__FUNCTION__ . ' ' . $user->id);
        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        if ($user->can('create rules')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  \App\Models\User\User $user
     * @return mixed
     */
    public function update(User $user)
    {
        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        if ($user->can('edit own rules')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  \App\Models\User\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        if ($user->can('delete own rules')) {
            return true;
        }

        return false;
    }
}
