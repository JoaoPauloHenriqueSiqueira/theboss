<?php

namespace App\Services;

use App\Models\Tasks;
use App\Repositories\Contracts\FlowRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use function GuzzleHttp\Promise\task;

class FlowService
{
    protected $repository;
    protected $carbon;
    protected $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        FlowRepositoryInterface $repository,
        Carbon $carbon,
        UserService $userService
    ) {
        $this->repository = $repository;
        $this->carbon = $carbon;
        $this->userService = $userService;
    }


    /**
     * Get repository
     */
    public function get()
    {
        if (Auth::user()->type->is_admin == 1) {
            $flows = $this->repository->orderBy('created_at', 'DESC')->paginate(6);
        }

        if (Auth::user()->type->is_admin != 1) {
            $flows = $this->repository->orderBy('created_at', 'DESC')->findWhere(['user_id' => Auth::user()->id]);
        }

        foreach ($flows as &$flow) {
            $flow['start_input'] = $this->carbon::parse($flow['start'])->format('Y-m-d\TH:i');
            $flow['finish_input'] = $this->carbon::parse($flow['finish'])->format('Y-m-d\TH:i');
        }

        return $flows;
    }

    /**
     * FUnction to search a task
     *
     * @param [type] $taskId
     * @return void
     */
    public function find($taskId)
    {
        return $this->repository->find($taskId)->toArray();
    }

    /**
     * Save a task with a validation
     *
     * @param [type] $request
     * @return void
     */
    public function save($request)
    {
        if ($request->validated()) {

            $idFlow = array_get($request, "id");

            if ($idFlow) {
                $flow = $this->repository->find($idFlow);
                $flow->tasks()->detach();
                $flow->points()->detach();
            }

            $response = $this->repository->updateOrCreate(["id" => $idFlow], $request->all());
            $response = $this->addTasks($request, $response);

            if ($response) {
                return redirect()->back()->with('message', 'Registro criado/atualizado!');
            }
        }
        return redirect()->back()->with('message', 'Ocorreu algum erro');
    }

    private function addTasks($request, $response)
    {
        $addTasks = [];
        $tasks = array_get($request, "tasks", []);
        $start = $this->carbon->parse(array_get($request, "start"));
        $finish = $this->carbon->parse(array_get($request, "finish"));

        $duration = $start->diffInMinutes($finish);
        $countTasks = count($tasks);
        $averagTime = $duration / $countTasks;
        $weight = floatval($countTasks / $duration);
        $point =  number_format($weight * $countTasks, 2);

        $this->addPoints(array_get($request, "user_id"), $response->id, $point, $start);

        foreach ($tasks as $task) {
            $newTask = [];
            $newTask["task_id"] = $task;
            $newTask["flow_id"] = $response->id;
            $newTask["duration"] = $averagTime;
            $newTask["created_at"] = $this->carbon->now();
            $newTask["updated_at"] = $this->carbon->now();
            array_push($addTasks, $newTask);
        }

        $response->tasks()->attach(
            $addTasks
        );

        return $response;
    }


    public function addPoints($user, $flow, $points, $date)
    {
        $flow = $this->repository->find($flow);
        $point = [];
        $point['user_id'] = $user;
        return $flow->points()->attach($point, [
            'points' => $points,
            'created_at' => $date,
            'updated_at' => $date,
            'user_id' => $user
        ]);
    }

    public function removePoint($flow, $points)
    {
        $flow = $this->repository->find($flow);
        return $flow->points()->detach();
    }


    /**
     * Remove specific task
     *
     * @param [type] $request
     * @return void
     */
    public function delete($request)
    {
        $taskId = array_get($request, "id");
        $response = $this->repository->delete($taskId);

        if ($response) {
            return response('Removido com sucesso', 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }
}
