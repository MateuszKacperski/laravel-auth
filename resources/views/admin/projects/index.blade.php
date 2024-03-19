@extends('layouts.app')

@section('title', 'Projects')

@section('content')

<header>
    <h1>Projects</h1>
</header>



    <table class="table table-dark table-striped">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Titolo</th>
            <th scope="col">Slug</th>
            <th scope="col">Creato il</th>
            <th scope="col">Ultima modifica</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
            @forelse($projects as $project)
            <tr>
              <th scope="row">{{$project->id}}</th>
              <td>{{ $project->title }}</td>
              <td>{{ $project->slug }}</td>
              <td>{{ $project->created_at }}</td>
              <td>{{ $project->updated_at}}</td>
              <td>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{route('admin.projects.show', $project)}}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>

                    <a href="{{route('admin.projects.edit', $project)}}" class="btn btn-sm btn-warning"> <i class="fas fa-pencil"></i></a>
                    <form action="{{route('admin.projects.destroy', $project)}}" method="POST" class="delete-form">
                     @csrf
                     @method('DELETE')
                     <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash-can"></i></button>
                    </form>

                 </td> 
                </tr>
                </div>

            @empty

            <tr>
                <td colspan="6">
                    <h3 class="text-center">Non ci sono progetti</h3>
                </td>
            </tr>

            @endforelse
        </tbody>
      </table>
@endsection

@section('scripts')
      @vite('resources/js/delete_confermation.js')
@endsection