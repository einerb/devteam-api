<?php

namespace App\Http\Controllers;

use App\ProjectStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $status = ProjectStatus::all();
            $response = [
                'success' => true,
                'data' => $status,
                'message' => 'Successful status listing!'
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
            ]
        );

        if ($validator->fails()) return response()->json(['success' => false, "messages" => $validator->errors()], 400);

        $status = new ProjectStatus([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        try {
            $status->save();

            $response = [
                'success' => true,
                'data' => $status,
                'message' => 'Estado creado exitosamente!'
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectStatus  $projectStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $status = ProjectStatus::find($id);
            if (!$status) return response()->json(['success' => false, 'message' => 'Estado de proyecto no existe!'], 401);

            $status = ProjectStatus::destroy($id);

            return response()->json(['success' => true, 'message' => 'Estado fue eliminado exitosamente!'], 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }
}
