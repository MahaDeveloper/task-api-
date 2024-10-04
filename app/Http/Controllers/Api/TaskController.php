<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UserApiValidation;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = Task::where('created_by', auth()->id())
        ->when($request->status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->when($request->priority, function ($query, $priority) {
            return $query->where('priority', $priority);
        })
        ->when($request->due_date, function ($query, $dueDate) {
            return $query->where('due_date', $dueDate);
        })
        ->orderBy($request->sort_by ?? 'created_at', $request->order ?? 'DESC')
        ->paginate(10);

        return response()->json(['status' => 'success', 'tasks' => $tasks], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $task = new Task();
        // task->title = $request->title;
        // task->description = $request->description;
        // task->title = $request->title;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = UserApiValidation::taskStoreValidation($request);

        if($validate['status'] == "error"){

            return response()->json(['status' => 'error','message' => $validate['message']],400);
        }

        $task = new Task();
        $task->created_by = auth()->id();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->priority = $request->priority;
        $task->status = 'pending';
        $task->save();

        if ($request->hasFile('attachment')) {

            $path = public_path('tasks/');
            $image_name= date('Y-m-d').time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($path, $image_name);
            $task->attachment = $image_name;

            $task->save();
        }

        return response()->json(['status' => 'success', 'message' => "Task Has Been Created Successfully"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);

        return response()->json(['status' => 'success', 'task' => $task], 200);
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
    public function update(Request $request, $id)
    {
        $validate = UserApiValidation::taskUpdateValidation($request);

        if($validate['status'] == "error"){

            return response()->json(['status' => 'error','message' => $validate['message']],400);
        }

        $task = Task::find($id);
        $task->title = $request->title ?? $task->title;
        $task->description = $request->description ?? $task->description;
        $task->due_date = $request->due_date ?? $task->due_date;
        $task->priority = $request->priority ?? $task->priority;
        $task->status = $request->status ?? $task->status;
        $task->save();

        if ($request->hasFile('attachment')) {

            $path = public_path('tasks/');
            $image_name= date('Y-m-d').time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($path, $image_name);
            $task->attachment = $image_name;

            $task->save();
        }

        return response()->json(['status' => 'success', 'message' => "Task Has Been Updated Successfully"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        if ($task->created_by !== auth()->id()) {

            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(['status' => 'success', 'message' => "Task Has Been Removed"], 200);

    }

    public function assignTask(Request $request)
    {
        $validate = UserApiValidation::assignTaskValidation($request);

        if($validate['status'] == "error"){

            return response()->json(['status' => 'error','message' => $validate['message']],400);
        }

        $task = Task::find($request->task_id);

        $task->assign_to = $request->assign_to_id;

        $task->save();

        return response()->json(['status' => 'success','message' => 'Task assigned successfully'],200);
    }


}