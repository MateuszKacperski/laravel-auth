<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $query = Project::orderByDesc('updated_at')->orderByDesc('created_at');
        if($filter){
            $value = $filter === 'published';
            $query->whereIsPublished($value);
        }
        $projects = $query->paginate(10)->withQueryString();
        return view('admin.projects.index', compact('projects', 'filter'));
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
        $data = $request->validate([
            'title' => 'required|string|min:5|max:50|unique:projects',
            'content' => 'required|string',
            'image' => 'nullable|url',
            'is_published' => 'nullable|boolean'
        ],[
            'title.required' => 'Il titolo e obligatorio',
            'content.reqwuire' => 'La descrizione e obligatoria',
            'title.min' => 'Il titolo e troppo corto',
            'title.max' => 'Il titolo e troppo lungo ',
            'title.unique' => 'Il titolo deve essere univoco',
            'image.url' => 'Devi inserire l`url',
            'is_published.boolean' => 'Il valore del campo publicazione non e valido',
        ]);

        $data = $request->all();

        $project = new Project();

        $project->fill($data);

        $project->slug = Str::slug($project->title);

        $project->is_published = Arr::exists($data, 'is_published');


        $project->save();

        return to_route('admin.projects.show', $project->id)->with('messager', 'Post creato con sucesso')->wtih('type', 'success');
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
 
        $data = $request->validate([
           'title' => ['required', 'string', 'min:5', 'max:50', Rule::unique('projects')->ignore($project->id)],
            'content' => 'required|string',
            'image' => 'nullable|url',
            'is_published' => 'nullable|boolean'
        ],[
            'title.required' => 'Il titolo e obligatorio',
            'content.reqwuire' => 'La descrizione e obligatoria',
            'title.min' => 'Il titolo e troppo corto',
            'title.max' => 'Il titolo e troppo lungo ',
            'title.unique' => 'Il titolo deve essere univoco',
            'image.url' => 'Devi inserire l`url',
            'is_published.boolean' => 'Il valore del campo publicazione non e valido',
        ]);

        $data = $request->all();


        $project->fill($data);
        
        $project->slug = Str::slug($project->title);

        $project->is_published = Arr::exists($data, 'is_published');
        
        $project->save();

    

        return to_route('admin.projects.show', $project->id)->with('messager', 'Progetto creato con sucesso')->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return to_route('admin.projects.index')->with('type', 'success')->with('message', 'Eliminato con sucesso');
    }
}
    
