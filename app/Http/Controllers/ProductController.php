<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;


class ProductController extends Controller
{

    public function index(){
        return view('products.index',[
            'products'=>Product::latest()->paginate(5)
        ]);
    }
    public function create(){
        return view('products.create');
    }
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif|max:10000'
        ]);

        // Upload Image
        if ($request->file('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('products'), $imageName);
        } else {
            return redirect()->back()->withErrors(['image' => 'Image upload failed.']);
        }

        // Save Product
        $product = new Product();
        $product->image = $imageName;
        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->save();

        // Redirect with success message
        return redirect()->back()->with('success', 'Product Created!');
    }

    public function edit($id){
        $product = Product::where('id',$id)->first();
        return view('products.edit',[ 'product' => $product]);
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // Find the product by ID
        $product = Product::findOrFail($id);
    
        // Handle the image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($product->image && file_exists(public_path('products/' . $product->image))) {
                unlink(public_path('products/' . $product->image));
            }
    
            // Upload the new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('products'), $imageName);
            $product->image = $imageName;
        }
    
        // Update the product details
        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->save();
    
        // Redirect with success message
        return redirect()->back()->with('success', 'Product updated successfully!');
    }
        public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }


    public function show($id){
        $product = Product::where('id',$id)->first();
        return view('products.show',['product'=>$product]);
    }

}
