<?php

namespace App\Policies;

use App\Entities\User;
use App\Todo;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class TodoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the todo.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Todo  $todo
     * @return mixed
     */
    public function view(User $user, Todo $todo)
    {
        //
    }

    /**
     * Determine whether the user can create todos.
     *
     * @param  \App\Entities\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        dd($user);
    }

    /**
     * Determine whether the user can update the todo.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Todo  $todo
     * @return mixed
     */
    public function update(User $user, Todo $todo)
    {
        //
    }

    /**
     * Determine whether the user can delete the todo.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Todo  $todo
     * @return mixed
     */
    public function delete(User $user, Todo $todo)
    {
        //
    }

    /**
     * Determine whether the user can restore the todo.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Todo  $todo
     * @return mixed
     */
    public function restore(User $user, Todo $todo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the todo.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Todo  $todo
     * @return mixed
     */
    public function forceDelete(User $user, Todo $todo)
    {
        //
    }
}
