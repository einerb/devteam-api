<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $projects = Project::all();
            $response = [
                'success' => true,
                'data' => $projects,
                'message' => 'Successful projects listing!'
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator  =   Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'string',
                'url' => 'string',
                'date_start' => 'string',
            ]
        );

        if ($validator->fails()) return response()->json(['success' => false, "messages" => $validator->errors()], 400);

        $project = new Project([
            'name' => $request->name,
            'description' => $request->description,
            'url' => $request->url,
            'date_start' => $request->date_start,
        ]);

        try {
            $project->save();

            $response = [
                'success' => true,
                'data' => $project,
                'message' => 'Proyecto creado exitosamente!'
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProjectController  $projectController
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $project = Project::where('id', $id)->first();
            if (!$project) return response()->json(['success' => false, 'message' => 'El proyecto no existe!'], 401);

            $response = [
                'success' => true,
                'data' => $project,
                'message' => 'Successful projects listing!'
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProjectController  $projectController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $project = Project::find($id);
            if (!$project) return response()->json(['success' => false, 'message' => 'El proyecto no existe!'], 401);

            $project->name = $request->name;
            $project->description = $request->description;
            $project->url = $request->url;
            $project->date_start = $request->date_start;
            $project->date_end = $request->date_end;
            $project->status_id = $request->status_id;
            $project->save();

            $response = [
                'success' => true,
                'data' => $project,
                'message' => 'Successfully updated project!'
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectController  $projectController
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $project = Project::find($id);
            if (!$project) return response()->json(['success' => false, 'message' => 'El proyecto no existe!'], 401);

            $project = Project::destroy($id);

            return response()->json(['success' => true, 'message' => 'El proyecto fue eliminado exitosamente!'], 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }
}
