<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Posts;
use Validator;
use Illuminate\Support\Facades\Storage;
class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $posts = Posts::all();

        return view('pages.index')->with('posts',$posts);
    }

    public function updateData(Request $request){

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.register');
    }

    public function createAccount(Request $request){
        
        $this->validate($request, [
            'product_name' => 'bail|required|unique:posts',
            'price' => 'required',
            'qty' => 'required',
            'description' => 'required',
            'product_image' => 'image|nullable|max:1999'
        ]);
        if($request->hasFile('product_image')){
            $fileNameWithExt = $request->file('product_image')->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = pathinfo($fileNameWithExt, PATHINFO_EXTENSION);
            $fileNameToStore = $filename .'_'.time().'.'.$extension;
            $storePath = $request->file('product_image')->storeAs('public/product_images',$fileNameToStore);
        }else{
            $fileNameToStore = 'no_image.jpg';
        }
        $post = new Posts;
        $post->product_name = $request->product_name;
        $post->price = $request->price;
        $post->qty =$request->qty;
        $post->description = $request->description;
        $post->product_image = $fileNameToStore;
        $post->user_id = auth()->user()->id;
        $post->save();
        return redirect('/posts')->with('success','Successfully Saved!');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'product_name' => 'required',
        //     'price' => 'required',
        //     'qty' => 'required'
        // ]);
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'price' => 'required',
            'qty' => 'required'
        ]);
        if($validator->passes()){
            $post = new Posts;
            $post->product_name = $request->product_name;
            $post->price = $request->price;
            $post->qty =$request->qty;
            $post->user_id = auth()->user()->id;
            $post->save();
            return response()->json(['success'=>'Added new records.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
      
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Posts::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {   
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'description' => 'required',
            'product_image' => 'image|nullable|max:1999'
        ]);
        if($validator->passes()){
            $post = Posts::find($id);
            $post->product_name = $request->product_name;
            $post->price = $request->price;
            $post->qty = $request->qty;
            $post->description = $request->description;
            if($request->hasFile('product_image')){
                $fileNameWithExt = $request->file('product_image')->getClientOriginalName();
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('product_image')->getClientOriginalExtension();
                $fileNameToStore = $filename .'_'.time().'.'.$extension;
                $storePath = $request->file('product_image')->storeAs('public/product_images',$fileNameToStore);
                $post->product_image = $fileNameToStore;
            }
            return $post->save();
        }
        return response()->json(['error' => $validator->errors()->all()]);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post =  Posts::find($id);
        if($post->product_image !== 'no_image.jpg'){
            Storage::delete(['public/product_images/'.$post->product_image]);
            return $post->delete();
        }
        return $post->delete();
    }
}
