<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\District;
use App\Models\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Shipping;
use App\Models\ShippingCharge;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Courierapi;
use App\Models\Division;
use App\Models\City;
use App\Models\Pourosaova;
use App\Models\Upazila;
use App\Models\IpBlock;
use App\Models\FraudLog;
use Session;
use Cart;
use Toastr; 
use Mail;

class OrderController extends Controller
{
    public function index($slug,Request $request){
        // $upazilas = Upazila::all();
        // foreach($upazilas as $upazila){
        //     $district = District::find($upazila->district_id);
        //     if($district){
        //         $division = Division::find($district->division_id);
        //         if($division){
        //             $upazila->division_id = $division->id;
        //         }
        //     }
        //     $upazila->save();
        // }
        
        if($slug == 'all'){
            $order_status = (object) [
                'name' => 'All',
                'orders_count'=> Order::count(),
            ];
            $show_data = Order::latest()->with('shipping','status','orderdetails','order_status');
            if($request->keyword){
                $show_data = $show_data->where(function ($query) use ($request) {
                    $query->orWhere('invoice_id', 'LIKE', '%' . $request->keyword . '%')
                          ->orWhereHas('shipping', function ($subQuery) use ($request) {
                              $subQuery->where('phone', $request->keyword);
                          });
                });
            }
           $show_data = $show_data->paginate(10);
        }else{
            $order_status = OrderStatus::where('slug',$slug)->withCount('orders')->first();
            $show_data = Order::where(['order_status'=>$order_status->id])->latest()->with('shipping','status')->paginate(10);
        }
        $users = User::get();
        $steadfast = Courierapi::where(['status'=>1, 'type'=>'steadfast'])->first();
        $pathao_info = Courierapi::where(['status'=>1, 'type'=>'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        // pathao courier
        if($pathao_info) {
            $response = Http::get($pathao_info->url . '/api/v1/countries/1/city-list');
            $pathaocities = $response->json();
            $response2 = Http::withHeaders([
                'Authorization' => 'Bearer ' . $pathao_info->token,
                'Content-Type' => 'application/json',
                ])->get($pathao_info->url . '/api/v1/stores');
            $pathaostore = $response2->json();
        } else {
            $pathaocities = [];
            $pathaostore = [];
        }
        
        // dd($show_data);
        return view('backEnd.order.index',compact('show_data','order_status','users', 'steadfast','pathaostore','pathaocities'));
    } 
    
    public function pathaocity(Request $request)
    {
        $pathao_info = Courierapi::where(['status'=>1, 'type'=>'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        if($pathao_info) {
            $response = Http::get($pathao_info->url . '/api/v1/cities/'.$request->city_id.'/zone-list');
            $pathaozones = $response->json();
            return response()->json($pathaozones);
        } else {
            return response()->json([]);
        }
    } 
    public function pathaozone(Request $request)
    {
        $pathao_info = Courierapi::where(['status'=>1, 'type'=>'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        if($pathao_info) {
            $response = Http::get($pathao_info->url . '/api/v1/zones/'.$request->zone_id.'/area-list');
            $pathaoareas = $response->json();
            return response()->json($pathaoareas);
        } else {
             return response()->json([]);
        }
    }
    
    public function order_pathao(Request $request)
    {
        $orders_id = $request->order_ids;

        foreach ($orders_id as $order_id) {
            $order = Order::with('shipping')->find($order_id);
            $order_count = OrderDetails::select('order_id')->where('order_id', $order->id)->count();
            // return $request->all();
            // pathao
            $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
            if ($pathao_info) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $pathao_info->token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post($pathao_info->url . '/api/v1/orders', [
                    'store_id' => $request->pathaostore,
                    'merchant_order_id' => $order->invoice_id,
                    'sender_name' => 'Test',
                    'sender_phone' => $order->shipping ? $order->shipping->phone : '',
                    'recipient_name' => $order->shipping ? $order->shipping->name : '',
                    'recipient_phone' => $order->shipping ? $order->shipping->phone : '',
                    'recipient_address' => $order->shipping ? $order->shipping->address : '',
                    'recipient_city' => $request->pathaocity,
                    'recipient_zone' => $request->pathaozone,
                    'recipient_area' => $request->pathaoarea,
                    'delivery_type' => 48,
                    'item_type' => 2,
                    'special_instruction' => 'Special note- product must be check after delivery',
                    'item_quantity' => 1,
                    'item_weight' => 0.5,
                    'amount_to_collect' => round($order->amount),
                    'item_description' => 'Special note- product must be check after delivery',
                ]);
            }
            if ($response->status() == '200') {
                Toastr::success($response['data']['consignment_id'], 'Courier Tracking ID');
                return response()->json(['status' => 'success', 'message' => $response['data']['consignment_id'], 'Courier Tracking ID']);
            } else {
                Toastr::error($response['message'], 'Courier Order Faild');
                return response()->json(['status' => 'failed', 'message' => $response['message'], 'Courier Order Faild']);
            }
            return redirect()->back();


        }
    }
    
    public function invoice($invoice_id){
        $order = Order::where(['invoice_id'=>$invoice_id])->with('orderdetails','payment','shipping','customer')->firstOrFail();
        return view('backEnd.order.invoice',compact('order'));
    }
    
    public function process($invoice_id){
        $data = Order::where(['invoice_id'=>$invoice_id])->select('id','invoice_id','order_status')->with('orderdetails')->first();
        $shippingcharge = ShippingCharge::where('status',1)->get();
        return view('backEnd.order.process',compact('data','shippingcharge'));
    }
    
    public function order_process(Request $request)
    {

        $link = OrderStatus::find($request->status)->slug;
        $order = Order::find($request->id);
        $courier = $order->order_status;
        $order->order_status = $request->status;
        $order->admin_note = $request->admin_note;
        $order->save();

        $shipping_update = Shipping::where('order_id', $order->id)->first();
        $shippingfee = ShippingCharge::find($request->area);
        if ($shippingfee->name != $request->area) {
            if ($order->shipping_charge > $shippingfee->amount) {
                $total = $order->amount + ($shippingfee->amount - $order->shipping_charge);
                $order->shipping_charge = $shippingfee->amount;
                $order->amount = $total;
                $order->save();
            } else {
                $total = $order->amount + ($shippingfee->amount - $order->shipping_charge);
                $order->shipping_charge = $shippingfee->amount;
                $order->amount = $total;
                $order->save();
            }
        }

        $shipping_update->name = $request->name;
        $shipping_update->phone = $request->phone;
        $shipping_update->address = $request->address;
        $shipping_update->area = $shippingfee->name;
        $shipping_update->save();

        if ($request->status == 5 && $courier != 5) {
            $courier_info = Courierapi::where(['status' => 1, 'type' => 'steadfast'])->first();
            if ($courier_info) {
                $consignmentData = [
                    'invoice' => $order->invoice_id,
                    'recipient_name' => $order->shipping ? $order->shipping->name : 'InboxHat',
                    'recipient_phone' => $order->shipping ? $order->shipping->phone : '01750578495',
                    'recipient_address' => $order->shipping ? $order->shipping->address : '01750578495',
                    'cod_amount' => $order->amount
                ];
                $client = new Client();
                $response = $client->post('$courier_info->url', [
                    'json' => $consignmentData,
                    'headers' => [
                        'Api-Key' => '$courier_info->api_key',
                        'Secret-Key' => '$courier_info->secret_key',
                        'Accept' => 'application/json',
                    ],
                ]);
                
                $responseData = json_decode($response->getBody(), true);
            } else {
                return "ok";
            }
            Toastr::success('Success', 'Order status change successfully');
            return redirect('admin/order/' . $link);
        }
        Toastr::success('Success', 'Order status change successfully');
        return redirect('admin/order/' . $link);
    }
    
    public function destroy(Request $request){
        $order = Order::where('id',$request->id)->delete();
        $order_details = OrderDetails::where('order_id',$request->id)->delete();
        $shipping = Shipping::where('order_id',$request->id)->delete();
        $payment = Payment::where('order_id',$request->id)->delete();
        Toastr::success('Success','Order delete success successfully');
        return redirect()->back();
    }
    
    public function order_assign(Request $request){
        $products = Order::whereIn('id', $request->input('order_ids'))->update(['user_id' => $request->user_id]);
        return response()->json(['status'=>'success','message'=>'Order user id assign']);
    }
    
    public function order_status(Request $request){
        $orders = Order::whereIn('id', $request->input('order_ids'))->update(['order_status' => $request->order_status]);

        if($request->order_status == 5){
            $orders = Order::whereIn('id', $request->input('order_ids'))->get();
            foreach($orders as $order){
                $orders_details = OrderDetails::select('id','order_id','product_id')->where('order_id',$order->id)->get();
                foreach($orders_details as $order_details){
                    $product = Product::select('id','stock')->find($order_details->product_id);
                    $product->stock -= $order_details->qty;
                    $product->save();
                }
            }
        }
        return response()->json(['status'=>'success','message'=>'Order status change successfully']);
    }
    
    public function bulk_destroy(Request $request){
        $orders_id = $request->order_ids;
        foreach($orders_id as $order_id){
            $order = Order::where('id',$order_id)->delete();
            $order_details = OrderDetails::where('order_id',$order_id)->delete();
            $shipping = Shipping::where('order_id',$order_id)->delete();
            $payment = Payment::where('order_id',$order_id)->delete();
        }
        return response()->json(['status'=>'success','message'=>'Order delete successfully']);
    }
    public function order_print(Request $request){
        $orders = Order::whereIn('id', $request->input('order_ids'))->with('orderdetails','payment','shipping','customer')->get();
        $view = view('backEnd.order.print', ['orders' => $orders])->render();
        return response()->json(['status' => 'success', 'view' => $view]);
    }
    // public function bulk_courier($slug, Request $request)
    // {
    //     $courier_info = Courierapi::where(['status' => 1, 'type' => $slug])->first();
    //     if ($courier_info) {
    //         $orders_id = $request->order_ids;
    //         foreach ($orders_id as $order_id) {
    //             $order = Order::find($order_id);
    //             $courier = $order->order_status;
    //             if ($request->status == 5 && $courier != 5) {
    //                 $consignmentData = [
    //                     'invoice' => $order->invoice_id,
    //                     'recipient_name' => $order->shipping ? $order->shipping->name : 'InboxHat',
    //                     'recipient_phone' => $order->shipping ? $order->shipping->phone : '01750578495',
    //                     'recipient_address' => $order->shipping ? $order->shipping->address : '01750578495',
    //                     'cod_amount' => $order->amount
    //                 ];
    //                 $client = new Client();
    //                 $response = $client->post('$courier_info->url', [
    //                     'json' => $consignmentData,
    //                     'headers' => [
    //                         'Api-Key' => '$courier_info->api_key',
    //                         'Secret-Key' => '$courier_info->secret_key',
    //                         'Accept' => 'application/json',
    //                     ],
    //                 ]);

    //                 $responseData = json_decode($response->getBody(), true);
    //                 if ($responseData['status'] == 200) {
    //                     $message = 'Your order place to courier successfully';
    //                     $status = 'success';
    //                     $order->order_status = 4;
    //                     $order->save();
    //                 } else {
    //                     $message = 'Your order place to courier failed';
    //                     $status = 'failed';
    //                 }
    //                 return response()->json(['status' => $status, 'message' => $message]);
    //             }
                
    //         }
    //     } else {
    //         return "stop";
    //     }
    // }


public function bulk_courier($slug, Request $request)
{
    $courier_info = Courierapi::where(['status' => 1, 'type' => $slug])->first();

    if (!$courier_info) {
        return response()->json(['status' => 'failed', 'message' => 'Courier info not found']);
    }

    $orders_id = $request->order_ids;

    if (!$orders_id || !is_array($orders_id) || count($orders_id) == 0) {
        return response()->json(['status' => 'failed', 'message' => 'No orders selected']);
    }

    $client = new Client();

    $ordersData = [];

    foreach ($orders_id as $order_id) {
        $order = Order::find($order_id);
        if (!$order) {
            continue;
        }

        $courier = $order->order_status;

        if ($request->status == 5 && $courier != 5) {
            $ordersData[] = [
                'invoice' => $order->invoice_id,
                'recipient_name' => $order->shipping ? $order->shipping->name : 'InboxHat',
                'recipient_phone' => $order->shipping ? $order->shipping->phone : '01750578495',
                'recipient_address' => $order->shipping ? $order->shipping->address : 'N/A',
                'cod_amount' => $order->amount,
            ];
        }
    }

    if (count($ordersData) == 0) {
        return response()->json(['status' => 'failed', 'message' => 'No eligible orders to send']);
    }

    try {
        $response = $client->post($courier_info->url, [
            'json' => ['data' => $ordersData],
            'headers' => [
                'Api-Key' => $courier_info->api_key,
                'Secret-Key' => $courier_info->secret_key,
                'Accept' => 'application/json',
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);

        if (isset($responseData[0]['status']) && $responseData[0]['status'] == 'success') {
            // Update orders status to 4 (shipped/processed) only for those sent
            foreach ($orders_id as $order_id) {
                $order = Order::find($order_id);
                if ($order && $order->order_status != 5) {
                    $order->order_status = 4;
                    $order->save();
                }
            }
            return response()->json(['status' => 'success', 'message' => 'All orders placed to courier successfully']);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Courier API returned an error']);
        }
    } catch (\Exception $e) {
        // Log error if needed: Log::error($e->getMessage());
        return response()->json(['status' => 'failed', 'message' => 'Exception: '.$e->getMessage()]);
    }
}


    
    
    
    
    public function stock_report(Request $request){
        $products = Product::select('id', 'name','new_price','stock')
            ->where('status', 1);
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category_id) {
            $products = $products->where('category_id', $request->category_id);
        }
        if ($request->start_date && $request->end_date) {
            $products =$products->whereBetween('updated_at', [$request->start_date,$request->end_date]);
        }
        $total_purchase = $products->sum(\DB::raw('purchase_price * stock'));
        $total_stock = $products->sum('stock');
        $total_price = $products->sum(\DB::raw('new_price * stock'));
        $products = $products->paginate(10);
        $categories = Category::where('status',1)->get();
        return view('backEnd.reports.stock',compact('products','categories','total_purchase','total_stock','total_price'));
    }
    public function order_report(Request $request){
        $users = User::where('status',1)->get();
        $orders = OrderDetails::with('shipping','order')->whereHas('order', function ($query){
                $query->where('order_status', 6);
            });
        if ($request->keyword) {
            $orders = $orders->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->user_id) {
            $orders = $orders->whereHas('order', function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            });
        }
        if ($request->start_date && $request->end_date) {
            $orders =$orders->whereBetween('updated_at', [$request->start_date,$request->end_date]);
        }
        $total_purchase = $orders->sum(\DB::raw('purchase_price * qty'));
        $total_item = $orders->sum('qty');
        $total_sales = $orders->sum(\DB::raw('sale_price * qty'));
        $orders = $orders->paginate(10);
        return view('backEnd.reports.order',compact('orders','users','total_purchase','total_item','total_sales'));
    }

    public function order_create(){
        $products = Product::select('id','name','new_price','product_code')->where(['status'=>1])->get();
        $cartinfo  = Cart::instance('pos_shopping')->content();
        $shippingcharge = ShippingCharge::where('status',1)->get();
        return view('backEnd.order.create',compact('products','cartinfo','shippingcharge'));
    }
    
    public function order_store(Request $request){
        // session submission lock
        if (Session::has('pos_order_submitting')) {
            FraudLog::create([
                'ip_address' => $request->ip(),
                'type' => 'duplicate',
                'message' => 'POS Session lock triggered. Rapid click detected.',
                'context' => json_encode(['url' => $request->fullUrl()])
            ]);
            Toastr::error('Order is being processed. Please wait.', 'Wait!');
            return redirect()->back();
        }
        Session::put('pos_order_submitting', true);

        try {
            // duplicate order check
            $previous_order = Order::where('created_at', '>=', now()->subSeconds(60))
                ->where('ip_address', $request->ip())
                ->exists();

            if ($previous_order) {
                FraudLog::create([
                    'ip_address' => $request->ip(),
                    'type' => 'duplicate',
                    'message' => 'Duplicate POS order attempt within 60 seconds.',
                    'context' => json_encode(['phone' => $request->phone])
                ]);
                Session::forget('pos_order_submitting');
                Toastr::error('Order is already being processed. Please wait 60 seconds.', 'Failed!');
                return redirect()->back();
            }

            $this->validate($request,[
                'name'=>'required',
                'phone'=>'required',
                'address'=>'required',
                'area'=>'required',
            ]);

            if(Cart::instance('pos_shopping')->count() <= 0) {
                Session::forget('pos_order_submitting');
                Toastr::error('Your shopping empty', 'Failed!');
                return redirect()->back();
            }
            // ... rest of the method logic encapsulated in try ...
            // (I will use multi_replace if needed but for now let's just close the try block at the end)

        $subtotal = Cart::instance('pos_shopping')->subtotal();
        $subtotal = str_replace(',','',$subtotal);
        $subtotal = str_replace('.00', '',$subtotal);
        $discount = Session::get('pos_discount')+Session::get('product_discount');
        $shippingfee  = ShippingCharge::find($request->area);
        
        $exits_customer = Customer::where('phone',$request->phone)->select('phone','id')->first();
        if($exits_customer){
            $customer_id = $exits_customer->id;
        }else{
            $password = rand(111111,999999);
            $store              = new Customer();
            $store->name        = $request->name;
            $store->slug        = $request->name;
            $store->phone       = $request->phone;
            $store->password    = bcrypt($password);
            $store->verify      = 1;
            $store->status      = 'active';
            $store->save();
            $customer_id = $store->id;
        }
 
         // order data save
        $order                   = new Order();
        $order->invoice_id       = rand(11111,99999);
        $order->amount           = ($subtotal + $shippingfee->amount) - $discount;
        $order->discount         = $discount ? $discount : 0;
        $order->shipping_charge  = $shippingfee->amount;
        $order->customer_id      =  $customer_id;
        $order->order_status     = 1;
        $order->note             = $request->note;
        $order->ip_address       = $request->ip();

        // Calculate Risk Score
        $risk_score = 0;
        $fraud_notes = [];

        // 1. Check for repeating digits in phone
        if (preg_match('/(\d)\1{4,}/', $request->phone)) {
            $risk_score += 30;
            $fraud_notes[] = "Suspicious phone pattern";
        }

        // 2. Check orders from same IP in last 24h
        $ip_order_count = Order::where('ip_address', $request->ip())
            ->where('created_at', '>=', now()->subDay())
            ->count();
        if ($ip_order_count >= 3) {
            $risk_score += ($ip_order_count - 2) * 20;
            $fraud_notes[] = "Multiple orders from same IP ($ip_order_count)";
        }

        // 3. Check if phone used with different names
        $distinct_names = Shipping::where('phone', $request->phone)
            ->distinct('name')
            ->count();
        if ($distinct_names > 1) {
            $risk_score += 15;
            $fraud_notes[] = "Phone used with different names";
        }

        // 4. Geo-Fencing
        try {
            $response = Http::timeout(2)->get("http://ip-api.com/json/{$request->ip()}?fields=countryCode");
            if ($response->successful()) {
                $country = $response->json('countryCode');
                if ($country && $country !== 'BD') {
                    $risk_score += 40;
                    $fraud_notes[] = "International IP ($country)";
                }
            }
        } catch (\Exception $e) {
            // Skip if API fails
        }

        $order->risk_score = min($risk_score, 100);
        $order->fraud_note = implode(', ', $fraud_notes);

        $order->save();

        // shipping data save
        $shipping              =   new Shipping();
        $shipping->order_id    =   $order->id;
        $shipping->customer_id =   $customer_id;
        $shipping->name        =   $request->name;
        $shipping->phone       =   $request->phone;
        $shipping->address     =   $request->address;
        $shipping->area        =   $shippingfee->name;
        $shipping->save();

        // payment data save
        $payment                 = new Payment();
        $payment->order_id       = $order->id;
        $payment->customer_id    = $customer_id;
        $payment->payment_method = 'Cash On Delivery';
        $payment->amount         = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();

       // order details data save
        foreach(Cart::instance('pos_shopping')->content() as $cart){
            $order_details                   =   new OrderDetails();
            $order_details->order_id         =   $order->id;
            $order_details->product_id       =   $cart->id;
            $order_details->product_name     =   $cart->name;
            $order_details->purchase_price   =   $cart->options->purchase_price;
            $order_details->product_discount =   $cart->options->product_discount;
            $order_details->sale_price       =   $cart->price;
            $order_details->qty              =   $cart->qty;
            $order_details->save();
        }
        Cart::instance('pos_shopping')->destroy();
        Session::forget('pos_shipping');
        Session::forget('pos_discount');
        Session::forget('product_discount');
        Toastr::success('Thanks, Your order place successfully', 'Success!');
        return redirect('admin/order/pending');
        } catch (\Exception $e) {
            Session::forget('pos_order_submitting');
            Toastr::error('Something went wrong. Please try again.', 'Error!');
            return redirect()->back();
        } finally {
            Session::forget('pos_order_submitting');
        }
    }
    public function cart_add(Request $request){
        $product = Product::select('id','name','stock','new_price','old_price','purchase_price','slug')->where(['id' => $request->id])->first();
        $qty = 1;
        $cartinfo = Cart::instance('pos_shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $product->new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $product->old_price,
                'purchase_price' => $product->purchase_price,
                'product_discount' => 0,
            ],
        ]);
        return response()->json(compact('cartinfo'));
    }
    public function cart_content(){
        $cartinfo = Cart::instance('pos_shopping')->content();
        return view('backEnd.order.cart_content',compact('cartinfo'));
    }
    public function cart_details(){
        $cartinfo = Cart::instance('pos_shopping')->content();
        $discount = 0;
        foreach($cartinfo as $cart){
            $discount += $cart->options->product_discount*$cart->qty;
        }
        Session::put('product_discount',$discount);
        return view('backEnd.order.cart_details',compact('cartinfo'));
    }
    public function cart_increment(Request $request){
        $qty = $request->qty + 1;
        $cartinfo = Cart::instance('pos_shopping')->update($request->id, $qty);
        return response()->json($cartinfo);
    }
    public function cart_decrement(Request $request){
        $qty = $request->qty - 1;
        $cartinfo = Cart::instance('pos_shopping')->update($request->id, $qty);
        return response()->json($cartinfo);
    }
    public function cart_remove(Request $request){
        $remove = Cart::instance('pos_shopping')->remove($request->id);
        $cartinfo = Cart::instance('pos_shopping')->content();
        return response()->json($cartinfo);
    }
    public function product_discount(Request $request){
        $discount = $request->discount;
        $cart = Cart::instance('pos_shopping')->content()->where('rowId', $request->id)->first();
        $cartinfo = Cart::instance('pos_shopping')->update($request->id, [
            'options' => [
                'slug' => $cart->options->slug,
                'image' => $cart->options->image,
                'old_price' => $cart->options->old_price,
                'purchase_price' => $cart->options->purchase_price,
                'product_discount' => $request->discount,
            ],
        ]);
        return response()->json($cartinfo);
    }
    public function cart_shipping(Request $request){
         $shipping = ShippingCharge::where(['status'=>1,'id'=>$request->id])->first()->amount;
        Session::put('pos_shipping', $shipping);
        return response()->json($shipping);
    }

    public function cart_clear(Request $request){
        $cartinfo = Cart::instance('pos_shopping')->destroy();
        Session::forget('pos_shipping');
        Session::forget('pos_discount');
        Session::forget('product_discount');
        return redirect()->back();
    }
    public function order_edit($invoice_id){
        $products = Product::select('id','name','new_price','product_code')->where(['status'=>1])->get();
        $shippingcharge = ShippingCharge::where('status',1)->get();
        $order = Order::where('invoice_id',$invoice_id)->first();
        $cartinfo  = Cart::instance('pos_shopping')->destroy();
        $shippinginfo  = Shipping::where('order_id',$order->id)->first();
        Session::put('product_discount',$order->discount);
        Session::put('pos_shipping',$order->shipping_charge);
        $orderdetails = OrderDetails::where('order_id',$order->id)->get();
        foreach($orderdetails as $ordetails){
        $cartinfo = Cart::instance('pos_shopping')->add([
            'id' => $ordetails->product_id,
            'name' => $ordetails->product_name,
            'qty' => $ordetails->qty,
            'price' => $ordetails->sale_price,
            'options' => [
                'image' => $ordetails->image->image,
                'purchase_price' => $ordetails->purchase_price,
                'product_discount' => $ordetails->product_discount,
                'details_id' => $ordetails->id,
            ],
        ]);
        }
        $cartinfo  = Cart::instance('pos_shopping')->content();
         $divisions=Division::orderBy('id','Desc')->get();
         $city = City::orderBy('id','ASC')->get();
         $Pourosaova=Pourosaova::orderBy('id','ASC')->get();
        return view('backEnd.order.edit',compact('products','cartinfo','shippingcharge','shippinginfo','order','divisions','city','Pourosaova'));
    }
    
    public function order_update(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
            'address'=>'required',
            'area'=>'required',
        ]);

