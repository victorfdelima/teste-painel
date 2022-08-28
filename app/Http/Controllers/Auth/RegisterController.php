<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\ServiceType;
use App\Helpers\Helper;
use App\Http\Controllers\Resource\ReferralResource;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/*
*--------------------------------------------------------------------------
* Register Controller
*--------------------------------------------------------------------------
*
* This controller handles the registration of new users as well as their
* validation and creation. By default this controller uses a trait to
* provide this functionality without requiring any additional code.
*
*/

class RegisterController extends Controller
{

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string $redirectTo
     */
    protected $redirectTo = '/painel';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('user.auth.register');
    }

    public function ride()
    {
        $services = ServiceType::get();
        return view('ride', compact('services'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'phone_number' => 'required',
            'country_code' => 'required',
            'cpf_cnpj' => 'required|max:20',
            'email' => 'required|email|max:100',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Checks if the given email address is already registered in the database.
     * 
     * @param string $email the email
     * @return boolean true if registered and false if not
     */
    private function verifyEmailRegistration(string $email): bool
    {
        try {
            return !!User::where('email', $email)->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Checks if the given phone number is already registered in the database.
     * 
     * @param string $countryCode the country code
     * @param string $phone the phone number
     * @return boolean true if registered and false if not
     */
    private function verifyPhoneRegistration(string $countryCode, string $phone): bool
    {
        try {
            return !!User::where(['country_code' => $countryCode, 'mobile' => $phone])->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a QrCode image and saves it, returning its file name
     * 
     * @param string $countryCode the country code
     * @param string $phone the phone number
     * @return string the file name
     */
    private function saveQrCodeAndGetFileName(string $countryCode, string $phone): string
    {
        /**
         * @var array $qrCodePayload the qrcode data
         */
        $qrCodePayload = [
            'country_code' => $countryCode,
            'phone_number' => $phone
        ];

        $qrCodeFile = QrCode::format('png')->size(500)->margin(10)
            ->generate(json_encode($qrCodePayload));

        /**
         * @var string $qrCodeFileName the file name
         */
        $qrCodeFileName = Helper::upload_qrCode($phone, $qrCodeFile);

        file_put_contents(public_path() . '/' . $qrCodeFileName, $qrCodeFile);

        return $qrCodeFileName;
    }


    private function getReferralCode(): string
    {
        return (new ReferralResource)->generateCode();
    }

    /** 
     * Creates the referral and returns its code if this option is active at constants.referral
     * 
     * @param string $referralCode the given referral code
     * @param App\User $user the User
     * @return string the generated referral code
     */
    private function createReferral(string $referralCode, User $user): string
    {
        // Checks if referral config is active
        if (config('constants.referral', 0) == 1) {
            /**
             * @var ReferralResource $referralResource the referral resource instance
             */
            $referralResource = new ReferralResource();
            // checks if referral_code exists
            $referralResource->create_referral($referralCode, $user);
            $validate['referral_unique_id'] = $referralCode;
            /**
             * @var \Illuminate\Contracts\Validation\Validator $validator the validator
             */
            $validator = $referralResource->checkReferralCode($validate);
            if ($validator->fails()) {
                $validator->errors()->add('referral_code', 'Invalid Referral Code');
                throw new \Illuminate\Validation\ValidationException($validator);
            } else return true;
        }

        return false;
    }

    /**
     * Creates a new User in the platform using the '/register' route
     * This is the method used to signup new ordinary users onto the platform
     *
     * @type `POST`
     * @endpoint `/register`
     * @param array $data the user data
     * @return App\User
     */
    protected function create(array $data): User
    {
        /**
         * @var User the current User instance
         */
        $validator  = Validator::make([], [], []);

        // Here occours the user registration
        if (!$this->verifyEmailRegistration($data['email'])) {
            if (!$this->verifyPhoneRegistration($data['country_code'], $data['phone_number'])) {

                /**
                 * @var string $phone the c_typed phone number
                 */
                $phone = preg_replace('/\D/m', '', $data['phone_number']);

                // /**
                //  * @var string $qrCodeFile the qr code file name
                //  */
                // $qrCodeFile = $this->saveQrCodeAndGetFileName($data['country_code'], $phone);

                /**
                 * @var string $password the encrypted password
                 */
                $password = bcrypt($data['password']);

                /**
                 * @var string $referralUniqueId the code generate uniquely to this user's referral
                 */
                $referralUniqueId = $this->getReferralCode();

                $user = User::create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'gender' => $data['gender'] ?? 'MALE',
                    'country_code' => $data['country_code'],
                    'cpf_cnpj' => $data['cpf_cnpj'],
                    'mobile' => $phone,
                    'password' => $password,
                    'referral_unique_id' => $referralUniqueId,
                    'qrcode_url' => '',
                    'payment_mode' => 'CASH',
                    'user_type' => 'NORMAL'
                ]);
                // Verifies if the user was created
                if ($user) {
                    // and if it was, creates the referral
                    $this->createReferral($referralUniqueId, $user);
                    // and return the user instance
                    return $user;
                } else {
                    throw new \Error("Ocorreu um erro ao tentar registrar este usuário.", 500);
                }

                //        if(config('constants.send_email', 0) == 1) {
                //            // send welcome email here
                //            Helper::site_registermail($User);
                //        }    

            } else {
                $validator->errors()->add('mobile', 'Este número de telefone já foi registrado');
                throw new \Illuminate\Validation\ValidationException($validator);
            }
        } else {
            $validator->errors()->add('email', 'Este e-mail já foi registrado!');
            throw new \Illuminate\Validation\ValidationException($validator);
        }
    }
}
