<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = new Project();
        return view('admin.projects.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->all();

        $data['slug'] = Str::slug($data['title'], '-');


        $new_project = new Project();
        if (array_key_exists('image', $data)) {
            $img_path = Storage::put('projects', $data['image']);
            $data['image'] =  $img_path;
        }
        $new_project->fill($data);

        $new_project->save();

        return  to_route('admin.projects.show', $new_project->id)->with('type', 'success')->with('msg', 'project created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $data = $request->all();



        $data['slug'] = Str::slug($data['title'], '-');

        // Upload files
        if (array_key_exists('image', $data)) {
            if ($project->image) {
                Storage::delete($project->image);
            }
            $img_path = Storage::put('projects', $data['image']);
            $data['image'] =  $img_path;
        }
        //update
        $project->update($data);

        return to_route('admin.projects.show', $project->id)
            ->with('type', 'success')
            ->with('msg', 'sucses change');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return to_route('admin.projects.index')
            ->with('type', 'danger')
            ->with('msg', 'Project deleted');
    }
}
