<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\AutomationResult;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AutomationResultNotification;

Route::post('/automation/result', function (Request $request) {
    $data = $request->all();
    $userId = $data['user_id'] ?? null;
    $status = $data['status'] ?? 'unknown';
    $result = $data['lead'] ?? $data['result'] ?? $data;
    if ($userId) {
        $automationResult = AutomationResult::create([
            'user_id' => $userId,
            'status' => $status,
            'result' => $result,
        ]);
        $user = \App\Models\User::find($userId);
        if ($user) {
            Notification::send($user, new AutomationResultNotification($automationResult));
        }
    }
    Log::info('n8n result received', [
        'data' => $data,
        'user_id' => $userId,
        'status' => $status,
    ]);
    return response()->json(['success' => true]);
});
