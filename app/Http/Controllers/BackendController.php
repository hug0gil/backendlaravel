<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class BackendController extends Controller
{

    private $names = [1 => ['name' => 'Hugo', 'age' => '20'], 2 => ['name' => 'Pablo', 'age' => '21'], 3 => ['name' => 'Antonio', 'age' => '24']];

    public function getAll()
    {
        return response()->json($this->names);
    }

    public function get(int $id = 0)
    {
        if (isset($this->names[$id])) {
            return response()->json($this->names[$id]);
        }
        return response()->json(['error' => 'Id not found'], status: Response::HTTP_NOT_FOUND);
    }

    public function create(Request $request)
    {
        $person = [
            'id' => count($this->names) + 1,
            'name' => $request->input('name'),
            'age' => $request->input('age'),
        ];

        $this->names[$person['id']] = $person;

        return response()->json(["message" => "Person created", "Person" => $person], status: Response::HTTP_CREATED);
    }

    function update(Request $request, int $id)
    {
        if (isset($this->names[$id])) {
            $this->names[$id]["name"] = $request->input("name", $this->names[$id]["name"]);
            $this->names[$id]["age"] = $request->input("age", $this->names[$id]["age"]);
            return response()->json(['message' => 'Person updated', "Person" => $this->names[$id]]);
        }
        return response()->json(['error' => 'Id not found'], status: Response::HTTP_NOT_FOUND);
    }

    function delete(Request $request, int $id)
    {
        if (isset($this->names[$id])) {
            unset($this->names[$id]);
            return response()->json(data: ['message' => 'Person deleted']);
        }
        return response()->json(data: ['error' => 'Id not found'], status: Response::HTTP_NOT_FOUND);
    }
}
