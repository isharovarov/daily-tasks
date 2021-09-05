<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class TasksController extends ApiController
{
    /**
     * @OA\Get(
     * path="/api/tasks",
     * summary="Get tasks list for auth user",
     * description="Get tasks list for auth user",
     * operationId="getUserListForAuthUser",
     * tags={"tasks"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     *    description="Tasks limit",
     *    in="query",
     *    name="limit",
     *    required=false,
     *    example=50
     * ),
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *    @OA\JsonContent(ref="#/components/schemas/Task")
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *        @OA\Property(property="error", type="string", example="Expired token"),
     *    )
     * )
     * )
     */
    public function index(Request $request)
    {
        return response()->json(TaskResource::collection($this->user->tasks));
    }

    /**
     * @OA\Get(
     * path="/api/tasks/new",
     * summary="Get one new task",
     * description="Get one new task for auth user",
     * operationId="getOneNewTaskForAuthUser",
     * tags={"tasks"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     *    description="Except this tasks (ids)",
     *    in="query",
     *    name="notInIds",
     *    required=false,
     *    example={1,2,3}
     * ),
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *    @OA\JsonContent(ref="#/components/schemas/Task")
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *        @OA\Property(property="error", type="string", example="Expired token"),
     *    )
     * )
     * )
     */
    public function new(Request $request)
    {
        // get unassigned task
        $task = Task::where('user_id', '!=', $this->user->id)
            ->whereNull('user_id')
            ->first();

        $task->update(['user_id' => $this->user->id]);

        return response()->json(new TaskResource($task));
    }

    /**
     * @OA\Post(
     * path="/api/tasks/{taskId}",
     * summary="Make task solved",
     * description="Make task solved",
     * operationId="makeTaskSolved",
     * tags={"tasks"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     *    description="ID of task",
     *    in="path",
     *    name="taskId",
     *    required=true,
     *    example="1",
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *    @OA\JsonContent(
     *        @OA\Property(property="status", type="boolean", example=true),
     *    )
     * ),
     * @OA\Response(
     *    response=400,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *        @OA\Property(property="status", type="boolean", example=false),
     *    )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *        @OA\Property(property="error", type="string", example="Expired token"),
     *    )
     * )
     * )
     */
    public function setSolved($id)
    {
        try
        {
            Task::where('id', $id)->update([ 'solved' => 1 ]);
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage(). ". File " .$e->getFile(). ": line " .$e->getLine());
            return $this->fail();
        }

        return $this->success();
    }

    /**
     * @OA\Post(
     * path="/api/tasks/assign",
     * summary="Assign daily tasks",
     * description="Assign daily tasks",
     * operationId="assignDailyTasks",
     * tags={"tasks"},
     * security={{ "apiAuth": {} }},
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *    @OA\JsonContent(
     *        @OA\Property(property="status", type="boolean", example=true),
     *    )
     * ),
     * @OA\Response(
     *    response=400,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *        @OA\Property(property="status", type="boolean", example=false),
     *    )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *        @OA\Property(property="error", type="string", example="Expired token"),
     *    )
     * )
     * )
     */
    public function assign()
    {
        try
        {
            Artisan::call('daily-tasks:assign');
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage(). ". File " .$e->getFile(). ": line " .$e->getLine());
            return $this->fail();
        }

        return $this->success();
    }
}
