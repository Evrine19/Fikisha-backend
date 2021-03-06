<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Fleet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $customers = Customer::with('orders')->paginate(20);
            return response()
                ->json([
                    'success'   =>true,
                    'message'   =>'You have successfully retrieved list of customers',
                    'data'      =>$customers
                ], 200);
        } catch (Exception $exception) {
            return response()
                ->json(['message'=>$exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try{
            $validator=Validator::make($request->all(),[
                'name'   => 'required',
                'email'   => 'required|email',
                'phone'   => 'required',
            ]);
            if ($validator->fails()) {
                return response()
                    ->json([
                        'success' => false,
                        'message' =>$validator->errors()->first()
                    ]);
            }
            $customer=Customer::create([
                'name'   =>$request->input('name'),
                'email'  =>$request->input('email'),
                'phone'  =>$request->input('phone')
            ]);
            return response()
                ->json([
                    'success'   =>true,
                    'message'   =>'You have successfully added a new customer',
                    'data'      =>$customer
                ], 200);
        } catch (Exception $exception) {
            return response()
                ->json(['message'=>$exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try{
            $customer = Customer::where('id',$id)
                ->first();
            return response()
                ->json([
                    'success'   =>true,
                    'message'   =>'You have successfully retrieved customer details',
                    'data'      =>$customer
                ], 200);
        } catch (Exception $exception) {
            return response()
                ->json(['message'=>$exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try{
            Customer::where('id',$id)
                ->update(array_filter($request->except('updated_at','created_at')));
            return response()
                ->json([
                    'success'   =>true,
                    'message'   =>'You have successfully updated customer details',
                ], 200);
        } catch (Exception $exception) {
            return response()
                ->json(['message'=>$exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try{
            Customer::where('id',$id)
                ->delete();
            return response()
                ->json([
                    'success'   =>true,
                    'message'   =>'You have successfully removed a customer',
                ], 200);
        } catch (Exception $exception) {
            return response()
                ->json(['message'=>$exception->getMessage()], $exception->getCode());
        }
    }

    public function getOrders()
    {
        try{
            $orders = Order::with('customer','fleet')->where('customer.email' !== null);
           $fleet = Fleet::with('order')->sortByDesc('created_at');
            return response()
                ->json([
                    'success'   =>true,
                    'message'   =>'You have successfully retrieved list of orders',
                    'data'      =>$orders
                ], 200);
        } catch (Exception $exception) {
            return response()
                ->json(['message'=>$exception->getMessage()], $exception->getCode());
        }
    }
}
