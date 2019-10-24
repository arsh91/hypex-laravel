<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use App\Models\SubscriptionsModel;


class BuySubscription extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subscriptionsID)
    {
        $this->id = $subscriptionsID;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        ini_set('memory_limit','-1');

        $user = Auth::user();
		$fName = $user->first_name;
		$lName = $user->last_name;
        
        $id = $this->id;
        //$fetchData = SubscriptionsModel::with(['product','size','shipping','billing'])->where(['id'=>$id])->first()->toArray();
        $fetchData = SubscriptionsModel::with(['plan'])->where(['id' => $id] )->first()->toArray();

        return $this->from('info@hypex.com', 'HYPEX')
                    ->subject('HYPEX SUBSCRIPTION SUCCESSFULLY !!')
                    ->view('email.buy-subscription')
                    ->with([
                        'name' => $fName.' '.$lName,
                        'totalPrice'=> $fetchData['price'],
                        'planName'=> $fetchData['plan']['duration'],
                        'startDate' => $fetchData['start_date'],
                        'endDate' => $fetchData['end_date'],
                    ]);
    }
}
