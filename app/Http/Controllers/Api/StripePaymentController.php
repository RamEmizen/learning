<?php
    
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Checkout\Session as CheckoutSession;
use Session;
use Stripe\Stripe;
     
class StripePaymentController extends Controller
{
    public function stripes(Request $request)
    {     
        try{
            $stripeSecretKey = 'sk_test_51M01OZSB6Jxnm07ldi9xAQfBcHe1fqu0g50ATUh3xTD7u7FZPSPFopHBdwupaLydRpKR25ce9LvuNW4B1tpsZxUr005VzPLHXg';
            $requestData  = $request->all();
            $stripe = \Stripe\Stripe::setApiKey($stripeSecretKey);
            $stripe = new \Stripe\StripeClient($stripeSecretKey);
            $metaData = array('id'=>'117');
            $checkout_session = CheckoutSession::create([
            'payment_method_types' => ['card'], //,'Wallets' ,'alipay','ideal', 'bancontact',  'p24', 'eps', 'sofort', 'sepa_debit'
            'line_items' => [[
                'price_data' => [
                    'currency' => 'INR',
                    'unit_amount' => 250*100,
                    'product_data' => [
                        'name' => 'Testing',
                    ],
  
                ],
                'quantity' => 1,
  
            ]],
            'mode' => 'payment',
            'success_url' => route('stripes.success')."?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('stripes.error'),
           'metadata' => $metaData
            ]);
            return response()->json(['status'=>200,'id'=>$checkout_session->url]);
        }catch(\Exception $e){
            return response()->json(['status'=>500,'message'=>'Something went wrong !']);
        }
    }
  
    public function stripeSuccesss(Request $request)
    {
        try{
            $stripeSecretKey = 'sk_test_51M01OZSB6Jxnm07ldi9xAQfBcHe1fqu0g50ATUh3xTD7u7FZPSPFopHBdwupaLydRpKR25ce9LvuNW4B1tpsZxUr005VzPLHXg';
  
            $stripe = \Stripe\Stripe::setApiKey($stripeSecretKey);
            $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));
            $metaData = $session->metadata;
            // dd($metaData);
  
           
            // $InsertData = [
            //     'membership_id'         =>      $data['membership_id'][$key],
            //     'package_id'            =>      $data['package_id'][$key],
            //     'package_detail_id'     =>      $data['package_detail_id'][$key],
            //     'user_id'               =>      auth()->user()->id,
            //     'quantity'              =>      $value,
            //     'price'                 =>      $data['amount'][$key],
            //     'days'                  =>      $data['days'][$key],
            //     'wallet'                =>      $usedWalletAmount
            // ];
            // Subscription::create($InsertData);
  
           Session::flash('success', 'Payment successful !');
            return redirect('admin/products')->with('success','Payment successful !');
        }catch(\Exception $e){
            return redirect('admin/products')->with('error', 'Something went wrong!');
        }
    }
  
    public function stripeErrors(Request $request)
    {
        return redirect('admin/products')->with('error', 'Payment faild,Please try again!');
    }
}



        
        




