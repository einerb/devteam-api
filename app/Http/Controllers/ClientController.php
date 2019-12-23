<?php

namespace App\Http\Controllers;

use App\Client;
use App\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $clients = Client::all();
            $response = [
                'success' => true,
                'data' => $clients,
                'message' => 'Successful clients listing!'
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    public function indexPublic()
    {
        try {
            $project = Client::select('name', 'lastname', 'company', 'description')->get();
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
                'name' => 'string',
                'lastname' => 'string',
                'company' => 'required|string',
                'email' => 'required|string|email',
                'phone' => 'string',
                'address' => 'string',
            ]
        );

        if ($validator->fails()) return response()->json(['success' => false, "messages" => $validator->errors()], 400);
        if (Client::where('email',  $request->email)->first()) return response()->json(['success' => false, 'message' => 'El correo ya existe!'], 401);

        $clients = new Client([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'company' => $request->company,
            'email' => $request->email,
            'description' => $request->description,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        try {
            $clients->save();

            $response = [
                'success' => true,
                'data' => $clients,
                'message' => 'Cliente creado exitosamente!'
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $client = Client::where('id', $id)->first();
            if (!$client) return response()->json(['success' => false, 'message' => 'El cliente no existe!'], 401);

            $response = [
                'success' => true,
                'data' => $client,
                'message' => 'Successful clients listing!'
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
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $client = Client::find($id);
            if (!$client) return response()->json(['success' => false, 'message' => 'El cliente no existe!'], 401);

            $client->name = $request->name;
            $client->lastname = $request->lastname;
            $client->company = $request->company;
            $client->email = $request->email;
            $client->description = $request->description;
            $client->phone = $request->phone;
            $client->address = $request->address;
            $client->status = $request->status;

            $client->save();

            // Create History Details
            $action = 'actualizado';
            $history = new History([
                'user_id_emitter' => $request->user()->id,
                'action' => $action,
                'client_id' => $id,
            ]);
            $history->save();

            $response = [
                'success' => true,
                'data' => $client,
                'message' => 'Successfully updated client!'
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $client = Client::find($id);
            if (!$client) return response()->json(['success' => false, 'message' => 'El cliente no existe!'], 401);

            $client = Client::destroy($client);

            // Create History Details
            $action = 'eliminado';
            $history = new History([
                'user_id_emitter' => $request->user()->id,
                'action' => $action,
                'client_id' => $id,
            ]);
            $history->save();

            return response()->json(['success' => true, 'message' => 'El cliente fue eliminado exitosamente!'], 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }
}
