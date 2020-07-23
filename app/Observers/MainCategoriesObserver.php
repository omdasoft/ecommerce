<?php

namespace App\Observers;

use App\Models\MainCategories;

class MainCategoriesObserver
{
    /**
     * Handle the main categories "created" event.
     *
     * @param  \App\Models\MainCategories  $mainCategories
     * @return void
     */
    public function created(MainCategories $mainCategories)
    {
        //
    }

    /**
     * Handle the main categories "updated" event.
     *
     * @param  \App\Models\MainCategories  $mainCategories
     * @return void
     */
    public function updated(MainCategories $mainCategories)
    {
        $mainCategories->vendors()->update(['active' => $mainCategories->active]);
    }

    /**
     * Handle the main categories "deleted" event.
     *
     * @param  \App\Models\MainCategories  $mainCategories
     * @return void
     */
    public function deleted(MainCategories $mainCategories)
    {
        //
    }

    /**
     * Handle the main categories "restored" event.
     *
     * @param  \App\Models\MainCategories  $mainCategories
     * @return void
     */
    public function restored(MainCategories $mainCategories)
    {
        //
    }

    /**
     * Handle the main categories "force deleted" event.
     *
     * @param  \App\Models\MainCategories  $mainCategories
     * @return void
     */
    public function forceDeleted(MainCategories $mainCategories)
    {
        //
    }
}
