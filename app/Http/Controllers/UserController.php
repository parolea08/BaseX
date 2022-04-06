<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;


/*
 * Controller to handle user register, login etc.
 * May be replaced by Laravel built-in feature later on (e.g. Laravel Breeze)
 */
class UserController extends Controller
{

    /**
   * Register user
   *
   * @param  Request $request
   * @return \Illuminate\View\View
   */
  public function register(Request $request)
  {
    if($request->pw !== $request->pwrepeated) {

      return view('register', [
        'message' => 'The given passwords are not matching!'
      ]);

    } else {

      $username = $request->user;
      $email = $request->email;
      $pw = Crypt::encryptString($request->pw);

      $users = DB::table('users');
      $user = $users->where('username', $username)
        ->orWhere('email', $email)->get();

      if($user->isNotEmpty()) {

        return view('register', [
          'message' => 'Username or E-Mail already has been registered!'
        ]);

      } else {

        User::create([
          'username' => $username,
          'email' => $email,
          'password' => $pw,
        ]);

        session()->put('loggedin', true);
        session()->put('user', $username);

        return view('dashboard', [
          'message' => 'You have been registered successfully'
        ]);
      }
    }
  }

    /**
   * Login user
   *
   * @param  Request $request
   * @return \Illuminate\View\View
   */
  public function login(Request $request)
  {

    $username = $request->user;
    $pw = $request->pw;

    $user = DB::table('users')->where('username', $username)->get();

    if($user->isEmpty()) {

      return view('login', [
        'message' => 'User could not been found. Make sure to enter an existing username'
      ]);

    } else {

      if($pw !== Crypt::decryptString($user->first()->password)) {

        return view('login', [
          'message' => 'The entered password is not correct'
        ]);

      } else {

        session()->put('loggedin', true);
        session()->put('user', $username);

        return redirect()->route('dashboard');
      }
    }
  }

    /**
   * Logout user
   *
   * @param  Request $request
   * @return \Illuminate\View\View
   */
  public function logout(Request $request)
  {
    session()->flush();

    return view('dashboard', [
      'message' => 'You have been logged out'
    ]);

  }
}
