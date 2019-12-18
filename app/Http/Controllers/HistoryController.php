<?php

namespace App\Http\Controllers;

use App\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $history = History::with('userEmitter', 'userReceiver', 'project', 'client')->get();
            $response = [
                'success' => true,
                'data' => $history,
                'message' => 'Successful histories listing!'
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }
}
