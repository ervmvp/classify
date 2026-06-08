<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\SubmissionComment;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');
    }

    public function show(Submission $submission)
    {
        $submission->loadMissing('assignment', 'files');
        $this->authorize('grade', $submission);
        $submission->loadMissing('assignment'); // Ensure assignment is loaded for the view
        $comments = $submission->comments()->with('user')->latest()->get();
        return view('teacher.submissions.show', compact('submission', 'comments'));
    }

    public function grade(Request $request, Submission $submission)
    {   
        $submission->loadMissing('assignment');
        $this->authorize('grade', $submission);
        
        $validated = $request->validate([
            'grade' => 'required|integer|min:0|max:' . $submission->assignment->total_points,
        ]);

        $submission->update([
            'grade' => $validated['grade'],
            'status' => 'graded',
            'graded_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'graded',
            'entity_type' => 'Submission',
            'entity_id' => $submission->id,
            'changes' => json_encode(['grade' => $validated['grade']]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Submission graded successfully');
    }

    public function comment(Request $request, Submission $submission)
    {   
        $submission->loadMissing('assignment');
        $this->authorize('grade', $submission);

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = SubmissionComment::create([
            'submission_id' => $submission->id,
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'commented',
            'entity_type' => 'Submission',
            'entity_id' => $submission->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Comment added successfully');
    }
}
