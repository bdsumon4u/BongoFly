<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Product;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $this->view();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return $this->view([
            'statuses' => config('app.orders', [])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $request->merge([
            'phone' => Str::startsWith($request->phone, '0') ? '+88'.$request->phone : $request->phone,
        ]);
        $data = $request->validate([
            'name' => 'required',
            'phone' => 'required|regex:/^\+8801\d{9}$/',
            'email' => 'nullable',
            'address' => 'required',
            'note' => 'nullable',
            'status' => 'required',
        ]);

        $order->update($data);
        return redirect(route('admin.orders.show', $order))->withSuccess('Order Has Been Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $products = is_array($order->products) ? $order->products : get_object_vars($order->products);
        array_map(function ($product) {
            if ($product = Product::find($product->id)) {
                $product->should_track && $product->increment('stock_count', intval($product->quantity));
            }
            return null;
        }, $products);
        $order->delete();
        return request()->expectsJson() ? true : redirect(action([self::class, 'index']))
            ->with('success', 'Order Has Been Deleted.');
    }
}
