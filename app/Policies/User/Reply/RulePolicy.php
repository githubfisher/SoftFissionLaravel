<?php
namespace App\Policies\User\Reply;

use App\Models\User\User;
use App\Models\User\Reply\Rule;
use Illuminate\Auth\Access\HandlesAuthorization;

class RulePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view the post.
     *
     * @param  \App\Models\User\User $user
     * @param  \App\Models\User\Reply\Rule $rule
     * @return mixed
     */
    public function view(User $user, Rule $rule)
    {
        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        // authors can view their own unpublished posts
        return $user->id == $rule->user_id;
    }

    /**
     * Determine whether the user can create posts.
     *
     * @param  \App\Models\User\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can('create rules')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  \App\Models\User\User $user
     * @param  \App\Models\User\Reply\Rule $rule
     * @return mixed
     */
    public function update(User $user, Rule $rule)
    {
        if ($user->can('edit own rules')) {
            return $user->id == $rule->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  \App\Models\User\User $user
     * @param  \App\Models\User\Reply\Rule $rule
     * @return mixed
     */
    public function delete(User $user, Rule $rule)
    {
        if ($user->can('delete own rules')) {
            if ($user->pid == 0) {
                return $user->id == $rule->user_id;
            }

            return $user->pid == $rule->user_id;
        }

        return false;
    }
}
