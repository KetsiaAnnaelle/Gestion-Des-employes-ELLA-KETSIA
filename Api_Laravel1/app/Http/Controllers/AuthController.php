<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
   

    public function user(string $id){
        $user = User::find($id);
        return response()->json([$user]);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => [
                'required',
                // 'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
              //      ->symbols()
            ]

        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->getMessageBag()],422);
        }else{

            $file = $request->file('profil');
            $name = time().$file->getClientOriginalName();

            $user = User::create([
                'name'=>$request->input('name'),
                'email'=>$request->input('email'),
                'password'=>Hash::make($request->input('password')),
                'profil'=> $name,
                'role'=>$request->input('role')
            ]);

            $file->move('uploadImage', $name);

            // return response()->json('true');


            $token = $user->createToken('CLE_SECRETE')->plainTextToken;

            $response = [
                'user' =>$user,
                'token' =>$token,
            ];

            return response()->json($response);

        }

    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        
        try {

            $message = "";

            $userData = $request->validate(
                [
                    'email' => ["required", "email"],
                    'password' => ["required", "min:6"]
                ]
            );
        } catch (ValidationException $validatorError) {
            // return response([$validatorError->errors()],422);
            return response()->json(['errors'=>$validatorError->errors()],422);
        }

        $user = User::where("email", $userData['email'])->first();

        if ($user && Hash::check($userData['password'],$user->password)) {
            // $user = Auth::user();
            $token = $user->createToken("CLE_SECRETE")->plainTextToken;
            return response()->json(['token' => $token, 'user'=>$user]);
        }else {
            
            return response()->json(['error' => 'Identifiants incorrects'], 401);
        }


    }


    public function VerifierExistenceDeEmail(Request $request){
        $email = $request->input('email');
        $user = User::where("email", $email)->first();

        if ($user) {
            return response()->json(['exits' => true]);
        } else {
            return response()->json(['exits' => false]);
        }

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function user()
    // {
    //     // return response()->json(auth()->user());
    //     return Auth::user();
    // }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */


    public function logout()
    {
        // auth()->logout();
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function allLogout()
    {
        // we delete all the tokens of user
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json(["message" => "logout"], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function refresh()
    // {
    //     return $this->respondWithToken(auth()->refresh());
    // }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */


}
