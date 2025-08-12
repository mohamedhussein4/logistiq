# اختبار نظام إدارة المدفوعات - Link2u

## قائمة الاختبارات

### 1. اختبار قاعدة البيانات

#### ✅ إنشاء الجداول
```bash
php artisan migrate
```
**النتيجة المتوقعة:** إنشاء 4 جداول جديدة بدون أخطاء

#### ✅ تشغيل البذور
```bash
php artisan db:seed --class=PaymentAccountsSeeder
```
**النتيجة المتوقعة:** إنشاء حسابات بنكية ومحافظ إلكترونية تجريبية

### 2. اختبار النماذج (Models)

#### ✅ BankAccount Model
```php
// اختبار إنشاء حساب بنكي
$account = BankAccount::create([
    'bank_name' => 'البنك الأهلي السعودي',
    'account_name' => 'Link2u للتجارة',
    'account_number' => '1234567890',
    'status' => 'active'
]);

// اختبار العلاقات
$account->paymentRequests; // يجب أن يعيد collection فارغ
```

#### ✅ ElectronicWallet Model
```php
// اختبار إنشاء محفظة إلكترونية
$wallet = ElectronicWallet::create([
    'wallet_name' => 'STC Pay',
    'wallet_type' => 'stc_pay',
    'account_number' => '966501234567',
    'account_name' => 'Link2u للتجارة',
    'status' => 'active'
]);

// اختبار العلاقات
$wallet->paymentRequests; // يجب أن يعيد collection فارغ
```

#### ✅ PaymentRequest Model
```php
// اختبار إنشاء طلب دفع
$request = PaymentRequest::create([
    'user_id' => 1,
    'request_number' => 'PAY-001',
    'payment_type' => 'product_order',
    'related_id' => 1,
    'related_type' => 'App\Models\ProductOrder',
    'amount' => 100.00,
    'payment_method' => 'bank_transfer',
    'status' => 'pending'
]);

// اختبار العلاقات
$request->user; // يجب أن يعيد User model
$request->related; // يجب أن يعيد related model
```

#### ✅ PaymentProof Model
```php
// اختبار إنشاء إثبات دفع
$proof = PaymentProof::create([
    'payment_request_id' => 1,
    'file_name' => 'proof.jpg',
    'file_path' => 'payment_proofs/proof.jpg',
    'file_type' => 'image',
    'file_size' => '1024',
    'status' => 'pending'
]);

// اختبار العلاقات
$proof->paymentRequest; // يجب أن يعيد PaymentRequest model
```

### 3. اختبار المتحكمات (Controllers)

#### ✅ PaymentController
```bash
# اختبار الوصول لصفحة دفع المنتج
GET /payments/product/1

# اختبار الوصول لصفحة دفع الفاتورة
GET /payments/invoice/1

# اختبار إنشاء طلب دفع
POST /payments

# اختبار رفع إثبات الدفع
POST /payments/1/proof
```

#### ✅ PaymentManagementController
```bash
# اختبار عرض قائمة طلبات الدفع
GET /admin/payments

# اختبار عرض الحسابات البنكية
GET /admin/payments/bank-accounts

# اختبار عرض المحافظ الإلكترونية
GET /admin/payments/electronic-wallets

# اختبار إنشاء حساب بنكي
POST /admin/payments/bank-accounts

# اختبار إنشاء محفظة إلكترونية
POST /admin/payments/electronic-wallets
```

### 4. اختبار الواجهات (Views)

#### ✅ صفحة إدارة الحسابات البنكية
- [ ] عرض الإحصائيات
- [ ] إظهار/إخفاء نموذج الإضافة
- [ ] عرض قائمة الحسابات
- [ ] نموذج التعديل
- [ ] حذف الحسابات

#### ✅ صفحة إدارة المحافظ الإلكترونية
- [ ] عرض الإحصائيات
- [ ] إظهار/إخفاء نموذج الإضافة
- [ ] عرض قائمة المحافظ
- [ ] نموذج التعديل
- [ ] حذف المحافظ

#### ✅ صفحة إدارة المدفوعات
- [ ] عرض الإحصائيات
- [ ] عرض قائمة طلبات الدفع
- [ ] الروابط للحسابات والمحافظ
- [ ] تحديث حالة الطلبات

### 5. اختبار الوظائف (JavaScript)

#### ✅ إدارة النماذج
```javascript
// اختبار إظهار/إخفاء نموذج الإضافة
toggleAddForm();

// اختبار إظهار/إخفاء نموذج التعديل
editBankAccount(1);
hideEditModal();

// اختبار حذف العناصر
deleteBankAccount(1);
deleteWallet(1);
```

#### ✅ التفاعل مع المستخدم
- [ ] إغلاق النماذج بالنقر خارجها
- [ ] إغلاق النماذج بمفتاح Escape
- [ ] تأكيد الحذف
- [ ] رسائل الخطأ والنجاح

### 6. اختبار الأمان

