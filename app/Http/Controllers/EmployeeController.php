<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function create(Request $request)
    {
        $response = [];

        $data = [
            'first_name' => $request->first_name ?? null,
            'last_name' => $request->last_name ?? null,
            'middle_name' => $request->middle_name ?? null,
            'contact_no' => $request->contact_no ?? null,
            'email' => strtolower($request->email) ?? null,
        ];

        if ($data['first_name'] == '' || $data['last_name'] == '' || $data['email'] == '') {
            $status = 400;
            $req = [];

            if ($data['first_name'] == '') {
                $req[] = 'first_name';
            }
            if ($data['last_name'] == '') {
                $req[] = 'last_name';
            }
            if ($data['email'] == '') {
                $req[] = 'email';
            }

            $response['message'] = 'Missing required parameter. ' . implode(', ', $req);

            return response()->json($response, $status);
        }

        $email_check = Employee::where('email', $data['email'])->count();

        if ($email_check == 0) {
            $status = 204;
            Employee::create($data);
        } else {
            $status = 400;
            $response['message'] = 'Email already registered.';
        }

        return response()->json($response, $status);
    }
}
