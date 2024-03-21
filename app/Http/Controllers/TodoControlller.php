<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoControlller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $todos = Todo::orderBy('created_at', 'desc')->where([
            'user_id' => Auth::user()->id,
        ]);

        if ($request->has('status') && $request->status !== '') {
            $todos->where('status', $request->status);
        }

        $todos = $todos->paginate($request->limit ?? 20);

        return response()->json([
            'success' => true,
            'message' => 'List of all todos',
            'data' => $todos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|in:pending,in-progress,completed',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages(),
                'errors' => [
                    'error_data' => $validator->errors(),
                    'error_code' => 'VALIDATION_ERROR',
                ]
            ], 422);
        }

        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status ?? 'pending',
            'user_id' => Auth::user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Todo created successfully',
            'data' => $todo,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'success' => false,
                'message' => 'Todo not found',
                'errors' => [
                    'error_data' => 'Todo not found',
                    'error_code' => 'NOT_FOUND',
                ]
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Todo details',
            'data' => $todo,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'success' => false,
                'message' => 'Todo not found',
                'errors' => [
                    'error_data' => 'Todo not found',
                    'error_code' => 'NOT_FOUND',
                ]
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|in:pending,in-progress,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages(),
                'errors' => [
                    'error_data' => $validator->errors(),
                    'error_code' => 'VALIDATION_ERROR',
                ]
            ], 422);
        }

        $todo->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Todo updated successfully',
            'data' => $todo,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'success' => false,
                'message' => 'Todo not found',
                'errors' => [
                    'error_data' => 'Todo not found',
                    'error_code' => 'NOT_FOUND',
                ]
            ], 404);
        }

        $todo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Todo deleted successfully',
        ]);
    }
}
