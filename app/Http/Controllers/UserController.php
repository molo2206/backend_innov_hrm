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

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            "data" => User::where('deleted', 0)->get()
        ], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            "full_name" => "required",
            "email" => "required",
            "type" => "required",
            "password" => "required",
        ]);
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                "message" => trans('emailExists')
            ], 422);
        }
        if (User::where('phone', $request->phone)->exists()) {
            return response()->json([
                "message" => trans('phoneExists')
            ], 422);
        }
        $codeValidation = (codeValidation::where('email', $request->email)->exists() ||
            codeValidation::where('status', 1));
        $codeVal = (codeValidation::where('code', $request->code)->exists());

        if ($request->code == false || $codeVal == null) {
            if ($codeValidation == true) {
                $code = mt_rand(1, 1897);
                $val = CodeValidation::where('email', $request->email)->first();
                if ($val) {
                    $val->code = $code;
                    $val->save();
                } else {
                    codeValidation::create(['email' => $request->email, 'code' => $code]);
                }
                Mail::to($request->email)->send(new Verificationmail($request->email, $code));
                return response()->json([
                    "message" => "Un code de validation vous a été envoyé à l'adresse " . $request->email,
                    "code_validation" => $code
                ], 200);
            }
        } else {
            $codeValidation = (codeValidation::where('code', $request->code)->exists());
            if ($codeValidation == null) {
                return response()->json([
                    "message" => "Code de validation invalide!"
                ], 402);
            } else {
                $user = User::create($request->all());
                $change = CodeValidation::where('code', $request->code)->first();
                $change->update([
                    "status" => 1,
                ]);
                Mail::to($request->email)->send(new Createcount($request->email, $request->password));
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken("accessToken")->plainTextToken;
                    return response()->json([
                        "message" => trans("Connecter avec succès"),
                        "data" => $user,
                        "status" => 200,
                        "token" => $token,
                    ], 200);
                }
            }
        }
    }
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);
        $user = Auth::user();
        if ($user) {
            if (User::where('email', $request->email)->where('id', '!=', $user->id)->exists()) {
                return response()->json([
                    "message" => trans('Utilisateur existe')
                ], 422);
            }
            $user->first_name = $user->first_name ? $request->first_name : $user->first_name;
            $user->last_name = $user->last_name ? $request->last_name : $user->last_name;
            $user->Email = $user->email ? $request->email : $user->email;
            $user->phone = $user->phone ? $request->phone : $user->phone;
            $user->gender = $user->gender ? $request->gender : $user->gender;
            $user->prename =  $request->prename;
            $user->save();
            return response()->json([
                "data" => $user,
                "message" => 'Modifier'
            ], 200);
        } else {
            return response()->json([
                "message" => 'Erreur de modification, verifier si vous etes connecté'
            ], 422);
        }
    }

    public function destroy(string $id)
    {
        if (Auth::user()) {
            $User = User::find($id);
            if ($User) {
                $User->deleted = 1;
                $User->save();
                return response()->json([
                    "message" => trans('deleted'),
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('id NotFound')
                ]);
            }
        } else {
            return response()->json([
                "message" => trans('Tu n\'est pas connecter')
            ], 422);
        }
    }
    public function status(Request $request, $id)
    {
        if (Auth::user()) {
            $request->validate([
                "status" => 'required'
            ]);
            $User = User::find($id);
            if ($User) {
                $User->status = $request->status;
                $User->save();
                return response()->json([
                    "message" => trans('statusChange')
                ], 200);
            } else {
                return response()->json([
                    "message" => trans('id NotFound')
                ]);
            }
        } else {
            return response()->json([
                "message" => trans('Tu n\'est pas connecter')
            ], 422);
        }
    }
    public function editPhoto(Request $request)
    {
        $request->validate([
            "image" => "required",
        ]);
        $image = MethodsController::uploadImageUrl($request->image, "hrmimages");
        Auth::user()->update(["image" => $image]);
        return response()->json([
            "message" => "Modifier",
            "data" => User::find(Auth::user()->id)
        ], 200);
    }
}
