<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Note::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable|max:500'
        ]);

        Note::create($fields);
    
        return response()->json([
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
        $fields = $request -> validate([
            'title' => 'required|max:255',
            'content'=>'nullable|max:500'
        ]);

        $note->update($fields); // updated your data
        return [
            "message" => "Note updated successfully"
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return response()->json([
            "message" => "Note deleted successfully"
        ]);
    }
}
