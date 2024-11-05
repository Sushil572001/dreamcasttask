<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function user_list(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('role')->latest()->get();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('DT_RowId', function ($row) {
                    return $row->id;
                })
                ->addColumn('name', function ($row) {
                    $image = asset('assets/default_user.png');
                    if ($row->image) {
                        $image = Storage::url($row->image);
                    }
                    $name = "<div class='table_user_profile'>
                                <figure>
                                    <img src='" . $image . "' class='img-fluid'>
                                </figure>
                                <figcaption>
                                    <h6>" . $row->name . "</h6>
                                    <p>" . $row->email . "</p>
                                </figcaption>
                            </div>";
                    return $name;
                })
                ->addColumn('phone', function ($row) {

                    return $row->phone;
                })
                ->addColumn('role', function ($row) {

                    return $row->role->role_name;
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M y, g:i A');
                })
                ->rawColumns(['name', 'phone', 'role', 'created_at'])
                ->make(true);
        }
        $role = Role::get();
        return view('user.index', compact('role'));
    }
    public function add_user(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'name' => 'required|min:5',
            'email' => 'required|email',
            'role' => 'required',
            'phone' => [
                'required',
                'numeric',
                'unique:users,phone',
                'digits:10',
                'regex:/^(?:[789]\d{9})$/',
            ],
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
                function ($attribute, $value, $fail) use ($request) {
                    $image = $request->file('image');
                    $imageSize = getimagesize($image);

                    if ($imageSize === false) {
                        return $fail('Invalid image file.');
                    }
                },
            ],
        ], [
            'phone.regex' => 'The phone number must be a valid 10-digit Indian number starting with 7, 8, or 9.',
            'phone.digits' => 'The phone number must be exactly 10 digits.',
        ]);

        if ($credentials->fails()) {
            return response()->json([
                'status' => 'serverside_error',
                'msg' => 'Validation failed',
                'errors' => $credentials->errors(),
            ], 422);
        }


        $imageFileName = $this->__imageSave($request, 'image', 'user-image');

        // Create the new record
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'role_id' => $request->input('role'),
            'description' => $request->input('description'),
            'image' => $imageFileName,
        ]);

        return response()->json([
            'status' => 'success',
            'msg' => 'Added Successfully',
        ]);
    }
}
