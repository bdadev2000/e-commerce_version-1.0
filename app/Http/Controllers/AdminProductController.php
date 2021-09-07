<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Components\Recusive;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductTag;
use App\Models\Tag;
use App\Traits\storageImageTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class AdminProductController extends Controller
{
    use StorageImageTrait;
    private $category;
    private $product;
    private $productImage;
    private $tag;
    private $productTag;

    public function __construct(Category $category,Product $product,ProductImage $productImage,
                                Tag $tag, ProductTag $productTag)
    {
        $this->category = $category;
        $this->product = $product;
        $this->productImage = $productImage;
        $this->tag = $tag;
        $this->productTag = $productTag;
    }
    public function index()
    {
        $products = $this->product->paginate(5);
        return view('admin.product.index',compact('products'));
    }

    public function create()
    {
        $htmlOption = $this->getCategory($parentId = '');        
        return view('admin.product.add',compact('htmlOption'));
    }

    public function getCategory($parentId)
    {
        $data = $this->category->all();
        $recusive = new Recusive($data);
        $htmlOption = $recusive->categoryRecusive($parentId);
        return $htmlOption;
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $dataProductCreate = [
                'name'=> $request->name,
                'price'=> $request->price,
                'content'=>$request->contents,
                'user_id'=> auth()->id(),
                'category_id'=>$request->category_id,
            ];
            $dataUploadFeatureImage = $this->storageTraitUpload($request,'feature_image_path','product');
            if(!empty($dataUploadFeatureImage))
            {
                $dataProductCreate['feature_image_path'] = $dataUploadFeatureImage['file_path'];
                $dataProductCreate['feature_image_name'] = $dataUploadFeatureImage['file_name'];
            }
            $product= $this->product::create($dataProductCreate);
            
            // Insert data to product_image
            if($request->hasFile('image_path'))
            {
                foreach($request->image_path as $fileItem){
                    $dataProductImageDetail = $this->storageTraitUploadMultiple($fileItem,'product');
                    $product->images()->create([
                        'image_path'=> $dataProductImageDetail['file_path'],
                        'image_name'=>$dataProductImageDetail['file_name'],
                    ]);
                }
            }
            //Insert tags for product
            if(!empty($request->tags)){
                foreach($request->tags as $tagItem){
                //insert to tags
                $tagInstance = $this->tag->firstOrCreate([
                    'name'=>$tagItem,
                ]);
                $tagIds[] = $tagInstance->id;
                }
            }
            
            
            $product->tags()->attach($tagIds);
            DB::commit();
            return redirect()->route('product.index');
        }catch(\Exception $exception){
            DB::rollback();
            Log::error('Message' . $exception->getMessage() . 'Line:' . $exception->getLine());
        }
        
    }

    public function edit($id)
    {
        $product = $this->product->find($id);
        $htmlOption = $this->getCategory($product->category_id);        
        return view('admin.product.edit',compact('htmlOption','product'));
    }

    public function update(Request $request,$id)
    {
        try{
            DB::beginTransaction();
            $dataProductUpdate = [
                'name'=> $request->name,
                'price'=> $request->price,
                'content'=>$request->contents,
                'user_id'=> auth()->id(),
                'category_id'=>$request->category_id,
            ];
            $dataUploadFeatureImage = $this->storageTraitUpload($request,'feature_image_path','product');
            if(!empty($dataUploadFeatureImage))
            {
                $dataProductUpdate['feature_image_path'] = $dataUploadFeatureImage['file_path'];
                $dataProductUpdate['feature_image_name'] = $dataUploadFeatureImage['file_name'];
            }
            $this->product->find($id)->update($dataProductUpdate);
            $product= $this->product->find($id);

            // Insert data to product_image
            if($request->hasFile('image_path'))
            $this->productImage->where('product_id',$id)->delete();
            {
                foreach($request->image_path as $fileItem){
                    $dataProductImageDetail = $this->storageTraitUploadMultiple($fileItem,'product');
                    $product->images()->create([
                        'image_path'=> $dataProductImageDetail['file_path'],
                        'image_name'=>$dataProductImageDetail['file_name'],
                    ]);
                }
            }
            //Insert tags for product
            if(!empty($request->tags)){
                foreach($request->tags as $tagItem){
                //insert to tags
                $tagInstance = $this->tag->firstOrCreate([
                    'name'=>$tagItem,
                ]);
                $tagIds[] = $tagInstance->id;
                }
            }
            
            
            $product->tags()->sync($tagIds);
            DB::commit();
            return redirect()->route('product.index');
        }catch(\Exception $exception){
            DB::rollback();
            Log::error('Message' . $exception->getMessage() . 'Line:' . $exception->getLine());
        }
    }

    public function delete($id)
    {
        try{
            $this->product->find($id)->delete();
            return response()->json([
                'code'=>200,
                'message'=>'success'
            ],200);
        }catch(\Exception $exception){
            Log::error('Message' . $exception->getMessage() . 'Line:' . $exception->getLine());
            return response()->json([
                'code'=>500,
                'message'=>'fail'
            ],500);
        }
    }
}

