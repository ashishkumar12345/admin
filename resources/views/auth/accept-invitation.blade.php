@extends('layouts.app')

@section('content')
<div style="max-width: 400px; margin: auto;">
    <h3>Accept Invitation</h3>
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('invitation.accept', $invitation->token) }}">
        @csrf
        
        <div>
            <label>Name:</label><br>
            <input type="text" name="name" value="{{ $invitation->name}}" required style="width: 100%;" readonly>
        </div><br>

        <div>
            <label>Password:</label><br>
            <input type="password" name="password" required style="width: 100%;">
        </div><br>

        <div>
            <label>Confirm Password:</label><br>
            <!-- Confirmation ke liye input ka name strictly 'password_confirmation' hona zaroori hai -->
            <input type="password" name="password_confirmation" required style="width: 100%;">
        </div><br>

        <button type="submit" style="width: 100%;">Create Account</button>
    </form>
</div>
@endsection