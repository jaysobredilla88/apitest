<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dtr;
use Illuminate\Support\Facades\Http;

class DtrController extends Controller
{
    public function create(Request $request)
    {
        $response = [];

        $req = $request->all();

        if (count($req) == 0) {
            $response['message'] = 'Invalid format. ';
            return response()->json($response, 400);
        }

        DB::beginTransaction();
        foreach ($req as $r) {
            $email = $r['email'] ?? null;
            $data = [
                'employee_id' => null,
                'date' => $r['date'] ?? null,
                'time_in' => $r['time_in'] ?? null,
                'time_out' => $r['time_out'] ?? null,
            ];

            if ($email == '' || $data['date'] == '' || $data['time_in'] == '' || $data['time_out'] == '') {
                $required = [];

                if ($email == '') {
                    $required[] = 'email';
                }
                if ($data['date'] == '') {
                    $required[] = 'date';
                }
                if ($data['time_in'] == '') {
                    $required[] = 'time_in';
                }
                if ($data['time_out'] == '') {
                    $required[] = 'time_out';
                }

                $response['message'] = 'Missing required parameter. ' . implode(', ', $required);

                DB::rollBack();
                return response()->json($response, 400);
            }

            $employee = Employee::where('email', strtolower($email))->first();

            if (isset($employee->id)) {
                $status = 204;
                $data['employee_id'] = $employee->id;
                Dtr::create($data);
            } else {
                $response['message'] = 'Invalid email '.$email.'.';

                DB::rollBack();
                return response()->json($response, 400);
            }
        }

        DB::commit();
        return response()->json($response, $status);
    }

    public function getLogs(Request $request)
    {
        if (!$request->has('email')) {
            return response()->json(['message' => 'Required parameter. email'], 400);
        }

        $employee = Employee::where('email', strtolower($request->email))->first();

        if (!$employee) {
            return response()->json(['message' => 'Invalid email '.$request->email.'.'], 400);
        }

        $dtrs = DTR::where('employee_id', $employee->id)
            ->orderBy('date')
            ->get();
        $logs = [];

        foreach ($dtrs as $dtr) {
            $logs[] = [
                $dtr->employee->last_name.', '.$dtr->employee->first_name,
                date('Y-m-d', strtotime($dtr->date)),
                $dtr->time_in,
                $dtr->time_out,
                $dtr->computeWorked(),
                $dtr->computeLate(),
                $dtr->computeUndertime(),
                $dtr->computeOvertime(),
            ];
        }

        return response()->json($logs, 200);
    }

    public function displayLogs(Request $request)
    {
        $response = '';
        $headers = ['Name','Date','Time In', 'Time Out', 'Hrs Worked', 'Hrs Late', 'Hrs Undertime', 'Hrs Overtime'];
        if (!$request->has('email')) {
            $response = 'Email Required';
            return response($response);
        }

        $employee = Employee::where('email', strtolower($request->email))->first();

        if (!$employee) {
            $response = 'Invalid email.';
        } else {
            $response = '<table cellpadding="5" cellspacing="5"><tr><td>'.implode('</td><td>', $headers).'</td></tr>';
            $dtrs = DTR::where('employee_id', $employee->id)
                ->orderBy('date')
                ->get();

            foreach ($dtrs as $dtr) {
                $data = [
                    $dtr->employee->last_name.', '.$dtr->employee->first_name,
                    date('Y-m-d', strtotime($dtr->date)),
                    $dtr->time_in,
                    $dtr->time_out,
                    $dtr->computeWorked(),
                    $dtr->computeLate(),
                    $dtr->computeUndertime(),
                    $dtr->computeOvertime(),
                ];

                $response .= '<tr><td>'.implode('</td><td>', $data).'</td></tr>';
            }
        }

        return response($response);
    }
}
