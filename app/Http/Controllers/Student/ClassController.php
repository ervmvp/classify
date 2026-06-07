<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $classes = auth()->user()->enrolledClasses()->paginate(12);
        return view('student.classes.index', compact('classes'));
    }

    public function join()
    {
        return view('student.classes.join');
    }

    public function joinByCode(Request $request)
    {
        $validated = $request->validate([
            'class_code' => 'required|string|exists:classes,class_code',
        ]);

        $classroom = ClassRoom::where('class_code', $validated['class_code'])->firstOrFail();

        // // Check if already enrolled
        // if (auth()->user()->enrolledClasses()->where('class_id', $classroom->id)->exists()) {
        //     return back()->with('error', 'You are already enrolled in this class');
        // }

        auth()->user()->enrolledClasses()->attach($classroom->id);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'joined_class',
            'entity_type' => 'Class',
            'entity_id' => $classroom->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('student.classes.show', $classroom)->with('success', 'Joined class successfully');
    }

    public function show(ClassRoom $classroom)
    {
        // Check if student is enrolled
        if (!auth()->user()->enrolledClasses()->where('class_id', $classroom->id)->exists()) {
            abort(403, 'Not enrolled in this class');
        }

        $assignments = $classroom->assignments()->latest()->paginate(10);
        return view('student.classes.show', compact('classroom', 'assignments'));
    }

    public function leave(Request $request, ClassRoom $classroom)
    {
        auth()->user()->enrolledClasses()->detach($classroom->id);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'left_class',
            'entity_type' => 'Class',
            'entity_id' => $classroom->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('student.classes.index')->with('success', 'Left class successfully');
    }
}
