<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $cartItems = Auth::check()
            ? Cart::with('product.discount')->where('user_id', $user->id)->get()->map(function($item){
                $product = $item->product;
                $discountedPrice = $product->price;
                if($product->discount && $product->discount->is_active){
                    if($product->discount->discount_percent){
                        $discountedPrice = $product->price * (1 - $product->discount->discount_percent/100);
                    } elseif($product->discount->discount_amount){
                        $discountedPrice = max($product->price - $product->discount->discount_amount, 0);
                    }
                }
                return (object)[
                    'id'=>$item->id,
                    'quantity'=>$item->quantity,
                    'product'=>$product,
                    'discounted_price'=>$discountedPrice
                ];
            })
            : collect($request->session()->get('cart',[]))->map(function($item,$key){
                $discountedPrice = $item['price'];
                if(!empty($item['discount']) && $item['discount']['is_active']){
                    if(!empty($item['discount']['discount_percent'])){
                        $discountedPrice = $item['price']*(1-$item['discount']['discount_percent']/100);
                    } elseif(!empty($item['discount']['discount_amount'])){
                        $discountedPrice = max($item['price'] - $item['discount']['discount_amount'], 0);
                    }
                }
                return (object)[
                    'id'=>$key,
                    'quantity'=>$item['quantity'],
                    'product'=>(object)[
                        'name'=>$item['name'],
                        'price'=>$item['price'],
                    ],
                    'discounted_price'=>$discountedPrice
                ];
            });

        $totalPrice = $cartItems->sum(fn($item)=> $item->discounted_price * $item->quantity);
        $provinces = Http::get('https://provinces.open-api.vn/api/p/')->json();

        return view('checkout.index', compact('cartItems','totalPrice','user','provinces'));
    }

public function store(Request $request)
{
    $request->validate([
        'fullname'=>'required|string|max:255',
        'phone'=>'required|string|max:20',
        'email'=>'nullable|email|max:255',
        'address'=>'required|string|max:500',
        'province'=>'required|string',
        'district'=>'required|string',
        'ward'=>'required|string',
        'payment_method'=>'required|string|in:COD,VNPAY,MOMO'
    ]);

    $cartItems = Auth::check()
        ? Cart::with('product.discount')->where('user_id',Auth::id())->get()->map(function($item){
            $product = $item->product;
            $discountedPrice = $product->price;
            if($product->discount && $product->discount->is_active){
                if($product->discount->discount_percent){
                    $discountedPrice = $product->price * (1 - $product->discount->discount_percent/100);
                } elseif($product->discount->discount_amount){
                    $discountedPrice = max($product->price - $product->discount->discount_amount, 0);
                }
            }
            return (object)[
                'id'=>$item->id,
                'quantity'=>$item->quantity,
                'product'=>$product,
                'discounted_price'=>$discountedPrice
            ];
        })
        : collect($request->session()->get('cart',[]))->map(function($item,$key){
            $discountedPrice = $item['price'];
            if(!empty($item['discount']) && $item['discount']['is_active']){
                if(!empty($item['discount']['discount_percent'])){
                    $discountedPrice = $item['price']*(1-$item['discount']['discount_percent']/100);
                } elseif(!empty($item['discount']['discount_amount'])){
                    $discountedPrice = max($item['price'] - $item['discount']['discount_amount'], 0);
                }
            }
            return (object)[
                'id'=>$key,
                'quantity'=>$item['quantity'],
                'product'=>(object)[
                    'name'=>$item['name'],
                    'price'=>$item['price'],
                ],
                'discounted_price'=>$discountedPrice
            ];
        });

    if($cartItems->isEmpty()){
        return redirect()->back()->with('error','Giỏ hàng trống');
    }

    $totalPrice = $cartItems->sum(fn($item)=> $item->discounted_price * $item->quantity);
    $paymentMethod = $request->payment_method;

    // Lấy tên tỉnh/huyện/xã từ API
    $province = Http::get('https://provinces.open-api.vn/api/p/'.$request->province)->json();
    $district = Http::get('https://provinces.open-api.vn/api/d/'.$request->district)->json();
    $ward = Http::get('https://provinces.open-api.vn/api/w/'.$request->ward)->json();

    // Gộp địa chỉ
    $fullAddress = $request->address;
    $fullAddress .= ', ' . ($ward['name'] ?? $request->ward);
    $fullAddress .= ', ' . ($district['name'] ?? $request->district);
    $fullAddress .= ', ' . ($province['name'] ?? $request->province);

    $order = DB::transaction(function() use($cartItems,$totalPrice,$paymentMethod,$fullAddress,$request){
        $order = Order::create([
            'user_id'=>Auth::id(),
            'fullname'=>$request->fullname,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'address'=>$fullAddress, // lưu gộp luôn
            'province_code'=>$request->province,
            'district_code'=>$request->district,
            'ward_code'=>$request->ward,
            'total_price'=>$totalPrice,
            'status'=>'pending',
            'payment_method'=>$paymentMethod,
            'payment_status'=>($paymentMethod=='COD')?'pending':'unpaid'
        ]);

        foreach($cartItems as $item){
            $price = $item->discounted_price ?? $item->product->price;
            $order->items()->create([
                'product_id'=>$item->product->id ?? $item->id,
                'quantity'=>$item->quantity,
                'price'=>$price
            ]);
        }

        return $order;
    });

    // Xóa giỏ hàng
    if(Auth::check()){
        Cart::where('user_id',Auth::id())->delete();
    } else {
        $request->session()->forget('cart');
    }

    // Thanh toán
    if($paymentMethod=='MOMO'){
        return redirect()->route('momo.payment', $order->id);
    }

    if($paymentMethod=='VNPAY'){
        return redirect()->route('vnpay.payment', $order->id);
    }

    return redirect()->route('checkout.success',$order->id)->with('success','Đặt hàng thành công!');
}


