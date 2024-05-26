<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Commitments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignPermission extends Controller
{
    public function assignPermissions(Request $request)
    {
        $request->validate([
            "engagementid" => "required",
            "permissions" => "required|array|min:1"
        ]);
        $engagement = Commitments::where('deleted', 0)->find($request->engagementid);
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
    }
}
