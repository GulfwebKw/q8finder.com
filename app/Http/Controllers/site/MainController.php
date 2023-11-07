<?php

namespace App\Http\Controllers\site;


use App\Classes\Payment\CBKPay;
use App\Http\Controllers\Controller;
use App\Models\PaymentResponse;
use App\Models\Area;
use App\Models\City;
use App\Models\Advertising;
use App\Models\OrderTransaction;
use App\Models\Package;
use App\Models\PackageHistory;
use App\Models\Payment;
use App\Models\Setting;
use App\User;
use Carbon\Carbon;
use http\Url;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use mysql_xdevapi\Exception;
use PhpParser\Node\Stmt\Switch_;

class MainController extends Controller
{
    // index page
    public function index()
    {
        // app('debugbar')->disable();
        $premiumAds = Advertising::where('expire_at', '>', date('Y-m-d'))
            ->where('advertising_type', 'premium')
            ->whereNotNull('expire_at')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        $cities = City::all();
        $areas = Area::orderBy('name_en')->get();

        $residentials = Advertising::where('expire_at', '>', date('Y-m-d'))
            ->where('advertising_type', 'normal')
            ->whereNotNull('expire_at')
            ->orderBy('created_at', 'desc')
            ->paginate(6);



        return view('site.pages.main', compact('premiumAds', 'residentials', 'cities', 'areas'));
    }

    //sign up page
    public function signup()
    {
        return view('site.auth.signup');
    }

    // login page
    public function login()
    {
        return view('site.auth.login');
    }

    // about us page
    public function aboutus()
    {

        $aboutus_large_ar = Setting::where('setting_key', 'aboutus_large_ar')->value('setting_value');
        $aboutus_large_en = Setting::where('setting_key', 'aboutus_large_en')->value('setting_value');

        return view('site.pages.aboutus', compact('aboutus_large_ar', 'aboutus_large_en'));
    }

    public function termsAndConditions()
    {
        $data_ar = Setting::where('setting_key', 'terms_and_conditions_ar')->value('setting_value');
        $data_en = Setting::where('setting_key', 'terms_and_conditions_en')->value('setting_value');
        return view('site.pages.simplePage', compact('data_ar', 'data_en'));
    }

    public function privacyPolicy()
    {
        $data_ar = Setting::where('setting_key', 'privacy_policy_ar')->value('setting_value');
        $data_en = Setting::where('setting_key', 'privacy_policy_en')->value('setting_value');

        return view('site.pages.simplePage', compact('data_ar', 'data_en'));
    }

    /*
    //
    my account page
    //
    */

    // get available ads for user
    public static function getBalance($ignoreGift = false)
    {
        $user = auth()->user();
        $date = date("Y-m-d");
        User::where('id', $user->id)->update(['last_activity' => date("Y-m-d")]);
        $listBalance = PackageHistory::where("user_id", $user->id)
            ->where("expire_at", ">", $date)
            ->where("is_payed", 1)
            ->where('accept_by_admin', 1)
            ->whereColumn('count_advertising', '>=', 'count_usage')
            ->whereColumn('count_premium', '>=', 'count_usage_premium')
            ->orderBy('id', 'desc');
        if ($ignoreGift) {
            $listBalance = $listBalance->where('title_en', "!=", 'gift credit');
        }
        $listBalance = $listBalance->get();
        if ($listBalance->count() >= 1) {
            $expireAt = $listBalance[0]->expire_at;
            $type     = $listBalance[0]->type;
            $titleAr  = $listBalance[0]->title_ar;
            $titleEn  = $listBalance[0]->title_en;

            $count             = 0;
            $countPremium      = 0;
            $countUsage        = 0;
            $countPremiumUsage = 0;
            foreach ($listBalance as $item) {
                $count             += $item->count_advertising;
                $countUsage        += $item->count_usage;
                $countPremium      += $item->count_premium;
                $countPremiumUsage += $item->count_usage_premium;
            }
            $av     = $count - $countUsage;
            $avp    = $countPremium - $countPremiumUsage;
            $record = [
                'type'                => $type,
                'title_en'            => $titleEn,
                'title_ar'            => $titleAr,
                'expire_at'           => $expireAt,
                'count_advertising'   => $count,
                'count_usage'         => $countUsage,
                'count_premium'       => $countPremium,
                'count_premium_usage' => $countPremiumUsage,
                'available'           => $av,
                'available_premium'   => $avp
            ];
            if ($record['available'] === 0 && $record['available_premium'] === 0)
                $record = 0;
        } else {
            $record = 0;
        }
        return $record;
    }

