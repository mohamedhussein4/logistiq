<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'original_price',
        'category_id',
        'stock_quantity',
        'status',
        'features',
        'specifications',
        'images',
        'rating',
        'reviews_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'decimal:1',
        'features' => 'array',
        'specifications' => 'array',
        'images' => 'array',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_OUT_OF_STOCK = 'out_of_stock';

    // Relationships
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function orders()
    {
        return $this->hasMany(ProductOrder::class);
    }

    // Static methods
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'متوفر',
            self::STATUS_INACTIVE => 'غير نشط',
            self::STATUS_OUT_OF_STOCK => 'نفذ المخزون',
        ];
    }

    // Helper methods
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0) . ' ر.س';
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return $this->original_price ? number_format($this->original_price, 0) . ' ر.س' : null;
    }

    public function getStatusNameAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }

    public function getIsOnSaleAttribute()
    {
        return $this->original_price && $this->original_price > $this->price;
    }

    public function getIsInStockAttribute()
    {
        return $this->stock_quantity > 0 && $this->status !== self::STATUS_OUT_OF_STOCK;
    }

    public function getMainImageAttribute()
    {
        return is_array($this->images) && count($this->images) > 0 ? $this->images[0] : null;
    }

    public function getStarsArrayAttribute()
    {
        $stars = [];
        $fullStars = floor($this->rating);
        $hasHalfStar = ($this->rating - $fullStars) >= 0.5;

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $fullStars) {
                $stars[] = 'full';
            } elseif ($i == $fullStars + 1 && $hasHalfStar) {
                $stars[] = 'half';
            } else {
                $stars[] = 'empty';
            }
        }

        return $stars;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0)
                    ->where('status', '!=', self::STATUS_OUT_OF_STOCK);
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('original_price')
                    ->whereColumn('original_price', '>', 'price');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Update stock quantity
    public function reduceStock($quantity)
    {
        if ($this->stock_quantity >= $quantity) {
            $this->decrement('stock_quantity', $quantity);

            if ($this->stock_quantity <= 0) {
                $this->update(['status' => self::STATUS_OUT_OF_STOCK]);
            }

            return true;
        }

        return false;
    }

    public function increaseStock($quantity)
    {
        $this->increment('stock_quantity', $quantity);

        if ($this->status === self::STATUS_OUT_OF_STOCK && $this->stock_quantity > 0) {
            $this->update(['status' => self::STATUS_ACTIVE]);
        }
    }
}
