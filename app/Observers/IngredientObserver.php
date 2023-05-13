<?php

namespace App\Observers;

use App\Jobs\SendMailJob;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Log;

class IngredientObserver
{
    /**
     * Handle the Ingredient "created" event.
     */
    public function created(Ingredient $ingredient): void
    {
        //
    }

    /**
     * Handle the Ingredient "updated" event.
     */
    public function updated(Ingredient $ingredient): void
    {
        if ($ingredient->minimum_stock > 0 && $ingredient->minimum_stock >= $ingredient->stock &&  $ingredient->ingredient_alert == false) {
            $ingredient->ingredient_alert = true;
            $ingredient->update();
            $message = 'Ingredient ' . $ingredient->name . ' has reached below 50% of its stock level. Please restock as soon as possible.';
            $to = config('alert.email');
            SendMailJob::dispatch( $to , $message , config('alert.subject'));
        }
    }

    /**
     * Handle the Ingredient "deleted" event.
     */
    public function deleted(Ingredient $ingredient): void
    {
        //
    }

    /**
     * Handle the Ingredient "restored" event.
     */
    public function restored(Ingredient $ingredient): void
    {
        //
    }

    /**
     * Handle the Ingredient "force deleted" event.
     */
    public function forceDeleted(Ingredient $ingredient): void
    {
        //
    }
}
