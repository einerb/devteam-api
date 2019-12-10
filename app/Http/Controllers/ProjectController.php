<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProjectController  $projectController
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectController $projectController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProjectController  $projectController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectController $projectController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectController  $projectController
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectController $projectController)
    {
        //
    }
}
