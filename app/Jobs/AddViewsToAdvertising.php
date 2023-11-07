<?php

namespace App\Jobs;

use App\Models\Advertising;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AdvertisingView;

class AddViewsToAdvertising implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $count;

    public function __construct($id, $count=1)
    {

        logger('__construct');
        $this->id = $id;
        $this->count = $count;
    }

    public function handle()
    {
        logger('handle');
        for ($i = 0; $i < $this->count; $i++) {
            $advertisingView = new AdvertisingView();
            $advertisingView->advertising_id = $this->id;
            $advertisingView->save();
        }
        $ad = Advertising::find($this->id);
        $ad->update([
            'view_count' => $ad->view_count + $i
        ]);

    }
}
