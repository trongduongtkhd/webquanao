<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class BrandProduct extends Controller
{
     public function add_brand_product()
    {
        return view('admin.add_brand_product');
    }

    public function all_brand_product()
    {
        $all_brand_product = DB::table('tbl_brand')->get();
        $manager_brand_product = view('admin.all_brand_product')->with('all_brand_product', $all_brand_product);
        return view('admin_layout')->with('admin.all_brand_product', $manager_brand_product);
    }
    public function save_brand_product(Request $request)
    {
        $data = array();
        //  $data['category_id'] = uniqid();
        $data['brand_name'] = $request->brand_product_name;
        $data['brand_status'] = $request->brand_product_status;
        $data['brand_desc'] = $request->brand_product_desc;
       DB::table('tbl_brand')->insert($data);
    return Redirect::to('/add-brand-product')->with('brand_message', 'Thêm thương hiệu  sản phẩm thành công');
    }
    public function unactive_brand_product($brand_product_id){
       DB::table('tbl_brand')->where('brand_id', $brand_product_id)->update(['brand_status' => 1]);
      return Redirect::to('/all-brand-product')->with('brand_message', 'Không kích hoạt thương hiệu  sản phẩm thành công');
    }
     public function active_brand_product($brand_product_id){
       DB::table('tbl_brand')->where('brand_id', $brand_product_id)->update(['brand_status' => 0]);
      return Redirect::to('/all-brand-product')->with('brand_message', ' kích hoạt thương hiệu  sản phẩm thành công');
    }
    public function edit_brand_product($brand_product_id){
         $edit_brand_product = DB::table('tbl_brand')->where('brand_id', $brand_product_id)->get();
        $manager_brand_product = view('admin.edit_brand_product')->with('edit_brand_product', $edit_brand_product);
        return view('admin_layout')->with('admin.edit_brand_product', $manager_brand_product);
    }
    public function update_brand_product(Request $request , $brand_product_id){
        $data = array();
         $data['brand_name'] = $request->brand_product_name;
          $data['brand_desc'] = $request->brand_product_desc;
           DB::table('tbl_brand')->where('brand_id', $brand_product_id)->update($data);
        return Redirect::to('/all-brand-product')->with('brand_message', 'Cập nhật  thương hiệu sản phẩm thành công');
    }
    public function delete_brand_product( $brand_product_id){
         DB::table('tbl_brand')->where('brand_id', $brand_product_id)->delete();
        return Redirect::to('/all-brand-product')->with('brand_message', 'Xóa  danh mục thương hiệu  thành công');
    }
      public function show_brand_home($brand_id){
         $cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id', 'desc')->get();
        $brand_by_id = DB::table('tbl_product')->join('tbl_brand', 'tbl_product.brand_id', '=', 'tbl_brand.brand_id')
            ->where('tbl_product.brand_id', $brand_id)->get();
              $brand_name = DB::table('tbl_brand')->where('tbl_brand.brand_id', $brand_id)->limit(1)->get();
        return view('pages.brand.show_brand')->with('category', $cate_product)->with('brand', $brand_product)->with('brand_by_id', $brand_by_id)->with('brand_name', $brand_name);
    }
}