<?php

namespace App\Console;

use App\Console\Commands\CreateLog;
use App\Models\Advertising;
use App\Models\AdvertisingView;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CreateLog::class
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // get all advertisings
        $schedule->call(function () {
            $ads = Advertising::whereNull('deleted_at')->getValidAdvertising()->withoutGlobalScope('notService')
                ->whereNotNull('expire_at')
                ->where('expire_at', '>', date('Y-m-d'))->get();
            foreach ($ads as $ad) {
                for($i =0; $i < 4; $i++){
                    $ad->advertisingView()->create([
                        'guest_ip' => null
                    ]);
                }
                $ad->update([
                    'view_count' => $ad->view_count + $i
                ]);
            }
        })->daily()->at('13:30');

        $schedule->call(function () {
            Advertising::query()
                ->whereNull('deleted_at')
                ->where('status', 'accepted')
//                ->withoutGlobalScope('notService')
                ->whereNotNull('expire_at')
                ->where('advertising_type' , 'premium')
                ->whereDate('created_at' , '<=' , \Illuminate\Support\Carbon::now()->subDays( \App\Http\Controllers\site\MessageController::getSettingDetails('convert_to_normal')) )
                ->where('expire_at', '>', date('Y-m-d'))
                ->update(['advertising_type' => 'normal']);
        })->daily();
        
        $schedule->call(function () {
            AdvertisingView::query()->where('created_at', '<', now()->subDays(40))
                ->delete();
        })->daily()->at('03:30');
        //   $schedule->call(function(){
        //     Artisan::call('queue:work');
        // })->everyMinute();
         $schedule->call(function () {
             $files = File::allFiles(public_path('resources/tempUploads/'));
             foreach ($files as $file){
                 if ( time() - $file->getCTime() > 5 * 60 * 60 )
                     unlink($file->getRealPath());
             }
             $files = File::allFiles(public_path('resources/uploads/images/thumb/'));
             foreach ($files as $file){
                 if ( time() - $file->getCTime() > 5 * 60 * 60 )
                     unlink($file->getRealPath());
             }
             $files = File::allFiles(public_path('resources/thumb/'));
             foreach ($files as $file){
                 if ( time() - $file->getCTime() > 5 * 60 * 60 )
                     unlink($file->getRealPath());
             }
         })->daily()->at('3:30');

        // $schedule->call(function () {
        //     foreach(Advertising::where('status' , 'accepted')->withoutGlobalScope('notService')->whereDate('expire_at' , Carbon::now())->where('auto_extend' , 1 )->get() as $ad) {
        //         $isValid = \App\Http\Controllers\Controller::isValidCreateAdvertising($ad->user_id, $ad->advertising_type);
        //         if ($isValid) {
        //             //DB::beginTransaction();
        //             $countShowDay =  \App\Http\Controllers\Controller::affectCreditUser($ad->user_id, $ad->advertising_type);
        //             $today = date("Y-m-d");
        //             $date = strtotime("+$countShowDay day", strtotime($today));
        //             $expireDate = date("Y-m-d", $date);
        //             $ad->expire_at = $expireDate;
        //             $ad->save();
        //             echo $ad->id ."\n";
        //             //DB::commit();
        //         }
        //     }
        // })->daily()->at('12:30');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