    // profile page
    public function profile()
    {
        $record = $this->getBalance();
        return view('site.pages.profile', [
            'balance' => $record
        ]);
    }

    // change password
    public function changePassword()
    {
        $record = $this->getBalance();
        return view('site.pages.changePassword', [
            'balance' => $record
        ]);
    }

    //wishlist
    public function wishList()
    {
        $record = $this->getBalance();
        //return  $user = User::whereId(Auth::user()->id)->with(['archiveAdvertising','advertising'])->first();
        $wishList = User::whereId(Auth::user()->id)->with(['archiveAdvertising' => function ($q) {
            $q->where('expire_at', '>=', Carbon::now()->format('Y-m-d'))->with(['user', 'city', 'area']);
        }])->first();
        //        return $wishList->archiveAdvertising;
        if (\request()->wantsJson()) {
            return [
                'balance' => $record,
                'wishLists' => $wishList->archiveAdvertising
            ];
        }
        return view('site.pages.wishList', [
            'balance' => $record,
            'wishLists' => $wishList->archiveAdvertising
        ]);
    }

    //payment history
    public function paymentHistory()
    {
        $record = $this->getBalance();
        $user = auth()->user();
        $payments = PackageHistory::where('user_id', $user->id)
            ->orderBy('id', 'desc')->paginate(20);

        return view('site.pages.paymentHistory', [
            'balance' => $record,
            'payments' => $payments
        ]);
    }

    public function paymentDetails(Request $request)
    {
        if (empty($request->paymentid)) {
            abort('404');
        }

        $record       = $this->getBalance();

        $payments     = Payment::where('payments.id', $request->paymentid);
        $payments     = $payments->select('tbl_transaction_api.*', 'payments.*')
            ->join('tbl_transaction_api', 'tbl_transaction_api.api_ref_id', '=', 'payments.ref_id');
        $payments     = $payments->first();
        return view('site.pages.paymentDetails', ['balance' => $record, 'paymentsDetails' => $payments]);
    }

    //my ads
    public function myAds()
    {
        $record = $this->getBalance();

        $credit = $this->getCreditUser(auth()->id());
        $user = auth()->user();
        $ads = Advertising::where('user_id', $user->id)->withoutGlobalScope('notService')
            ->whereDate('expire_at', '>=', Carbon::now())
            ->orderBy('id', 'desc')->paginate(20);

        return view('site.pages.myAds', [
            'balance' => $record,
            'ads' => $ads,
            'credit' => $credit
        ]);
    }
    //my ads
    public function myAdsArchived()
    {
        $record = $this->getBalance();

        $credit = $this->getCreditUser(auth()->id());
        $user = auth()->user();
        $ads = Advertising::where('user_id', $user->id)->withoutGlobalScope('notService')
            ->whereDate('expire_at', '<', Carbon::now())
            ->orderBy('id', 'desc')->paginate(20);

        return view('site.pages.myAdsArchived', [
            'balance' => $record,
            'ads' => $ads,
            'credit' => $credit
        ]);
    }



