<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\ClassRoom;
use App\Models\AssignmentFile;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function create(Request $request)
    {
        $classroom = ClassRoom::findOrFail($request->query('classroom_id'));
        $this->authorize('update', $classroom);
        return view('teacher.assignments.create', compact('classroom'));
    }

    public function store(Request $request)
    {
        $classroom = ClassRoom::findOrFail($request->input('classroom_id'));
        $this->authorize('update', $classroom);

        $validated = $request->validate([
            'classroom_id' => 'required|integer|exists:classes,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'due_date'     => 'required|date|after:now',
            'total_points' => 'required|integer|min:1',
            'files.*'      => 'nullable|file|max:10240',
        ]);

        $validated['class_id']   = $classroom->id;
        $validated['teacher_id'] = auth()->id();

        $assignment = Assignment::create($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('assignments/' . $assignment->id, 'public');
                AssignmentFile::create([
                    'assignment_id' => $assignment->id,
                    'file_path'     => $path,
                    'file_name'     => $file->getClientOriginalName(),
                    'mime_type'     => $file->getMimeType(),
                    'file_size'     => $file->getSize(),
                ]);
            }
        }

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'created',
            'entity_type' => 'Assignment',
            'entity_id'   => $assignment->id,
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('teacher.classes.show', $classroom)
            ->with('success', 'Assignment created successfully');
    }

    public function show(Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $submissions = $assignment->submissions()->with('student')->paginate(20);
        return view('teacher.assignments.show', compact('assignment', 'submissions'));
    }

    public function edit(Assignment $assignment)
    {
        $this->authorize('update', $assignment);
        return view('teacher.assignments.edit', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $this->authorize('update', $assignment);

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'due_date'     => 'required|date',
            'total_points' => 'required|integer|min:1',
        ]);

        $assignment->update($validated);

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'updated',
            'entity_type' => 'Assignment',
            'entity_id'   => $assignment->id,
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('teacher.assignments.show', $assignment)
            ->with('success', 'Assignment updated successfully');
    }

    public function destroy(Request $request, Assignment $assignment)
    {
        $this->authorize('delete', $assignment);

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'deleted',
            'entity_type' => 'Assignment',
            'entity_id'   => $assignment->id,
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        $assignment->delete();

        return back()->with('success', 'Assignment deleted successfully');
    }
}