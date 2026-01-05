<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Productimage;
use App\Models\Productprice;
use App\Models\Productcolor;
use App\Models\Productsize;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use App\Models\ProductVariant;
use App\Models\VariantImage;
use Toastr;
use File;
use Str;
use Image;
use App\Models\Tag;
use DB; 

class ProductController extends Controller
{
    public function getSubcategory(Request $request)
    {
        $subcategory = DB::table("subcategories")
        ->where("category_id", $request->category_id)
        ->pluck('subcategoryName', 'id');
        return response()->json($subcategory);
    }
    public function getChildcategory(Request $request)
    {
        $childcategory = DB::table("childcategories")
        ->where("subcategory_id", $request->subcategory_id)
        ->pluck('childcategoryName', 'id');
        return response()->json($childcategory);
    }
    
    
    function __construct()
    {
         $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    
    
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $query = Product::orderBy('id', 'DESC')->with('image', 'category', 'variants');

        if ($keyword) {
            // Check for hashtag search
            if (str_starts_with($keyword, '#')) {
                $tagName = ltrim($keyword, '#');
                $query->whereHas('product_tags', function ($q) use ($tagName) {
                    $q->where('name', 'LIKE', '%' . $tagName . '%')
                      ->orWhere('slug', 'LIKE', '%' . $tagName . '%');
                });
            } else {
                // Check if it's an exact SKU match first
                $skuProduct = ProductVariant::where('sku', $keyword)->first();
                if ($skuProduct) {
                    $query->where('id', $skuProduct->product_id);
                } else {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', '%' . $keyword . '%')
                          ->orWhere('product_code', 'LIKE', '%' . $keyword . '%')
                          ->orWhereHas('product_tags', function ($qt) use ($keyword) {
                              $qt->where('name', 'LIKE', '%' . $keyword . '%');
                          })
                          ->orWhereHas('variants', function ($qv) use ($keyword) {
                              $qv->where('sku', 'LIKE', '%' . $keyword . '%');
                          });
                    });
                }
            }
        }

        $data = $query->paginate(50);
        return view('backEnd.product.index', compact('data'));
    }
    public function create()
    {
        $categories = Category::where('parent_id','=','0')->where('status',1)->select('id','name','status')->with('childrenCategories')->get();
        $brands = Brand::where('status','1')->select('id','name','status')->get();
        $colors = Color::where('status','1')->get();
        $sizes = Size::where('status','1')->get();
        return view('backEnd.product.create',compact('categories','brands','colors','sizes'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'new_price' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'description' => 'required|string',
            'variants' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Tags processing
            $tagsString = $request->input('tags', '');
            $tagsArray = $tagsString ? array_map('trim', explode(',', $tagsString)) : [];
            $tagsJson = json_encode($tagsArray);

            // Get last product ID
            $last_id = Product::orderBy('id', 'desc')->select('id')->first();
            $last_id = $last_id ? $last_id->id + 1 : 1;

            // Prepare product data (exclude variant and image related fields)
            $input = $request->except(['image', 'files', 'proSize', 'proColor', 'sku', 'variants', 'color_images']);
            
            // Set required and optional fields
            $input['slug'] = strtolower(preg_replace('/[\/\s]+/', '-', $request->name . '-' . $last_id));
            $input['status'] = $request->status ? 1 : 0;
            $input['topsale'] = $request->topsale ? 1 : 0;
            $input['feature_product'] = $request->feature_product ? 1 : 0;
            $input['product_code'] = $request->product_code ?? 'P' . str_pad($last_id, 4, '0', STR_PAD_LEFT);
            $input['tags'] = $tagsJson;
            $input['subcategory_id'] = $request->subcategory_id ?? 0;
            $input['childcategory_id'] = $request->childcategory_id ?? null;
            $input['stock'] = $request->stock ?? 0;

            // Create product
            $product = Product::create($input);

            // Sync Tags
            if ($request->tags) {
                $tags = explode(',', $request->tags);
                $tagIds = [];
                foreach ($tags as $tagName) {
                    $tagName = trim($tagName);
                    if ($tagName) {
                        $tag = Tag::firstOrCreate(
                            ['name' => $tagName],
                            ['slug' => Str::slug($tagName)]
                        );
                        $tagIds[] = $tag->id;
                    }
                }
                $product->product_tags()->sync($tagIds);
            }

            // Store color images separately
            $colorImagesData = [];

            if ($request->has('color_images')) {
                foreach ($request->file('color_images') as $colorId => $images) {
                    foreach ($images as $key => $image) {
                        if (!$image->isValid()) {
                            continue;
                        }

                        // Use requested folder structure
                        $uploadDir = public_path('uploads/products');

                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $fileName = time() . "-{$colorId}-{$key}.webp";
                        $fullPath = $uploadDir . '/' . $fileName;

                        // Use Intervention Image to resize and convert
                        Image::make($image)
                            ->resize(1200, null, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })
                            ->encode('webp', 80)
                            ->save($fullPath);

                        // Save relative path
                        $colorImagesData[$colorId][] = 'uploads/products/' . $fileName;
                    }
                }
            }

            // Store product variants
            $processedColors = [];
            $variantCount = 0;
            
            foreach ($request->variants as $key => $variantData) {
                if (!isset($variantData['color_id']) || !isset($variantData['size_id']) || !isset($variantData['price'])) {
                    continue;
                }

                if (empty($variantData['color_id']) || empty($variantData['size_id'])) {
                    continue;
                }

                $sku = !empty($variantData['sku']) 
                    ? $variantData['sku'] 
                    : 'V' . str_pad($product->id, 4, '0', STR_PAD_LEFT) . '-' . $variantData['color_id'] . '-' . $variantData['size_id'];

                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $variantData['color_id'],
                    'size_id' => $variantData['size_id'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'] ?? 0,
                    'sku' => $sku,
                    'availability' => isset($variantData['availability']) ? true : false,
                ]);

                $variantCount++;

                $colorId = $variantData['color_id'];
                if (isset($colorImagesData[$colorId]) && !in_array($colorId, $processedColors)) {
                    foreach ($colorImagesData[$colorId] as $imagePath) {
                        VariantImage::create([
                            'product_variant_id' => $variant->id,
                            'image_path' => $imagePath,
                        ]);
                    }
                    $processedColors[] = $colorId;
                }
            }

            if ($variantCount === 0) {
                DB::rollback();
                Toastr::error('Error', 'No valid variants were created');
                return redirect()->back()->withInput();
            }

            DB::commit();
            Toastr::success('Success', 'Product created successfully with ' . $variantCount . ' variants');
            return redirect()->route('products.index');

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Error', 'Failed to create product: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $edit_data = Product::with(['images', 'variants.color', 'variants.size', 'variants.images', 'product_tags'])->findOrFail($id);
        $categories = Category::where('parent_id', '0')->where('status', 1)->with('childrenCategories')->get();
        $brands = Brand::where('status', 1)->get();
        $sizes = Size::where('status', 1)->get();
        $colors = Color::where('status', 1)->get();
        $subcategory = Subcategory::where('category_id', $edit_data->category_id)->get();
        $childcategory = Childcategory::where('subcategory_id', $edit_data->subcategory_id)->get();
        $selectsizes = Productsize::where('product_id', $id)->get();
        $selectcolors = Productcolor::where('product_id', $id)->get();
        
        $existingVariants = $edit_data->variants->groupBy('color_id');

        return view('backEnd.product.edit', [
            'edit_data' => $edit_data,
            'categories' => $categories,
            'brands' => $brands,
            'totalsizes' => $sizes, // For main edit view
            'totalcolors' => $colors, // For main edit view
            'sizes' => $sizes, // For variants view
            'colors' => $colors, // For variants view
            'subcategory' => $subcategory,
            'childcategory' => $childcategory,
            'selectsizes' => $selectsizes,
            'selectcolors' => $selectcolors,
            'existingVariants' => $existingVariants
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'new_price' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'description' => 'required|string',
            'variants' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);

            $tagsString = $request->input('tags', '');
            $tagsArray = $tagsString ? array_map('trim', explode(',', $tagsString)) : [];
            $tagsJson = json_encode($tagsArray);

            $input = $request->except(['image', 'files', 'proSize', 'proColor', 'sku', 'variants', 'color_images', '_token', '_method']);
            
            $input['status'] = $request->status ? 1 : 0;
            $input['topsale'] = $request->topsale ? 1 : 0;
            $input['feature_product'] = $request->feature_product ? 1 : 0;
            $input['tags'] = $tagsJson;
            $input['subcategory_id'] = $request->subcategory_id ?? 0;
            $input['childcategory_id'] = $request->childcategory_id ?? null;

            $product->update($input);

            // Sync Tags
            if ($request->tags) {
                $tags = explode(',', $request->tags);
                $tagIds = [];
                foreach ($tags as $tagName) {
                    $tagName = trim($tagName);
                    if ($tagName) {
                        $tag = Tag::firstOrCreate(
                            ['name' => $tagName],
                            ['slug' => Str::slug($tagName)]
                        );
                        $tagIds[] = $tag->id;
                    }
                }
                $product->product_tags()->sync($tagIds);
            }

            // Delete old variants and their images
            foreach ($product->variants as $variant) {
                foreach ($variant->images as $variantImage) {
                    if (file_exists($variantImage->image_path)) {
                        unlink($variantImage->image_path);
                    }
                    $variantImage->delete();
                }
                $variant->delete();
            }

            // Store new color images
            $colorImagesData = [];

            if ($request->has('color_images') && $request->color_images) {
                foreach ($request->color_images as $colorId => $images) {
                    if (!isset($colorImagesData[$colorId])) {
                        $colorImagesData[$colorId] = [];
                    }

                    if (is_array($images)) {
                        foreach ($images as $key => $image) {
                            if ($image && $image->isValid()) {
                                $name = time() . '-' . $colorId . '-' . $key . '-' . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                                $name = strtolower(preg_replace('/\s+/', '-', $name)) . '.webp';
                                $uploadPath = public_path('uploads/products/');

                                if (!file_exists($uploadPath)) {
                                    mkdir($uploadPath, 0777, true);
                                }

                                $fullPath = $uploadPath . $name;
                                Image::make($image)
                                    ->resize(1200, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                        $constraint->upsize();
                                    })
                                    ->encode('webp', 80)
                                    ->save($fullPath);
                                    
                                $colorImagesData[$colorId][] = 'uploads/products/' . $name;
                            }
                        }
                    }
                }
            }

            // Store new variants
            $processedColors = [];
            $variantCount = 0;
            
            foreach ($request->variants as $key => $variantData) {
                if (!isset($variantData['color_id']) || !isset($variantData['size_id']) || !isset($variantData['price'])) {
                    continue;
                }

                if (empty($variantData['color_id']) || empty($variantData['size_id'])) {
                    continue;
                }

                $sku = !empty($variantData['sku']) 
                    ? $variantData['sku'] 
                    : 'V' . str_pad($product->id, 4, '0', STR_PAD_LEFT) . '-' . $variantData['color_id'] . '-' . $variantData['size_id'];

                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $variantData['color_id'],
                    'size_id' => $variantData['size_id'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'] ?? 0,
                    'sku' => $sku,
                    'availability' => isset($variantData['availability']) ? true : false,
                ]);

                $variantCount++;

                $colorId = $variantData['color_id'];
                if (isset($colorImagesData[$colorId]) && !in_array($colorId, $processedColors)) {
                    foreach ($colorImagesData[$colorId] as $imagePath) {
                        VariantImage::create([
                            'product_variant_id' => $variant->id,
                            'image_path' => $imagePath,
                        ]);
                    }
                    $processedColors[] = $colorId;
                }
            }

            if ($variantCount === 0) {
                DB::rollback();
                Toastr::error('Error', 'No valid variants were created');
                return redirect()->back()->withInput();
            }

            DB::commit();
            Toastr::success('Success', 'Product updated successfully with ' . $variantCount . ' variants');
            return redirect()->route('products.index');

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Error', 'Failed to update product: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
 
    public function inactive(Request $request)
    {
        $inactive = Product::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Product::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = Product::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
    public function imgdestroy(Request $request)
    { 
        $delete_data = Productimage::find($request->id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    } 
    public function pricedestroy(Request $request)
    { 
        $delete_data = Productprice::find($request->id);
        $delete_data->delete();
        Toastr::success('Success','Product price delete successfully');
        return redirect()->back();
    }
    public function update_deals(Request $request){
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['topsale' => $request->status]);
        return response()->json(['status'=>'success','message'=>'Hot deals product status change']);
    }
    public function update_feature(Request $request){
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['feature_product' => $request->status]);
        return response()->json(['status'=>'success','message'=>'Feature product status change']);
    }
    public function update_status(Request $request){
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['status' => $request->status]);
        return response()->json(['status'=>'success','message'=>'Product status change successfully']);
    }
    
public function copy($id)
{
    $product = Product::with(['colors', 'sizes', 'images'])->findOrFail($id);

    // নতুন product তৈরি
    $newProduct = $product->replicate(); // সব attributes copy করে
    $newProduct->slug = $product->slug . '-copy-' . time();
    $newProduct->product_code = 'P' . str_pad(Product::max('id') + 1, 4, '0', STR_PAD_LEFT);
    $newProduct->name = $product->name . ' (Copy)';
    $newProduct->save();

    // Sizes ও Colors গুলো attach করি
    $newProduct->sizes()->attach($product->sizes->pluck('id'));
    $newProduct->colors()->attach($product->colors->pluck('id'));

    // Image গুলো কপি করি (same image path use করবো)
    foreach ($product->images as $image) {
        $newImage = new Productimage();
        $newImage->product_id = $newProduct->id;
        $newImage->image = $image->image;
        $newImage->save();
    }

    Toastr::success('Success', 'Product copied successfully');
    return redirect()->route('products.index');
}

}
