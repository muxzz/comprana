<?php

namespace App\Observers;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageObserver
{
    /**
     * Handle the Image "created" event.
     */
    public function created(Image $image): void
    {
        //
    }

    public function updating(Image $image): void
    {
        Storage::disk('images')->delete($image->getOriginal('name'));
    }

    /**
     * Handle the Image "updated" event.
     */
    public function updated(Image $image): void {}

    /**
     * Handle the Image "deleted" event.
     */
    public function deleted(Image $image): void
    {
        Storage::disk('images')->delete($image->name);
    }

    /**
     * Handle the Image "restored" event.
     */
    public function restored(Image $image): void
    {
        //
    }

    /**
     * Handle the Image "force deleted" event.
     */
    public function forceDeleted(Image $image): void
    {
        //
    }
}
