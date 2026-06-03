<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');
    }

    public function index()
    {
        $classes = auth()->user()->createdClasses()->paginate(12);
        return view('teacher.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('teacher.classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
        ]);

        $validated['teacher_id'] = auth()->id();
        $validated['class_code'] = Str::upper(substr(md5(uniqid()), 0, 6));

        $classroom = ClassRoom::create($validated);

        // Generate QR code
        $qrCode = new QrCode($classroom->class_code);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $path = 'qrcodes/' . $classroom->id . '.png';
        \Storage::disk('public')->put($path, $result->getString());
        $classroom->update(['qr_code_path' => $path]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'entity_type' => 'Class',
            'entity_id' => $classroom->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('teacher.classes.show', $classroom)->with('success', 'Class created successfully');
    }

    public function show(ClassRoom $classroom)
    {
        $this->authorize('update', $classroom);
        $assignments = $classroom->assignments()->latest()->paginate(10);
        return view('teacher.classes.show', compact('classroom', 'assignments'));
    }

    public function edit(ClassRoom $classroom)
    {
        $this->authorize('update', $classroom);
        return view('teacher.classes.edit', compact('classroom'));
    }

    public function update(Request $request, ClassRoom $classroom)
    {
        $this->authorize('update', $classroom);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'announcement' => 'nullable|string',
        ]);

        $classroom->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'entity_type' => 'Class',
            'entity_id' => $classroom->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('teacher.classes.show', $classroom)->with('success', 'Class updated successfully');
    }

    public function destroy(Request $request, ClassRoom $classroom)
    {
        $this->authorize('delete', $classroom);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'entity_type' => 'Class',
            'entity_id' => $classroom->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $classroom->delete();

        return redirect()->route('teacher.classes.index')->with('success', 'Class deleted successfully');
    }

    public function students(ClassRoom $classroom)
    {
        $this->authorize('update', $classroom);
        $students = $classroom->students()->paginate(20);
        return view('teacher.classes.students', compact('classroom', 'students'));
    }

    public function removeStudent(Request $request, ClassRoom $classroom)
    {
        $this->authorize('update', $classroom);

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $classroom->students()->detach($validated['student_id']);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'removed_student',
            'entity_type' => 'ClassStudent',
            'entity_id' => $validated['student_id'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Student removed successfully');
    }
}
