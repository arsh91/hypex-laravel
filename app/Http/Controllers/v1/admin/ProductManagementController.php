<?php

namespace App\Http\Controllers\v1\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\NewProductImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ExcelFileValidation;
use App\Http\Requests\ProductDetailRequest;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;

use File;
use Validator;

use App\Models\ProductsModel;
use App\Models\Monitoring;
use App\Models\CategoriesModel;
use App\Models\CategoryBrands;
use App\Models\ProductsSizes;
use App\Models\ProductSizeTypes;
use App\Models\BrandTypesModel;
use App\Models\BrandsModel;
use App\Models\SizeListModel;
use App\Traits\CustomFunction;
use App\Models\ProductsBidder;
use App\Models\ProductSellerModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Sunra\PhpSimple\HtmlDomParser;
use DOMDocument;

//use Illuminate\Http\Request;


use Datatables;

class ProductManagementController extends Controller
{
    use CustomFunction;

    public function newProductImport(ExcelFileValidation $request){
    	// https://github.com/Maatwebsite/Laravel-Excel
    	if($request->isMethod('post')){
    		$validated = $request->validated();
            if($request->hasFile('file')){
                $extension = $request->file('file')->getClientOriginalExtension();
                if ($extension == "xlsx" || $extension == "xls" || $extension == "csv" || $extension == "ods") {
                        try {
                            $array = Excel::toArray(new NewProductImport, request()->file('file'));
                        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                            return redirect('admin/new-product-import')->withErrors($e->getMessage());
                        }
						
                        if(count($array) > 0){
                            // echo '<pre>'; print_r($array[0]); exit;
                            $validator = Validator::make($array[0], [
                                 '*.0' => 'required|unique:products,product_name|max:100|bail',
                                 '*.1' => 'nullable|max:50|bail',
                                 '*.2' => 'required|max:50|bail',
                                 '*.3' => 'required|max:50|bail',
                                 '*.4' => 'required|max:50|bail',
                                 '*.5' => 'nullable|max:50|bail',
                                 '*.6' => 'nullable|max:50|bail',
                                 '*.7' => 'nullable|max:50|bail',
                                 '*.8' => 'required|max:50|bail',
                                 '*.9' => 'required|max:255|bail',
                                 '*.10' => 'required|max:255|bail',
                                 '*.11' => 'required|max:10|bail',
                                 '*.12' => 'nullable',
                                 '*.13' => 'nullable|numeric|bail',
                                 '*.14' => 'required|bail'
                            ],$this->customValidationMessages($array),$this->customValidationAttributes())->validate();
                            $all_product_name = array();
                            $all_brand_type_name = array();

                            foreach ($array[0] as $row) {
                                if(in_array($row[0], $all_product_name)){
                                    return redirect('admin/new-product-import')->withErrors('All product name must be unique.');
                                }

                                if(!empty($row[9])){
                                    $all_size_array = explode(',',$row[9]);
                                    foreach ($all_size_array as $element) {
                                        if ($element){
                                        }else{
                                            return redirect('admin/new-product-import')->withErrors('All size must be required.');
                                        }
                                    }
                                }

                                
                                if(in_array($row[4], $all_brand_type_name)){
                                   // return redirect('admin/new-product-import')->withErrors('All brand type name must be unique.');
                                }
                                $all_product_name[] = $row[0];
                                $all_brand_type_name[] = $row[4];
                            }

                            foreach ($array[0] as $row) {
                                try{
                                    $product_images = array();
                                    if(!empty($row[14])){
                                        $testString = urlencode($row[14]);
                                        $string = str_replace('%EF%BC%8C', ',', $testString);
                                        $product_images_tmp = explode(',',urldecode($string));
                                        
                                        for ($i=0; $i < count($product_images_tmp); $i++) {
                                            $product_image_path = $this->downloadImage($product_images_tmp[$i]);
                                            $product_images[] = $product_image_path;
                                        }
                                        $product_images = implode(',', $product_images);
                                    }else{
                                         return redirect('admin/new-product-import')->withErrors('Oops Something went wrong with images.');
                                    }

                                    $other_images = array();
                                    if(!empty($row[15])){
                                        $testString = urlencode($row[15]);
                                        $string = str_replace('%EF%BC%8C', ',', $testString);
                                        $product_images_tmp = explode(',',urldecode($string));
                                        for ($i=0; $i < count($product_images_tmp); $i++) {
                                            $product_image_path = $this->downloadImage($product_images_tmp[$i]);
                                            $other_images[] = $product_image_path;
                                        }
                                    }
                                    $other_images = implode(',', $other_images);
                                    
                                    $category_id = CategoriesModel::where('category_name',$row[2])->pluck('id');
                                    if(!count($category_id) > 0){
                                        $category_id = CategoriesModel::insertGetId(['category_name' => $row[2], 'status' => 1, 'created_at' => Date('Y-m-d H:i:s'), 'updated_at' => Date('Y-m-d H:i:s')]);
                                    }else{
                                        $category_id = $category_id[0];
                                    }
                                    $brand_id = BrandsModel::where('brand_name',$row[3])->pluck('id');
                                    if(!count($brand_id) > 0){
                                        $brand_id = BrandsModel::insertGetId(['brand_name' => $row[3], 'status' => 1, 'created_at' => Date('Y-m-d H:i:s'), 'updated_at' => Date('Y-m-d H:i:s') ]);
                                    }else{
                                        $brand_id = $brand_id[0];
                                    }

                                    $category_brand_data = CategoryBrands::where(['category_id' => $category_id, 'brand_id' =>  $brand_id])->first();
                                    if(empty($category_brand_data)){
                                        CategoryBrands::insert(['category_id' => $category_id, 'brand_id' =>  $brand_id, 'status' => 1, 'created_at' => Date('Y-m-d H:i:s'), 'updated_at' => Date('Y-m-d H:i:s')]);
                                    }

                                    $brand_type_id = BrandTypesModel::where(['brand_type_name' => "$row[4]", 'brand_id' => $brand_id])->pluck('id');
                                    if(!count($brand_type_id) > 0){
                                        $brand_type_id = BrandTypesModel::insertGetId(['brand_type_name' => "$row[4]", 'status' => 1,'brand_id' => $brand_id, 'created_at' => Date('Y-m-d H:i:s'), 'updated_at' => Date('Y-m-d H:i:s') ]);
                                    }else{
                                        $brand_type_id = $brand_type_id[0];
                                    }

                                    $size_type_id = ProductSizeTypes::where(['size_type' => $row[8]])->pluck('id');
                                    if(!count($size_type_id) > 0){
                                        $size_type_id = ProductSizeTypes::insertGetId(['size_type' => $row[8], 'status' => 1]);
                                    }else{
                                        $size_type_id = $size_type_id[0];
                                    }

                                    if(!is_numeric($row[13])){
                                        $row[13] = 0;
                                    }
                                    $product_insert_array = [
                                                'product_name' => $row[0], 
                                                'product_nick_name' => $row[1], 
                                                'category_id'=> $category_id, 
                                                'brand_id' => $brand_id,
                                                'brand_type_id' => $brand_type_id, 
                                                'style' => $row[5], 
                                                'size_type_id'=> $size_type_id, 
                                                'color' => $row[6], 
                                                'season' => $row[7], 
                                                'retail_price' => $row[10],
                                                'description' => '',
                                                'product_images'=> $product_images, 
                                                'other_product_images' => $other_images, 
                                                'release_date' => $this->transformDate($row[12]), 
                                                'start_counter' => $row[13], 
                                                'trending' => 0, 
                                                'pass_value' => null, 
                                                'currency_code' => $row[11],
                                                'pass_code' => null,
                                                'status' => 1, 
                                                'scrapped_status' => 0, 
                                                'vip_status' => 0, 
                                                'created_at' => Date('Y-m-d H:i:s'), 
                                                'updated_at' => Date('Y-m-d H:i:s')];
                                    // echo'<pre>'; print_r( $product_insert_array); exit;
                                    $insert_product = ProductsModel::insertGetId($product_insert_array);
                                        if($insert_product){
                                            $size_array = explode(',',$row[9]); 
                                            if(count($size_array) > 0){
                                                foreach ($size_array as $index => $value) {
                                                    $size_id = ProductsSizes::where(['product_id' => $insert_product, 'size' => $value])->pluck('id');
                                                    if(!count($size_id) > 0){
                                                        $size_id = ProductsSizes::insertGetId(['size' => $value, 'status' => 1,'product_id' => $insert_product]);
                                                    }
                                                }
                                            }
                                        }
                                    } catch (\Illuminate\Database\QueryException $e) {
                                        return redirect('admin/new-product-import')->withErrors($e->getMessage());
                                    }
                                }
                                // exit;
                                return redirect('admin/new-product-import')->with('success', 'Product uploaded successfully!');
                        }else{
                            return redirect('admin/new-product-import')->withErrors('Oops Something went wrong');
                        }
                }else {
                    return redirect('admin/new-product-import')->withErrors('Excel File must be in given format like xlsx,xls,ods.');
                }
            }else{
                return redirect('admin/new-product-import')->withErrors('Excel File must be in given format like xlsx,xls,ods.');
            }
    	}else{
    		return view('admin/product/new-product-import');
    	}
    }

