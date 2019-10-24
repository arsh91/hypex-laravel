<?php

namespace App\Imports;

// use App\Models\ProductsModel;
// use App\Models\CategoriesModel;
// use App\Models\CategoryBrands;
// use App\Models\ProductsSizes;
// use App\Models\ProductSizeTypes;
// use App\Models\BrandTypesModel;
// use App\Models\BrandsModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;

class NewProductImport implements WithStartRow, ToCollection
{
    use Importable;

    public function startRow() : int {
        return 2;
    }

    public function collection(Collection $rows)
    {
        
    }

    // public function onError(\Throwable $e)
    // {
    //     return redirect('admin/new-product-import')->withErrors($e->getMessage());
    // }
    
}