    // buy package
    public function buyPackage()
    {
        cache()->forget('balance_values' . auth()->id());
        $record = $this->getBalance();

        if (auth()->user()->type_usage == 'individual') {
            $normals = Package::where('type', 'normal')
                ->where('title_en', '!=', 'gift credit')
                ->where('user_type', 'individual')
                ->where('is_enable', 1)
                ->where('is_visible', 1)->get();
            $statics = Package::where('type', 'static')
                ->where('user_type', 'individual')
                ->where('is_enable', 1)
                ->where('is_visible', 1)->get();
        } elseif (auth()->user()->type_usage == 'company') {
            $normals = Package::where('type', 'normal')
                ->where('title_en', '!=', 'gift credit')
                ->where('user_type', 'company')
                ->where('is_enable', 1)
                ->where('is_visible', 1)->get();
            $statics = Package::where('type', 'static')
                ->where('user_type', 'company')
                ->where('is_enable', 1)
                ->where('is_visible', 1)->get();
        }

        $credit = $this->getCreditUser(auth()->id());
        if ($credit === [])
            $credit = ['count_premium_advertising' => 0, 'count_normal_advertising' => 0];

        // dd(@$statics, @$credit, @$normals);

        return view('site.pages.buyPackage', [
            'balance' => $record,
            'normals' => $normals,
            'statics' => $statics,
            'credit' => $credit,
        ]);
    }

