<?php
session_start();

// بيانات المشرفين (يمكن تعديلها)
$admins = [
    'admin' => 'admin123',  // username => password
    'مدير' => '123456',
    'مشرف' => '789012'
];

// التحقق من تسجيل الدخول
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (isset($admins[$username]) && $admins[$username] === $password) {
        $_SESSION['admin'] = $username;
        header('Location: admin_dashboard.php');
        exit();
    } else {
        $error = "❌ اسم المستخدم أو كلمة المرور غير صحيحة";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المشرف - كلية شرق النيل</title>
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header p {
            color: #7f8c8d;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        input:focus {
            border-color: #3498db;
            outline: none;
        }
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .error {
            background: #fceae9;
            color: #e74c3c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #95a5a6;
            font-size: 14px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #3498db;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="header">
            <h1>🔐 لوحة تحكم المشرف</h1>
            <p>كلية شرق النيل - المختبرات الطبية</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>👤 اسم المستخدم</label>
                <input type="text" name="username" required placeholder="أدخل اسم المستخدم">
            </div>
            <div class="form-group">
                <label>🔑 كلمة المرور</label>
                <input type="password" name="password" required placeholder="أدخل كلمة المرور">
            </div>
            <button type="submit" name="login">تسجيل الدخول</button>
        </form>
        
        <a href="نتائج_الكلية.php" class="back-link">← العودة إلى صفحة النتائج</a>
        <div class="footer">
            <p> </p>
        </div>
    </div>
</body>
</html>