public function success(Order $order)
{
    if(Auth::id() !== $order->user_id){
        abort(403);
    }

    // Địa chỉ đã lưu gộp trong database, không cần gọi API nữa
    return view('checkout.success', compact('order'));
}


    // ==================== VNPay ====================
    public function vnpayPayment(Order $order){
        $vnp_TmnCode = env('VNP_TMNCODE'); // Mã website
        $vnp_HashSecret = env('VNP_HASHSECRET');
        $vnp_Url = env('VNP_URL');
        $vnp_Returnurl = route('vnpay.return');

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $order->total_price * 100, // VNPay tính đơn vị là 100đ
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_TxnRef" => $order->id,
            "vnp_OrderInfo" => "Thanh toán đơn #".$order->id,
            "vnp_Locale" => "vn",
            "vnp_ReturnUrl" => $vnp_Returnurl
        ];

        ksort($inputData);
        $query = http_build_query($inputData);
        $hashdata = hash_hmac('sha512', $query, $vnp_HashSecret);
        $vnp_Url .= "?".$query."&vnp_SecureHash=".$hashdata;

        return redirect($vnp_Url);
    }

    public function vnpayReturn(Request $request){
        $order = Order::findOrFail($request->get('vnp_TxnRef'));
        if($request->get('vnp_ResponseCode') == '00'){
            $order->payment_status = 'paid';
            $order->status = 'processing';
        } else {
            $order->payment_status = 'failed';
        }
        $order->save();
        return redirect()->route('checkout.success', $order->id);
    }

    // ==================== MOMO ====================
    public function momoPayment(Order $order){
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $orderId = $order->id.'_'.time();
        $amount = $order->total_price;
        $redirectUrl = route('checkout.success',$order->id);
        $ipnUrl = route('momo.ipn'); // URL nhận callback

        $requestId = time();
        $requestType = "captureWallet";

        $rawData = [
            "partnerCode"=>$partnerCode,
            "accessKey"=>$accessKey,
            "requestId"=>$requestId,
            "amount"=>$amount,
            "orderId"=>$orderId,
            "orderInfo"=>"Thanh toán đơn #".$order->id,
            "returnUrl"=>$redirectUrl,
            "notifyUrl"=>$ipnUrl,
            "extraData"=>"",
            "requestType"=>$requestType
        ];

        ksort($rawData);
        $signature = hash_hmac('sha256', http_build_query($rawData), $secretKey);
        $rawData['signature'] = $signature;

        $response = Http::post($endpoint, $rawData)->json();

        if(!empty($response['payUrl'])){
            $order->payment_code = $orderId;
            $order->save();
            return redirect($response['payUrl']);
        }

        return redirect()->route('checkout.index')->with('error','Thanh toán Momo lỗi');
    }

    public function momoIpn(Request $request){
        $data = $request->all();
        $order = Order::findOrFail($data['orderId']);
        if($data['resultCode']==0){
            $order->payment_status = 'paid';
            $order->status = 'processing';
        } else {
            $order->payment_status = 'failed';
        }
        $order->save();
        return response()->json(['status'=>'ok']);
    }


}
