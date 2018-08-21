<?php

namespace App\Http\Controllers;

use App\Mail\UserRegisteredEmail;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class TestController extends Controller
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

    }
    public function mailable()
    {
        $user = $this->userRepository->find(1);
        return new UserRegisteredEmail($user);

    }
}
