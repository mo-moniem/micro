<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Repository\Contracts\NoteContract;

class NoteController extends Controller
{

    public function __construct(public NoteContract $repo)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = $this->repo->index();

        return response()->json([
            'status' => true,
            'notes' => NoteResource::collection($notes)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteRequest $request)
    {
       $note = $this->repo->store($request->validated());
       return response()->json([
           'status' => true,
           'message' => 'Note created successfully',
           'note' => new NoteResource($note)
       ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        return response()->json([
            'status' => true,
            'note' => new NoteResource($note)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteRequest $request, Note $note)
    {
        $note = $this->repo->update($request->validated(),$note);
        return response()->json([
            'status' => true,
            'message' => 'Note updated successfully',
            'note' => new NoteResource($note)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();
        return response()->json([
            'status' => true,
            'message' => 'Note deleted successfully'
        ]);
    }
}
