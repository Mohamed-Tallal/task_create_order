<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ShowDataTrait;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends Controller
{
    use ShowDataTrait;

    public function __invoke(OrderRequest $request)
    :JsonResponse
    {
        $products = collect($request->input('products'));
        $productIds = $products->pluck('product_id');
        $productsData = Product::whereIn('id', $productIds)->with('ingredients')->get();
        $returnData = $this->createOrder($products, $productsData);

        if( $returnData['status'] == true){
            return $this->succesWithoutData($returnData['msg']);
        }

        return $this->errorFind($returnData['msg']);
    }

    private function calculateTotalPrice($products , $productsData)
    {
        return $products->sum(function ($item) use ($productsData) {
            $product = $productsData->firstWhere('id', $item['product_id']);
            return $product->price * $item['quantity'];
        });
    }


    private function createOrder($products , $productsData)
    :array
    {
        DB::beginTransaction();
        try {

            // create order

            $order = Order::create([
                'user_id' => 1,
                'total_price' =>  $this->calculateTotalPrice($products , $productsData ),
            ]);

            $orderProducts = $products->map(function ($item) use ($productsData) {
                $product = $productsData->firstWhere('id', $item['product_id']);
                return [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                ];
            });
            $order->products()->attach($orderProducts);

            // update stock

            foreach ($products as $item) {
                $product = $productsData->firstWhere('id', $item['product_id']);
                foreach ($product->ingredients as $ingredient) {
                    $quantityToConsume = $ingredient->pivot->quantity * $item['quantity'] / 1000;
                    $stock = $ingredient->stock;

                    if ($stock >= $quantityToConsume) {
                        $ingredient->decrement('stock', $quantityToConsume);
                    } else {
                        return [
                            'status' => true ,
                            'msg'    => 'Insufficient stock for ingredient ' . $ingredient->name,
                        ];
                    }
                }
            }
            DB::commit();
            return [
                'status' => true ,
                'msg'    => 'Order Created Successfully',
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => true ,
                'msg'    => 'There was an error while creating the Order. Please try again.',
            ];
        }
    }
}
