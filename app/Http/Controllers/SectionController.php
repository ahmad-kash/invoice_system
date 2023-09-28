<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateSectionRequest;
use App\Models\Section;
use App\Notifications\Database\Section\SectionCreated;
use App\Notifications\Database\Section\SectionDeleted;
use App\Notifications\Database\Section\SectionUpdated;
use App\Services\Notification\AdminNotifyService;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    public function __construct(private AdminNotifyService $adminNotifyService)
    {
        $this->authorizeResource(Section::class, 'section');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('section.index', ['sections' => Section::paginate(5)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('section.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateSectionRequest $request)
    {
        $section = Section::create($request->validated() + ['user_id' => auth()->id()]);

        $this->adminNotifyService->notifyAdmins(new SectionCreated($section, auth()->user()));

        return redirect()->route('sections.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        return view('section.show', ['section' => $section]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        return view('section.edit', ['section' => $section]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateSectionRequest $request, Section $section)
    {
        $section->update($request->validated());

        $this->adminNotifyService->notifyAdmins(new SectionUpdated($section, auth()->user()));

        return redirect()->route('sections.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        $section->delete();

        $this->adminNotifyService->notifyAdmins(new SectionDeleted($section, auth()->user()));

        return redirect()->route('sections.index', status: 303);
    }
}
