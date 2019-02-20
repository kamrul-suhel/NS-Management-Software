<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ApiResponser;

    //

    public function login(Request $request)
    {
        $email_login = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        if ((!Auth::attempt($email_login))) {
            $redirect = ($request->input('redirect')) ? '?redirect=' . $request->input('redirect') : '';

            $error = [
                'note' => 'Invalid login, please try again.',
                'note_type' => 'error'
            ];

            if ($request->ajax() || $request->isJson()) {
                return $this->errorResponse('Invalid login, please try again.');
            }

            return Redirect::to('login' . $redirect)->with($error);
        }

        if ($request->ajax() || $request->isJson()) {
            $response_data['user'] = Auth::user();
            $hotel = Hotel::where('id', Auth::user()->hotel_id)
                ->first();
            $response_data['hote'] = $hotel;
            return $this->successResponse($response_data, 200);
        }

    }

    public function isLogin(Request $request)
    {
        $user = Auth::user();
        if (!$user) {

            return response()->json(['error' => 'not login']);
        }

        $hotel = Hotel::where('id', $user->hotel_id)->first();
        $data['user'] = $user;
        $data['hotel'] = $hotel;

        return response()->json($data);
    }

    public function showLoginForm()
    {
        return view('welcome');
    }
}
