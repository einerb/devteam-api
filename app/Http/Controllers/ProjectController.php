<?php

namespace App\Http\Controllers;

use App\Project;
use App\History;
use App\Picture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Uuid;

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
            $projects = Project::with('picture')->get();
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

    public function uploadPicture(Request $request)
    {
        try {
            $project = Project::where('id', $request->project_id)->first();
            if (!$project) return response()->json(['success' => false, 'message' => 'El proyecto no existe!'], 401);

            $validator  =   Validator::make(
                $request->all(),
                [
                    'url_picture' => 'required',
                ]
            );
            if ($validator->fails()) return response()->json(['success' => false, "messages" => $validator->errors()], 400);

            $image = $request->file('url_picture');
            if ($image->isValid()) {
                $tamano = $image->getSize();
                $extension = $image->getClientOriginalExtension();

                if ($tamano >= 1048576) return response()->json(['success' => false, 'message' => 'La imagen supera el máximo permitido. La imagen debe pesar menos de 1MB!'], 401);
                if ($extension !== "jpg") return response()->json(['success' => false, 'message' => 'Formato de imagen no permitido. Solo imágenes .jpg o .jpeg!'], 401);

                $imageFileName = Carbon::now()->toDateString() . time() .Uuid::generate()->string. '.' . $extension;
                $s3 = \Storage::disk('s3');
                $filePath = '/images/' . $project->name . '/' . $imageFileName;
                $s3->put($filePath, file_get_contents($image), 'public');

                $picture = new Picture([
                    'url_picture' => 'https://devteam-resources.s3.us-east-1.amazonaws.com' . $filePath,
                    'project_id' => $request->project_id,
                ]);
                $picture->save();

                $response = [
                    'success' => true,
                    'message' => 'Image saved successfully!'
                ];
                return response()->json($response, 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Imagen inválida'], 401);
            }
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

            // Create History Details
            $action = 'creado';
            $history = new History([
                'user_id_emitter' => $request->user()->id,
                'action' => $action,
                'project_id' => $project->id,
            ]);
            $history->save();

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
            $project->save();

            // Create History Details
            $action = 'actualizado';
            $history = new History([
                'user_id_emitter' => $request->user()->id,
                'action' => $action,
                'project_id' => $id,
            ]);
            $history->save();

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

            // Create History Details
            $action = 'eliminado';
            $history = new History([
                'user_id_emitter' => $request->user()->id,
                'action' => $action,
                'project_id' => $id,
            ]);
            $history->save();

            return response()->json(['success' => true, 'message' => 'El proyecto fue eliminado exitosamente!'], 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }
}
