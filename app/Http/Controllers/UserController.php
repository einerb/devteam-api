<?php

namespace App\Http\Controllers;

use App\User;
use App\History;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            $response = [
                'success' => true,
                'data' => $users,
                'message' => 'Successful users listing!'
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::where('id', $id)->first();
            if (!$user) return response()->json(['success' => false, 'message' => 'El usuario no existe!'], 401);

            $response = [
                'success' => true,
                'data' => $user,
                'message' => 'Successful users listing!'
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator  =   Validator::make(
            $request->all(),
            [
                'identification' => 'required|integer',
                'name' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
                'password' => 'required|alpha_num',
                'phone' => 'required',
            ]
        );

        if ($validator->fails()) return response()->json(['success' => false, "messages" => $validator->errors()], 400);

        $user = new User([
            'identification' => $request->identification,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
            'date_start' => $request->date_start,
            'photo' => $request->photo,
            'online' => $request->online,
        ]);

        try {
            $userEmail = User::where('email', $request->email)->first();
            $userID = User::where('identification', $request->identification)->first();
            if (!is_null($userEmail)) {
                return response()->json(['success' => false, 'message' => 'Lo sentimos, la dirección de correo ya existe!'], 401);
            }
            if (!is_null($userID)) {
                return response()->json(['success' => false, 'message' => 'Lo sentimos, la identificación de usuario ya existe!'], 401);
            }

            $user->save();

            // Create History Details
            $action = 'creado';
            $history = new History([
                'user_id_emitter' => $request->user()->id,
                'action' => $action,
                'user_id_receiver' => $user->id,
            ]);
            $history->save();

            $response = [
                'success' => true,
                'data' => $user,
                'message' => 'Usuario creado exitosamente!'
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'       => 'required|string|email',
                'password'    => 'required|string',
                'remember_me' => 'boolean',
            ]);
            $credentials = request(['email', 'password']);
            $credentials['status'] = 1;
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403);
            }

            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }

            $token->save();
            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse(
                    $tokenResult->token->expires_at
                )
                    ->toDateTimeString(),
                'user' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'success' => false,
            'message' => 'Se ha desconectado con éxito!'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json(['success' => true, "data" => $request->user()], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) return response()->json(['success' => false, 'message' => 'El usuario no existe!'], 401);

            $user->identification = $request->identification;
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->password = bcrypt($request->password);
            $user->address = $request->address;
            $user->phone = $request->phone;
            $user->birthdate = $request->birthdate;
            $user->date_start = $request->date_start;
            $user->photo = $request->photo;
            $user->save();

            // Create History Details
            $action = 'actualizado';
            $history = new History([
                'user_id_emitter' => $request->user()->id,
                'action' => $action,
                'user_id_receiver' => $id,
            ]);
            $history->save();

            $response = [
                'success' => true,
                'data' => $user,
                'message' => 'Successfully updated user!'
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    public function activateDeactivate(Request $request, $id)
    {
        try {
            $user = User::where('id', $id)->first();
            if (!$user) return response()->json(['success' => false, 'message' => 'El usuario no existe!'], 401);

            $user->status = $request->status;
            $user->save();

            // Create History Details
            $action = 'activado/inactivado';
            $history = new History([
                'user_id_emitter' => $request->user()->id,
                'action' => $action,
                'user_id_receiver' => $id,
            ]);
            $history->save();

            $response = [
                'success' => true,
                'data' => $user,
                'message' => 'El estado del usuario cambió'
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) return response()->json(['success' => false, 'message' => 'El usuario no existe!'], 401);

            $user = User::destroy($id);

            // Create History Details
            $action = 'eliminado';
            $history = new History([
                'user_id_emitter' => $request->user()->id,
                'action' => $action,
                'user_id_receiver' => $id,
            ]);
            $history->save();

            return response()->json(['success' => true, 'message' => 'El usuario fue eliminado exitosamente!'], 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }
}
