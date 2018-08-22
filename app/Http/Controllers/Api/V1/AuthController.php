<?php

namespace App\Http\Controllers\Api\V1;


use App\Events\UserRegistered;
use App\Http\Controllers\Api\ApiController;
use App\Repositories\UserRepository;
use App\Rules\Password\StrongPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Parser;
use Prettus\Repository\Exceptions\RepositoryException;


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
            'token' => $value
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
            'is_term_agree' => 'required|boolean'
        ];
        $validator = $this->validate($request, $rules);

        if ($validator->fails()) {
            return $this->response($validator);
        }
        $payload['password'] = Hash::make($payload['password']);

        try {
            DB::beginTransaction();
            $user = $this->userRepository->create($payload);
            DB::commit();
            $responseData = [
                'message' => 'User registered successfully!!!'
            ];
        } catch (RepositoryException $exception) {
            DB::rollBack();
            $responseData = [
                'error' => $exception
            ];
        }
        event(new UserRegistered($user));

        return $this->response($responseData);
    }

    public function registerUserVerifyAction($userToken)
    {
        if (!empty($userToken)) {
            $userObject = decrypt($userToken);
        }
        if (isset($userObject['time']) && $userObject['time'] instanceof Carbon) {
            $diffInHours = Carbon::now()->diffInHours($userObject['time']);
//            $diffInMinutes = Carbon::now()->diffInMinutes($userObject['time']);

            // Set for withing three days
            if ($diffInHours <= config('auth.register.expire_in.hours')) {
                $userData = [
                    'email' => $userObject['email'],
                    'is_active' => 0,
                    'is_confirm' => 0
                ];
                $user = $this->userRepository->findWhere($userData)->first();
                if ($user) {
                    try {
                        $userData = [
                            'is_active' => 1,
                            'is_confirm' => 1
                        ];
                        DB::beginTransaction();
                        $this->userRepository->update($userData, $user->id);
                        DB::commit();
                        $responseData = [
                            'message' => 'User activated successfully!!!'
                        ];
                    } catch (RepositoryException $exception) {
                        DB::rollBack();
                        $responseData = [
                            'error' => $exception
                        ];
                    }
                } else {
                    $responseData = [
                        'error' => [
                            'message' => 'You are already registerd with us.'
                        ]

                    ];
                }

            } else {
                $responseData = [
                    'error' => 'Kindly contact to administrator'
                ];
            }
        }

        return $this->response($responseData);
    }


    /**
     * Resent password once user token generated.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPasswordVerifyAction(Request $request)
    {
        $payload = $request->all();
        $rules = [
            'email' => 'required|email',
            'password' => [
                'required',
                'min:8',
                'max:80',
                new StrongPassword()
            ],
            'password_confirm' => 'required|same:password',
        ];
        $validator = $this->validate($request, $rules);

        if ($validator->fails()) {
            return $this->response($validator);
        }
        $user = $this->userRepository->findByField('email', $payload['email'])->first();
        if ($user) {
            $updateData = [
                'password' => Hash::make($payload['password'])
            ];
            try {

                DB::beginTransaction();
                $this->userRepository->update($updateData, $user->id);
                DB::commit();
                $responseData = [
                    'message' => 'Password updated successfully!!!'
                ];
            } catch (RepositoryException $exception) {
                DB::rollBack();
                $responseData = [
                    'error' => $exception
                ];
            }


        } else {
            $responseData = [
                'error' => [
                    'message' => 'User not found!!!'
                ]
            ];
        }


        return $this->response($responseData);
    }
}