#### ✅ التحقق من الصلاحيات
```bash
# اختبار الوصول بدون تسجيل دخول
GET /admin/payments/bank-accounts
# يجب أن يعيد 401 أو redirect للogin

# اختبار الوصول بدون صلاحيات مدير
GET /admin/payments/bank-accounts
# يجب أن يعيد 403
```

#### ✅ حماية CSRF
```bash
# اختبار إرسال طلب بدون token
POST /admin/payments/bank-accounts
# يجب أن يعيد 419
```

#### ✅ التحقق من الملكية
```bash
# اختبار الوصول لطلب دفع ليس للمستخدم
GET /payments/999
# يجب أن يعيد 403 أو 404
```

### 7. اختبار الأداء

#### ✅ سرعة التحميل
- [ ] صفحة الحسابات البنكية < 2 ثانية
- [ ] صفحة المحافظ الإلكترونية < 2 ثانية
- [ ] صفحة إدارة المدفوعات < 3 ثانية

#### ✅ استهلاك الذاكرة
- [ ] تحميل 100 حساب بنكي < 50MB
- [ ] تحميل 100 محفظة < 50MB
- [ ] تحميل 1000 طلب دفع < 100MB

### 8. اختبار التوافق

#### ✅ المتصفحات
- [ ] Chrome (أحدث إصدار)
- [ ] Firefox (أحدث إصدار)
- [ ] Safari (أحدث إصدار)
- [ ] Edge (أحدث إصدار)

#### ✅ الأجهزة
- [ ] Desktop (1920x1080)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

### 9. اختبار قاعدة البيانات

#### ✅ العلاقات
```sql
-- اختبار العلاقة بين PaymentRequest و User
SELECT pr.*, u.name 
FROM payment_requests pr 
JOIN users u ON pr.user_id = u.id 
LIMIT 5;

-- اختبار العلاقة بين PaymentRequest و BankAccount
SELECT pr.*, ba.bank_name 
FROM payment_requests pr 
LEFT JOIN bank_accounts ba ON pr.payment_account_id = ba.id 
WHERE pr.payment_account_type = 'bank_account' 
LIMIT 5;

-- اختبار العلاقة بين PaymentRequest و ElectronicWallet
SELECT pr.*, ew.wallet_name 
FROM payment_requests pr 
LEFT JOIN electronic_wallets ew ON pr.payment_account_id = ew.id 
WHERE pr.payment_account_type = 'electronic_wallet' 
LIMIT 5;
```

#### ✅ الفهارس (Indexes)
```sql
-- اختبار وجود الفهارس
SHOW INDEX FROM payment_requests;
SHOW INDEX FROM payment_proofs;
SHOW INDEX FROM bank_accounts;
SHOW INDEX FROM electronic_wallets;
```

### 10. اختبار الأخطاء

#### ✅ معالجة الأخطاء
- [ ] خطأ 404 للصفحات غير الموجودة
- [ ] خطأ 403 للصلاحيات غير المطلوبة
- [ ] خطأ 500 للأخطاء الداخلية
- [ ] رسائل خطأ واضحة للمستخدم

#### ✅ التحقق من المدخلات
- [ ] رفض البيانات الفارغة
- [ ] رفض البيانات غير الصحيحة
- [ ] رفض الملفات الكبيرة جداً
- [ ] رفض أنواع الملفات غير المدعومة

## كيفية تشغيل الاختبارات

### 1. اختبار سريع
```bash
# تشغيل الهجرات
php artisan migrate:fresh

# تشغيل البذور
php artisan db:seed --class=PaymentAccountsSeeder

# تشغيل الخادم
php artisan serve
```

### 2. اختبار شامل
```bash
# تشغيل اختبارات PHPUnit
php artisan test

# تشغيل اختبارات مخصصة
php artisan test --filter=PaymentTest
```

### 3. اختبار قاعدة البيانات
```bash
# فحص حالة الهجرات
php artisan migrate:status

# إعادة تشغيل الهجرات
php artisan migrate:refresh

# تشغيل البذور
php artisan db:seed
```

## النتائج المتوقعة

### ✅ نجح الاختبار
- جميع الجداول تم إنشاؤها
- جميع النماذج تعمل
- جميع المتحكمات تستجيب
- جميع الواجهات تعرض
- جميع الوظائف تعمل

### ❌ فشل الاختبار
- أخطاء في قاعدة البيانات
- أخطاء في النماذج
- أخطاء في المتحكمات
- أخطاء في الواجهات
- أخطاء في الوظائف

## استكشاف الأخطاء

### 1. أخطاء قاعدة البيانات
```bash
# فحص السجلات
tail -f storage/logs/laravel.log

# فحص حالة الهجرات
php artisan migrate:status

# إعادة تشغيل الهجرات
php artisan migrate:refresh
```

### 2. أخطاء التطبيق
```bash
# مسح التخزين المؤقت
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# إعادة تشغيل الخادم
php artisan serve
```

### 3. أخطاء الواجهات
- فتح Developer Tools
- فحص Console للأخطاء
- فحص Network للطلبات
- فحص Elements للـ HTML

---

**ملاحظة:** قم بتشغيل هذه الاختبارات بعد كل تحديث للنظام للتأكد من عدم وجود أخطاء.
