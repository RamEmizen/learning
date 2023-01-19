<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Checkout\Session as CheckoutSession;
use App\Products;
use App\Pyment_history;
use Session;

class PayPalPaymentController extends Controller
{
    public function stripe(Request $request)
   {     
       try{
        $data= $request->all();                                 
         $product =  Products::where('id' , $data['product_id'])->first();
        if (isset($product) && !empty($product)) {
        $InsertData = [
            'customer_id'  => $data['customer_id'] ,
            'product_id'     =>  $data['product_id'] ,
            'price'    =>  $product->price,

          ]; 
        //  dd($InsertData);
           $paymentHistory = Pyment_history::create($InsertData);
           $stripeSecretKey = 'sk_test_51M4O52SH46OkB1Wz3DkSYgzDm4hsjv3jlUZ5Cf9YB6tmguGD9aIf1gL94vyM1qNwo8QxBGA2dhJguMcSL0LfPDik00MohiKCm9';
           $stripe = \Stripe\Stripe::setApiKey($stripeSecretKey);
           $stripe = new \Stripe\StripeClient($stripeSecretKey);
           $metaData = array('payment_id' => $paymentHistory->id);
        // dd($paymentHistory->id);
           $checkout_session = CheckoutSession::create([
           'payment_method_types' => ['card'], //,'Wallets' ,'alipay','ideal', 'bancontact',  'p24', 'eps', 'sofort', 'sepa_debit'
           'line_items' => [[
               'price_data' => [
                   'currency' => 'INR',
                   'unit_amount' => $product->price*100,
                   'product_data' => [
                       'name' => 'Testing',
                   ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success')."?session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' => route('stripe.error'),
                'metadata' => $metaData
            ]);
            //dd($checkout_session);
            // dd($checkout_session->url);
           return response()->json(['status'=>200,'id'=>$checkout_session->url]);
        } else {
            $obj = ["Status" => false, "success" => 0, "msg" => "please valide product id!"];
            return response()->json($obj);
        }

       }catch(\Exception $e){
           dd($e);
           return response()->json(['status'=>500,'message'=>'Something went wrong !']);
       }
   }
 
   public function stripeSuccess(Request $request)
   {   
       try{
           $stripeSecretKey = 'sk_test_51M4O52SH46OkB1Wz3DkSYgzDm4hsjv3jlUZ5Cf9YB6tmguGD9aIf1gL94vyM1qNwo8QxBGA2dhJguMcSL0LfPDik00MohiKCm9';
           $stripe = \Stripe\Stripe::setApiKey($stripeSecretKey);
         
           $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));
          // dd( $session->customer_details->email);
           $metaData = $session->metadata;
            $paymentHistory = Pyment_history::where('id', $metaData->payment_id)->first();
            $email =  $session->customer_details->email;
            $transaction_id = $session->payment_intent;
            $paymentHistory->transaction_id = $transaction_id;
            $paymentHistory->email = $email;
            $paymentHistory->status = '1';
            $paymentHistory->save();
            Session::flash('success', 'Payment successful !');
            return redirect('admin/stripe')->with('success','Payment successful !');
       }catch(\Exception $e){
           dd('execpton h',$e);
           return redirect('admin/stripe')->with('error', 'Something went wrong!');
       }
   }
 
   public function stripeError(Request $request)
   {
       dd('error aa rahi h');
       return redirect('admin/products')->with('error', 'Payment faild,Please try again!');
   }
}
