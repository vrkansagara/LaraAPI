<?php

namespace App\Http\Controllers\Api;


use App\Events\UserRegistered;
use App\Repositories\UserRepository;
use App\Rules\Password\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Parser;


class AuthController extends ApiController
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

    }

    public function loginAction(Request $request)
    {
        $payload = $request->all();
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
        $validator = $this->validate($request, $rules);

        if ($validator->fails()) {
            return $this->response($validator);
        }

        if (Auth::attempt(['email' => $payload['email'], 'password' => $payload['password']])) {
            $user = Auth::user();
            $data = [
                'message' => 'Successfully logged',
                'token' => $user->createToken('MyApp')];
        } else {
            $data = ['error' => 'Unauthorised'];
        }
        return $this->response($data);
    }

    public function logoutAction(Request $request)
    {

        $value = $request->bearerToken();
        $id = (new Parser())->parse($value)->getHeader('jti');

        /**
         * @todo
         * @fixme
         * User coding standard practise.
         */
        DB::table('oauth_access_tokens')
            ->where('id', $id)
            ->update([
                'revoked' => true
            ]);
        $data = [
            'message' => 'You are successfully logout.',
            'token' => $request->headers->get('Authorization')
        ];

        return $this->response($data);
    }

    public function registerAction(Request $request)
    {
        $payload = $request->all();
        $rules = [
            'name' => 'required|min:8|max:80',
            'email' => 'required|unique:users,email',
            'password' => [
                'required',
                'min:8',
                'max:80',
                new StrongPassword()
            ],
            'password_confirm' => 'required|same:password',
            'is_term_agree' => 'required|boolean',
            'created_by' => 'required',

        ];
        $validator = $this->validate($request, $rules);

        if ($validator->fails()) {
            return $this->response($validator);
        }
        $payload['password'] = Hash::make($payload['password']);
        $user = $this->userRepository->create($payload);

        event(new UserRegistered($user));

        return $this->response($user);

    }
}
