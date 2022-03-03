<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Validator;
use SweetAlert;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::select('id','name','description','price')->get();
        return view('home', compact('products'));
        // return view('home')->with('home',$products);
        // return view('home', ['products' => $products]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        //
        return view('products.create');
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
        $product = Product::create($data);
        if( $product ) {
            return response()->json(['status'=>201, 'message' => 'Product Added Successfully', 'data' => $product ]);
        } else {
            return response()->json(['status'=>400, 'message'=>'Record Not Saved..']);
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $findProduct = Product::find($id);
        if($findProduct){

            $data = $request->all();

            $validate = Validator::make($data, [
                'name' => 'required|string',
                'gender' => 'required',
                'work_desire' => 'required',
                'Productname' => 'required|string',
                'password' => 'required|string|min:8',
            ]);
            // validate incoming requests
            if($validate->fails()) {
                return response()->json(['Oops'=>'Something went wrong!!', 'error'=>$validate->errors() ]);
            }

            $findProduct->update($data);
            // $findProduct = Product::update($request->all());
            return response()->json(['status'=>200, 'message'=>'Updated Successfully!', 'data'=>$findProduct]);
        }else {
            return response()->json(['status'=>404, 'message'=>'Product Record Not Found!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 1st find Product exists
        $Product = Product::find($id);
        if($Product){
            $Product->delete($id);
            // $Product = Product::delete($id);
            return response()->json(['status'=>200, 'message'=>'Deleted Successfully!']);
        }else {
            return response()->json(['status'=>404, 'message'=>'Product Record Not Found']);
        }
    }

}
