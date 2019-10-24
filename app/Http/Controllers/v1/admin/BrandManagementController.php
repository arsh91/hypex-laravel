<?php

namespace App\Http\Controllers\v1\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\BrandFormRequest;
use Datatables;
use App\Models\BrandsModel;
use App\Models\BrandTypesModel;
use App\Models\ProductsModel;

class BrandManagementController extends Controller
{
    public function brandList(){
    	return view('admin/brand/brand-list');
    }

    public function brandListPaginate(){
        $get_brand_list = BrandsModel::orderBy('updated_at','desc')->get();
        // print_r($get_brand_list->toArray()); exit;
    	return Datatables::of($get_brand_list)
            ->setRowClass('clickable-row')
            ->addIndexColumn()
             ->addColumn('status_custom', function(BrandsModel $brandsModel) {
                if($brandsModel->status == 1){
                    $return_data = '<label class="badge badge-gradient-success">Active</label>';
                }else{
                    $return_data = '<label class="badge badge badge-danger">Deactivated</label>';
                }
                return $return_data;
             })
             ->addColumn('brand_types_list_with_comma_custom', function(BrandsModel $brandsModel) {
                if(!empty($brandsModel->brand_types_list_with_comma)){
                    return $brandsModel->brand_types_list_with_comma;
                }else{
                    return 'N/A';
                }
             })
             ->addColumn('action_custom', function(BrandsModel $brandsModel) {
                    return '<button type="button" class="btn btn-danger btn-fw">Danger</button>';
             })
            ->rawColumns(['status_custom','action_custom','brand_type_list_custom'])
            ->make(true);
    }

    public function addBrand(BrandFormRequest $request){
		if($request->isMethod('post')){
            $validated = $request->validated();
            $brand_model = new BrandsModel;
            $brand_model->brand_name = $request->brand_name;
            $brand_model->status = 1;
            $brand_model->created_at = Date('Y-m-d H:i:s');
            $brand_model->updated_at = Date('Y-m-d H:i:s');
            $brand_model->save();

            $select_brand_type = $request->select_brand_type;
            if(isset($select_brand_type) && count($select_brand_type)){
                foreach ($select_brand_type as $key => $value) {
                    $brand_type_model = new BrandTypesModel;
                    $brand_type_model->brand_type_name = $value;
                    $brand_type_model->brand_id = $brand_model->id;
                    $brand_type_model->status = 1;
                    $brand_type_model->created_at = Date('Y-m-d H:i:s');
                    $brand_type_model->updated_at = Date('Y-m-d H:i:s');
                    $brand_type_model->save();
                }
            }
            return redirect('admin/brand-list')->with('success', 'Brand inserted successfully!');
		}else{
			return view('admin/brand/add-brand');
		}
    }

 	public function editBrand(BrandFormRequest $request, $id){
 		if($request->isMethod('post')){
            $validated = $request->validated();
            $brand_model = BrandsModel::findorFail($id);
            $brand_model->brand_name = $request->brand_name;
            $brand_model->status = 1;
            $brand_model->updated_at = Date('Y-m-d H:i:s');
            $brand_model->save();

            $delete_brand_type_array = $collection = collect(BrandTypesModel::where('brand_id',$id)->pluck('brand_type_name'));
            $delete_brand_type_array = $delete_brand_type_array->diff($request->select_brand_type)->all();
            BrandTypesModel::where('brand_id',$brand_model->id)->whereIn('brand_type_name',$delete_brand_type_array)->delete();

            $insert_brand_type_array =  $collection = collect($request->select_brand_type);
            $insert_brand_type_array = $insert_brand_type_array->diff(BrandTypesModel::where('brand_id',$id)->pluck('brand_type_name'))->all();
            if(isset($brand_model->id) && count($insert_brand_type_array) > 0){
                foreach ($insert_brand_type_array as $key => $value) {
                    $insert_brand_type = new BrandTypesModel();
                    $insert_brand_type->brand_type_name = $value;
                    $insert_brand_type->brand_id = $id;
                    $insert_brand_type->status = 1;
                    $insert_brand_type->created_at = Date('Y-m-d H:i:s');
                    $insert_brand_type->updated_at = Date('Y-m-d H:i:s');
                    $insert_brand_type->save();
                }
            }
            return redirect("admin/brand-list")->with('success', 'Brand updated successfully!');
		}else{
			$get_brand_detail = BrandsModel::findorFail($id);
    		return view('admin/brand/edit-brand',compact('get_brand_detail'));
		}
    }

    public function eachBrandDetail($id){
    	$get_brand_detail = BrandsModel::findorFail($id);
        return view('admin/brand/each-brand-detail',compact('get_brand_detail'));
    }

    // public function deleteBrand($id){
    //     $get_brand_detail = BrandsModel::findorFail($id);
    //     if(!empty($get_brand_detail)){
    //         $get_brand_detail->status = 0;
    //         $get_brand_detail->updated_at = Date('Y-m-d H:i:s');
    //         $get_brand_detail->save();
    //         return redirect('admin/brand-list');
    //     }
       
    // }

    public function deleteBrand(Request $request, $id){
        $get_brand_detail = BrandsModel::findorFail($id);
        if($get_brand_detail->status == 1){
            $get_brand_detail->status = 0;
            $get_brand_detail->updated_at = Date('Y-m-d H:i:s');
            $get_brand_detail->save();
             return redirect("admin/each-brand-detail/$id")->with('success', 'Brand deactivated successfully!');
        }else{
            $get_brand_detail->status = 1;
            $get_brand_detail->updated_at = Date('Y-m-d H:i:s');
            $get_brand_detail->save();
             return redirect("admin/each-brand-detail/$id")->with('success', 'Brand activated successfully!');
        }
    }



    public function deleteBrandType(Request $request){
        $brand_type_name = $request->brand_type_val;
        $brand_id = $request->brand_id;
        $get_brand_type_id = BrandTypesModel::where(['brand_id' => $brand_id, 'brand_type_name' => $brand_type_name])->first();
        if(isset($get_brand_type_id) && !empty($get_brand_type_id)){
            $check_assign_brand_type = ProductsModel::where('brand_type_id',$get_brand_type_id->id)->first();
            if(isset($check_assign_brand_type) && !empty($check_assign_brand_type)){
                return json_encode(['status' => 2]);
            }else{
                BrandTypesModel::where('id',$get_brand_type_id->id)->delete();
                return json_encode(['status' => 1]);
            }
        }else{
            return json_encode(['status' => 1]);
        }     
    }
 }
