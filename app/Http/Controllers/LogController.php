<?php

namespace App\Http\Controllers;

use App\Models\Log;

class LogController extends Controller
{
    public function index()
    {
        return response()->json(Log::with(['inventory', 'user'])->latest()->get());
    }

    public function show(Log $log)
    {
        return response()->json($log->load(['inventory', 'user']));
    }

    public function destroy(Log $log)
    {
        $log->delete();

        return response()->json(['message' => 'Log deleted']);
    }
}