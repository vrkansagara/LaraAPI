@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="row justify-content-center">
            <a href="{{url(route('todo.index'))}}">Todo</a>
            <a href="{{url(route('todo.create'))}}">Create</a>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table>
                    <tr>
                        <td>Task</td>
                        <td>Status</td>
                        <td>Created</td>
                    </tr>
                    @foreach($todos as $todo)
                        <tr>
                            <td>{{$todo->task}}</td>
                            <td>{{$todo->status}}</td>
                            <td>{{$todo->created_at}}</td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
@endsection
