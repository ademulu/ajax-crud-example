<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class CustomerController extends Controller
{
    //
    public function index()
    {
        return view('customer');
    }

    public function getCustomers(Request $request)
    {
        $data = Customer::latest()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="edit" class="edit btn btn-primary btn-sm editCustomer">Edit</a>';
                $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="delete" class="delete btn btn-danger btn-sm deleteCustomer">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function store(Request $request)
    {
        Customer::updateOrCreate(['id' => $request->Customer_id],
            ['firstName' => $request->firstName, 'lastName' => $request->lastName, 'info' => $request->info]);
        return response()->json(['success' => 'Customer saved successfully!']);
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        return response()->json($customer);
    }

    public function destroy($id)
    {
        Customer::find($id)->delete();
        return response()->json(['success' => 'Customer deleted!']);
    }
}
