<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateResource;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with(['user', 'applications'])->get();
        return response()->json([
            'success' => true,
            'message' => 'Candidate list retrieved successfully',
            'data' => CandidateResource::collection($candidates),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'education' => 'nullable|string',
        ]);

        // Lưu CV
        if ($request->hasFile('resume')) {
            $validated['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        $candidate = Candidate::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Candidate created successfully',
            'data' => new CandidateResource($candidate),
        ], 201);
    }

    public function show(Candidate $candidate) 
    {
        $candidate->load(['user', 'applications']); 

        return response()->json([
            'success' => true,
            'message' => 'Candidate retrieved successfully',
            'data' => new CandidateResource($candidate),
        ]);
    }

    public function update(Request $request, Candidate $candidate) 
    {
        $validated = $request->validate([
            'full_name' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'address' => 'sometimes|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'education' => 'sometimes|string',
        ]);

        if ($request->hasFile('resume')) {
            // Xóa CV cũ 
            if ($candidate->resume) {
                Storage::disk('public')->delete($candidate->resume);
            }

            // Lưu CV mới
            $validated['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        $candidate->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Candidate updated successfully',
            'data' => new CandidateResource($candidate),
        ]);
    }

    public function destroy(Candidate $candidate) 
    {
        // Xóa CV
        if ($candidate->resume) {
            Storage::disk('public')->delete($candidate->resume);
        }

        $candidate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Candidate deleted successfully',
            'data' => [],
        ]);
    }
}
