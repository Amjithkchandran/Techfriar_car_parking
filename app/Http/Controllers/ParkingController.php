<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Parking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;

class ParkingController extends Controller
{
    /**
     * @author Amjithk
     * car parking api
     */
    public function park(Request $request)
    {
        $response = array();
        $validate = Validator::make($request->all(), [
            'driving_licence' => 'required|mims:pdf|min:2000|max:5000',
            'start_date' => 'required',
            'end_date' => 'required',
            'vehicle_number' => 'required|unique',
            'customer_name' => 'required'
        ]);

        if (!$validate) {
            return response(['status' => FALSE, 'message' => 'Validation Failed.', 'data' => []], 200);
        }

        $file = $request->file('driving_licence');
        $files = Storage::put($file->getClientOriginalName(), file_get_contents($file));

        $time = Carbon::parse()->parse(request('start_date'))->diffInHours(Carbon::parse()->parse(request('end_date')));
        if ($time <= 72)
            $fare = 10;
        elseif ($time > 72) {
            $extra = $time - 72;
            $fare = 10 + ($extra * 5);
        }
        $data = Parking::latest()->first();
        $p_count =Parking::count();

        if ($p_count < 1 || Parking::where('vehicle_number', request('vehicle_number'))->first()) {
            $park = new Parking;
            $park->customer_name = request('customer_name');
            $park->driving_licence = $file;
            $park->vehicle_number = request('vehicle_number');
            $park->start_date = request('start_date');
            $park->end_date = request('end_date');
            if ($p_count < 1) {
                $park->appointment_number = 'A01AAA';
                $park->slot = 'A01';
            } else {
                $char = str_split($data->appointment_number);
                if ($char[2] != '0' && $char[2] > 4 && $char[5] != 'Z') {
                    $park->appointment_number = ++$char[0] . $char[1] . '1' . ++$char[0] . ++$char[0] . ++$char[0];
                    $park->slot = $char[0] . $char[1] . '1';
                } elseif ($char[5] == 'Z' && $char[2] > 5) {
                    return response(['status' => FALSE, 'message' => 'Appointment Full.', 'data' => []], 200);
                } else {
                    $park->appointment_number = $char[0] . $char[1] . ++$char[2] . $char[0] . $char[0] . $char[0];
                    $park->slot = $char[0] . $char[1] . $char[2];
                }
            }
            $park->parking_fee = $fare;
            $park->save();
            $response['appointment_number'] = $park->appointment_number;
            $response['slot'] = $park->slot;
            $response['fare'] = $fare;
        } else {
            return response(['status' => FALSE, 'message' => 'Vehicle Already Registered.', 'data' => []], 200);
        }
        return response($response, 200);
    }
}
