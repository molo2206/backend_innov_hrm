<?php

namespace App\Http\Controllers;

use App\Mail\Createcount;
use App\Mail\Verificationmail;
use App\Models\CodeValidation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Filesystem\FilesystemManager;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'required|unique:users',
        ]);

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email already exists'
            ], 422);
        }
        if (User::where('phone', $request->phone)->exists()) {
            return response()->json([
                'message' => 'Phone already exists'
            ], 422);
        }

        // Verification email et son status dans la table Code validation
        $codeValidation = (CodeValidation::where('email', $request->email)->where('status', 1));
        // Verification de code validation entrer
        $codeVal = (CodeValidation::where('code', $request->code)->exists());

        //Condition pour tester le code validation
        if ($codeVal == null) {
            if ($codeValidation == true) {
                $code = mt_rand(1, 9999);
                $val = CodeValidation::where('email', $request->email)->first();
                if ($val) {
                    $val->code = $code;
                    $val->save();
                } else {
                    CodeValidation::create(['email' => $request->email, 'code' => $code]);
                }
                Mail::to($request->email)->send(new Verificationmail($request->email, $code));
                return response()->json([
                    "message" => "Otp envoyé sur cette adresse " . $request->email,
                    "otp" => $code
                ], 200);
            }
        } else {
            $codeValidation = (CodeValidation::where('code', $request->code)->exists());
            if ($codeValidation == null) {
                return response()->json([
                    "message" => "Otp invalide!"
                ], 402);
            } else {
                //Creation utilisateur
                $user = User::create(array_merge(["password" => Hash::make($request->password), "type" => "admin"], $request->all()));
                $change = CodeValidation::where('code', $request->code)->first();
                $change->update([
                    "status" => 1,
                ]);
                //Envoie de Email de connection
                Mail::to($request->email)->send(new Createcount($request->email, $request->password));
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken("accessToken")->plainTextToken;
                    return response()->json([
                        "message" => "Connecter avec succès",
                        "data" => $user,
                        "status" => 200,
                        "token" => $token,
                    ], 200);
                }
            }
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->status == 1) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken("accessToken")->plainTextToken;
                    return response()->json([
                        "message" => "Connecter avec succès",
                        "data" => $user,
                        "status" => 200,
                        "token" => $token,
                    ], 200);
                } else {
                    return response()->json([
                        "message" => 'Mot de passe incorrect'
                    ], 422);
                }
            } else {
                return response()->json([
                    "message" => 'Votre compte est desactivé'
                ], 422);
            }
        } else {
            return response()->json([
                "message" => 'Email incorrect'
            ], 404);
        }
    }
    public function checkemail(Request $request)
    {
        $request->validate([
            "email" => "required|email",
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                "message" => 'Exist'
            ], 200);
        } else {
            return response()->json([
                "message" => 'Email n\'existe pas'
            ], 422);
        }
    }
    public function editPassword(Request $request)
    {
        $request->validate([
            "old_password" => "required",
            "new_password" => "required",
            "email" => "required"
        ]);
        $user = User::where('email', $request->email)->first();
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return response()->json([
                "message" => "Modifier",
                "data" => $user,
                "status" => 200,
            ], 200);
        } else {
            return response()->json([
                "message" => 'Ancien mot de passe incorrect'
            ], 422);
        }
    }

}
