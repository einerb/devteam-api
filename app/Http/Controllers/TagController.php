<?php

namespace App\Http\Controllers;

use App\Tag;
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
            $labels = Tag::all();
            $response = [
                'success' => true,
                'data' => $labels,
                'message' => 'Successful labels listing!'
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
                'name' => 'required|unique:labels',
                'description' => 'string',
                'color' => 'string',
                'icon' => 'string',
            ]
        );

        if ($validator->fails()) return response()->json(['success' => false, "messages" => $validator->errors()], 400);

        $label = new Tag([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
        ]);

        try {
            $label->save();

            $response = [
                'success' => true,
                'data' => $label,
                'message' => 'Etiqueta creada exitosamente!'
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $label = Tag::find($id);
            if (!$label) return response()->json(['success' => false, 'message' => 'La etiqueta no existe!'], 401);

            $label = Tag::destroy($id);

            return response()->json(['success' => true, 'message' => 'La etiqueta fue eliminada exitosamente!'], 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }
}
