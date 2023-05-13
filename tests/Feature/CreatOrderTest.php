<?php

namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CreatOrderTest extends TestCase
{
    use DatabaseTransactions;

    public function testOrderIsStoredAndTotalPriceIsCorrect()
    {
        $user = User::factory()->create();
        $ingredient1 = Ingredient::factory()->create(['stock' => 100]);
        $ingredient2 = Ingredient::factory()->create(['stock' => 200]);

        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $product1->ingredients()->attach($ingredient1, ['quantity' => 10]);
        $product2->ingredients()->attach($ingredient2, ['quantity' => 10]);

        $payload = [
            'products' => [
                [
                    'product_id' => $product1->id,
                    'quantity' => 2,
                ],
                [
                    'product_id' => $product2->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $payload);

        $response->assertStatus(200);
        $order = Order::latest()->first();
        $this->assertInstanceOf(Order::class, $order);
        $expectedTotalPrice = (2 * $product1->price + $product2->price) ;

        $this->assertEqualsWithDelta($expectedTotalPrice, $order->total_price, 0.0001);
    }



    public function testOrderIsStoredAndUpdateStock()
    {
        $user = User::factory()->create();
        $ingredient1 = Ingredient::factory()->create(['stock' => 100]);
        $ingredient2 = Ingredient::factory()->create(['stock' => 200]);
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $product1->ingredients()->attach($ingredient1, ['quantity' => 10]);
        $product2->ingredients()->attach($ingredient2, ['quantity' => 10]);

        $stockBeforeUpdate = array();
        $stockBeforeUpdate[0] =  $ingredient1->stock ;
        $stockBeforeUpdate[1] =  $ingredient2->stock ;

        $payload = [
            'products' => [
                [
                    'product_id' => $product1->id,
                    'quantity' => 2,
                ],
                [
                    'product_id' => $product2->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $payload);
        $response->assertStatus(200);

        foreach ($payload['products'] as $index => $item) {
            $product = Product::find($item['product_id']);
            foreach ($product->ingredients as $ingredient) {
                if ($ingredient->pivot) {
                    $this->assertEquals(
                        $stockBeforeUpdate[$index] - ($item['quantity'] * $ingredient->pivot->quantity / 1000),
                        $ingredient->fresh()->stock
                    );
                }
            }
        }
    }


    public function testStockSmallerThanOrEqualMinimumStockSendMail()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $ingredient = Ingredient::factory()->create(['stock' => 4]);
        $product->ingredients()->attach($ingredient, ['quantity' => 2000]);
        $payload = [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $payload);
        $response->assertStatus(200);

        $ingredient = Ingredient::latest()->first();
        $this->assertInstanceOf(Ingredient::class, $ingredient);
        $this->assertEquals(1, $ingredient->ingredient_alert);

    }


    public function testInsufficientStockNotUpdateStock()
    {
        $product = Product::factory()->create();
        $ingredient = Ingredient::factory()->create(['stock' => 0]);
        $product->ingredients()->attach($ingredient, ['quantity' => 100]);
        $user = User::factory()->create();

        $payload = [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $payload);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Insufficient stock for ingredient '. $ingredient->name]);
        $this->assertEquals(0, $ingredient->fresh()->stock);
    }
}
