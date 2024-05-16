<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'pays' => 'required',
        ]);
        if (Company::where('email', $request->email)->exists()) {
            return response()->json([
                "message" => 'Cette adresse email existe'
            ], 422);
        }
        if (Company::where('phone', $request->phone)->exists()) {
            return response()->json([
                "message" => 'Numèro de téléphone existe'
            ], 422);
        }
        $image = MethodsController::uploadImageOtherServer($request->logo);
        if ($image) {
            $company = Company::create($request->all());
            return response()->json([
                "data" => $company,
                "message" => 'succès'
            ], 200);
        } else {
            $company = Company::create($request->all());
            return response()->json([
                "data" => $company,
                "message" => 'succès'
            ], 200);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'pays' => 'required',
        ]);

    }
}
