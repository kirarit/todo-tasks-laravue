<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Show the todos page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('todos');
    }

    /**
     * Returns todos as json response
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function todos()
    {
        return response()->json(Task::all());
    }

    /**
     * Creates and stores todo task to database.
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        /**
         * Validation
         */
        $this->validate($request, [
            'name' => [ 'required',  'string', 'min:3', 'max:50', 'unique:tasks,name' ],
            'description' => [ 'required', 'string', 'min:5' ]
        ]);

        $todoItem = new Task();
        $todoItem->name = $request->name;
        $todoItem->description = $request->description;
        $todoItem->save();

        return $todoItem;
    }

    /**
     * Updates a todo task
     * @param Request $request
     * @param TodoItem $todoItem
     * @return TodoItem
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Task $todoItem)
    {
        /**
         * Validate the request,
         * Exempt the current TodoItem if the name has not changed
         */
        $this->validate($request, [
            'name' => [ 'required', 'string', 'min:3', 'max:50', 'unique:tasks,name,'.$todoItem->id ],
            'description' => [ 'required', 'string', 'min:5' ]
        ]);

        $todoItem->name = $request->name;
        $todoItem->description =$request->description;
        $todoItem->save();

        return $todoItem;
    }

    public function destroy(Task $todoItem)
    {
        $todoItem->delete();
        return response()->json(null, 204);
    }
}
