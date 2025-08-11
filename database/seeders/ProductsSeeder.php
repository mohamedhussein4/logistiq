<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductOrder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Product Categories
        $categories = [
            [
                'name' => 'أجهزة السيارات',
                'slug' => 'car-devices',
                'description' => 'أجهزة تتبع مخصصة للسيارات الشخصية والتجارية',
            ],
            [
                'name' => 'أجهزة الشاحنات',
                'slug' => 'truck-devices',
                'description' => 'أجهزة تتبع قوية ومتطورة للشاحنات والمركبات الثقيلة',
            ],
            [
                'name' => 'أجهزة متقدمة',
                'slug' => 'advanced-devices',
                'description' => 'أجهزة تتبع بتقنيات متطورة وذكاء اصطناعي',
            ],
            [
                'name' => 'الاكسسوارات',
                'slug' => 'accessories',
                'description' => 'كابلات وملحقات أجهزة التتبع',
            ],
        ];

        foreach ($categories as $categoryData) {
            ProductCategory::create($categoryData);
        }

        // Get created categories
        $carCategory = ProductCategory::where('slug', 'car-devices')->first();
        $truckCategory = ProductCategory::where('slug', 'truck-devices')->first();
        $advancedCategory = ProductCategory::where('slug', 'advanced-devices')->first();
        $accessoriesCategory = ProductCategory::where('slug', 'accessories')->first();

        // Create Products
        $products = [
            // Car Devices
            [
                'name' => 'جهاز تتبع GT06N',
                'description' => 'جهاز تتبع متقدم مع GPS وGSM، مناسب للسيارات والمركبات الصغيرة مع إمكانيات التتبع الفوري والتنبيهات الذكية',
                'price' => 450,
                'original_price' => 500,
                'category_id' => $carCategory->id,
                'stock_quantity' => 50,
                'features' => [
                    'GPS عالي الدقة',
                    'بطارية تدوم 30 يوم',
                    'مقاوم للماء IP67',
                    'تنبيهات فورية',
                    'تطبيق جوال مجاني'
                ],
                'specifications' => [
                    'الأبعاد' => '95 × 47 × 17 ملم',
                    'الوزن' => '60 جرام',
                    'البطارية' => '3.7V/5000mAh',
                    'درجة الحرارة' => '-20°C إلى +70°C',
                    'الشبكة' => 'GSM/GPRS 850/900/1800/1900MHz'
                ],
                'rating' => 4.5,
                'reviews_count' => 28,
            ],
            [
                'name' => 'جهاز تتبع شخصي PT02',
                'description' => 'جهاز تتبع صغير الحجم للأشخاص والأصول القيمة مع تصميم أنيق وبطارية طويلة المدى',
                'price' => 320,
                'category_id' => $carCategory->id,
                'stock_quantity' => 35,
                'features' => [
                    'وزن خفيف 50 جرام',
                    'إنذارات ذكية',
                    'وضع السكون الذكي',
                    'مقاوم للماء',
                    'تتبع دقيق'
                ],
                'specifications' => [
                    'الأبعاد' => '52 × 30 × 18 ملم',
                    'الوزن' => '50 جرام',
                    'البطارية' => '3.7V/800mAh',
                    'مدة التشغيل' => '7-15 يوم',
                    'دقة GPS' => '5 متر'
                ],
                'rating' => 4.0,
                'reviews_count' => 32,
            ],

            // Truck Devices
            [
                'name' => 'جهاز تتبع TK905',
                'description' => 'جهاز تتبع قوي للشاحنات والمركبات الثقيلة مع مقاومة عالية للصدمات ونظام تنبيه متطور',
                'price' => 680,
                'category_id' => $truckCategory->id,
                'stock_quantity' => 25,
                'features' => [
                    'GPS + GLONASS',
                    'بطارية تدوم 45 يوم',
                    'مستشعر درجة الحرارة',
                    'مقاوم للصدمات',
                    'تقارير مفصلة'
                ],
                'specifications' => [
                    'الأبعاد' => '150 × 90 × 30 ملم',
                    'الوزن' => '350 جرام',
                    'البطارية' => '3.7V/10000mAh',
                    'مقاومة الماء' => 'IP67',
                    'نطاق الحرارة' => '-40°C إلى +85°C'
                ],
                'rating' => 5.0,
                'reviews_count' => 15,
            ],

            // Advanced Devices
            [
                'name' => 'جهاز تتبع ST940',
                'description' => 'جهاز تتبع ذكي مع إنترنت الأشياء وتحليلات متقدمة باستخدام الذكاء الاصطناعي',
                'price' => 1200,
                'category_id' => $advancedCategory->id,
                'stock_quantity' => 15,
                'features' => [
                    'اتصال 4G/WiFi',
                    'ذكاء اصطناعي',
                    'تحليلات في الوقت الفعلي',
                    'كاميرا مدمجة',
                    'صوت ثنائي الاتجاه'
                ],
                'specifications' => [
                    'الأبعاد' => '120 × 70 × 25 ملم',
                    'الوزن' => '200 جرام',
                    'البطارية' => '3.7V/8000mAh',
                    'الكاميرا' => '2MP HD',
                    'التخزين' => '32GB'
                ],
                'rating' => 4.2,
                'reviews_count' => 8,
            ],
            [
                'name' => 'نظام إدارة الأسطول FMS',
                'description' => 'نظام متكامل لإدارة الأسطول مع برنامج مراقبة شامل وتحليلات متقدمة',
                'price' => 2800,
                'category_id' => $advancedCategory->id,
                'stock_quantity' => 0,
                'status' => 'out_of_stock',
                'features' => [
                    'برنامج مراقبة متقدم',
                    'تقارير تفصيلية',
                    'إدارة متعددة المستخدمين',
                    'تحليلات الوقود',
                    'صيانة تنبؤية'
                ],
                'specifications' => [
                    'المنصة' => 'Web + Mobile',
                    'قاعدة البيانات' => 'Cloud Based',
                    'المستخدمين' => 'غير محدود',
                    'التحديثات' => 'تلقائية',
                    'الدعم' => '24/7'
                ],
                'rating' => 4.8,
                'reviews_count' => 72,
            ],

            // Accessories
            [
                'name' => 'كابل توصيل OBD',
                'description' => 'كابل توصيل مع منفذ OBD للحصول على بيانات السيارة وتشخيص الأعطال',
                'price' => 180,
                'category_id' => $accessoriesCategory->id,
                'stock_quantity' => 80,
                'features' => [
                    'متوافق مع جميع السيارات',
                    'قراءة بيانات المحرك',
                    'كشف الأعطال',
                    'جودة عالية',
                    'سهل التركيب'
                ],
                'specifications' => [
                    'طول الكابل' => '1.5 متر',
                    'الموصل' => 'OBD-II 16 Pin',
                    'المادة' => 'PVC عالي الجودة',
                    'درجة الحرارة' => '-20°C إلى +80°C',
                    'الضمان' => 'سنة واحدة'
                ],
                'rating' => 4.7,
                'reviews_count' => 45,
            ],
            [
                'name' => 'هوائي GPS خارجي',
                'description' => 'هوائي GPS عالي الحساسية لتحسين دقة الإشارة في المناطق الصعبة',
                'price' => 95,
                'category_id' => $accessoriesCategory->id,
                'stock_quantity' => 60,
                'features' => [
                    'حساسية عالية',
                    'مقاوم للطقس',
                    'سهل التركيب',
                    'كابل 3 متر',
                    'مغناطيس قوي'
                ],
                'specifications' => [
                    'التردد' => '1575.42 MHz',
                    'المكسب' => '28dB',
                    'الكابل' => '3 متر RG174',
                    'الموصل' => 'SMA',
                    'الأبعاد' => '35 × 35 × 15 ملم'
                ],
                'rating' => 4.3,
                'reviews_count' => 22,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        // Create some product orders
        $users = User::where('status', User::STATUS_ACTIVE)->get();
        $products = Product::where('status', 'active')->get();

        foreach ($users->take(10) as $user) {
            // Create 1-3 orders per user
            $orderCount = rand(1, 3);
            for ($i = 0; $i < $orderCount; $i++) {
                $product = $products->random();
                $quantity = rand(1, 5);
                $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                $status = $statuses[array_rand($statuses)];

                ProductOrder::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'total_amount' => $product->price * $quantity,
                    'delivery_address' => $this->getRandomAddress(),
                    'notes' => $this->getRandomOrderNotes(),
                    'status' => $status,
                    'ordered_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }

    private function getRandomAddress(): string
    {
        $addresses = [
            'حي العليا، شارع الملك فهد، الرياض 12345',
            'حي الزهراء، طريق الملك عبدالعزيز، جدة 21589',
            'حي الفيصلية، شارع الأمير محمد بن عبدالعزيز، الدمام 31444',
            'حي النسيم، طريق الإمام سعود بن عبدالعزيز، الرياض 13258',
            'حي البوادي، شارع التحلية، جدة 23531',
            'حي الشاطئ، كورنيش الدمام، الدمام 32415',
            'حي الملقا، طريق الملك سلمان، الرياض 13521',
            'حي الروضة، شارع الستين، جدة 23435',
        ];

        return $addresses[array_rand($addresses)];
    }

    private function getRandomOrderNotes(): ?string
    {
        $notes = [
            'يرجى التواصل قبل التسليم',
            'التسليم في ساعات العمل فقط',
            'يفضل التسليم في نهاية الأسبوع',
            'عنوان الشركة في الطابق الثاني',
            'استلام من المستودع الخلفي',
            'التواصل مع الحارس للدخول',
            null, // Some orders without notes
            null,
        ];

        return $notes[array_rand($notes)];
    }
}
