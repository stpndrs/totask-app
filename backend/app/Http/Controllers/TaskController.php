<?php

namespace App\Http\Controllers;

use App\Models\TaskModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $task = TaskModel::getDataByUser($request->user()->id);

        return $this->success(['message' => 'Task Data', 'task' => $task]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'in:1,2,3',
            'deadline' => 'required:datetime',
        ]);

        if ($validate->fails()) {
            return $this->failed(['message' => 'Validation Error', 'errors' => $validate->errors()]);
        }

        $create = TaskModel::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 1,
            'deadline' => $request->deadline,
            'user_id' => $request->user()->id
        ]);

        return $this->success(['message' => 'Create task success', 'data' => $create]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TaskModel $task)
    {
        return $this->success(['message' => 'Get data success', 'data' => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskModel $task)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'in:1,2,3',
            'deadline' => 'required:date',
        ]);

        if ($validate->fails()) {
            return $this->failed(['message' => 'Validation Error', 'errors' => $validate->errors()]);
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'status' => $request->status,
        ]);

        return $this->success(['message' => 'Update task success', 'data' => $task]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($task)
    {
        $task->delete();

        if ($task) return $this->success(['message' => 'Delete task success']);
        else return $this->failed(['message' => 'Delete task failed']);
    }
}
