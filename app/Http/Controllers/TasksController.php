<?php

namespace App\Http\Controllers;

use App\ValueObjects\TaskID;
use App\ValueObjects\TaskTitle;
use App\ValueObjects\TaskPriority;
use App\ValueObjects\TaskStatus;

use Illuminate\Http\Request;
use App\Repositories\TaskRepository as Task;

class TasksController extends Controller
{
    /**
     * @var Task
     */
    private $task;

    /**
     * @var Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Shows all tasks
     *
     * @return mixed
     */
    public function show()
    {
        $tasks = $this->task->byOrder();

        $tasks->priority = config('app.priority');
        $tasks->status = config('app.status');

        foreach ($tasks as $task) {
            $task["tags"] = unserialize($task["tags"]);
            $task["priority"] = array(
                "status" => $task["priority"],
                "title" => $tasks->priority[$task["priority"]]["title"],
                "bg" => $tasks->priority[$task["priority"]]["bg"]
            );
            $task["status"] = array(
                "status" => $task["status"],
                "title" => $tasks->status[$task["status"]]["title"],
                "bg" => $tasks->status[$task["status"]]["bg"]
            );
        }

        return view('app', compact('tasks'));
    }

    /**
     * Creates new task
     *
     * @param Request $request
     *
     * @return array
     */
    public function create(Request $request)
    {
        $id = new TaskID(random_bytes(16));
        $title = new TaskTitle($request["title"]);
        $priority = new TaskPriority($request["priority"]);
        if (!empty($request["tags"])) {
            $tags = serialize(array_map("htmlspecialchars", array_map("trim", $request["tags"])));
        } else {
            $tags = serialize([]);
        }
        $status = new TaskStatus($request["status"]);

        $data = array(
            "id" => $id->generateUIDv4(),
            "title" => $title->convertString(),
            "priority" => $priority->value(),
            "tags" => $tags,
            "status" => $status->value()
        );

        $this->task->create($data);

        $data["neighbour"] = $this->findNeighbour($data["id"]);
        $data["tags"] = unserialize($data["tags"]);

        return $data;
    }

    /**
     * Changes priority or status of the task
     *
     * @param Request $request
     *
     * @return string
     */
    public function change(Request $request)
    {
        $id = $request["id"];
        $field = $request["field"];
        $value = $request["value"];

        switch ($field) {
            case "tags":
                $value = !empty($value) ? serialize(array_map("htmlspecialchars", array_map("trim", $value))) : serialize([]);
                break;
            case "title":
                $value = new TaskTitle($value);
                $value = $value->convertString();
                break;
            case "priority":
                $value = new TaskPriority($value);
                $value = $value->value();
                break;
            case "status":
                $value = new TaskStatus($value);
                $value = $value->value();
        }

        $this->task->update(array($field => $value), $id);

        if ($field != "tags" && $field != "title") {
            return $this->findNeighbour($id);
        } else {
            return "1";
        }
    }

    /**
     * Finds out a neighbour for the task
     *
     * @param string $id
     *
     * @return string
     */
    private function findNeighbour($id)
    {
        $neighbour = '';
        $nextTask = false;
        $tasks = $this->task->byOrder();
        foreach ($tasks as $task) {
            if ($nextTask) {
                $neighbour = $task["id"];
                break;
            }
            if ($task["id"] == $id) {
                $nextTask = true;
            }
        }

        return $neighbour;
    }
}
