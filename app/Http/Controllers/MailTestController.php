<?php

namespace App\Http\Controllers;

use App\Services\ZeptoMailService;
use Illuminate\Http\Request;

class MailTestController extends Controller
{
    protected $zeptoMailService;

    public function __construct(ZeptoMailService $zeptoMailService)
    {
        $this->zeptoMailService = $zeptoMailService;
    }

    public function testBasicEmail(Request $request)
    {
        try {
            $result = $this->zeptoMailService->sendTestEmail($request->email ?? 'your-test-email@example.com');
            return response()->json(['message' => 'Test email sent successfully', 'result' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}