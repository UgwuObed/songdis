<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountDetail;

class AccountDetailController extends Controller
{

    public function show()
    {
        $accountDetail = AccountDetail::where('user_id', auth()->id())->first();
        
        if (!$accountDetail) {
            return response()->json(null, 404);
        }

        return response()->json($accountDetail);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'account_number' => 'required|string',
            'bank_name' => 'required|string',
            'account_name' => 'required|string',
        ]);

        $accountDetail = AccountDetail::create([
            'user_id' => auth()->id(),
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
        ]);

        return response()->json($accountDetail, 201);
    }

    public function update(Request $request, AccountDetail $accountDetail)
    {
        $this->authorize('update', $accountDetail);

        $request->validate([
            'account_number' => 'required|string',
            'bank_name' => 'required|string',
            'account_name' => 'required|string',
        ]);

        $accountDetail->update($request->all());

        return response()->json($accountDetail, 200);
    }

    public function destroy(AccountDetail $accountDetail)
    {
        $this->authorize('delete', $accountDetail);

        $accountDetail->delete();

        return response()->json(['message' => 'Account detail deleted'], 200);
    }
}

