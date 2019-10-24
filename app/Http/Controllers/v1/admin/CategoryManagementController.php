<?php

namespace App\Http\Controllers\v1\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use File;
use Validator;

use Datatables;
use App\Models\CategoriesModel;
use App\Models\CategoryBrands;
use App\Models\BrandsModel;
use App\Http\Requests\CategorgFormRequest;
use App\Traits\CustomFunction;

class CategoryManagementController extends Controller
{
	use CustomFunction;
    public function categoryList(){
    	return view('admin/category/category-list');
    }

    public function categoryListPaginate(){
	 	$get_user_list = CategoriesModel::orderBy('updated_at','desc')->get();
    	return Datatables::of($get_user_list)
            ->setRowClass('clickable-row')
            ->addIndexColumn()
             ->addColumn('status_custom', function(CategoriesModel $categoriesList) {
                if($categoriesList->status == 1){
                    $return_data = '<label class="badge badge-gradient-success">Active</label>';
                }else{
                    $return_data = '<label class="badge badge badge-danger">Deactivated</label>';
                }
                return $return_data;
             })
             ->addColumn('brand_list_custom', function(CategoriesModel $categoriesList) {
                $brand_list = $categoriesList->categoryBrands->pluck('brand_name');
                
                if(count($brand_list) > 0){
                    $collection = collect($brand_list)->implode(', ');
                }else{
                    $collection = 'N/A';
                }
                return $collection;
             })
             ->addColumn('action_custom', function(CategoriesModel $categoriesList) {
                    return '<button type="button" class="btn btn-danger btn-fw">Danger</button>';
             })
            ->rawColumns(['status_custom','action_custom','brand_list_custom'])
            ->make(true);
    }

    public function addCategory(CategorgFormRequest $request){
		if($request->isMethod('post')){
            $validated = $request->validated();

			$category_image = '';
			if ($request->hasFile('category_image')){
				$cat_image = $request->category_image;
				$category_image = $this->uploadCategoryImage($cat_image);
			}

            $insert_category = new CategoriesModel();
            $insert_category->category_name = $request->category_name;
			$insert_category->category_image = $category_image; //category image uploading
            $insert_category->status = 1;
            $insert_category->created_at = Date('Y-m-d H:i:s');
            $insert_category->updated_at = Date('Y-m-d H:i:s');
            $insert_category->save();
            if(isset($insert_category->id) && count($request->select_brand) > 0){
                $category_brand = array();
                foreach ($request->select_brand as $key => $value) {
                    $insert_category_brand = new CategoryBrands();
                    $insert_category_brand->category_id = $insert_category->id;
                    $insert_category_brand->brand_id = $value;
                    $insert_category_brand->status = 1;
                    $insert_category_brand->created_at = Date('Y-m-d H:i:s');
                    $insert_category_brand->updated_at = Date('Y-m-d H:i:s');
                    $insert_category_brand->save();
                }
            }
            return redirect('admin/category-list')->with('success', 'Category inserted successfully!');
		}else{
            $brand_list = BrandsModel::where('status',1)->select(['id','brand_name'])->get();
			return view('admin/category/add-category',compact('brand_list'));
		}
    }

 	public function editCategory(CategorgFormRequest $request, $id){
 		if($request->isMethod('post')){
            $validated = $request->validated();

		    $cat_image = $request->category_image;

		    $insert_category = CategoriesModel::findorFail($id);
            $insert_category->category_name = $request->category_name;

		    //if image is changed only then need to upload otherwise keep the same
            if ($request->hasFile('category_image')){
			    $category_image = $this->uploadCategoryImage($cat_image);
			    $insert_category->category_image = $category_image;
		    }

            $insert_category->updated_at = Date('Y-m-d H:i:s');
            $insert_category->status = 1;
            $insert_category->save();

            $delete_brand_array = $collection = collect(CategoryBrands::where('category_id',$id)->pluck('id'));
            $delete_brand_array = $delete_brand_array->diff($request->select_brand)->all();
            CategoryBrands::whereIn('id',$delete_brand_array)->delete();

            $insert_brand_array =  $collection = collect($request->select_brand);
            $insert_brand_array = $insert_brand_array->diff(CategoryBrands::where('category_id',$id)->pluck('id'))->all();
            if(isset($insert_category->id) && count($insert_brand_array) > 0){
                $category_brand = array();
                foreach ($insert_brand_array as $key => $value) {
                    $insert_category_brand = new CategoryBrands();
                    $insert_category_brand->category_id = $insert_category->id;
                    $insert_category_brand->brand_id = $value;
                    $insert_category_brand->status = 1;
                    $insert_category_brand->created_at = Date('Y-m-d H:i:s');
                    $insert_category_brand->updated_at = Date('Y-m-d H:i:s');
                    $insert_category_brand->save();
                }
            }
            return redirect("admin/category-list")->with('success', 'Category updated successfully!');
		}else{
            $brand_list = BrandsModel::where('status',1)->select(['id','brand_name'])->get();
			$get_category_detail = CategoriesModel::findorFail($id);
    		return view('admin/category/edit-category',compact('get_category_detail','brand_list'));
		}
    }

    public function eachCategoryDetail($id){
    	$get_category_detail = CategoriesModel::findorFail($id);
        return view('admin/category/each-category-detail',compact('get_category_detail'));
    }

    // public function deleteCategory($id){
    //     $get_category_detail = CategoriesModel::findorFail($id);
    //     if(!empty($get_category_detail)){
    //         $get_category_detail->status = 0;
    //         $get_category_detail->updated_at = Date('Y-m-d H:i:s');
    //         $get_category_detail->save();
    //         return redirect('admin/category-list');
    //     }
       
    // }

    public function deleteCategory(Request $request, $id){
        $get_category_detail = CategoriesModel::findorFail($id);
        if($get_category_detail->status == 1){
            $get_category_detail->status = 0;
            $get_category_detail->updated_at = Date('Y-m-d H:i:s');
            $get_category_detail->save();
             return redirect("admin/each-category-detail/$id")->with('success', 'Category deactivated successfully!');
        }else{
            $get_category_detail->status = 1;
            $get_category_detail->updated_at = Date('Y-m-d H:i:s');
            $get_category_detail->save();
             return redirect("admin/each-category-detail/$id")->with('success', 'Category activated successfully!');
        }
    }

}
