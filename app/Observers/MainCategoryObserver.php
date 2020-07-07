<?php

namespace App\Observers;

use App\Models\Main_Category;

class MainCategoryObserver
{
    /**
     * Handle the main_ category "created" event.
     *
     * @param  \App\Main_Category  $mainCategory
     * @return void
     */
    public function created(Main_Category $mainCategory)
    {
        //
    }

    /**
     * Handle the main_ category "updated" event.
     *
     * @param  \App\Main_Category  $mainCategory
     * @return void
     */
    public function updated(Main_Category $mainCategory)
    {
        $mainCategory -> vendors()-> update(['active' => $mainCategory -> active]);

    }

    /**
     * Handle the main_ category "deleted" event.
     *
     * @param  \App\Main_Category  $mainCategory
     * @return void
     */
    public function deleted(Main_Category $mainCategory)
    {
        //
    }

    /**
     * Handle the main_ category "restored" event.
     *
     * @param  \App\Main_Category  $mainCategory
     * @return void
     */
    public function restored(Main_Category $mainCategory)
    {
        //
    }

    /**
     * Handle the main_ category "force deleted" event.
     *
     * @param  \App\Main_Category  $mainCategory
     * @return void
     */
    public function forceDeleted(Main_Category $mainCategory)
    {
        //
    }
}
