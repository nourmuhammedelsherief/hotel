<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class HotelSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check description date and change status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = today()->format('Y-m-d');
        /**
         * handle tentative subscriptions
        */
        \Log::info('check hotel that have tentative subscriptions');
        $tentative_subscriptions = Subscription::where('end_at' , '<' , $today)
            ->whereStatus('tentative')
            ->get();
        if ($tentative_subscriptions->count() > 0)
        {
            foreach ($tentative_subscriptions as $tentative_subscription)
            {
                $tentative_subscription->update([
                    'status'     => 'tentative_finished',
                    'is_payment' => 'false',
                ]);
                if ($tentative_subscription->type == 'hotel')
                {
                    $tentative_subscription->hotel->update([
                        'status'   => 'tentative_finished',
                    ]);
                }
                if ($tentative_subscription->branch)
                {
                    $tentative_subscription->branch->update([
                        'status'   => 'tentative_finished',
                    ]);
                }
            }
        }
        /**
         * handle active subscriptions
         */
        \Log::info('check hotel that have active subscriptions');
        $active_subscriptions = Subscription::where('end_at' , '<' , $today)
            ->whereStatus('active')
            ->get();
        if ($active_subscriptions->count() > 0)
        {
            foreach ($active_subscriptions as $active_subscription)
            {
                $active_subscription->update([
                    'status'   => 'finished',
                    'is_payment' => 'false',
                ]);
                if ($active_subscription->type == 'hotel')
                {
                    $active_subscription->hotel->update([
                        'status'   => 'finished',
                    ]);
                }
                if ($active_subscription->branch)
                {
                    $active_subscription->branch->update([
                        'status'   => 'finished',
                    ]);
                }
            }
        }
    }
}
