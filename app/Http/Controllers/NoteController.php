<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::all();
        return response()->json([
            'ok' => true,
            'data' => $notes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)

    {
        $fields = $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable|max:500',
            'user_id' => 'required|exists:users,id'
        ]);

       $newUser =  Note::create($fields);

        if(!$newUser) {
            return response()->json([
                'ok'=> false,
                'message' => 'User was not created, retry'
            ]);
        }
        return response()->json([
            'ok' => true,
            'message' => 'Note created succeffully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        return $note;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        Gate::authorize('modify', $note);

        $fields = $request -> validate([
            'title' => 'required|max:255',
            'content'=>'nullable|max:500',
            'user_id' => 'required|exists:users,id'
        ]);

        $note->update($fields); // updated your data
        return response() -> json([
            'ok'=>true,
            'message' => 'Note updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        // Policy
        Gate::authorize('modify', $note);

        $note->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Note deleted successfully'
        ]);
    }
}
