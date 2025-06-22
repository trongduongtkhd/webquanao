<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
session_start();
class CategoryProduct extends Controller
{
    public function add_category_product()
    {
        return view('admin.add_category_product');
    }

    public function all_category_product()
    {
        $all_category_product = DB::table('tbl_category_product')->get();
        $manager_category_product = view('admin.all_category_product')->with('all_category_product', $all_category_product);
        return view('admin_layout')->with('admin.all_category_product', $manager_category_product);
    }
    public function save_category_product(Request $request)
    {
        $data = array();
        //  $data['category_id'] = uniqid();
        $data['category_name'] = $request->category_product_name;
        $data['category_status'] = $request->category_product_status;
        $data['category_desc'] = $request->category_product_desc;
       DB::table('tbl_category_product')->insert($data);
    return Redirect::to('/add-category-product')->with('category_message', 'Thêm danh mục sản phẩm thành công');
    }
    public function unactive_category_product($category_product_id){
       DB::table('tbl_category_product')->where('category_id', $category_product_id)->update(['category_status' => 1]);
      return Redirect::to('/all-category-product')->with('category_message', 'Không kích hoạt danh mục sản phẩm thành công');
    }
    public function active_category_product($category_product_id){
         DB::table('tbl_category_product')->where('category_id', $category_product_id)->update(['category_status' => 0]);
      return Redirect::to('/all-category-product')->with('category_message', ' Kích hoạt danh mục sản phẩm thành công');
    }
    public function edit_category_product($category_product_id){
         $edit_category_product = DB::table('tbl_category_product')->where('category_id', $category_product_id)->get();
        $manager_category_product = view('admin.edit_category_product')->with('edit_category_product', $edit_category_product);
        return view('admin_layout')->with('admin.edit_category_product', $manager_category_product);
    }
    public function update_category_product(Request $request , $category_product_id){
        $data = array();
         $data['category_name'] = $request->category_product_name;
          $data['category_desc'] = $request->category_product_desc;
           DB::table('tbl_category_product')->where('category_id', $category_product_id)->update($data);
        return Redirect::to('/add-category-product')->with('category_message', 'Cập nhật  danh mục sản phẩm thành công');
    }
    public function delete_category_product( $category_product_id){
         DB::table('tbl_category_product')->where('category_id', $category_product_id)->delete();
        return Redirect::to('/all-category-product')->with('category_message', 'Xóa  danh mục sản phẩm thành công');
    }
// end function admin page
    public function show_category_home($category_id){
         $cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id', 'desc')->get();
        $category_by_id = DB::table('tbl_product')->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
            ->where('tbl_category_product.category_id', $category_id)->get();
            $category_name = DB::table('tbl_category_product')->where('tbl_category_product.category_id', $category_id)->limit(1)->get();
        return view('pages.category.show_category')->with('category', $cate_product)->with('brand', $brand_product)->with('category_by_id', $category_by_id)->with('category_name', $category_name);
    }
  
}