<?php

namespace App\Http\Controllers\v1\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VipSaleCounter;
use App\Models\CurrencyConversionModel;



class VipSaleCounterController extends Controller
{
    public function showSale(){
        
        $vipSale = VipSaleCounter::get()->toArray();
        return view('admin.vip_sale_counter.vip_sale_count_list', ['vipSale' => $vipSale]);
    }
    
    public function editSale(Request $request, $id){
        
        if($request->isMethod('post')){
            $sale = $request->all();
            $dateRange = $sale['datetimes'];
            $dates = explode("-",$dateRange);

            $startDate = current($dates);
            $endDate = end($dates);
            
            $startDate = str_replace("/","-",$startDate);
            $endDate = str_replace("/","-",$endDate);
            
            $existingRecord = VipSaleCounter::where('id', $id)->first();
            
            $existingRecord->start_date = $startDate;
			$existingRecord->end_date = $endDate;
            
            if($existingRecord->save()){
                
                $vipSale = VipSaleCounter::get()->toArray();
                return view('admin.vip_sale_counter.vip_sale_count_list', ['vipSale' => $vipSale]);
                
            } else {
                return back()
                           ->with('error','Error in updating VIP sale');
            }
        }
        
        $sale = VipSaleCounter::where('id', $id)->first();
        return view('admin.vip_sale_counter.edit_sale_count_detail', ['sale' => $sale]);
    }

    
    public function showCurrency(){
        
        $currencyList = CurrencyConversionModel::get()->toArray();
        return view('admin.currency.currency_list', ['currencyList' => $currencyList]);
    }


    public function editCurrency(Request $request, $id ){

        if($request->isMethod('post')){
            $currency = request()->all();
            $update = CurrencyConversionModel::where('id', $id)->update(request()->except(['_token']));
            if($update){
                return redirect('/admin/currency-list')->with('success','Currency rate has been updated successfully!');
            } else {
                return back()
                           ->with('error','Error in updating currency rate');
            }
        }
        $currency = CurrencyConversionModel::where('id', $id)->first();
        return view('admin.currency.edit_currency', ['currency' => $currency]);

    }

    

}
