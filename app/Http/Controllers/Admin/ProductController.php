<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * عرض جميع المنتجات
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // فلترة حسب التصنيف
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب المخزون
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->where('stock_quantity', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock_quantity', '<=', 0);
            } elseif ($request->stock_status === 'low_stock') {
                $query->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10);
            }
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(10);

        // إحصائيات
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
            'low_stock' => Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10)->count(),
            'total_value' => Product::selectRaw('sum(price * stock_quantity)')->value('sum'),
            'avg_price' => Product::avg('price'),
        ];

        $categories = ProductCategory::all();

        return view('admin.products.index', compact('products', 'stats', 'categories'));
    }

    /**
     * عرض نموذج إضافة منتج جديد
     */
    public function create()
    {
        $categories = ProductCategory::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * حفظ منتج جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'features' => 'nullable|array',
            'specifications' => 'nullable|array',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,out_of_stock',
        ]);

        $productData = $request->except(['images']);

        // إنشاء SKU تلقائي إذا لم يتم توفيره
        if (!$request->filled('sku')) {
            $productData['sku'] = 'PRD-' . strtoupper(Str::random(8));
        }

        // تحويل المصفوفات إلى JSON
        if ($request->has('features')) {
            $productData['features'] = json_encode($request->features);
        }

        if ($request->has('specifications')) {
            $productData['specifications'] = json_encode($request->specifications);
        }

        // معالجة الصور
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
        }

        if (!empty($images)) {
            $productData['images'] = json_encode($images);
        }

        try {
            $product = Product::create($productData);
            return redirect()->route('admin.products.index')->with('success', 'تم إضافة المنتج "' . $product->name . '" بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حفظ المنتج: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * عرض تفاصيل المنتج
     */
    public function show(Product $product)
    {
        $product->load(['category', 'orders.user']);

        // إحصائيات المنتج
        $stats = [
            'total_orders' => $product->orders()->count(),
            'total_quantity_sold' => $product->orders()->sum('quantity'),
            'total_revenue' => $product->orders()->sum('total_amount'),
            'avg_rating' => $product->rating,
            'reviews_count' => $product->reviews_count,
        ];

        return view('admin.products.show', compact('product', 'stats'));
    }

    /**
     * عرض نموذج تعديل المنتج
     */
    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * تحديث المنتج
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'features' => 'nullable|array',
            'specifications' => 'nullable|array',
            'new_images' => 'nullable|array|max:5',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,out_of_stock',
        ]);

        $productData = $request->except(['new_images', 'remove_images']);

        // تحويل المصفوفات إلى JSON
        if ($request->has('features')) {
            $productData['features'] = json_encode($request->features);
        }

        if ($request->has('specifications')) {
            $productData['specifications'] = json_encode($request->specifications);
        }

        // معالجة الصور
        $currentImages = json_decode($product->images, true) ?? [];

        // إزالة الصور المحددة
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageToRemove) {
                if (in_array($imageToRemove, $currentImages)) {
                    Storage::disk('public')->delete($imageToRemove);
                    $currentImages = array_diff($currentImages, [$imageToRemove]);
                }
            }
        }

        // إضافة صور جديدة
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('products', 'public');
                $currentImages[] = $path;
            }
        }

        $productData['images'] = json_encode(array_values($currentImages));

        $product->update($productData);

        return redirect()->route('admin.products.show', $product)->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * حذف المنتج
     */
    public function destroy(Product $product)
    {
        // التحقق من وجود طلبات مرتبطة
        if ($product->orders()->exists()) {
            return redirect()->back()->with('error', 'لا يمكن حذف المنتج لوجود طلبات شراء مرتبطة به');
        }

        // حذف الصور
        $images = json_decode($product->images, true) ?? [];
        foreach ($images as $image) {
            Storage::disk('public')->delete($image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح');
    }

    /**
     * تحديث حالة المنتج
     */
    public function updateStatus(Request $request, Product $product)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,out_of_stock'
        ]);

        $product->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'تم تحديث حالة المنتج بنجاح');
    }

    /**
     * تحديث المخزون
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'action' => 'required|in:set,add,subtract'
        ]);

        switch ($request->action) {
            case 'set':
                $product->update(['stock_quantity' => $request->stock_quantity]);
                break;
            case 'add':
                $product->increment('stock_quantity', $request->stock_quantity);
                break;
            case 'subtract':
                $product->decrement('stock_quantity', $request->stock_quantity);
                break;
        }

        // تحديث الحالة حسب المخزون
        if ($product->stock_quantity <= 0) {
            $product->update(['status' => 'out_of_stock']);
        } elseif ($product->status === 'out_of_stock') {
            $product->update(['status' => 'active']);
        }

        return redirect()->back()->with('success', 'تم تحديث المخزون بنجاح');
    }

    /**
     * إدارة تصنيفات المنتجات
     */
    public function categories()
    {
        $categories = ProductCategory::withCount('products')->get();
        return view('admin.products.categories', compact('categories'));
    }

    /**
     * إضافة تصنيف جديد
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories,slug',
            'description' => 'nullable|string',
        ]);

        ProductCategory::create($request->all());

        return redirect()->back()->with('success', 'تم إضافة التصنيف بنجاح');
    }

    /**
     * تحديث التصنيف
     */
    public function updateCategory(Request $request, ProductCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories,slug,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->back()->with('success', 'تم تحديث التصنيف بنجاح');
    }

    /**
     * حذف التصنيف
     */
    public function destroyCategory(ProductCategory $category)
    {
        if ($category->products()->exists()) {
            return redirect()->back()->with('error', 'لا يمكن حذف التصنيف لوجود منتجات مرتبطة به');
        }

        $category->delete();

        return redirect()->back()->with('success', 'تم حذف التصنيف بنجاح');
    }
}
