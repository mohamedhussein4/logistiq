<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب التمويل #{{ $fundingRequest->id }}</title>
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
            max-width: 900px;
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
        
        .sections-grid {
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
        
        .section.full-width {
            grid-column: span 2;
        }
        
        .section h3 {
            color: #1e40af;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            padding: 5px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-row strong {
            color: #374151;
            min-width: 140px;
            font-weight: 600;
        }
        
        .info-row span {
            color: #6b7280;
            flex: 1;
            text-align: left;
        }
        
        .amount-summary {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
            text-align: center;
        }
        
        .amount-summary h3 {
            margin-bottom: 20px;
            font-size: 22px;
        }
        
        .amount-display {
            font-size: 48px;
            font-weight: bold;
            margin: 20px 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .amount-text {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-under_review { background: #dbeafe; color: #2563eb; }
        .status-approved { background: #dcfce7; color: #16a34a; }
        .status-rejected { background: #fee2e2; color: #dc2626; }
        .status-disbursed { background: #d1fae5; color: #059669; }
        
        .document-info {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .document-info h4 {
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .document-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .document-item {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .document-item i {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }
        
        .timeline {
            margin: 30px 0;
        }
        
        .timeline-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border-right: 4px solid #3b82f6;
        }
        
        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-size: 18px;
        }
        
        .timeline-content h5 {
            color: #1e40af;
            margin-bottom: 5px;
        }
        
        .timeline-content p {
            color: #6b7280;
            font-size: 14px;
        }
        
        .footer-note {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e5e5;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(0,0,0,0.05);
            z-index: -1;
            font-weight: bold;
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
            
            .sections-grid {
                grid-template-columns: 1fr 1fr;
                page-break-inside: avoid;
            }
            
            .amount-summary {
                page-break-inside: avoid;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="watermark">طلب تمويل</div>
    
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
                <h2>🏦 طلب التمويل</h2>
                <p><strong>رقم الطلب:</strong> #{{ $fundingRequest->id }}</p>
                <p><strong>تاريخ التقديم:</strong> {{ $fundingRequest->requested_at->format('Y-m-d H:i') }}</p>
                <p><strong>الحالة:</strong> 
                    <span class="status-badge status-{{ $fundingRequest->status }}">
                        @switch($fundingRequest->status)
                            @case('pending') في الانتظار @break
                            @case('under_review') قيد المراجعة @break
                            @case('approved') موافق عليه @break
                            @case('rejected') مرفوض @break
                            @case('disbursed') تم الصرف @break
                            @default {{ $fundingRequest->status }}
                        @endswitch
                    </span>
                </p>
            </div>
        </div>

        <!-- Amount Summary -->
        <div class="amount-summary">
            <h3>💰 المبلغ المطلوب</h3>
            <div class="amount-display">{{ number_format($fundingRequest->amount, 2) }} ر.س</div>
            <div class="amount-text">المبلغ المطلوب للتمويل</div>
        </div>

        <!-- Company and Request Details -->
        <div class="sections-grid">
            <div class="section">
                <h3>🏢 معلومات الشركة</h3>
                <div class="info-row">
                    <strong>اسم الشركة:</strong>
                    <span>{{ $fundingRequest->logisticsCompany->company_name }}</span>
                </div>
                <div class="info-row">
                    <strong>رقم السجل التجاري:</strong>
                    <span>{{ $fundingRequest->logisticsCompany->commercial_registration ?? 'غير محدد' }}</span>
                </div>
                <div class="info-row">
                    <strong>المدير المسؤول:</strong>
                    <span>{{ $fundingRequest->logisticsCompany->user->name }}</span>
                </div>
                <div class="info-row">
                    <strong>البريد الإلكتروني:</strong>
                    <span>{{ $fundingRequest->logisticsCompany->user->email }}</span>
                </div>
                @if($fundingRequest->logisticsCompany->user->phone)
                <div class="info-row">
                    <strong>رقم الهاتف:</strong>
                    <span>{{ $fundingRequest->logisticsCompany->user->phone }}</span>
                </div>
                @endif
                <div class="info-row">
                    <strong>عنوان الشركة:</strong>
                    <span>{{ $fundingRequest->logisticsCompany->address ?? 'غير محدد' }}</span>
                </div>
            </div>

            <div class="section">
                <h3>📋 تفاصيل الطلب</h3>
                <div class="info-row">
                    <strong>نوع التمويل:</strong>
                    <span>
                        @switch($fundingRequest->funding_type)
                            @case('working_capital') رأس مال عامل @break
                            @case('equipment') معدات @break
                            @case('expansion') توسع @break
                            @case('other') أخرى @break
                            @default {{ $fundingRequest->funding_type }}
                        @endswitch
                    </span>
                </div>
                <div class="info-row">
                    <strong>فترة السداد:</strong>
                    <span>{{ $fundingRequest->repayment_period ?? 'غير محدد' }} شهر</span>
                </div>
                <div class="info-row">
                    <strong>الضمانات المقدمة:</strong>
                    <span>{{ $fundingRequest->collateral_type ?? 'غير محدد' }}</span>
                </div>
                @if($fundingRequest->monthly_income)
                <div class="info-row">
                    <strong>الدخل الشهري:</strong>
                    <span>{{ number_format($fundingRequest->monthly_income, 2) }} ر.س</span>
                </div>
                @endif
                @if($fundingRequest->current_debt)
                <div class="info-row">
                    <strong>الديون الحالية:</strong>
                    <span>{{ number_format($fundingRequest->current_debt, 2) }} ر.س</span>
                </div>
                @endif
            </div>

            @if($fundingRequest->purpose)
            <div class="section full-width">
                <h3>🎯 الغرض من التمويل</h3>
                <p style="line-height: 1.6; color: #374151; padding: 10px 0;">{{ $fundingRequest->purpose }}</p>
            </div>
            @endif

            @if($fundingRequest->business_plan)
            <div class="section full-width">
                <h3>📈 خطة العمل</h3>
                <p style="line-height: 1.6; color: #374151; padding: 10px 0;">{{ $fundingRequest->business_plan }}</p>
            </div>
            @endif
        </div>

        @if($fundingRequest->documents)
        <div class="document-info">
            <h4>📄 المستندات المرفقة</h4>
            <div class="document-grid">
                @foreach((is_array($fundingRequest->documents) ? $fundingRequest->documents : json_decode($fundingRequest->documents, true)) ?? [] as $doc)
                    <div class="document-item">
                        <i class="fas fa-file-alt"></i>
                        <div>{{ basename($doc) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Timeline -->
        <div class="section full-width">
            <h3>⏱️ سجل حالات الطلب</h3>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="timeline-content">
                        <h5>تم تقديم الطلب</h5>
                        <p>{{ $fundingRequest->requested_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                @if($fundingRequest->reviewed_at)
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="timeline-content">
                        <h5>تمت المراجعة</h5>
                        <p>{{ $fundingRequest->reviewed_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @endif

                @if($fundingRequest->approved_at)
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="timeline-content">
                        <h5>تمت الموافقة</h5>
                        <p>{{ $fundingRequest->approved_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @endif

                @if($fundingRequest->disbursed_at)
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="timeline-content">
                        <h5>تم الصرف</h5>
                        <p>{{ $fundingRequest->disbursed_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @endif

                @if($fundingRequest->rejected_at)
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-times"></i>
                    </div>
                    <div class="timeline-content">
                        <h5>تم الرفض</h5>
                        <p>{{ $fundingRequest->rejected_at->format('Y-m-d H:i') }}</p>
                        @if($fundingRequest->rejection_reason)
                        <p><strong>سبب الرفض:</strong> {{ $fundingRequest->rejection_reason }}</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if($fundingRequest->notes)
        <div class="section full-width">
            <h3>📝 ملاحظات إضافية</h3>
            <p style="line-height: 1.6; color: #374151; padding: 10px 0;">{{ $fundingRequest->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer-note">
            <p><strong>هذا المستند سري ومخصص للاستخدام الداخلي فقط</strong></p>
            <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i:s') }}</p>
            <p>طُبع من نظام إدارة التمويل - {{ \App\Models\Setting::get('site_name', 'Logistiq') }}</p>
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
