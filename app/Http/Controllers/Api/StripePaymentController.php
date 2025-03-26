<?php

namespace App\Http\Controllers\Api;

use Exception;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Customer;
use App\Models\Cours;
use App\Models\Payment;
use App\Models\Enrollement;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\CoursController;

class StripePaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        
       
        $request->validate([
            'cours_id' => 'required|exists:courses,id'
        ]);
        $course = Cours::findOrFail($request->cours_id);
        // $enrollement = Enrollement::where('course_id', $request->course_id)
        //     ->where('user_id', Auth::user()->id)->first();
        // dd($enrollement->id);

        if($course->price == 0.00){
            CoursController::enrolle($request->cours_id);
            return response()->json([
                'message' => 'Enrolle with success',
                
            ], 200);
        }else{
        
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'MAD',
                        'product_data' => [
                            'name' => $course->title,
                        ],
                        'unit_amount' => intval($course->price * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/api/payments/success/' . $course->id) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => url(''),
                'metadata' => [
                    'cours_id' => $course->id,
                    'user_id' => Auth::id()
                ]
            ]);

            Payment::create([
                'enrollement_id' => null,
                'payment_type' => 'card',
                'status' => 'pending',
                'amount' => $course->price,
                'transaction_id' => $session->id
            ]);

            return response()->json([
                'checkout_url' => $session->url,
                'session_id' => $session->id,
                'cours'=>$course->price,
            ]);
        


        } catch (Exception $e) {
            return response()->json([
                'message' => 'Payment session creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    }

    public function paymentSuccess(Request $request, $cours_id)
    {
        try {
            $session_id = $request->json('session_id');
    
            if (!$session_id) {
                return response()->json([
                    "message" => "Session ID is required!"
                ], 400);
            }
    
            $payment = Payment::where('transaction_id', $session_id)
                ->where('status', 'pending')
                ->first();

            
    
            if (!$payment) {
                return response()->json([
                    "message" => "No payment found!",
                ], 404);
            }
            CoursController::enrolle($cours_id);

            $enrollement = Enrollement::where('cours_id', $cours_id)
                ->where('user_id', Auth::user()->id)
                ->first();
            $payment->update([
                'status' => 'successful',
                'enrollement_id' => $enrollement->id,
                
            ]);
    
    
            return response()->json([
                "message" => "Payment successful and enrollment confirmed!",
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
                
            ], 500);
        }
    }

    public function getUserPaymentHistory()
{
    try {
        $user = Auth::user(); 

        $payments = Payment::whereHas('enrollement', function ($query) use ($user) {  
            $query->where('user_id', $user->id);
        })->with(['enrollement.cours'])->get(); 

        if ($payments->isEmpty()) {
            return response()->json([
                'message' => 'Aucun paiement trouvé pour cet utilisateur.'
            ], 404);
        }

        return response()->json([
            'message' => 'Historique des paiements récupéré avec succès.',
            'payments' => $payments
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erreur lors de la récupération des paiements',
            'details' => $e->getMessage()
        ], 500);
    }
}

    
}
