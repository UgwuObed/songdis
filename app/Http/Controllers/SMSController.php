<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendContactsToQueue;
use App\Jobs\MyJob;
use DB;

class SMSController extends Controller
{
    public function sendSMS()
    {
        $contacts = DB::table('contacts')->get();

        SendContactsToQueue::dispatch($contacts);

        return response()->json(['message' => 'Contacts sent to queue successfully!']);
    }
}
