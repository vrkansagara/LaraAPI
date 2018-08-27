<?php

namespace App\Http\Controllers;

use App\Entities\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TodoCreateRequest;
use App\Http\Requests\TodoUpdateRequest;
use App\Repositories\TodoRepository;
use App\Validators\TodoValidator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Class TodosController.
 *
 * @package namespace App\Http\Controllers;
 */
class TodosController extends Controller
{
    /**
     * @var TodoRepository
     */
    protected $repository;

    /**
     * @var TodoValidator
     */
    protected $validator;

    /**
     * TodosController constructor.
     *
     * @param TodoRepository $repository
     * @param TodoValidator $validator
     */
    public function __construct(TodoRepository $repository, TodoValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->permissions;
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $todos = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $todos,
            ]);
        }

        return view('todos.index', compact('todos'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $this->authorize('create', Auth::user());
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TodoCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(TodoCreateRequest $request)
    {
            echo '<pre>'; print_r('hsdfds'); echo __FILE__; echo __LINE__;
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $todo = $this->repository->create($request->all());

            $response = [
                'message' => 'Todo created.',
                'data'    => $todo->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->route('todo.index')->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todo = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $todo,
            ]);
        }

        return view('todos.show', compact('todo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $todo = $this->repository->find($id);

        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TodoUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(TodoUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $todo = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Todo updated.',
                'data'    => $todo->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Todo deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Todo deleted.');
    }
}
