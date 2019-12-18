<?php

namespace App\Http\Controllers;

use App\Tag;
use App\ProjectTag;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $tags = Tag::all();
            $response = [
                'success' => true,
                'data' => $tags,
                'message' => 'Successful tags listing!'
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
                'color' => 'string',
                'icon' => 'string',
            ]
        );

        if ($validator->fails()) return response()->json(['success' => false, "messages" => $validator->errors()], 400);
        if(Tag::where('name',  $request->name)->first()) return response()->json(['success' => false, 'message' => 'El nombre de la etiqueta ya existe!'], 401);

        $tag = new Tag([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
        ]);

        try {
            $tag->save();

            $response = [
                'success' => true,
                'data' => $tag,
                'message' => 'Etiqueta creada exitosamente!'
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    public function addTagProject(Request $request)
    {
        $validator  =   Validator::make(
            $request->all(),
            [
                'project_id' => 'required|integer',
                'tag_id' => 'required|integer',
            ]
        );

        if ($validator->fails()) return response()->json(['success' => false, "messages" => $validator->errors()], 400);
        if (!Project::where('id',  $request->project_id)->first()) return response()->json(['success' => false, 'message' => 'El proyecto no existe!'], 401);
        if (!Tag::where('id',  $request->tag_id)->first()) return response()->json(['success' => false, 'message' => 'La etiqueta no existe!'], 401);
        if (ProjectTag::where('tag_id',  $request->tag_id)->first()) return response()->json(['success' => false, 'message' => 'La etiqueta ya se encuentra agregada a este proyecto!'], 401);

        $tag = new ProjectTag([
            'project_id' => $request->project_id,
            'tag_id' => $request->tag_id
        ]);

        try {
            $tag->save();

            $response = [
                'success' => true,
                'data' => $tag,
                'message' => 'Etiqueta agregada al proyecto exitosamente!'
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $tag = Tag::find($id);
            if (!$tag) return response()->json(['success' => false, 'message' => 'La etiqueta no existe!'], 401);

            $tag = Tag::destroy($id);

            return response()->json(['success' => true, 'message' => 'La etiqueta fue eliminada exitosamente!'], 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }
}
