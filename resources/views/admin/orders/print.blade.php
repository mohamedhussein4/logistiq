<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة الطلب #{{ $order->id }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: #fff;
            color: #333;
            direction: rtl;
            text-align: right;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e5e5;
        }
        
        .company-info h1 {
            color: #2563eb;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: #666;
            margin: 2px 0;
        }
        
        .invoice-details {
            text-align: left;
        }
        
        .invoice-details h2 {
            color: #dc2626;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .invoice-details p {
            color: #666;
            margin: 2px 0;
        }
        
        .customer-product-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 30px 0;
        }
        
        .section {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .section h3 {
            color: #1e40af;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 8px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            padding: 5px 0;
        }
        
        .info-row strong {
            color: #374151;
            min-width: 120px;
        }
        
        .info-row span {
            color: #6b7280;
            flex: 1;
            text-align: left;
        }
        
        .payment-summary {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
        }
        
        .payment-summary h3 {
            margin-bottom: 20px;
            font-size: 20px;
            text-align: center;
        }
        
        .payment-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 16px;
        }
        
        .payment-row.total {
            border-top: 2px solid rgba(255,255,255,0.3);
            padding-top: 15px;
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-processing { background: #dbeafe; color: #2563eb; }
        .status-shipped { background: #d1fae5; color: #059669; }
        .status-delivered { background: #dcfce7; color: #16a34a; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        
        .footer-note {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        
        @media print {
            body {
                font-size: 12pt;
            }
            
            .invoice-container {
                box-shadow: none;
                margin: 0;
                max-width: none;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>{{ \App\Models\Setting::get('site_name', 'Logistiq') }}</h1>
                <p>{{ \App\Models\Setting::get('site_address', 'المملكة العربية السعودية') }}</p>
                <p>هاتف: {{ \App\Models\Setting::get('site_phone', '+966XXXXXXXXX') }}</p>
                <p>البريد الإلكتروني: {{ \App\Models\Setting::get('site_email', 'info@logistiq.sa') }}</p>
            </div>
            <div class="invoice-details">
                <h2>فاتورة الطلب</h2>
                <p><strong>رقم الطلب:</strong> #{{ $order->id }}</p>
                <p><strong>تاريخ الطلب:</strong> {{ $order->ordered_at->format('Y-m-d H:i') }}</p>
                <p><strong>الحالة:</strong> 
                    <span class="status-badge status-{{ $order->status }}">
                        @switch($order->status)
                            @case('pending') في الانتظار @break
                            @case('processing') قيد المعالجة @break
                            @case('shipped') تم الشحن @break
                            @case('delivered') تم التسليم @break
                            @case('cancelled') ملغي @break
                            @default {{ $order->status }}
                        @endswitch
                    </span>
                </p>
            </div>
        </div>

        <!-- Customer and Product Info -->
        <div class="customer-product-section">
            <div class="section">
                <h3>📋 معلومات العميل</h3>
                <div class="info-row">
                    <strong>اسم العميل:</strong>
                    <span>{{ $order->user->name }}</span>
                </div>
                <div class="info-row">
                    <strong>البريد الإلكتروني:</strong>
                    <span>{{ $order->user->email }}</span>
                </div>
                @if($order->user->phone)
                <div class="info-row">
                    <strong>رقم الهاتف:</strong>
                    <span>{{ $order->user->phone }}</span>
                </div>
                @endif
                @if($order->shipping_address)
                <div class="info-row">
                    <strong>عنوان الشحن:</strong>
                    <span>{{ $order->shipping_address }}</span>
                </div>
                @endif
            </div>

            <div class="section">
                <h3>📦 تفاصيل المنتج</h3>
                <div class="info-row">
                    <strong>اسم المنتج:</strong>
                    <span>{{ $order->product->name }}</span>
                </div>
                <div class="info-row">
                    <strong>الكمية:</strong>
                    <span>{{ $order->quantity }}</span>
                </div>
                <div class="info-row">
                    <strong>سعر الوحدة:</strong>
                    <span>{{ number_format($order->unit_price, 2) }} ر.س</span>
                </div>
                @if($order->product->description)
                <div class="info-row">
                    <strong>الوصف:</strong>
                    <span>{{ Str::limit($order->product->description, 100) }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="payment-summary">
            <h3>💰 ملخص الدفع</h3>
            <div class="payment-row">
                <span>المبلغ الفرعي:</span>
                <span>{{ number_format($order->unit_price * $order->quantity, 2) }} ر.س</span>
            </div>
            @if($order->shipping_cost)
            <div class="payment-row">
                <span>تكلفة الشحن:</span>
                <span>{{ number_format($order->shipping_cost, 2) }} ر.س</span>
            </div>
            @endif
            @if($order->tax_amount)
            <div class="payment-row">
                <span>الضريبة:</span>
                <span>{{ number_format($order->tax_amount, 2) }} ر.س</span>
            </div>
            @endif
            <div class="payment-row total">
                <span>إجمالي المبلغ:</span>
                <span>{{ number_format($order->total_amount, 2) }} ر.س</span>
            </div>
        </div>

        @if($order->notes)
        <div class="section">
            <h3>📝 ملاحظات</h3>
            <p>{{ $order->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer-note">
            <p>شكراً لتعاملكم معنا! نتطلع إلى خدمتكم مرة أخرى.</p>
            <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
