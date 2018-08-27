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
                <form action="{{route('todo.store')}}" method="post">
                    @csrf
                    <span>Task: </span><input type="text" name="task">
                    <span>Priority: </span>
                    <select name="priority" id="">
                        <option value="0" selected>0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>
                    <span>Status: </span>
                    <select name="status" id="">
                        <option value="1" selected>1</option>
                        <option value="0">0</option>
                    </select>

                    <input type="submit" value="Save">
                    <input type="reset" value="Clear">
                </form>
            </div>
        </div>
    </div>
@endsection