    public function customValidationMessages($array)
    {   
        $customMessages = array();
        foreach ($array[0] as $key => $value) {
            $index =  $key + 1;
            $customMessages[$key.'.0.required'] = ':attribute :input is required at '.$index.' row.';
            $customMessages[$key.'.0.unique'] = ':attribute :input is already taken at '.$index.' row.';
            $customMessages[$key.'.1.required'] = ':attribute :input is required at '.$index.' row.';
            $customMessages[$key.'.1.max'] = ':attribute :input must be less than 50 char at '.$index.' row.'; 
            $customMessages[$key.'.2.required'] = ':attribute :input is required at '.$index.' row.';
            $customMessages[$key.'.2.max'] = ':attribute :input must be less than 50 char at '.$index.' row.';
            $customMessages[$key.'.3.required'] = ':attribute :input is required at '.$index.' row.'; 
            $customMessages[$key.'.3.max'] = ':attribute :input must be less than 50 char at '.$index.' row.'; 
            $customMessages[$key.'4.required'] = ':attribute :input is required at '.$index.' row.';
            $customMessages[$key.'.4.max'] = ':attribute :input must be less than 50 char at '.$index.' row.';
            $customMessages[$key.'.4.unique'] =':attribute :input is already taken at '.$index.' row.'; 
            $customMessages[$key.'.5.required'] = ':attribute :input is required at '.$index.' row.';
            $customMessages[$key.'.5.max'] = ':attribute :input must be less than 50 char at '.$index.' row.'; 
            $customMessages[$key.'.6.required'] = ':attribute :input is required at '.$index.' row.';
            $customMessages[$key.'.6.max'] = ':attribute :input must be less than 50 char at '.$index.' row.'; 
            $customMessages[$key.'.7.required'] = ':attribute :input is required at '.$index.' row.';
            $customMessages[$key.'.7.max'] = ':attribute :input must be less than 50 char at '.$index.' row.';
            $customMessages[$key.'.8.required'] = ':attribute :input is required at '.$index.' row.'; 
            $customMessages[$key.'.8.max'] = ':attribute :input must be less than 50 char at '.$index.' row.'; 
            $customMessages[$key.'.9.required'] = ':attribute :input is required at '.$index.' row.';
            $customMessages[$key.'.9.max'] = ':attribute :input must be less than 255 char at '.$index.' row.'; 
            $customMessages[$key.'.10.required'] = ':attribute :input is required at '.$index.' row.';
            $customMessages[$key.'.10.max'] = ':attribute :input must be less than 8 char at '.$index.' row.';
            $customMessages[$key.'.11.required'] = ':attribute :input is required at '.$index.' row.';
            $customMessages[$key.'.11.max'] = ':attribute :input must be less than 8 char at '.$index.' row.';
            $customMessages[$key.'.12.required'] = ':attribute :input is required at '.$index.' row.'; 
            $customMessages[$key.'.12.date_format'] = ':attribute :input must be in format of YYYY-MM-DD at '.$index.' row.';
            $customMessages[$key.'.13.required'] = ':attribute :input is required at '.$index.' row.';
            $customMessages[$key.'.13.numeric'] = ':attribute :input must be less numeric at '.$index.' row.';
        }
        return $customMessages;
    }

