<?php

namespace App\Http\Controllers\ProviderResources;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Controllers\Controller;

// use Auth;
use Setting;
// use Storage;
use Exception;
use Carbon\Carbon;
use App\Provider;
use App\ProviderProfile;
use App\UserRequests;
use App\ProviderService;
use App\Fleet;
use App\FleetCities;
use App\RequestFilter;
use App\Document;
use App\Reason;
use App\Http\Controllers\SendPushNotification;
use App\Http\Controllers\ProviderResources\DocumentController;
use App\Http\Controllers\Resource\ReferralResource;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Create a new user instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('provider.api', ['except' => ['show', 'store', 'available', 'location_edit', 'location_update', 'stripe', 'verifyCredentials']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @type `GET`
     * @endpoint `/api/provider/profile`
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $provider = Auth::user();
            $provider->service = ProviderService::where('provider_id', $provider->id)
                ->with('service_type')
                ->first();
            $provider->fleet = Fleet::find($provider->fleet);
            $provider->currency = config('constants.currency', '$');
            $provider->sos = config('constants.sos_number', '911');
            $provider->measurement = config('constants.distance', 'Kms');
            $provider->profile = ProviderProfile::where('provider_id', $provider->id)
                ->first();

            $align = '';

            if ($provider->profile != null) {
                app()->setLocale($provider->profile->language);
                $align = ($provider->profile->language == 'ar') ? 'text-align: right' : '';
            }

            $provider->cash = (int)config('constants.cash');
            $provider->card = (int)config('constants.card');

            //TODO ALLAN - Alterações débito na máquina e voucher
            $provider->pic_pay = (int)config('constants.pic_pay');
            $provider->debit_machine = (int)config('constants.debit_machine');
            $provider->referral_count = config('constants.referral_count', '0');
            $provider->referral_amount = config('constants.referral_amount', '0');
            $provider->referral_text = trans('api.provider.invite_friends');
            $provider->referral_total_count = (new ReferralResource)->get_referral('provider', $provider->id)[0]->total_count;
            $provider->referral_total_amount = (new ReferralResource)->get_referral('provider', $provider->id)[0]->total_amount;
            $provider->referral_total_text = "<p style='font-size:16px; color: #000; $align'>" . trans('api.provider.referral_amount') . ": " . (new ReferralResource)->get_referral('user', $provider->id)[0]->total_amount . "<br>" . trans('api.provider.referral_count') . ": " . (new ReferralResource)->get_referral('user', $provider->id)[0]->total_count . "</p>";
            $provider->ride_otp = (int)config('constants.ride_otp');
            //(new ReferralResource)->get_referral('provider', $provider->id)
            if ($provider->financial) {
                $provider->financial = json_decode($provider->financial);
            }
            return $provider;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Properly fills the provider profile with the given parameters.
     * 
     * @param Provider &$provider the provider
     * @param Request $request the request
     * @param array $fields the request fields to be filled
     * 
     */
    private function fillProvider(Provider &$provider, Request $request, array $fields)
    {
        foreach ($fields as $field) {
            if ($request->has($field)) {
                if ($field === 'financial') {
                    $provider->{$field} = json_encode($request->input($field));
                } else
                    $provider->{$field} = $request->input($field);
            }
        }

        if ($request->has('mobile')) {
            $FleetCities = FleetCities::where('city_id', $provider->city_id)->first();
            if ($FleetCities->city_id) {
                $provider->fleet = $FleetCities->fleet_id;
            }

            // $fileName = Helper::upload_qrCode($request->mobile, $file);
            $provider->qrcode_url = "";
        }
        if ($request->hasFile('avatar')) {
            Storage::delete($provider->avatar);
            $provider->avatar = $request->avatar->store('provider/profile');
        }

        if ($request->has('service_type')) {
            if ($provider->service) {
                if ($provider->service->service_type_id != $request->service_type) {
                    $provider->status = 'banned';
                }
                $provider->service->service_type_id = $request->service_type;
                $provider->service->service_number = $request->service_number;
                $provider->service->service_model = $request->service_model;
                $provider->service->save();
            } else {
                ProviderService::create([
                    'provider_id' => $provider->id,
                    'service_type_id' => $request->service_type,
                    'service_number' => $request->service_number,
                    'service_model' => $request->service_model,
                ]);
                $provider->status = 'banned';
            }
        }
    }

    /**
     * Validates the Provider's inputs in the current request
     * 
     * @param Request $request
     * @param boolean $update
     * @return boolean success state
     * @throws ValidationException
     */
    private function validateProvider(Request $request, bool $update = false)
    {
        try {
            $this->validate($request, [
                'first_name' => !$update ? 'required|max:255' : 'max:255',
                'last_name' => !$update ? 'required|max:255' : 'max:255',
                'cpf_cnpj' => 'max:255',
                'avatar' => 'mimes:jpeg,bmp,png',
                'language' => 'max:255',
                'address' => 'max:255',
                'address_secondary' => 'max:255',
                'city' => 'max:255',
                'country' => 'max:255',
                'postal_code' => 'max:255',
                'financial' => 'max:255'
            ]);
            return true;
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    /**
     * Upsert a Provider into the database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($this->validateProvider($request)) {
                /**
                 * @var Provider $provider
                 */
                $provider = Auth::user();
                if ($provider && $provider instanceof Provider) {
                    $this->fillProvider($provider, $request, [
                        'first_name',
                        'last_name',
                        'cpf_cnpj',
                        'avatar',
                        'financial',
                    ]);
                    if ($provider->profile) {
                        $provider->profile->update([
                            'language' => $request->language ?? $provider->profile->language,
                            'address' => $request->address ?? $provider->profile->address,
                            'address_secondary' => $request->address_secondary ?? $provider->profile->address_secondary,
                            'city' => $request->city ?? $provider->profile->city,
                            'country' => $request->country ?? $provider->profile->country,
                            'postal_code' => $request->postal_code ?? $provider->profile->postal_code,
                        ]);
                    } else {
                        ProviderProfile::create([
                            'provider_id' => $provider->id,
                            'language' => $request->language,
                            'address' => $request->address,
                            'address_secondary' => $request->address_secondary,
                            'city' => $request->city,
                            'country' => $request->country,
                            'postal_code' => $request->postal_code,
                        ]);
                    }

                    $provider->save();
                    return back()->with('flash_success', trans('api.user.profile_updated'));
                } else {
                    throw new Exception("User not found.", 422);
                }
            }
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage(), 'fields' => $e->errors()], $e->status);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Error $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('provider.profile.index');
    }

    /**
     * Sets the referrals props to the giver provider
     * 
     * @param Provider $provider the current provider
     */
    private function setProviderReferral(Provider &$provider)
    {
        $provider->referral_count = config('constants.referral_count', '0');
        $provider->referral_amount = config('constants.referral_amount', '0');
        $provider->referral_text = trans('api.provider.invite_friends');
        $provider->referral_total_count = (new ReferralResource)->get_referral('provider', $provider->id)[0]->total_count;
        $provider->referral_total_amount = (new ReferralResource)->get_referral('provider', $provider->id)[0]->total_amount;
        $provider->referral_total_text = "<p style='font-size:16px; color: #000;'>" . trans('api.provider.referral_amount');
        $provider->referral_total_text .= ": " . (new ReferralResource)->get_referral('user', $provider->id)[0]->total_amount . "<br>";
        $provider->referral_total_text .= trans('api.provider.referral_count') . ": ";
        $provider->referral_total_text .= (new ReferralResource)->get_referral('user', $provider->id)[0]->total_count . "</p>";
    }

    /**
     * Update the specified resource in storage.
     *
     * @type `POST`
     * @endpoint `/api/provider/profile`
     * @body 
     * ```json
     * {
     *   "first_name": "André2",
     *   "last_name": "Mury",
     *   "cpf_cnpj": "11924859637",
     *   "avatar": "",
     *   "language": "pr-BR",
     *   "address": "R. NDC 421",
     *   "address_secondary": "",
     *   "city": "Itajubá",
     *   "country": "Brazil",
     *   "postal_code": "37501132",
     *   "financial": {
     *       "bankName": "Nubank",
     *       "accountNumber": "2556611",
     *       "agency": "1",
     *       "type": "check"
     *   }
     * } 
     * ```
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            if ($this->validateProvider($request, true)) {
                /**
                 * @var Provider $provider
                 */
                $provider = Auth::user();
                if ($provider && $provider instanceof Provider) {
                    $this->fillProvider($provider, $request, [
                        'first_name',
                        'last_name',
                        'cpf_cnpj',
                        'avatar',
                        'financial',
                    ]);
                    if ($provider->profile) {
                        $provider->profile->update([
                            'language' => $request->language ?? $provider->profile->language,
                            'address' => $request->address ?? $provider->profile->address,
                            'address_secondary' => $request->address_secondary ?? $provider->profile->address_secondary,
                            'city' => $request->city ?? $provider->profile->city,
                            'country' => $request->country ?? $provider->profile->country,
                            'postal_code' => $request->postal_code ?? $provider->profile->postal_code,
                        ]);
                    } else {
                        ProviderProfile::create([
                            'provider_id' => $provider->id,
                            'language' => $request->language,
                            'address' => $request->address,
                            'address_secondary' => $request->address_secondary,
                            'city' => $request->city,
                            'country' => $request->country,
                            'postal_code' => $request->postal_code,
                        ]);
                    }

                    $provider->save();
                    $provider->service = ProviderService::where('provider_id', $provider->id)
                        ->with('service_type')->first();
                    $this->setProviderReferral($provider);

                    return $provider;
                } else {
                    throw new Exception("User not found.", 422);
                }
            }
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage(), 'fields' => $e->errors()], $e->status);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Error $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function update_details(Request $request)
    {
        if ($request->has('callid')) {
            if (is_null(Auth::user()->callid) || empty(Auth::user()->callid)) {
                $user = Provider::find(Auth::user()->id);
                $user->callid = $request->get('callid');
                $user->save();
            } else if (Auth::user()->callid != $request->get('callid')) {
                $user = Provider::find(Auth::user()->id);
                $user->callid = $request->get('callid');
                $user->save();
            }
        }
    }

    /**
     * Update latitude and longitude of the user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function location(Request $request)
    {
        $this->validate($request, [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($Provider = Auth::user()) {

            $Provider->latitude = $request->latitude;
            $Provider->longitude = $request->longitude;
            $Provider->save();

            return response()->json(['message' => trans('api.provider.location_updated')]);
        } else {
            return response()->json(['error' => trans('api.provider.provider_not_found')]);
        }
    }

    public function update_language(Request $request)
    {
        $this->validate($request, [
            'language' => 'required',
        ]);

        try {

            $Provider = Auth::user();

            if ($Provider->profile) {
                $Provider->profile->update([
                    'language' => $request->language ?: $Provider->profile->language
                ]);
            } else {
                ProviderProfile::create([
                    'provider_id' => $Provider->id,
                    'language' => $request->language,
                ]);
            }

            return response()->json(['message' => trans('api.provider.language_updated'), 'language' => $request->language]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => trans('api.provider.provider_not_found')], 404);
        }
    }

    /**
     * Toggle service availability of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request)
    {
        $this->validate($request, [
            'service_status' => 'required|in:active,offline',
        ]);

        $Provider = Auth::user();
        if ($Provider->service) {

            $provider = $Provider->id;
            $OfflineOpenRequest = RequestFilter::with(['request.provider', 'request'])
                ->where('provider_id', $provider)
                ->whereHas('request', function ($query) use ($provider) {
                    $query->where('status', 'SEARCHING');
                    $query->where('current_provider_id', '<>', $provider);
                    $query->orWhereNull('current_provider_id');
                })->pluck('id');

            if (count($OfflineOpenRequest) > 0) {
                RequestFilter::whereIn('id', $OfflineOpenRequest)->delete();
            }
            if ($Provider->status == 'approved')
                $Provider->service->update(['status' => $request->service_status]);
        } else {
            return response()->json(['error' => trans('api.provider.not_approved')]);
        }

        return $Provider;
    }

    /**
     * Update password of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
            'password_old' => 'required',
        ]);

        $Provider = Auth::user();

        if (password_verify($request->password_old, $Provider->password)) {
            $Provider->password = bcrypt($request->password);
            $Provider->save();

            return response()->json(['message' => trans('api.provider.password_updated')]);
        } else {
            return response()->json(['error' => trans('api.provider.change_password')], 422);
        }
    }

    /**
     * Show providers daily target.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function target(Request $request)
    {
        try {

            $Rides = UserRequests::where('provider_id', Auth::user()->id)
                ->where('status', 'COMPLETED')
                ->where('created_at', '>=', Carbon::today())
                ->with('payment', 'service_type')
                ->get();

            //\Log::info($Rides);

            return response()->json([
                'rides' => $Rides,
                'rides_count' => $Rides->count(),
                'target' => config('constants.daily_target', '0')
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    public function chatPush(Request $request)
    {

        $this->validate($request, [
            'user_id' => 'required|numeric',
            'message' => 'required',
        ]);

        try {

            $user_id = $request->user_id;
            $message = $request->message;

            $message = \PushNotification::Message($message, array(
                'badge' => 1,
                'sound' => 'default',
                'custom' => array('type' => 'chat')
            ));

            (new SendPushNotification)->sendPushToUser($user_id, $message);
            //(new SendPushNotification)->sendPushToProvider($user_id, $message);

            return response()->json(['success' => 'true']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //provider document list
    public function documents(Request $request)
    {
        try {

            $provider_id = Auth::user()->id;

            $Documents = Document::select('id', 'name', 'type')
                ->with(['providerdocuments' => function ($query) use ($provider_id) {
                    $query->where('provider_id', $provider_id);
                }])->get();

            return response()->json(['documents' => $Documents]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


    /**
     * Saves the file uploaded by the provider
     * 
     * @type `POST`
     * @endpoint `/api/provider/documents/store`
     * @param \Illuminate\Http\Request $request
     */
    public function documentstore(Request $request): JsonResponse
    {
        $this->validate($request, [
            'document' => 'required',
            'document.*' => 'mimes:jpg,jpeg,png|max:2048'
        ]);
        try {
            //\Log::info($request->all());

            if ($request->hasFile('document')) {

                return $this->uploadFileMultipart($request);
            } elseif (
                // Checks if the file was uploaded using base64 method
                $request->has('docTypeId')
                && preg_match('/data:image\/(jpg|jpeg|png);base64,/im', $request->input('document'))
            ) {
                // And then handle it
                return $this->uploadFileJson($request);
            } else {
                return response()->json(['error' => 'Invalid body'], 422);
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 422);
        }
    }

    /**
     * Upload a base64 file that may come in the body
     * 
     * @body `multipart/form-data`
     * ```json
     * {
     *      "document": "octetstream",
     *      "id": "The document  id" 
     * }
     * ```
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function uploadFileMultipart(Request $request)
    {
        foreach ($request->file('document') as $ikey => $image) {
            $ids = $request->input('id');
            $doc_id = $ids[$ikey];
            $provider_id = Auth::user()->id;
            (new DocumentController)->documentupdate($image, $doc_id, $provider_id);
        }

        if (config('constants.card', 0) == 1) {
            Provider::where('id', Auth::user()->id)->where('status', 'document')->update(['status' => 'onboarding']);
        } else {
            if (Setting::get('demo_mode', 0) == 1) {
                Provider::where('id', Auth::user()->id)->where('status', 'document')->update(['status' => 'approved']);
            } else {
                Provider::where('id', Auth::user()->id)->where('status', 'document')->update(['status' => 'onboarding']);
            }
        }

        return $this->documents($request);
    }

    /**
     * Upload a base64 file that may come in the body
     * 
     * @body 
     * ```json
     * {
     *      "document": "String",
     *      "type": "image/type",
     *      "file": "String",
     *      "docTypeId": "Integer" 
     * }
     * ```
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function uploadFileJson(Request $request)
    {
        /**
         * @var string $file base64 encoded file
         */
        $file = explode(',', $request->input('document'))[1];
        /**
         * @var string $fileName original file name
         */
        $fileName = $request->input('file');
        /**
         * @var string $fileType the file's mime type
         */
        $fileType = explode('/', $request->input('type'))[1];
        /**
         * @var int $docTypeId the document type id
         */
        $docTypeId = $request->input('docTypeId');

        /**
         * @var int $userId the current user id
         */
        $userId = Auth::user()->id;

        $documentCtl = new DocumentController();

        $filePath = tempnam(sys_get_temp_dir(), 'UploadedFile') . '.' . $fileType;
        file_put_contents($filePath, base64_decode($file));

        /**
         * @var Illuminate\Http\UploadedFile $image the decoded image
         */
        $image = new UploadedFile($filePath, $fileName, $fileType);
        /**
         * @var File $image the decoded image;
         */
        $documentCtl->documentupdate($image, $docTypeId, $userId, $fileType);
        if ($documentCtl) {
            return response()->json("ok", 200);
        } else {
            return response()->json(['error' => 'Error while uploading file.'], 422);
        }
    }

    public function stripe(Request $request)
    {
        if (isset($request->code)) {
            $post = [
                'client_secret' => config('constants.stripe_secret_key'),
                'code' => $request->code,
                'grant_type' => 'authorization_code'
            ];
            $curl = curl_init("https://connect.stripe.com/oauth/token");
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            $curl_error = curl_error($curl);
            curl_close($curl);
            $stripe = json_decode($result);

            if ($stripe->stripe_user_id) {
                $provider = Provider::where('id', Auth::user()->id)->first();
                $provider->stripe_acc_id = $stripe->stripe_user_id;
                $provider->save();

                if ($request->ajax()) {
                    return response()->json(['message' => 'Your stripe account connected successfully']);
                } else {
                    return redirect('/provider')->with('flash_success', 'Your stripe account connected successfully');
                }
            } else {
                if ($request->ajax()) {
                    return response()->json(['message' => $curl_error]);
                } else {
                    return redirect('/provider')->with('flash_error', $curl_error);
                }
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => $request->error_description]);
            } else {
                return redirect('/provider')->with('flash_error', $request->error_description);
            }
        }
    }

    public function reasons(Request $request)
    {
        $reason = Reason::where('type', 'PROVIDER')->where('status', 1)->get();

        return $reason;
    }

    public function verifyCredentials(Request $request)
    {

        if ($request->has("mobile")) {
            $Provider = Provider::where([['country_code', $request->input("country_code")], ['mobile', $request->input("mobile")]])
                ->first();
            if ($Provider != null) {
                return response()->json(['message' => trans('api.mobile_exist')], 422);
            }
        }

        if ($request->has("email")) {
            $Provider = Provider::where('email', $request->input("email"))->first();
            if ($Provider != null) {
                return response()->json(['message' => trans('api.email_exist')], 422);
            }
        }

        return response()->json(['message' => trans('api.available')]);
    }
}
