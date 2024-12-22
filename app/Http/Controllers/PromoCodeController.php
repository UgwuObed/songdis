<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoCodeController extends Controller
{
    /**
     * List all promo codes with usage statistics
     */
    public function index()
    {
        $promoCodes = PromoCode::withCount('uses')->get()->map(function($code) {
            return [
                'id' => $code->id,
                'code' => $code->code,
                'is_active' => $code->is_active,
                'duration_days' => $code->duration_days,
                'times_used' => $code->times_used,
                'max_uses' => $code->max_uses,
                'remaining_uses' => $code->max_uses - $code->times_used,
                'created_at' => $code->created_at
            ];
        });

        return response()->json($promoCodes);
    }

    /**
     * Generate a new promo code
     */
    public function generate(Request $request)
    {
        $request->validate([
            'prefix' => 'nullable|string|max:10',
            'max_uses' => 'required|integer|min:1',
            'duration_days' => 'required|integer|min:1',
            'number_of_codes' => 'required|integer|min:1|max:100',
        ]);

        $generatedCodes = [];
        $prefix = $request->prefix ? strtoupper($request->prefix) : 'PROMO';

        for ($i = 0; $i < $request->number_of_codes; $i++) {
            $code = PromoCode::create([
                'code' => $this->generateUniqueCode($prefix),
                'is_active' => true,
                'duration_days' => $request->duration_days,
                'max_uses' => $request->max_uses,
                'times_used' => 0
            ]);

            $generatedCodes[] = $code;
        }

        return response()->json([
            'status' => 'success',
            'message' => count($generatedCodes) . ' promo codes generated successfully',
            'codes' => $generatedCodes
        ]);
    }

    /**
     * Toggle promo code status (activate/deactivate)
     */
    public function toggleStatus($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        $promoCode->is_active = !$promoCode->is_active;
        $promoCode->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Promo code ' . ($promoCode->is_active ? 'activated' : 'deactivated') . ' successfully',
            'promo_code' => $promoCode
        ]);
    }

    /**
     * Update promo code details
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'max_uses' => 'integer|min:1',
            'duration_days' => 'integer|min:1',
        ]);

        $promoCode = PromoCode::findOrFail($id);
        $promoCode->update($request->only(['max_uses', 'duration_days']));

        return response()->json([
            'status' => 'success',
            'message' => 'Promo code updated successfully',
            'promo_code' => $promoCode
        ]);
    }

    /**
     * Get usage statistics for a specific promo code
     */
    public function getStats($id)
    {
        $promoCode = PromoCode::with(['uses' => function($query) {
            $query->with('user:id,name,email');
        }])->findOrFail($id);

        return response()->json([
            'code' => $promoCode->code,
            'total_uses' => $promoCode->times_used,
            'remaining_uses' => $promoCode->max_uses - $promoCode->times_used,
            'is_active' => $promoCode->is_active,
            'users' => $promoCode->uses->map(function($use) {
                return [
                    'user' => $use->user,
                    'used_at' => $use->created_at,
                    'expires_at' => $use->expires_at
                ];
            })
        ]);
    }

    /**
     * Generate a unique promo code
     */
    private function generateUniqueCode($prefix)
    {
        do {
            $code = $prefix . '-' . strtoupper(Str::random(8));
        } while (PromoCode::where('code', $code)->exists());

        return $code;
    }
}