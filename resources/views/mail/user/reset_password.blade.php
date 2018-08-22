<div>
    User: {{ $user->name}} <br>
    Email: {{ $user->email}}

    Kindly reset your password using <a href="{{url(route('user.reset.password.verify',$userToken))}}">this link</a>
</div>