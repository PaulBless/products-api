<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Currency;
use OpenExchangeRates;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        // use query builder class
        // $products = Product::get()->orderBy('price','ASC');
        $products = DB::table('products')->orderBy('price','DESC')->get();
        // $products = Product::orderBy('price','DESC')->get();
        if( $products ){
            return response()->json(['status'=>200, 'message'=>'List of Products!!', 'data' => $products]);
        } else {
            return response()->json(['status'=>404, 'message'=>'Products List Empty!!']);

        }
    }

    /** 
     * Return list of products based on currency
     * as specified by user
    */
    public function products($currency = null) 
    {
        $param1 = 'USD'; $param2 = 'EUR'; $param3 = 'GBP'; $param4 = 'GHS';
        if( $currency === null ) {
            return response()->json(Product::orderBy('price','asc')->get(), 200);
        } else if ( $currency == $param1 ){
            $products = Product::select('name','description','price')->pluck();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        // get incoming request inputs data
        $data = $request->all();

        // validation rule
        $validate = Validator::make($data, [
            'name' => 'required|string|unique:products',
            'description' => 'required|string',
            'price' => 'required'
        ]);
        // validate the request
        if( $validate->fails() ) {
            return response()->json(['sorry'=>'Validation failed..', 'error'=>$validate->errors() ]);
        }


        // add new resource
        $product = new Product;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        if( $product->save() ) {
            return response()->json(['status'=>201, 'message' => 'Product Added Successfully', 'data' => $product ]);

        } else {
            return response()->json(['status'=>400, 'message'=>'Record Not Saved..']);
        }

        // $product = Product::create($data);
        // if( $product ) {
        //     return response()->json(['status'=>201, 'message' => 'Product Added Successfully', 'data' => $product ]);
        // } else {
        //     return response()->json(['status'=>400, 'message'=>'Record Not Saved..']);
        // }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        // find by product ID
        $product = Product::find($id);
        if($product) {
            return response()->json(['status'=>200, 'product'=>$product]);
        } else {
            return response()->json(['status'=>404, 'message'=>'Product Not Found!!']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product) 
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(empty($request)) { return response()->json(['error'=>'specify product details!']); }

        // find product by ID
        $product = Product::find($id);
        if( $product ) {
            
            // get incoming request data
            $data = $request->all();

            // validation rules
             $validate = Validator::make($data, [
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required'
            ]);
            // validate the request
            if( $validate->fails() ) {
                return response()->json(['Validation failed..', 'error'=>$validate->errors() ]);
            }

            //update the product resource
            $product->update($data);
            return response()->json(['status'=>200, 'message'=>'Product Updated Successfully!', 'product'=>$product]);


        } else {
            // show error response for product not found
            return response()->json(['status'=> 404, 'message'=> 'Update Failed!! Product Not Found ']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find if Product Exists
        $product = Product::find($id);
        if(!$product){
            return response()->json(['status'=>404, 'message'=>'Product Not Found!!']);
        }

        // proceed to delete specific product resource
        $product->delete($id);
        return response()->json(['status'=>200, 'message'=> 'Product Deleted Successfully']);
    }
}
