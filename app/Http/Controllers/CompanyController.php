<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Commitments;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        $image = MethodsController::uploadImageOtherServer($request->logo);
        if ($image) {
            $company = Company::create($request->all());
            $engagement = Commitments::create([
                'iduser' => $user->id,
                'idcompany' => $company->id,
            ]);
            $engagement->permissions()->detach();
            foreach ($request->permissions as $item) {
                $engagement->permissions()->attach(
                    [
                        $engagement->id => [
                            'permission_id' => $item['permissionid'],
                            'create' => $item['create'],
                            'read' => $item['read'],
                            'update' => $item['update'],
                            'delete' => $item['delete']
                        ]
                    ]
                );
            }
            return response()->json([
                "message" => trans('messages.saved'),
            ], 200);

            return response()->json([
                "data" => $company,
                "message" => 'succès'
            ], 200);
        } else {
            $company = Company::create($request->all());
            Commitments::create([
                'iduser' => $user->id,
                'idcompany' => $company->id,
            ]);
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
