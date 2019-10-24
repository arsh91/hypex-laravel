<?php

namespace App\Http\Controllers\v1\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionsModel;


class SubscriptionPlansController extends Controller
{
    public function list(){
        
        $plans = SubscriptionPlan::orderby('id', 'desc')->get()->toArray();
        return view('admin.subscription_plans.plans-list', ['plans' => $plans]);
    }

    protected function validator(array $data)
    {
        $types = SubscriptionPlan::feature_types();
        $values = [];
        foreach($types as $type){
            $values[$type['name']]  =   $type['validation'];
        }
        return Validator::make($data, $values);
    }

    public function add( Request $request ){
        if($request->isMethod('post')){
            $plan = request()->all();
            $insert = SubscriptionPlan::create([
                'duration'  => $plan['duration'],
                'title'     => $plan['title'],
                'feature_1' => $plan['feature_1'],
                'feature_2' => $plan['feature_2'],
                'feature_3' => $plan['feature_3'],
                'feature_4' => $plan['feature_4'],  
                'price'     => $plan['price'],
                'status'    => '1'
            ]);
            if($insert){
                return redirect('/admin/plans/add-plan')->with('success','Subscription Plan has been added successfully!');
            } else {
                return back()
                           ->with('error','Error in adding subscription plan');
            }
        }
        return view('admin.subscription_plans.add-plan');
    }
    
    public function edit( Request $request, $id ){
        
        if($request->isMethod('post')){
            $plan = request()->all();
            $update = SubscriptionPlan::where('id', $id)->update(request()->except(['_token']));
            if($update){
                return redirect('/admin/plans-list')->with('success','Subscription Plan has been updated successfully!');
            } else {
                return back()
                           ->with('error','Error in updating subscription plan');
            }
        }
        $plan = SubscriptionPlan::where('id', $id)->first();
        return view('admin.subscription_plans.edit-plans-detail', ['plan' => $plan]);
    }

    public function eachPlanDetail(Request $request, $id ){
    	$get_plan_detail = SubscriptionPlan::findorFail($id);
        return view('admin.subscription_plans.each-plans-detail', ['get_plan_detail' => $get_plan_detail]);
    }


    public function subscribedUserlist(){
        $subscribed_user = SubscriptionsModel::with(['plan','user'])->where(['status' => 1])->orderby('id', 'desc')->get()->toArray();
        return view('admin.subscribed_users.users-list', ['subscribed_user' => $subscribed_user]);
    }

    public function eachUserSubscribedDetail(Request $request, $id ){
    	$subscribed_user_detail = SubscriptionsModel::with(['plan','user'])->where('id', $id)->first()->toArray();
        return view('admin.subscribed_users.each-sub-users-detail', ['subscribed_user_detail' => $subscribed_user_detail]);
    }
    

}