    public function customValidationAttributes()
    {
        return [
            '*.0' => 'Product name',
            '*.1' => 'Product nick name',
            '*.2' => 'Category name',
            '*.3' => 'Brand name',
            '*.4' => 'Brand type',
            '*.5' => 'Style',
            '*.6' => 'Color',
            '*.7' => 'Season',
            '*.8' => 'Size type',
            '*.9' => 'Size',
            '*.10' => 'Retail price',
            '*.11' => 'Currency code',
            '*.12' => 'Release date',
            '*.13' => 'Start Counter',
            '*.14' => 'Product Image',
            '*.15' => 'Other Image',
        ];
    }

    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->toDateString();
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value)->toDateString();
        }
    }

    public function productList(){
        return view('admin/product/product-list');
    }

    public function productListPaginate(){
        $brand_ids = [];
        $category_ids = [];
        $brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
        $category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();
        $get_product_list = ProductsModel::select('product_name','brand_id','category_id','product_images','release_date','other_product_images','retail_price','status')->whereIn('brand_id', $brand_ids )->whereIn('category_id',$category_ids)->orderBy('updated_at','desc')->get();
        return Datatables::of($get_product_list)
                    ->setRowClass('clickable-row')
                    ->addIndexColumn()
                    ->addColumn('product_image_custom', function(ProductsModel $productList) {
                        if(isset($productList->product_image_link[0])){
                          if(!empty($productList->product_image_link[0])){
                            $image_url = url($productList->product_image_link[0]);
                          }else{
                            $image_url = url('public/v1/website/img/favicon.png');
                          }
                        }else{
                            $image_url = url('public/v1/website/img/favicon.png');
                        }

                        $return_data = "<img src='$image_url' class='mr-2' alt='image'> ";
                        return  $return_data;
                    })
                    ->addColumn('product_name_custom', function(ProductsModel $productList) {
                        $return_data = ucfirst($productList->product_name);
                        return  $return_data;
                    })
                     ->addColumn('status_custom', function(ProductsModel $productList) {
                        if($productList->status == 1){
                            $return_data = '<label class="badge badge-gradient-success">Active</label>';
                        }else{
                            $return_data = '<label class="badge badge badge-danger">Block</label>';
                        }
                        return $return_data;
                     })
                    ->addColumn('category_name', function(ProductsModel $productList) {
                        return  $productList->productCategory->category_name;
                    })
                    ->addColumn('brand_name', function(ProductsModel $productList) {
                        return  $productList->productBrand->brand_name;
                    })
                    ->rawColumns(['product_name_custom','status_custom','product_image_custom'])
                    ->make(true);
                //print_r($get_product_list);
                exit();

    }

    public function allproduct(){

        $shoesData = array();
		$brand_ids = [];
        $category_ids = [];
        $brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
        $category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();

		$shoesData['paginate'] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->whereIn('brand_id', $brand_ids )->whereIn('category_id',$category_ids)->where(['status' => '1','vip_status'=>'0'])->orderBy('updated_at', 'DESC')->paginate(10);
		

		$productsData = $shoesData['paginate']->toArray();

		$shoesData['allProducts'] = $productsData['data'];

		$shoesData['allBrands'] = BrandsModel::where(['status' => '1'])->get()->toArray();

        $shoesData['allSizeTypes'] = ProductSizeTypes::all()->toArray();
        return view('admin/product/allproduct-list', ['shoesData' => $shoesData]);
        
    }
    
    
    public function vipProducts(){

        $shoesData = array();
		$brand_ids = [];
        $category_ids = [];
        $brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
        $category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();

		$shoesData['paginate'] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->whereIn('brand_id', $brand_ids )->whereIn('category_id',$category_ids)->where(['status' => '1','vip_status'=>'1'])->orderBy('updated_at', 'DESC')->paginate();
		

		$productsData = $shoesData['paginate']->toArray();

		$shoesData['allProducts'] = $productsData['data'];

		$shoesData['allBrands'] = BrandsModel::where(['status' => '1'])->get()->toArray();

        $shoesData['allSizeTypes'] = ProductSizeTypes::all()->toArray();
        return view('admin/product/vip-product-list', ['shoesData' => $shoesData]);
        
    }


    public function deactivatedProducts(){

        $shoesData = array();
		$brand_ids = [];
        $category_ids = [];
        $brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
        $category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();

		$shoesData['paginate'] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->whereIn('brand_id', $brand_ids )->whereIn('category_id',$category_ids)->where(['status' => '0'])->orderBy('updated_at', 'DESC')->paginate();
		

		$productsData = $shoesData['paginate']->toArray();

		$shoesData['allProducts'] = $productsData['data'];

		$shoesData['allBrands'] = BrandsModel::where(['status' => '1'])->get()->toArray();

        $shoesData['allSizeTypes'] = ProductSizeTypes::all()->toArray();
        return view('admin/product/deactivated-product-list', ['shoesData' => $shoesData]);
        
    }

    
    

    public function productSearch(Request $request){
        $data = request()->get('myInput');
        if(!request()->get('myInput'))
        {
           return json_encode(['data'=>'Product not found', 'isFound'=>'0']);

        }else{
            $shoesData = array();
            $brand_ids = [];
            $category_ids = [];
            $brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
            $category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();
            $queryString = $data;
            $shoesData['paginate'] = ProductsModel::with([
                'productCategory',
                'productBrandType',
                'productBrand',
                'productSizeTypes'
            ])->whereIn('brand_id', $brand_ids )->whereIn('category_id',$category_ids)->where('product_name', 'LIKE', "%$queryString%")->where(['status' => '1','vip_status' => '0'])->orderBy('updated_at',
                'DESC')->paginate(10);
            $productsData = $shoesData['paginate']->toArray();
            $shoesData['allProducts'] = $productsData['data'];
            if($shoesData['allProducts']){
                return $shoesData;
            }else{
                return $shoesData;
            }
        }
    }
    
    
     public function vipProductSearch(Request $request){
        $data = request()->get('myInput');
        if(!request()->get('myInput'))
        {
           return json_encode(['data'=>'Product not found', 'isFound'=>'0']);

        }else{
            $shoesData = array();
            $brand_ids = [];
            $category_ids = [];
            $brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
            $category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();
            $queryString = $data;
            $shoesData['paginate'] = ProductsModel::with([
                'productCategory',
                'productBrandType',
                'productBrand',
                'productSizeTypes'
            ])->whereIn('brand_id', $brand_ids )->whereIn('category_id',$category_ids)->where('product_name', 'LIKE', "%$queryString%")->where(['status' => '1','vip_status' => '1'])->orderBy('updated_at',
                'DESC')->paginate(10);
            $productsData = $shoesData['paginate']->toArray();
            $shoesData['allProducts'] = $productsData['data'];
            if($shoesData['allProducts']){
                return $shoesData;
            }else{
                return $shoesData;
            }
        }
    }
    
    
    
    
    
    public function ajaxallproduct(Request $request){
            $shoesData = array();
            $brand_ids = [];
            $category_ids = [];
            $brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
            $category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();
            $shoesData['paginate'] = ProductsModel::with([
                'productCategory',
                'productBrandType',
                'productBrand',
                'productSizeTypes'
            ])->whereIn('brand_id', $brand_ids )->whereIn('category_id',$category_ids)->where(['status' => '1','vip_status'=>'0'])->orderBy('updated_at',
                'DESC')->paginate(10);
            $productsData = $shoesData['paginate']->toArray();
            $shoesData['allProducts'] = $productsData['data'];
            if($shoesData['allProducts']){
                return $shoesData;
            }else{
                return $shoesData;
            }
        
    }
    
    
    
    public function ajaxVipAllproduct(Request $request){
            $shoesData = array();
            $brand_ids = [];
            $category_ids = [];
            $brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
            $category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();
            $shoesData['paginate'] = ProductsModel::with([
                'productCategory',
                'productBrandType',
                'productBrand',
                'productSizeTypes'
            ])->whereIn('brand_id', $brand_ids )->whereIn('category_id',$category_ids)->where(['status' => '1','vip_status'=>'1'])->orderBy('updated_at',
                'DESC')->paginate(10);
            $productsData = $shoesData['paginate']->toArray();
            $shoesData['allProducts'] = $productsData['data'];
            if($shoesData['allProducts']){
                return $shoesData;
            }else{
                return $shoesData;
            }
        
    }




    public function eachProductDetail($id){
        $get_product_detail = ProductsModel::findorFail($id);
        return view('admin/product/each-product-detail',compact('get_product_detail'));
    }
    


    public function addNewProduct(ProductDetailRequest $request){
        if($request->isMethod('post')){
            $validated = $request->validated();
            $image_array = $request->img;
            $image_array_final = array();
            if(count($image_array) > 0){
                for($i = 0; $i < count($image_array); $i++){
                    if($image_array[$i]->isValid()){
                        $return_value = $this->uploadProductImage($image_array[$i]);
                        if($return_value){
                            $image_array_final[] = $return_value;
                        }
                    }
                }
                $image_array_final = implode(',',$image_array_final);
            }else{
                $image_array_final = '';
            }

            $other_image_array = $request->othere_img;
            $other_image_array_final = array();
            if(!empty($other_image_array) && count($other_image_array) > 0){
                for($i = 0; $i < count($other_image_array); $i++){
                    if($other_image_array[$i]->isValid()){
                        $return_value = $this->uploadProductImage($other_image_array[$i]);
                        if($return_value){
                            $other_image_array_final[] = $return_value;
                        }
                    }
                }
                $other_image_array_final = implode(',',$other_image_array_final);
            }else{
                $other_image_array_final = '';
            }
            $add_new_product = new ProductsModel();
            $add_new_product->product_name = $request->product_name;
            $add_new_product->product_nick_name = !empty($request->product_nick_name) ? $request->product_nick_name : '';
            $add_new_product->category_id = $request->category_name;
            $add_new_product->brand_id = $request->brand_name;
            $add_new_product->brand_type_id = $request->brand_type;
            $add_new_product->style = !empty($request->style) ? $request->style : '';
            $add_new_product->size_type_id = $request->size_type;
            $add_new_product->color = !empty($request->color) ? $request->color : '';
            $add_new_product->season = $request->season;
            $add_new_product->currency_code = $request->currency_code;
            $add_new_product->retail_price = $request->retail_price;
            $add_new_product->description = $request->description;
            $add_new_product->product_images = $image_array_final;
            $add_new_product->other_product_images = $other_image_array_final;
            $add_new_product->release_date = !empty($request->release_date) ? $request->release_date : '';
            $add_new_product->start_counter = $request->start_counter;
            $add_new_product->pass_code = $request->pass_code;
            $add_new_product->pass_value = $request->pass_value;
            $add_new_product->vip_status = ($request->vip_status != 1) ? 0 : 1;
            $add_new_product->trending = ($request->trending != 1) ? 0 : 1;
            $add_new_product->status = ($request->status != 1) ? 0 : 1;
            $add_new_product->created_at = Date('Y-m-d H:i:s');
            $add_new_product->updated_at = Date('Y-m-d H:i:s');
            $add_new_product->save();

            if(isset($add_new_product->id)){
                $size_array = $request->size;
                if(count($size_array) > 0){
                    for($i = 0; $i < count($size_array); $i++){
                        $insert_product_size = new ProductsSizes();
                        $insert_product_size->product_id = $add_new_product->id;
                        $insert_product_size->size = $size_array[$i];
                        $insert_product_size->status = 1;
                        $insert_product_size->created_at = Date('Y-m-d H:i:s');
                        $insert_product_size->updated_at = Date('Y-m-d H:i:s');
                        $insert_product_size->save();
                    }
                }
            }

            return redirect('admin/product-list')->with('success', 'Product uploaded successfully!');
        }else{
            $get_size_list = SizeListModel::where('status',1)->get();
            $get_category_list = CategoriesModel::where(['status' => 1])->get(['id','category_name']);
            $get_brand_list = BrandsModel::where(['status' => 1])->get(['id','brand_name']);
            $get_product_size_type = ProductSizeTypes::where(['status' => 1])->get(['id','size_type']);
            return view('admin/product/add-new-product',compact('get_category_list','get_brand_list','get_size_list','get_product_size_type'));
        }
    }

    public function getBrandTypeList(Request $request){
        return BrandTypesModel::where(['brand_id' => $request->id])->get(['id','brand_type_name'])->toJson();
    }

    public function editProduct(ProductDetailRequest $request,$id){
        if($request->isMethod('post')){
            $validated = $request->validated();
            $get_product_detail = ProductsModel::findorFail($id);
            $image_array = $request->img;
            $image_array_final = array();
            if(!empty($image_array) && count($image_array) > 0){
                for($i = 0; $i < 4; $i++){
                    if(isset($image_array[$i])){
                        if($image_array[$i]->isValid()){
                            $return_value = $this->uploadProductImage($image_array[$i]);
                            if($return_value){
                                $image_array_final[] = $return_value;
                            }
                        }
                    }else{
                        if(isset($get_product_detail->product_image_link[$i])){
                            $image_array_final[] = $get_product_detail->product_image_link[$i];
                        }
                    }
                }
                $image_array_final = implode(',',$image_array_final);
            }

            $other_image_array = $request->othere_img;
            $other_image_array_final = array();
            if(!empty($other_image_array) && count($other_image_array) > 0){
                for($i = 0; $i < 4; $i++){
                    if(isset($other_image_array[$i])){
                        if($other_image_array[$i]->isValid()){
                            $return_value = $this->uploadProductImage($other_image_array[$i]);
                            if($return_value){
                                $other_image_array_final[] = $return_value;
                            }
                        }
                    }else{
                        if(isset($get_product_detail->other_product_image_link[$i])){
                            $other_image_array_final[] = $get_product_detail->other_product_image_link[$i];
                        }
                    }
                }
                $other_image_array_final = implode(',',$other_image_array_final);
            }
            $get_product_detail->product_name = $request->product_name;
            $get_product_detail->product_nick_name = !empty($request->product_nick_name) ? $request->product_nick_name : '';
            $get_product_detail->category_id = $request->category_name;
            $get_product_detail->brand_id = $request->brand_name;
            $get_product_detail->brand_type_id = $request->brand_type;
            $get_product_detail->style = !empty($request->style) ? $request->style : '';
            $get_product_detail->size_type_id = $request->size_type;
            $get_product_detail->color = !empty($request->color) ? $request->color : '';
            $get_product_detail->season = !empty($request->season) ? $request->season : '';
            $get_product_detail->retail_price = $request->retail_price;
            $get_product_detail->currency_code = $request->currency_code;
            $get_product_detail->description = !empty($request->description) ? $request->description : '';

            if(isset($image_array_final) && !empty($image_array_final)){
                $get_product_detail->product_images = $image_array_final;
            }

            if(isset($other_image_array_final) && !empty($other_image_array_final)){
                $get_product_detail->other_product_images = $other_image_array_final;
            }
            
            $get_product_detail->release_date = !empty($request->release_date) ? $request->release_date : '';
            $get_product_detail->start_counter = $request->start_counter;
            $get_product_detail->pass_code = $request->pass_code;
            $get_product_detail->pass_value = $request->pass_value;
            $get_product_detail->vip_status = ($request->vip_status != 1) ? 0 : 1;
            $get_product_detail->trending = ($request->trending != 1) ? 0 : 1;
            $get_product_detail->status = ($request->status != 1) ? 0 : 1;
            $get_product_detail->created_at = Date('Y-m-d H:i:s');
            $get_product_detail->updated_at = Date('Y-m-d H:i:s');
            $get_product_detail->save();

            $delete_brand_array = $collection = collect($get_product_detail->productSizes()->pluck('size')->toArray());
            $delete_brand_array = $delete_brand_array->diff($request->size)->all();
            ProductsSizes::where(['product_id' => $id])->whereIn('size',$delete_brand_array)->delete();

            $insert_brand_array =  $collection = collect($request->size);
            $insert_brand_array = $insert_brand_array->diff($get_product_detail->productSizes()->pluck('size')->toArray())->all();
            if(isset($get_product_detail->id)){
                if(count($insert_brand_array) > 0){
                    for($i = 0; $i < count($insert_brand_array); $i++){
                        if(isset($insert_brand_array[$i]) && !empty($insert_brand_array[$i])){
                            $insert_product_size = new ProductsSizes();
                            $insert_product_size->product_id = $get_product_detail->id;
                            $insert_product_size->size = $insert_brand_array[$i];
                            $insert_product_size->status = 1;
                            $insert_product_size->created_at = Date('Y-m-d H:i:s');
                            $insert_product_size->updated_at = Date('Y-m-d H:i:s');
                            $insert_product_size->save();
                        }
                    }
                }
            }
            return redirect('admin/product-list')->with('success', 'Product updated successfully!');
        }else{
            $get_product_detail = ProductsModel::findorFail($id);
            $get_size_list = SizeListModel::where('status',1)->get();
            $get_category_list = CategoriesModel::where(['status' => 1])->get(['id','category_name']);
            $get_brand_list = BrandsModel::where(['status' => 1])->get(['id','brand_name']);
            $get_product_size_type = ProductSizeTypes::where(['status' => 1])->get(['id','size_type']);
            return view('admin/product/edit-product',compact('get_product_detail','get_category_list','get_brand_list','get_size_list','get_product_size_type'));
        }
    }

    public function actionOnProduct(Request $request, $id){
        $get_product_detail = ProductsModel::findorFail($id);
        if($get_product_detail->status == 1){
            $get_product_detail->status = 0;
            $get_product_detail->updated_at = Date('Y-m-d H:i:s');
            $get_product_detail->save();
             return redirect("admin/each-product-detail/$id")->with('success', 'Product deactivated successfully!');
        }else{
            $get_product_detail->status = 1;
            $get_product_detail->updated_at = Date('Y-m-d H:i:s');
            $get_product_detail->save();
             return redirect("admin/each-product-detail/$id")->with('success', 'Product activated successfully!');
        }
    }



    public function removeProduct(Request $request, $id){

        $get_product_detail = ProductsModel::findorFail($id);
        if($get_product_detail){
            ProductsModel::where(['id' => $id])->delete();
            return redirect("admin/product-list")->with('success', 'Product deleted successfully!');
        }else{
            return redirect("admin/product-list")->with('success', 'Product deleted successfully!');
        }

    }



    // SCRAP BIDS from STOCKX website
    public function scrap(Request $request , $id){

        if($request->isMethod('post')){
            
            $postedData = $request->all();
            if(isset($postedData['scrap_link']) && $postedData['scrap_link'] != ''){
                $stockxURL = $postedData['scrap_link'];
                if (strpos($stockxURL, 'stockx.com') == false) {
                    echo 'Please enter STOCKX URL'; die;
                }
            }else{
                 echo 'Please enter URL'; die;
            }
            
            
        }
        
        $productID = $id;        
        $user_id = Auth::id();
        $sku = '';
        $productData  = ProductsModel::with(['productSizeTypes','productSizes',])
                       ->findOrFail($productID)
                       ->toArray();
        
        if(!empty($productData) && is_array($productData)){
            $retailPrice = $productData['retail_price'];
        }
        // View Bids       - State 300
        // View Sell/Asks  - State 400
        
        
        $mozillaVersion = 6.0;
        $windowType = 8.0;
        
        
        $agents = array(
                        'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
                        'User-Agent: Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
                        'User-Agent: Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
                        'User-Agent: Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
                        'User-Agent: Mozilla/6.0 (Windows NT 10; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2661.102 Safari/537.36'
                   );
        
        $context = stream_context_create(
        array(
            "http" => array(
            "header" => $agents[array_rand($agents)]
                )
            )
        );

        $homepage = file_get_contents($stockxURL, false, $context);

        //die;
        //dd($homepage);
        //echo "<pre>"; print_r($homepage); die;
        //echo"<pre>"; print_r($homepage); die;
        
        
        //$stockxURL = "https://stackoverflow.com/questions/9445489/performing-http-requests-with-curl-using-proxy";
        //$homepage = file_get_contents($stockxURL); 
        
        //$homepage = $this->url_get_contents($stockxURL);
        // echo $homepage; die;
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($homepage);
        libxml_clear_errors();

        if($dom->loadHTML($homepage))
        {
            $scripts = $dom->getElementsByTagName('script');
            //echo $scripts->length; die;
            for ($i = 0; $i < $scripts->length; $i++)
            {
                if($i == '29'){ // 30th script on the DOM page
                   $script = $scripts->item($i);
                   $scriptCode = $script->nodeValue;
                   $fetchSKU = explode(":",$scriptCode);
                   
                   
                   $positionArray = $fetchSKU['10']; // 10th tag for fetching SKU
                   
// print_r($positionArray); die;
                   $fetchSKU = explode(",",$positionArray);
                   $sku = current($fetchSKU);
                   $sku = trim(str_replace('"', '', $sku));
                }
            }
            if($sku != ''){
                
                $url = "https://stockx.com/api/products/$sku/activity?currency=USD&state=300&limit=1000&page=1&sort=amount&order=ASC";
                
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url,
                    CURLOPT_USERAGENT => 'SCRAP DATA'
                ]);

                $resp = curl_exec($curl);
                curl_close($curl);
                $fetchSKU = explode("},{",$resp);
                //echo $productID; echo "<br/>";
               // echo "<pre>"; print_r($resp); die;
                foreach($fetchSKU as $key=>$value){


                    $bidsData = explode(",",$value);
                    print_r($bidsData); die;
                    $sizeData = $bidsData[count($bidsData) - 8];
                    $sizeQuotes = str_replace('"shoeSize":',"",$sizeData);
                    $size = str_replace('"', '', $sizeQuotes);
                    
                    $amountData = $bidsData[count($bidsData)-2];
                    $amount = str_replace('"localAmount":',"",$amountData);

                    if(!is_numeric($amount)){
                        continue;
                    }

                    $currencyData = $bidsData[count($bidsData)-1];
                    $currencyQuotes = str_replace('"localCurrency":',"",$currencyData);
                    $currency = str_replace('"', '', $currencyQuotes);
                    
                    if(is_numeric($currency)){
                        continue;
                    }
                    //echo $size; echo "---"; 
                    $sizeID = $this->getProductSizeID($size,$productID);
                    //echo  $sizeID;
                    //echo "<br/>";
                    //echo $sizeID; die;
                    $expiryDate = Carbon::now()->addDays(15); // Valid for Default 15 days
                    //$sizeID = 266; // for testing only
                    if($sizeID > '0'){

                        //$amount =  $currency.'$ '.$amount;
                        $bidderPrimaryID = ProductsBidder::firstOrCreate([
                            'user_id' => $user_id,
                            'product_id' => $productID,
                            'size_id' => $sizeID,
                            'bid_price' => $amount,
                            'status' => 1
                        ],
                        [
                            'user_id' => $user_id,
                            'product_id' => $productID,
                            'size_id' => $sizeID,
                            'actual_price' => $retailPrice,
                            'bid_price' => $amount,
                            'shipping_price' => 0,
                            'total_price' => $amount,
                            'commission_price' => 0,
                            'processing_fee' => 0,
                            'billing_address_id' => 1, // Default billing address
                            'shiping_address_id' => 2, // 2 LIVE Default shipping address
                            'bid_expiry_date' => $expiryDate, // 15 days 
                            'currency_code' => $currency,
                            'status' => 1
                        ]); 
                        $sizeID = 0;
                    }else{
                        continue;
                    }
                    
                    
                }
                
                $askUrl = "https://stockx.com/api/products/$sku/activity?currency=USD&state=400&limit=1000&page=1&sort=amount&order=ASC";
                
                $monitoringSaveBid = Monitoring::firstOrCreate
                ([
                            'product_id' => $productID,
                            'stockx_bid_url' => $url,
                            'stockx_ask_url' => $askUrl,
                ],
                [           
                            'product_id' => $productID,
                            'stockx_bid_url' => $url,
                            'stockx_ask_url' => $askUrl,
                            'status' => 0
                ]);
                
                
            }else{
                echo "WARNING - Stockx Security, Please try again later"; die;
            }

            
            
        }
        
        echo "BIDS IMPORTED SUCCESSFULLY !!";
    }
    
    
    
     public function getProductSizeId($size,$productID){
		
		$productDetails = ProductsModel::with(['productSizeTypes','productSizes'])->findOrFail($productID)->toArray();
		$productAllSizes = $productDetails['product_sizes'];
        //echo "<pre>"; print_r($productDetails); die;
		foreach($productAllSizes as $key=>$value){
                $sizeDB = $value['size'];
                if(strcmp($value['size'], $size) == 0){
                    return $value['id']; 
                }
			
			
		}		
	}
    
    
    // Default Billing & Shipping Address is required
    public function scrapSell(Request $request , $id){
        
        if($request->isMethod('post')){
            
            $postedData = $request->all();
            if(isset($postedData['scrap_link']) && $postedData['scrap_link'] != ''){
                $stockxURL = $postedData['scrap_link'];
                if (strpos($stockxURL, 'stockx.com') == false) {
                    echo 'Please enter STOCKX URL'; die;
                }
            }else{
                 echo 'Please enter URL'; die;
            }
            
            
        }
        
        
        
        $productID = $id;        
        $user_id = Auth::id();
        
        $productData  = ProductsModel::with(['productSizeTypes','productSizes',])
                       ->findOrFail($productID)
                       ->toArray();
        
        if(!empty($productData) && is_array($productData)){
            $retailPrice = $productData['retail_price'];
        }
        // View Bids       - State 300
        // View Sell/Asks  - State 400
        
        $mozillaVersion = 6.0;
        $windowType = 8.0;
        
        
        $agents = array(
                        'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
                        'User-Agent: Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
                        'User-Agent: Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
                        'User-Agent: Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
                        'User-Agent: Mozilla/6.0 (Windows NT 10; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2661.102 Safari/537.36'
                   );
        
        $context = stream_context_create(
        array(
            "http" => array(
            "header" => $agents[array_rand($agents)]
                )
            )
        );

        $homepage = file_get_contents($stockxURL, false, $context);
        
        
        //$homepage = file_get_contents($stockxURL); 
        
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($homepage);
        libxml_clear_errors();

        if($dom->loadHTML($homepage))
        {
            $scripts = $dom->getElementsByTagName('script');
            for ($i = 0; $i < $scripts->length; $i++)
            {
                if($i == '31'){ // 30th script on the DOM page
                   $script = $scripts->item($i);
                   //echo"<pre>"; print_r($script); die;
                   $scriptCode = $script->nodeValue;
                   $fetchSKU = explode(":",$scriptCode);
                   
                   
                   $positionArray = $fetchSKU['10']; // 10th tag for fetching SKU
                   $fetchSKU = explode(",",$positionArray);
                   $sku = current($fetchSKU);
                   $sku = trim(str_replace('"', '', $sku));
                }
            }

            if($sku != ''){
                
                $url = "https://stockx.com/api/products/$sku/activity?currency=USD&state=400&limit=1000&page=1&sort=amount&order=ASC";
                
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url,
                    CURLOPT_USERAGENT => 'SCRAP DATA'
                ]);

                $resp = curl_exec($curl);
                curl_close($curl);
                $fetchSKU = explode("},{",$resp);
                
                foreach($fetchSKU as $key=>$value){

                    $bidsData = explode(",",$value);
                    
                    $sizeData = $bidsData[count($bidsData)-8];
                    $sizeQuotes = str_replace('"shoeSize":',"",$sizeData);
                    $size = str_replace('"', '', $sizeQuotes);
                    
                    $amountData = $bidsData[count($bidsData)-2];
                    $amount = str_replace('"localAmount":',"",$amountData);

                    if(!is_numeric($amount)){
                        continue;
                    }

                    $currencyData = $bidsData[count($bidsData)-1];
                    $currencyQuotes = str_replace('"localCurrency":',"",$currencyData);
                    $currency = str_replace('"', '', $currencyQuotes);
                    
                    if(is_numeric($currency)){
                        continue;
                    }
                    //echo $size.'----'.$productID; echo "<br/>";
                    $sizeID = $this->getProductSizeID($size,$productID);
                    //echo $sizeID; die;
                    $expiryDate = Carbon::now()->addDays(15); // Valid for Default 15 days
                    //$sizeID = 266; // for testing only
                    if($sizeID > '0'){

                        $interest = ((3/100) * $amount) + ((10/100) * $amount) + 18 + 20;
                        $amount = $amount + $interest;
                        $bidderPrimaryID = ProductSellerModel::firstOrCreate([
                            'user_id' => $user_id,
                            'product_id' => $productID,
                            'size_id' => $sizeID,
                            'ask_price' => $amount,
                            'status' => 1
                        ],
                        [
                            'user_id' => $user_id,
                            'product_id' => $productID,
                            'size_id' => $sizeID,
                            'actual_price' => $retailPrice,
                            'ask_price' => $amount,
                            'shipping_price' => 0,
                            'total_price' => $amount,
                            'commission_price' => 0,
                            'processing_fee' => 0,
                            'billing_address_id' => 1, // Default billing address
                            'shiping_address_id' => 2, // 2 LIVE Default shipping address
                            'sell_expiry_date' => $expiryDate, // 15 days 
                            'currency_code' => $currency,
                            'status' => 1
                        ]); 
                        
                    }else{
                        continue;
                    }
                                        
                }
                
                
                $bidUrl = "https://stockx.com/api/products/$sku/activity?currency=USD&state=300&limit=1000&page=1&sort=amount&order=ASC";
                
                $monitoringSaveSell = Monitoring::firstOrCreate
                ([
                            'product_id' => $productID,
                            'stockx_bid_url' => $bidUrl,
                            'stockx_ask_url' => $url,
                ],
                [           
                            'product_id' => $productID,
                            'stockx_bid_url' => $bidUrl,
                            'stockx_ask_url' => $url,
                            'status' => 0
                ]);
                
            } 
            
        }
        
        echo "ASKS IMPORTED SUCCESSFULLY !!";
    }
    
    
    public function url_get_contents ($Url) {
        
        $proxy = '54.69.36.24:8080';
        
        $agents = array(
                        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
                        'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
                        'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
                        'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
                        'Mozilla/6.0 (Windows NT 10; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2661.102 Safari/537.36'
                   );
        
        
        if (!function_exists('curl_init')){ 
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch,CURLOPT_USERAGENT,$agents[array_rand($agents)]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
        
    }
    
    
    
    public function automatedScrapping(){
        
        $getProductList = Monitoring::where(['status' => '0'])->limit('10')->get()->toArray();

        if(is_array($getProductList) && !empty($getProductList)){
            
            foreach($getProductList as $key=>$value){
                
                $id = $value['id'];
                $productID = $value['product_id'];
                $bidScrapUrl = $value['stockx_bid_url'];
                $askScrapUrl = $value['stockx_ask_url'];
                
              //  $bidsSuccess = $this->autoScrapBids($bidScrapUrl,$id,$productID);
              
               $bidsSuccess = 1;
	        if($bidsSuccess){
                    $askSuccess = $this->autoScrapAsk($askScrapUrl,$id,$productID);
                }
                
                if($askSuccess){
                    Monitoring::where('id', $id)->update(array('status' => '1'));
                }
                
            }
            
            echo "DATA MONITORED SUCCESSFULLY !!";
            
        }else{
            
            DB::table('monitoring')->where('status', '=', 1)->update(array('status' => 0));
            echo "MONITORING POINTER RESET SUCCESSFULLY !!";
        }
        
    }
    
    
    
    public function autoScrapBids($bidScrapUrl,$id,$productID){
        
        $user_id = '1'; // Default Admin
        $productData  = ProductsModel::with(['productSizeTypes','productSizes',])
                       ->findOrFail($productID)
                       ->toArray();
        
        if(!empty($productData) && is_array($productData)){
            $retailPrice = $productData['retail_price'];
        }else{
            return true;
        }
        
        $agents = array(
                        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
                        'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
                        'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
                        'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
                        'Mozilla/6.0 (Windows NT 10; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2661.102 Safari/537.36'
                   );
        
            if($bidScrapUrl != '' && $id != ''){
                
                $url = $bidScrapUrl;
                
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url,
                    CURLOPT_USERAGENT => $agents[array_rand($agents)],
                ]);

                $resp = curl_exec($curl);
                curl_close($curl);
                $fetchSKU = explode("},{",$resp);
                
                foreach($fetchSKU as $key=>$value){

                    $bidsData = explode(",",$value);
                    
                    $sizeData = $bidsData[count($bidsData)-8];
                    $sizeQuotes = str_replace('"shoeSize":',"",$sizeData);
                    $size = str_replace('"', '', $sizeQuotes);
                    
                    $amountData = $bidsData[count($bidsData)-2];
                    $amount = str_replace('"localAmount":',"",$amountData);

                    if(!is_numeric($amount)){
                        continue;
                    }

                    $currencyData = $bidsData[count($bidsData)-1];
                    $currencyQuotes = str_replace('"localCurrency":',"",$currencyData);
                    $currency = str_replace('"', '', $currencyQuotes);
                    
                    if(is_numeric($currency)){
                        continue;
                    }
                    //echo $size.'----'.$productID; echo "<br/>";
                    $sizeID = $this->getProductSizeID($size,$productID);
                    //echo $sizeID; die;
                    $expiryDate = Carbon::now()->addDays(15); // Valid for Default 15 days
                    //$sizeID = 266; // for testing only
                    if($sizeID > '0'){

                        $bidderPrimaryID = ProductsBidder::firstOrCreate([
                            'user_id' => $user_id,
                            'product_id' => $productID,
                            'size_id' => $sizeID,
                            'bid_price' => $amount,
                            'status' => 1
                        ],
                        [
                            'user_id' => $user_id,
                            'product_id' => $productID,
                            'size_id' => $sizeID,
                            'actual_price' => $retailPrice,
                            'bid_price' => $amount,
                            'shipping_price' => 0,
                            'total_price' => $amount,
                            'commission_price' => 0,
                            'processing_fee' => 0,
                            'billing_address_id' => 1, // Default billing address
                            'shiping_address_id' => 2, // 2 LIVE Default shipping address
                            'bid_expiry_date' => $expiryDate, // 15 days 
                            'currency_code' => $currency,
                            'status' => 1
                        ]); 
                        $sizeID = 0;
                        
                    }else{
                        continue;
                    }
                                        
                }
                
                return true;
            } 
        
    }
    
    
     public function autoScrapAsk($AskScrapUrl,$id,$productID){
         
         $user_id = '1'; // Default Admin
         $productData  = ProductsModel::with(['productSizeTypes','productSizes',])
                       ->findOrFail($productID)
                       ->toArray();
        
        if(!empty($productData) && is_array($productData)){
            $retailPrice = $productData['retail_price'];
        }else{
            return true;
        }
        
        $agents = array(
                        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
                        'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
                        'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
                        'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
                        'Mozilla/6.0 (Windows NT 10; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2661.102 Safari/537.36'
                   );
        
            if($AskScrapUrl != '' && $id != ''){
                
                $url = $AskScrapUrl;
                
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url,
                    CURLOPT_USERAGENT => $agents[array_rand($agents)],
                ]);

                $resp = curl_exec($curl);
                curl_close($curl);
                $fetchSKU = explode("},{",$resp);
                
                foreach($fetchSKU as $key=>$value){

                    $bidsData = explode(",",$value);
                    
                    $sizeData = $bidsData[count($bidsData)-8];
                    $sizeQuotes = str_replace('"shoeSize":',"",$sizeData);
                    $size = str_replace('"', '', $sizeQuotes);
                    
                    $amountData = $bidsData[count($bidsData)-2];
                    $amount = str_replace('"localAmount":',"",$amountData);

                    if(!is_numeric($amount)){
                        continue;
                    }

                    $currencyData = $bidsData[count($bidsData)-1];
                    $currencyQuotes = str_replace('"localCurrency":',"",$currencyData);
                    $currency = str_replace('"', '', $currencyQuotes);
                    
                    if(is_numeric($currency)){
                        continue;
                    }
                    //echo $size.'----'.$productID; echo "<br/>";
                    $sizeID = $this->getProductSizeID($size,$productID);
                    //echo $sizeID; die;
                    $expiryDate = Carbon::now()->addDays(15); // Valid for Default 15 days
                    //$sizeID = 266; // for testing only
                    if($sizeID > '0'){

                        //$amount =  $currency.'$ '.$amount;
                        $bidderPrimaryID = ProductSellerModel::firstOrCreate([
                            'user_id' => $user_id,
                            'product_id' => $productID,
                            'size_id' => $sizeID,
                            'ask_price' => $amount,
                            'status' => 1
                        ],
                        [
                            'user_id' => $user_id,
                            'product_id' => $productID,
                            'size_id' => $sizeID,
                            'actual_price' => $retailPrice,
                            'ask_price' => $amount,
                            'shipping_price' => 0,
                            'total_price' => $amount,
                            'commission_price' => 0,
                            'processing_fee' => 0,
                            'billing_address_id' => 1, // Default billing address
                            'shiping_address_id' => 2, // 2 LIVE Default shipping address
                            'sell_expiry_date' => $expiryDate, // 15 days 
                            'currency_code' => $currency,
                            'status' => 1
                        ]); 
                        
                    }else{
                        continue;
                    }
                                        
                }
                
                 return true;              
            } 

     }
    
     
}
