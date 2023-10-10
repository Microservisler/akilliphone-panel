<?php

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class WebService
{
    const WEBSERVICE_URL = 'http://api.duzzona.site/';
    const AUTH_URL = 'http://auth.akillimagaza.com/connect/token';
    protected $userName = '';
    protected $userPassword = '';

    public static function checkUser($username, $password)
    {
        $result = [
            'token'=>false,
            'tokenData'=>false,
            'user'=>[],
            'error'=>false,
        ];
        try{
            $token = self::TOKEN($username, $password);
            if($token){
                $tokenData = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
                if($tokenData){
                    $tokenData = (array)$tokenData;
                    if(isset($tokenData['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/nameidentifier'])){
                        $userId = $tokenData['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/nameidentifier'];
                        $user = self::getUser($userId, $token);
                        if($user){
                            $user['jwtToken'] = $token;
                            $user['jwtExp'] = $tokenData['exp'];
                            $result['tokenData'] = $tokenData;
                            $result['user'] = $user;
                            $result['token'] = $token;
                        } else {
                            $result['error'] = 'Kullanıcı Bilgisi alınamadı';
                        }
                    } else {
                        $result['error'] = 'Kullanıcı Idsi alınamadı';
                    }
                } else {
                    $result['error'] = 'Kullanıcı Tokenı hatalı';
                }
            }  else {
                $result['error'] = 'Kullanıcı Tokenı alınamadı';
            }
        } catch (\Exception $ex){
            $result['error'] = $ex->getMessage();
        }
        return $result;
    }
    public  static function getUser($userId, $token){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(self::WEBSERVICE_URL.'user', ['userId'=>$userId]);
        return $response['data'];
    }
    public static function isLogged(){
        return request()->session()->get('user', null);
    }
    public static function logout()
    {
        request()->session()->put('jwtToken', null);
        request()->session()->put('user', null);
    }
    public static function login($user, $tokenData)
    {
        request()->session()->put('jwtToken', $user['jwtToken']);
        request()->session()->put('user', $user);
    }


    static private function TOKEN($username, $password){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . request()->session()->get('token', null),
        ])->post(self::WEBSERVICE_URL.'login', ['username'=>$username, 'password'=>$password]);
        $responseData = json_decode($response->body(), true);

        if(isset($responseData['token'])){
            return $responseData['token'];
        }
        return null;
    }
    static private function GET($service, $data){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . request()->session()->get('token', null),
        ])->get(self::WEBSERVICE_URL.$service, $data);
        return self::standartResponse($response);
    }
    static private function POST($service, $data){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . request()->session()->get('token', null),
        ])->post(self::WEBSERVICE_URL.$service, $data);
        return self::standartResponse($response);
    }
    static private function standartResponse($response){
        $responseData = json_decode($response->body(), true);
        if($responseData ){
            $result['status'] = 1;
            $result['data'] =  isset($responseData['data'])?$responseData['data']:[];
            $result['errors'] = isset($responseData['errors'])?$responseData['errors']:[];
        } else {
            $result['status'] = 0;
            $result['data'] =  [];
            $result['errors'] = [];
        }
        return $result;
    }

    static function userToAuthUser($user){
        $authUser = new \App\Models\User();

        $authUser->id = $user['id'];
        $authUser->userName = $user['userName'];
        $authUser->normalizedUserName = $user['normalizedUserName'];
        $authUser->email = $user['email'];
        $authUser->normalizedEmail = $user['normalizedEmail'];
        $authUser->emailConfirmed = $user['emailConfirmed'];
        $authUser->passwordHash = $user['passwordHash'];
        $authUser->securityStamp = $user['securityStamp'];
        $authUser->concurrencyStamp = $user['concurrencyStamp'];
        $authUser->phoneNumber = $user['phoneNumber'];
        $authUser->phoneNumberConfirmed = $user['phoneNumberConfirmed'];
        $authUser->twoFactorEnabled = $user['twoFactorEnabled'];
        $authUser->lockoutEnd = $user['lockoutEnd'];
        $authUser->lockoutEnabled = $user['lockoutEnabled'];
        $authUser->accessFailedCount = $user['accessFailedCount'];
        $authUser->code = $user['code'];
        $authUser->firstName = $user['firstName'];
        $authUser->passwordS = $user['passwordS'];
        $authUser->tcKimlik = $user['tcKimlik'];
        $authUser->telefon = $user['telefon'];
        $authUser->lastName = $user['lastName'];
        $authUser->userId = $user['userId'];
        $authUser->active = $user['active'];
        $authUser->privateDiscountType = $user['privateDiscountType'];
        $authUser->newsletter = $user['newsletter'];
        $authUser->shippingAddressId = $user['shippingAddressId'];
        $authUser->hasDropshippingPermission = $user['hasDropshippingPermission'];
        $authUser->invoiceAddressId = $user['invoiceAddressId'];
        $authUser->birthDate = $user['birthDate'];
        $authUser->basket = $user['basket'];
        $authUser->addresses = $user['addresses'];
        $authUser->billingAddresses = $user['billingAddresses'];
        $authUser->customerFavorites = $user['customerFavorites'];
        $authUser->orderCustomers = $user['orderCustomers'];
        $authUser->orderHistories = $user['orderHistories'];
        $authUser->orders = $user['orders'];
        $authUser->shippingAddresses = $user['shippingAddresses'];
        $authUser->jwtToken = $user['jwtToken'];
        $authUser->jwtExp = $user['jwtExp'];
        return $authUser;
    }
}