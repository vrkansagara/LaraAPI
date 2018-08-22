<div>
    User: {{ $user->name}} <br>
    Email: {{ $user->email}}

    Kindly verify you email address  <a href="{{url(route('user.register.verify',$userToken))}}">Verify</a>
</div>