<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تشخيص النظام</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-size: 2em;
        }
        
        .status-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-right: 5px solid #ddd;
        }
        
        .status-card.success {
            border-right-color: #28a745;
            background: #d4edda;
        }
        
        .status-card.error {
            border-right-color: #dc3545;
            background: #f8d7da;
        }
        
        .status-card.warning {
            border-right-color: #ffc107;
            background: #fff3cd;
        }
        
        .status-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }
        
        .status-item:last-child {
            margin-bottom: 0;
        }
        
        .status-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-weight: bold;
        }
        
        .status-icon.success {
            background: #28a745;
            color: white;
        }
        
        .status-icon.error {
            background: #dc3545;
            color: white;
        }
        
        .status-text {
            flex: 1;
            font-size: 1.1em;
        }
        
        .database-config {
            background: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-top: 10px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        
        .database-config div {
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .database-config div:last-child {
            border-bottom: none;
        }
        
        .recommendations {
            background: #e7f3ff;
            border-right: 4px solid #2196F3;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .recommendations h3 {
            color: #2196F3;
            margin-bottom: 10px;
        }
        
        .recommendations ul {
            list-style: none;
            padding-right: 0;
        }
        
        .recommendations li {
            padding: 8px 0;
            border-bottom: 1px solid #ccc;
        }
        
        .recommendations li:last-child {
            border-bottom: none;
        }
        
        .error-list {
            background: #f8d7da;
            border-right: 4px solid #dc3545;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .error-list h3 {
            color: #dc3545;
            margin-bottom: 10px;
        }
        
        .error-list ul {
            list-style: none;
            padding-right: 0;
        }
        
        .error-list li {
            padding: 8px 0;
            color: #721c24;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .actions {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 تشخيص النظام</h1>
        
        <div class="status-card {{ $diagnostics['system_installed'] && $diagnostics['database_connected'] ? 'success' : 'error' }}">
            <h2 style="margin-bottom: 15px;">الحالة العامة</h2>
            
            <div class="status-item">
                <div class="status-icon {{ $diagnostics['install_state_exists'] ? 'success' : 'error' }}">
                    {{ $diagnostics['install_state_exists'] ? '✓' : '✗' }}
                </div>
                <div class="status-text">
                    ملف التثبيت: {{ $diagnostics['install_state_exists'] ? 'موجود' : 'غير موجود' }}
                </div>
            </div>
            
            <div class="status-item">
                <div class="status-icon {{ $diagnostics['install_state_valid'] ? 'success' : 'error' }}">
                    {{ $diagnostics['install_state_valid'] ? '✓' : '✗' }}
                </div>
                <div class="status-text">
                    صحة ملف التثبيت: {{ $diagnostics['install_state_valid'] ? 'صالح' : 'تالف' }}
                </div>
            </div>
            
            <div class="status-item">
                <div class="status-icon {{ $diagnostics['system_installed'] ? 'success' : 'error' }}">
                    {{ $diagnostics['system_installed'] ? '✓' : '✗' }}
                </div>
                <div class="status-text">
                    حالة التثبيت: {{ $diagnostics['system_installed'] ? 'مكتمل' : 'غير مكتمل' }}
                </div>
            </div>
            
            <div class="status-item">
                <div class="status-icon {{ $diagnostics['database_connected'] ? 'success' : 'error' }}">
                    {{ $diagnostics['database_connected'] ? '✓' : '✗' }}
                </div>
                <div class="status-text">
                    الاتصال بقاعدة البيانات: {{ $diagnostics['database_connected'] ? 'ناجح' : 'فاشل' }}
                </div>
            </div>
        </div>
        
        @if(!empty($diagnostics['database_config']))
        <div class="status-card">
            <h3 style="margin-bottom: 10px;">إعدادات قاعدة البيانات</h3>
            <div class="database-config">
                <div><strong>نوع الاتصال:</strong> {{ $diagnostics['database_config']['connection'] }}</div>
                <div><strong>المضيف:</strong> {{ $diagnostics['database_config']['host'] }}</div>
                <div><strong>المنفذ:</strong> {{ $diagnostics['database_config']['port'] }}</div>
                <div><strong>قاعدة البيانات:</strong> {{ $diagnostics['database_config']['database'] }}</div>
                <div><strong>اسم المستخدم:</strong> {{ $diagnostics['database_config']['username'] }}</div>
                <div><strong>كلمة المرور:</strong> {{ $diagnostics['database_config']['password_set'] ? '✓ محددة' : '✗ غير محددة' }}</div>
            </div>
        </div>
        @endif
        
        @if(!empty($diagnostics['errors']))
        <div class="error-list">
            <h3>⚠️ الأخطاء المكتشفة</h3>
            <ul>
                @foreach($diagnostics['errors'] as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        @if(!empty($diagnostics['recommendations']))
        <div class="recommendations">
            <h3>💡 التوصيات</h3>
            <ul>
                @foreach($diagnostics['recommendations'] as $recommendation)
                <li>{{ $recommendation }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <div class="actions">
            @if($diagnostics['system_installed'] && $diagnostics['database_connected'])
            <a href="/" class="btn">الذهاب إلى الصفحة الرئيسية</a>
            @else
            <a href="/install" class="btn">بدء عملية التثبيت</a>
            @endif
            
            <button onclick="testConnection()" class="btn" style="background: #28a745; margin-right: 10px;">
                اختبار الاتصال
            </button>
        </div>
    </div>
    
    <script>
        async function testConnection() {
            try {
                const response = await fetch('/system-diagnostics/test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('✓ ' + data.message);
                    location.reload();
                } else {
                    alert('✗ ' + data.message);
                }
            } catch (error) {
                alert('✗ حدث خطأ أثناء اختبار الاتصال');
            }
        }
    </script>
</body>
</html>
