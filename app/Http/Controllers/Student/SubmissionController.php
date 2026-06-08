<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\SubmissionComment;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Assignment $assignment)
    {
        $submission = Submission::firstOrCreate([
            'assignment_id' => $assignment->id,
            'student_id' => auth()->id(),
        ]);

        return view('student.submissions.create', compact('assignment', 'submission'));
    }

    public function store(Request $request, Assignment $assignment)
    {
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        $validated = $request->validate([
            'content' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,docx,pptx,jpg,png,zip|max:10240',
        ]);

        // At least one file or content is required
        if (!$validated['content'] && !$request->hasFile('files')) {
            return back()->withErrors(['submission' => 'Please add either content or upload files']);
        }

        $submission->update([
            'content' => $validated['content'],
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('submissions/' . $submission->id, 'public');
                SubmissionFile::create([
                    'submission_id' => $submission->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'submitted',
            'entity_type' => 'Submission',
            'entity_id' => $submission->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('student.submissions.show', $submission)->with('success', 'Assignment submitted successfully');
    }

    public function show(Submission $submission)
    {
        if ($submission->student_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $submission->loadMissing('files');
        $submission->loadMissing('assignment'); // Ensure assignment is loaded for the view
        $comments = $submission->comments()->with('user')->latest()->get();
        return view('student.submissions.show', compact('submission', 'comments'));
        $comments = $submission->comments()->with('user')->latest()->get();
        return view('student.submissions.show', compact('submission', 'comments'));
    }

    public function comment(Request $request, Submission $submission)
    {
        if ($submission->student_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        SubmissionComment::create([
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