        if(Cart::instance('pos_shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }

        $subtotal = Cart::instance('pos_shopping')->subtotal();
        $subtotal = str_replace(',','',$subtotal);
        $subtotal = str_replace('.00', '',$subtotal);
        $discount = Session::get('pos_discount')+Session::get('product_discount');
        $shippingfee  = ShippingCharge::find($request->area);

        $exits_customer = Customer::where('phone',$request->phone)->select('phone','id')->first();
        if($exits_customer){
            $customer_id = $exits_customer->id;
        }else{
            $password = rand(111111,999999);
            $store              = new Customer();
            $store->name        = $request->name;
            $store->slug        = $request->name;
            $store->phone       = $request->phone;
            $store->password    = bcrypt($password);
            $store->verify      = 1;
            $store->status      = 'active';
            $store->save();
            $customer_id = $store->id;
        }
 
         // order data save
        $order                   =  Order::where('id',$request->order_id)->first();
        $order->invoice_id       = rand(11111,99999);
        $order->amount           = ($subtotal + $shippingfee->amount) - $discount;
        $order->discount         = $discount ? $discount : 0;
        $order->shipping_charge  = $shippingfee->amount;
        $order->customer_id      =  $customer_id;
        $order->order_status     = 1;
        $order->note             = $request->note;
        $order->save();


        // shipping data save
        $shipping              =   Shipping::where('order_id',$request->order_id)->first();
        $shipping->order_id    =   $order->id;
        $shipping->customer_id =   $customer_id;
        $shipping->name        =   $request->name;
        $shipping->division   =  $request->division;
        $shipping->city     =$request->city;
        $shipping->pourasava =$request->pourasava;
        
        $shipping->phone       =   $request->phone;
        $shipping->address     =   $request->address;
        $shipping->area        =   $shippingfee->name;
        $shipping->save();

        // payment data save
        $payment                 =  Payment::where('order_id',$request->order_id)->first();
        $payment->order_id       = $order->id;
        $payment->customer_id    = $customer_id;
        $payment->payment_method = 'Cash On Delivery';
        $payment->amount         = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();

       // order details data save
        foreach(Cart::instance('pos_shopping')->content() as $cart){
            $exits = OrderDetails::where('id',$cart->options->details_id)->first();
            if($exits){
                $order_details                   =   OrderDetails::find($exits->id);
                $order_details->product_discount =   $cart->options->product_discount;
                $order_details->sale_price       =   $cart->price;
                $order_details->qty              =   $cart->qty;
                $order_details->save();
            }else{
                $order_details                   =   new OrderDetails();
                $order_details->order_id         =   $order->id;
                $order_details->product_id       =   $cart->id;
                $order_details->product_name     =   $cart->name;
                $order_details->purchase_price   =   $cart->options->purchase_price;
                $order_details->product_discount =   $cart->options->product_discount;
                $order_details->sale_price       =   $cart->price;
                $order_details->qty              =   $cart->qty;
                $order_details->save();
            }
            
        }
        Cart::instance('pos_shopping')->destroy();
        Session::forget('pos_shipping');
        Session::forget('pos_discount');
        Session::forget('product_discount');
        Toastr::success('Thanks, Your order place successfully', 'Success!');
        return redirect('admin/order/pending');
    }
     public function fraud_checker(Request $request)
    {
        // $headers = [
        //     'email' => 'zadumia441@gmail.com',
        //     'api_key' => 'JQAGMXHUUNPAAA5W',
        // ];
        // $body = [
        //     'phone' => $shipping->phone,
        // ];
        // $response = Http::withHeaders($headers)
        //     ->post('https://fraudchecker.webleez.com/api/v1/fraud-checker', $body);
        // $result = $response->json();

        /*
         "total_parcel": 0,
            "success_parcel": 0,
            "cancelled_parcel": 0,
            "success_ratio": 0
         */

        $shipping = Shipping::where('order_id', $request->id)->first();
        $phone = $shipping->phone;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://bdcourier.com/api/courier-check?phone=" . urlencode($phone),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ZkEEfBAEBRxVkgcLpR3Z5e3sPHQ6dy0XViGTqYyg4clRjj06rRKmAs41Smp2'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);

        $result = $data;

        $name = $result['name'] ?? 'N/A';
        $phone = $shipping->phone ?? 'N/A';

        $steadfast_total = $result['courierData']['steadfast']['total_parcel'] ?? 0;
        $steadfast_success = $result['courierData']['steadfast']['success_parcel'] ?? 0;
        $steadfast_cancel = $result['courierData']['steadfast']['cancelled_parcel'] ?? 0;

        $pathao_total = $result['courierData']['pathao']['total_parcel'] ?? 0;
        $pathao_success = $result['courierData']['pathao']['success_parcel'] ?? 0;
        $pathao_cancel = $result['courierData']['pathao']['cancelled_parcel'] ?? 0;

        $redx_total = $result['courierData']['redx']['total_parcel'] ?? 0;
        $redx_success = $result['courierData']['redx']['success_parcel'] ?? 0;
        $redx_cancel = $result['courierData']['redx']['cancelled_parcel'] ?? 0;

        $paperfly_total = $result['courierData']['paperfly']['total_parcel'] ?? 0;
        $paperfly_success = $result['courierData']['paperfly']['success_parcel'] ?? 0;
        $paperfly_cancel = $result['courierData']['paperfly']['cancelled_parcel'] ?? 0;

        $total_parcel = $result['courierData']['summary']['total_parcel'] ?? 0;
        $total_success = $result['courierData']['summary']['success_parcel'] ?? 0;
        $total_cancel = $result['courierData']['summary']['cancelled_parcel'] ?? 0;
        $status = $result['status'] ?? 'N/A';

        $order = Order::find($request->id);
        
        return view('backEnd.order.fraud_checker', compact(
            'order',
            'status',
            'name',
            'phone',
            'steadfast_total',
            'steadfast_success',
            'steadfast_cancel',
            'pathao_total',
            'pathao_success',
            'pathao_cancel',
            'redx_total',
            'redx_success',
            'redx_cancel',
            'paperfly_total',
            'paperfly_success',
            'paperfly_cancel',
            'total_parcel',
            'total_success',
            'total_cancel'
        ));
    }

}