    //buy package or credit
    public function buyPackageOrCredit(Request $request)
    {

        try {
            $user = auth()->user();
            cache()->forget('balance_values' . auth()->id());
            $validate = Validator::make($request->all(), [
                'package_id'   => 'required|numeric',
                'type'         => 'required|in:static,normal',
                'count'        => 'nullable|numeric',
                'payment_type' => 'required|in:Cash,CBKPay',
            ]);
            if ($validate->fails()) {
                return redirect()->route('Main.buyPackage', app()->getLocale())->with(['status' => 'validation_failed']);
            }
            $package = Package::find($request->package_id);
            // untill now request data is validated
            // now we check user doesn't choose a package that already bought
            if ($package->type == "normal") {
                if ($user->package_id != null && $user->package_id != 0) {
                    $balance = $this->getBalance(true);
                    //                dd($this->getBalance());
                    if ($balance !== 0 && $balance['available'] > 0 && $balance['available_premium'] > 0) {
                        return redirect(app()->getLocale() . '/buypackage#result')->with(['status' => 'ads_remaining']);
                    }
                }
            }
            $countDay = optional($package)->count_day;

            $today = date("Y-m-d");
            $date = strtotime("+$countDay day", strtotime($today));
            $expireDate = date("Y-m-d", $date);


            if (isset($request->count) && is_numeric($request->count) && $request->count > 1) {
                $count = $request->count;
            } else {
                $count = 1;
            }
            $countP = intval($package->count_premium) * intval($count);
            $countN = intval($package->count_advertising) * intval($count);
            $price = intval($package->price) * intval($count);


            if ($request->payment_type == "Cash" || $request->payment_type == "cash") {
                $accept = 0;
            } else {
                $accept = 1;
            }


            $ref = $this->makeRefId($user->id);
            $payment = Payment::create([
                'user_id'      => $user->id,
                'package_id'   => $request->package_id,
                'payment_type' => $request->payment_type,
                'amount'        => $price,
                'status'       => 'new',
            ]);

            //todo:: 'is_payed'=>1  change to 0 after implement logic payment
            $res = PackageHistory::create([
                'title_en'          => $package->title_en,
                'title_ar'          => $package->title_ar,
                'user_id'           => $user->id,
                'type'              => $request->type,
                'package_id'        => $request->package_id,
                'date'              => date('Y-m-d'),
                'is_payed'          => 0,
                'price'             => $package->price,
                'count_day'         => $package->count_day,
                'count_show_day'    => $package->count_show_day,
                'count_advertising' => $countN,
                'count_premium'     => $countP,
                'count'             => $count,
                'expire_at'         => $expireDate,
                'payment_type'      => $request->payment_type,
                'accept_by_admin'   => $accept
            ]);
            $payment->package_history_id = $res->id;
            $payment->ref_id             = $ref;
            $res->payment_id             = $payment->id;
            $res->save();
            $payment->save();


            if ($request->get('payment_type') == "CBKPay" and $price > 0) {
                $cbkPay = new CBKPay();
                $form = $cbkPay->initiatePayment($price, $ref, '', 'mraqar007', '', '', '', '', '', 'en', request()->getSchemeAndHttpHost() . '/'.app()->getLocale().'/payment-response/0/cbk');
                return $form;
            } else {
                // if payment type is cash
                $res->accept_by_admin = 1;
                $res->is_payed        = 1;

                $package_id       = $res->package_id;
                $user->package_id = $package_id;
                $user->save();
                $res->save();

                if ($user->type_usage == 'company' && $user->companied_at === null)
                    $user->update(['companied_at' => now()]);

                return redirect()->route('Main.myAds', app()->getLocale())->with(['status' => 'package_bought']);
                // return redirect(app()->getLocale().'/paymenthistory#result')->with(['status' => 'package_bought']);
                //return redirect('/paymenthistory#result',app()->getLocale())->with(['status' => 'package_bought']);
            }
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function paymentResponseCBK(Request $request , $locale ="en" , $hide = 0)
    {
        $hideHeaderAndFooter = $hide == "1";
        try {
            // !!for testing only
            // $paymentStatus = (object)$request->all();

            $payment= [];
            $paymentStatus= [];
            $paymentResponse = new PaymentResponse();
            $paymentResponse->response = json_encode($request->all());
            if (empty($request->encrp)) {
                throw new \Exception('payment failed');
            }
            $cbkPay = new CBKPay();
            $paymentStatus = $cbkPay->getPaymentStatusDetails($request->encrp);
            $paymentResponse->status_data = json_encode($paymentStatus);

            if (!@$paymentStatus || (!@$paymentStatus->Status && !@$paymentStatus->ErrorCode)) {
                throw new \Exception("Unable to get payment status");
            }
            if (@$paymentStatus->Status) {
                $message = $cbkPay->getPaymentResultMsg($paymentStatus->Status);
            } elseif (@$paymentStatus->ErrorCode) {
                $message = $cbkPay->getCBKError($paymentStatus->ErrorCode);
            }
            $payment = Payment::with(['package', 'packageHistory', 'user'])->where('ref_id', @$paymentStatus->PayId ?? @$paymentStatus->PayTrackID)->first();
            if(@$paymentStatus->Status !== '1' || !@$payment){
                $paymentResponse->status = $cbkPay->getPaymentStatus($paymentStatus);
                if(@$payment){
                    $payment->description = @$message;
                    $payment->status = $cbkPay->getPaymentStatus($paymentStatus) === 'invalid' ? 'canceled' : $cbkPay->getPaymentStatus($paymentStatus);
                    $payment->save();
                    $payment->packageHistory->accept_by_admin = 0;
                    $payment->packageHistory->update();
                }
                throw new \Exception($message);
            }

            $paymentResponse->user_id      = $payment->user_id;

            $refId                 = @$payment->ref_id;
            $payment->user->update(['package_id' => intval($payment->package_id)]);
            $payment->status       = "completed";
            $payment->payment_type = @$paymentStatus->PayType;
            $payment->track_id     = @$paymentStatus->TrackId;
            $payment->payed_amount = @$paymentStatus->Amount;
            $payment->description  = @$paymentStatus->Message;
            $payment->update();
            $payment->packageHistory->accept_by_admin = 1;
            $payment->packageHistory->is_payed        = 1;
            $payment->packageHistory->update();
            $paymentResponse->payment_id   = $payment->id;

            $order             = new OrderTransaction();
            $order->api_ref_id = @$refId;
            $order->payment_id = @$payment->id;
            $order->user_id    = @$payment->user_id;
            $order->type       = @$payment->packageHistory->type;
            $order->package_id = @$payment->package_id;
            // $order->presult    = $paymentStatus;
            // $order->postdate   = $paymentStatus;
            $order->tranid     = @$paymentStatus->TransactionId;
            $order->auth       = @$paymentStatus->AuthCode;
            $order->ref        = @$paymentStatus->ReferenceId;
            $order->trackid    = @$paymentStatus->TrackId;
            $order->amt        = @$paymentStatus->Amount;
            $order->udf1       = @$paymentStatus->MerchUdf1;
            $order->udf2       = @$paymentStatus->MerchUdf2;
            $order->udf3       = @$paymentStatus->MerchUdf3;
            $order->udf4       = @$paymentStatus->MerchUdf4;
            $order->udf5       = @$paymentStatus->MerchUdf5;
            $order->pdate      = @$paymentStatus->PostDate;
            $order->accept     = 1;
            $order->save();

            return view("site.pages.payment", compact('hideHeaderAndFooter','message', 'refId', 'paymentResponse', 'payment', 'paymentStatus'));
        } catch (\Exception $e) {
            // dd($e, @$paymentStatus, @$payment);
            $message = $e->getMessage() ?? 'Payment Failed!';
            $unsuccessful = true;
            return view("site.pages.payment", compact('hideHeaderAndFooter','unsuccessful','message','paymentStatus','payment', 'paymentStatus'));
        } finally {
            $paymentResponse->save();
        }
    }

    public function getPaymentStatus($locale,$payId){
        $cbkPay = new CBKPay();
        return $cbkPay->getPaymentStatusDetailsAlt($payId);
    }

    public function makeRefId($userId)
    {
        return substr(time(), 5, 4) . rand(1000, 9999) . $userId;
    }


    // private function MYFToken()
    // {
    //     $curl = curl_init();
    //     curl_setopt($curl, CURLOPT_URL, env('ISLIVE', true) ? 'https://apikw.myfatoorah.com/Token' : 'https://apidemo.myfatoorah.com/Token');
    //     curl_setopt($curl, CURLOPT_POST, 1);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //     curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('grant_type' => 'password', 'username' => env('MF_USERNAME'), 'password' => env('MF_PASSWORD'))));
    //     $result = curl_exec($curl);
    //     curl_close($curl);
    //     $json = json_decode($result, true);
    //     if (isset($json['access_token']) && !empty($json['access_token'])) {
    //         return $json['access_token'];
    //     } else
    //         throw new \Exception(__('throttle', ['seconds' => 30]));
    // }

    // public function paymentResult(Request $request)
    // {
    //     if (empty($request->paymentId)) {
    //         return redirect('/' . app()->getLocale() . '/');
    //     }
    //     $url =  (env('ISLIVE', true) ? 'https://apikw.myfatoorah.com/ApiInvoices/Transaction/' : 'https://apidemo.myfatoorah.com/ApiInvoices/Transaction/') . $request->paymentId;
    //     $soap_do1 = curl_init();
    //     curl_setopt($soap_do1, CURLOPT_URL, $url);
    //     curl_setopt($soap_do1, CURLOPT_CONNECTTIMEOUT, 10);
    //     curl_setopt($soap_do1, CURLOPT_TIMEOUT, 10);
    //     curl_setopt($soap_do1, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($soap_do1, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($soap_do1, CURLOPT_SSL_VERIFYHOST, false);
    //     curl_setopt($soap_do1, CURLOPT_POST, false);
    //     curl_setopt($soap_do1, CURLOPT_POST, 0);
    //     curl_setopt($soap_do1, CURLOPT_HTTPGET, 1);
    //     curl_setopt($soap_do1, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Accept: application/json', 'Authorization: Bearer ' . $this->MYFToken()));
    //     $result_in = curl_exec($soap_do1);
    //     $err_in = curl_error($soap_do1);
    //     $file_contents = htmlspecialchars(curl_exec($soap_do1));
    //     curl_close($soap_do1);
    //     $getRecorById = json_decode($result_in, true);
    //     $payment = Payment::with(['package', 'packageHistory', 'user'])->where('pay_id', $getRecorById['InvoiceId'])->first();
    //     $order = DB::table('tbl_transaction_api')->where("trackid", $getRecorById['InvoiceId'])->first();
    //     $refId = null;
    //     $message = $getRecorById['Error'];
    //     $trackid = $getRecorById['InvoiceId'];
    //     if ($payment) {
    //         $refId = $payment->ref_id;
    //         if (!empty($getRecorById['TransactionStatus']) && $getRecorById['TransactionStatus'] == 2) {
    //             $payment->status = "completed";
    //             $payment->packageHistory->accept_by_admin = 1;
    //             $payment->packageHistory->is_payed = 1;
    //             \App\User::find($payment->user->id)->update(['package_id' => intval($payment->package_id)]);
    //         } else {
    //             $payment->status = "failed";
    //             $payment->packageHistory->accept_by_admin = 0;
    //         }
    //         $payment->description = $getRecorById['Error'];
    //         $payment->update();
    //         $payment->packageHistory->update();
    //         //event(new \App\Events\Payment($message, $payment, $refId, $trackid));

    //     }
    //     return view("site.pages.payment", compact('message', 'refId', 'trackid', 'payment', 'order'));
    // }

    public function getAreas()
    {
        return app()->getLocale() == 'en' ?  Area::orderBy('name_en')->get() : Area::orderBy('name_ar')->get();
    }

    public function confirmReport($locale, $type, $id)
    {
        if ($type == 'ad') {
            $action = '/' . app()->getLocale() . '/advertising/' . $id . '/report';
            $method = 'GET';
            $confirmMsg = trans('why_report_title').' '.trans('advertising_title');
        } else {
            $action = '/' . app()->getLocale() . '/company/' . $id . '/report';
            $method = 'GET';
            $confirmMsg = trans('why_report_title').' '.trans('user');
        }
        session()->put('prev_url', url()->previous());
        return view('site.pages.confirmReport', compact('action', 'type', 'id', 'confirmMsg','method'));
    }

    public function confirmBlock($locale, $type, $id)
    {
        if ($type == 'ad') {
            $action = '/' . app()->getLocale() . '/advertising/' . $id . '/block';
            $method = 'GET';
            $confirmMsg = trans('sure_block').' '.trans('advertising_title');
        } else {
            $action = '/' . app()->getLocale() . '/company/' . $id . '/block';
            $method = 'GET';
            $confirmMsg = trans('sure_block').' '.trans('user');
        }
        session()->put('prev_url', url()->previous());
        return view('site.pages.confirm', compact('action', 'type', 'id', 'confirmMsg','method'));
    }

    public function cardAction($locale, Advertising $ad){
        return view('site.pages.advertising.cardAction', compact('ad'));
    }


    public function thumbanail($file){
        $mainPatch = public_path('resources/uploads/images/'.$file);
        $thumbPatch = public_path('resources/uploads/images/thumb/');
        if ( file_exists($mainPatch) ){
            \Illuminate\Support\Facades\File::ensureDirectoryExists($thumbPatch);
            $img = Image::make($mainPatch);
            $img->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbPatch.$file);
            $response = Response::make($img, 200);
            $response->header("Content-Type", $img->mime());
            return $response;
        }
        abort(404);
    }

    public function resizeAbleThumbnail($width = null , $height = null , $file = 'xx.pdf'){
        $mainPatch = public_path('resources/uploads/images/'.$file);
        $thumbPatch = public_path('resources/thumb/'.$width.'x'.$height.'/resources/uploads/images/');
        if ( file_exists($mainPatch) ){
            $width = (int) $width > 0 ? (int) $width : null;
            $height = (int) $height > 0 ? (int) $height : null;
            \Illuminate\Support\Facades\File::ensureDirectoryExists($thumbPatch);
            $img = Image::make($mainPatch);
            $img->orientate()->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbPatch.$file);
            $response = Response::make($img, 200);
            $response->header("Content-Type", $img->mime());
            return $response;
        }
        abort(404);
    }

}
