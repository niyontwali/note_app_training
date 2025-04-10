<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function modify(User $user, Note $note): Response
    {
        return $user->id === $note->user->id
        ? Response::allow()
        : Response::deny('You can only modify your own note');
    }
}
