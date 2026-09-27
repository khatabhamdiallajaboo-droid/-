<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

// ✅ استيراد قاعدة البيانات من ملف النتائج الأصلي
// تأكد أن مسار الملف صحيح
$studentsDatabase = [];
if (file_exists('نتائج_الكلية.php')) {
    // قراءة محتوى الملف
    $content = file_get_contents('نتائج_الكلية.php');
    
    // استخراج مصفوفة studentsDatabase باستخدام regex
    preg_match('/const studentsDatabase = (\[.*?\]);/s', $content, $matches);
    
    if (isset($matches[1])) {
        // تحويل النص إلى مصفوفة PHP
        $jsonStr = $matches[1];
        // تحويل الـ JSON إلى مصفوفة
        $jsonStr = str_replace("'", '"', $jsonStr); // تحويل الأقواس المفردة لمزدوجة
        $studentsDatabase = json_decode($jsonStr, true);
    }
}

// إذا فشل الاستيراد، استخدم البيانات الافتراضية
if (empty($studentsDatabase)) {
    // بيانات افتراضية للطوارئ
    $studentsDatabase = [
        [
            "الرقم_الجامعي" => "2231021850",
            "الاسم" => "ابرار حاتم الخضر قرشي",
            "المواد" => [
                "General & Organic" => "F",
                "Biochemistry" => "F",
                "Histology" => "F",
                "Anatomy" => "F",
                "Physiology" => "F",
                "Physics" => "F",
                "Biology" => "F",
                "Laboratory Safety" => "F",
                "Statistic" => "F",
                "Mathematics" => "F",
                "Arabic Language" => "F",
                "English Language" => "F",
                "Islamic Culture" => "F",
                "Computer Science" => "F"
            ],
            "النسبة_المئوية" => "",
            "التقدير" => "0.0",
            "عدد_الرسوب" => "14",
            "الموقف_الأكاديمي" => "فصل بسبب الرسوب"
        ],
        [
            "الرقم_الجامعي" => "2211227229",
            "الاسم" => "ابرار حاتم الماحي حسن",
            "المواد" => [
                "General & Organic" => "F",
                "Biochemistry" => "C",
                "Histology" => "B",
                "Anatomy" => "C",
                "Physiology" => "F",
                "Physics" => "C",
                "Biology" => "F",
                "Laboratory Safety" => "C",
                "Statistic" => "F",
                "Mathematics" => "C",
                "Arabic Language" => "F",
                "English Language" => "C",
                "Islamic Culture" => "F",
                "Computer Science" => "F"
            ],
            "النسبة_المئوية" => "",
            "التقدير" => "0.0",
            "عدد_الرسوب" => "7",
            "الموقف_الأكاديمي" => "اعاده"
        ],
        [
            "الرقم_الجامعي" => "2211551784",
            "الاسم" => "احمد محمد الصادق مصطفى",
            "المواد" => [
                "General & Organic" => "A",
                "Biochemistry" => "B",
                "Histology" => "B+",
                "Anatomy" => "A",
                "Physiology" => "B",
                "Physics" => "B",
                "Biology" => "B",
                "Laboratory Safety" => "A",
                "Statistic" => "B+",
                "Mathematics" => "A",
                "Arabic Language" => "B+",
                "English Language" => "B",
                "Islamic Culture" => "A",
                "Computer Science" => "B+"
            ],
            "النسبة_المئوية" => "82.5%",
            "التقدير" => "A",
            "عدد_الرسوب" => "0",
            "الموقف_الأكاديمي" => "منقول"
        ]
    ];
}

// معالجة الإضافات والتعديلات
if (isset($_POST['add_student'])) {
    $new_student = [
        "الرقم_الجامعي" => $_POST['university_id'],
        "الاسم" => $_POST['student_name'],
        "المواد" => [],
        "النسبة_المئوية" => $_POST['percentage'],
        "التقدير" => $_POST['grade'],
        "عدد_الرسوب" => $_POST['failed_count'],
        "الموقف_الأكاديمي" => $_POST['academic_status']
    ];
    
    // إضافة المواد
    $subjects = [
        "General & Organic", "Biochemistry", "Histology", "Anatomy",
        "Physiology", "Physics", "Biology", "Laboratory Safety",
        "Statistic", "Mathematics", "Arabic Language", "English Language",
        "Islamic Culture", "Computer Science"
    ];
    
    foreach ($subjects as $subject) {
        $field_name = str_replace([' ', '&'], '_', $subject);
        $new_student["المواد"][$subject] = $_POST[$field_name] ?? '';
    }
    
    // إضافة الطالب إلى المصفوفة
    array_push($studentsDatabase, $new_student);
    
    // حفظ التعديلات في الملف (اختياري)
    saveToFile($studentsDatabase);
    
    $success = "✅ تم إضافة الطالب بنجاح";
}

if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    
    // حذف الطالب من المصفوفة
    foreach ($studentsDatabase as $key => $student) {
        if ($student['الرقم_الجامعي'] == $id_to_delete) {
            unset($studentsDatabase[$key]);
            break;
        }
    }
    
    // إعادة ترتيب المصفوفة
    $studentsDatabase = array_values($studentsDatabase);
    
    // حفظ التعديلات في الملف
    saveToFile($studentsDatabase);
    
    $success = "✅ تم حذف الطالب بنجاح";
}

if (isset($_POST['edit_student'])) {
    $id_to_edit = $_POST['edit_id'];
    
    // تحديث بيانات الطالب
    foreach ($studentsDatabase as $key => $student) {
        if ($student['الرقم_الجامعي'] == $id_to_edit) {
            $studentsDatabase[$key]['الاسم'] = $_POST['student_name'];
            $studentsDatabase[$key]['النسبة_المئوية'] = $_POST['percentage'];
            $studentsDatabase[$key]['التقدير'] = $_POST['grade'];
            $studentsDatabase[$key]['عدد_الرسوب'] = $_POST['failed_count'];
            $studentsDatabase[$key]['الموقف_الأكاديمي'] = $_POST['academic_status'];
            
            // تحديث المواد
            $subjects = [
                "General & Organic", "Biochemistry", "Histology", "Anatomy",
                "Physiology", "Physics", "Biology", "Laboratory Safety",
                "Statistic", "Mathematics", "Arabic Language", "English Language",
                "Islamic Culture", "Computer Science"
            ];
            
            foreach ($subjects as $subject) {
                $field_name = str_replace([' ', '&'], '_', $subject);
                $studentsDatabase[$key]["المواد"][$subject] = $_POST[$field_name] ?? '';
            }
            break;
        }
    }
    
    // حفظ التعديلات في الملف
    saveToFile($studentsDatabase);
    
    $success = "✅ تم تعديل بيانات الطالب بنجاح";
}

// دالة حفظ البيانات في الملف
function saveToFile($data) {
    $filename = 'نتائج_الكلية.php';
    if (file_exists($filename)) {
        // قراءة محتوى الملف الحالي
        $content = file_get_contents($filename);
        
        // تحويل المصفوفة إلى نص JSON
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        // استبدال البيانات القديمة بالجديدة
        $pattern = '/const studentsDatabase = \[.*?\];/s';
        $replacement = 'const studentsDatabase = ' . $jsonData . ';';
        $newContent = preg_replace($pattern, $replacement, $content);
        
        // حفظ الملف
        file_put_contents($filename, $newContent);
    }
}

// معالجة تسجيل الخروج
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit();
}

// إحصائيات سريعة
$total_students = count($studentsDatabase);
$failed_students = 0;
$excellent_students = 0;

foreach ($studentsDatabase as $student) {
    if ($student['التقدير'] === 'A' || $student['التقدير'] === 'A') {
        $excellent_students++;
    }
    if (isset($student['عدد_الرسوب']) && $student['عدد_الرسوب'] > 0) {
        $failed_students++;
    }
}

// جلب بيانات الطالب للتعديل
$edit_student = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    foreach ($studentsDatabase as $student) {
        if ($student['الرقم_الجامعي'] == $edit_id) {
            $edit_student = $student;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - كلية شرق النيل</title>
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background: #f5f7fa;
            padding: 20px;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        /* القائمة العلوية */
        .navbar {
            background: white;
            border-radius: 15px;
            padding: 15px 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .navbar h2 {
            color: #2c3e50;
        }
        .navbar a {
            padding: 10px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .navbar a:hover {
            background: #c0392b;
        }
        
        /* بطاقات الإحصائيات */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            color: #7f8c8d;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        /* أزرار الإجراءات */
        .actions {
            margin-bottom: 20px;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #3498db;
            color: white;
        }
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        .btn-success {
            background: #27ae60;
            color: white;
        }
        .btn-success:hover {
            background: #229954;
        }
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        .btn-warning:hover {
            background: #e67e22;
        }
        
        /* نافذة إضافة طالب */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-content h3 {
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #34495e;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #3498db;
            outline: none;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .close-btn {
            background: #95a5a6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            margin-left: 10px;
        }
        
        /* جدول الطلاب */
        .students-table {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #3498db;
            color: white;
            padding: 15px 10px;
            font-size: 14px;
        }
        td {
            padding: 12px 10px;
            border-bottom: 1px solid #e0e0e0;
            text-align: center;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .action-btns {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        .edit-btn, .delete-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .edit-btn {
            background: #f39c12;
            color: white;
        }
        .delete-btn {
            background: #e74c3c;
            color: white;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .grade-F { color: #e74c3c; font-weight: bold; }
        .grade-A { color: #27ae60; font-weight: bold; }
        .grade-B { color: #2980b9; font-weight: bold; }
        .grade-C { color: #f39c12; font-weight: bold; }
        
        /* زر العودة */
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- القائمة العلوية -->
        <div class="navbar">
            <h2>🔧 لوحة تحكم المشرف - كلية شرق النيل</h2>
            <div>
                <span style="margin-left: 15px; color: #7f8c8d;">👤 <?php echo $_SESSION['admin']; ?></span>
                <a href="?logout=1">🚪 تسجيل الخروج</a>
            </div>
        </div>
        
        <!-- رابط العودة لصفحة النتائج -->
        <a href="نتائج_الكلية.php" class="back-link">← العودة لصفحة النتائج العامة</a>
        
        <?php if (isset($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <!-- بطاقات الإحصائيات -->
        <div class="stats">
            <div class="stat-card">
                <h3>إجمالي الطلاب</h3>
                <div class="number"><?php echo $total_students; ?></div>
            </div>
            <div class="stat-card">
                <h3>الطلاب المتفوقون (A)</h3>
                <div class="number"><?php echo $excellent_students; ?></div>
            </div>
            <div class="stat-card">
                <h3>الطلاب الراسبون</h3>
                <div class="number"><?php echo $failed_students; ?></div>
            </div>
            <div class="stat-card">
                <h3>نسبة النجاح</h3>
                <div class="number">
                    <?php 
                        $success_rate = $total_students > 0 ? 
                            round((($total_students - $failed_students) / $total_students) * 100, 1) : 0;
                        echo $success_rate . '%';
                    ?>
                </div>
            </div>
        </div>
        
        <!-- أزرار الإجراءات -->
        <div class="actions">
            <button class="btn btn-primary" onclick="openAddModal()">➕ إضافة طالب جديد</button>
            <button class="btn btn-success" onclick="exportData()">📥 تصدير البيانات (CSV)</button>
        </div>
        
        <!-- نافذة إضافة طالب -->
        <div class="modal" id="addStudentModal">
            <div class="modal-content">
                <h3>➕ إضافة طالب جديد</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>الرقم الجامعي</label>
                        <input type="text" name="university_id" required>
                    </div>
                    <div class="form-group">
                        <label>الاسم الكامل</label>
                        <input type="text" name="student_name" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>النسبة المئوية</label>
                            <input type="text" name="percentage">
                        </div>
                        <div class="form-group">
                            <label>التقدير</label>
                            <select name="grade">
                                <option value="A">A</option>
                                <option value="B+">B+</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>عدد مواد الرسوب</label>
                            <input type="number" name="failed_count" value="0">
                        </div>
                        <div class="form-group">
                            <label>الموقف الأكاديمي</label>
                            <input type="text" name="academic_status" value="منقول">
                        </div>
                    </div>
                    
                    <h4 style="margin: 20px 0 10px; color: #2c3e50;">تقديرات المواد</h4>
                    <div class="form-row">
                        <?php
                        $subjects = [
                            "General & Organic", "Biochemistry", "Histology", "Anatomy",
                            "Physiology", "Physics", "Biology", "Laboratory Safety",
                            "Statistic", "Mathematics", "Arabic Language", "English Language",
                            "Islamic Culture", "Computer Science"
                        ];
                        foreach ($subjects as $subject):
                        ?>
                        <div class="form-group">
                            <label><?php echo $subject; ?></label>
                            <select name="<?php echo str_replace([' ', '&'], '_', $subject); ?>">
                                <option value="">--</option>
                                <option value="A">A</option>
                                <option value="B+">B+</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div style="margin-top: 20px; text-align: left;">
                        <button type="button" class="close-btn" onclick="closeAddModal()">إلغاء</button>
                        <button type="submit" name="add_student" class="btn btn-success">حفظ الطالب</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- نافذة تعديل طالب -->
        <?php if ($edit_student): ?>
        <div class="modal active" id="editStudentModal">
            <div class="modal-content">
                <h3>✏️ تعديل بيانات الطالب</h3>
                <form method="POST" action="">
                    <input type="hidden" name="edit_id" value="<?php echo $edit_student['الرقم_الجامعي']; ?>">
                    
                    <div class="form-group">
                        <label>الرقم الجامعي</label>
                        <input type="text" value="<?php echo $edit_student['الرقم_الجامعي']; ?>" disabled style="background:#f5f5f5;">
                    </div>
                    <div class="form-group">
                        <label>الاسم الكامل</label>
                        <input type="text" name="student_name" value="<?php echo $edit_student['الاسم']; ?>" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>النسبة المئوية</label>
                            <input type="text" name="percentage" value="<?php echo $edit_student['النسبة_المئوية']; ?>">
                        </div>
                        <div class="form-group">
                            <label>التقدير</label>
                            <select name="grade">
                                <option value="A" <?php echo $edit_student['التقدير'] == 'A' ? 'selected' : ''; ?>>A</option>
                                <option value="B+" <?php echo $edit_student['التقدير'] == 'B+' ? 'selected' : ''; ?>>B+</option>
                                <option value="B" <?php echo $edit_student['التقدير'] == 'B' ? 'selected' : ''; ?>>B</option>
                                <option value="C" <?php echo $edit_student['التقدير'] == 'C' ? 'selected' : ''; ?>>C</option>
                                <option value="F" <?php echo $edit_student['التقدير'] == 'F' ? 'selected' : ''; ?>>F</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>عدد مواد الرسوب</label>
                            <input type="number" name="failed_count" value="<?php echo $edit_student['عدد_الرسوب']; ?>">
                        </div>
                        <div class="form-group">
                            <label>الموقف الأكاديمي</label>
                            <input type="text" name="academic_status" value="<?php echo $edit_student['الموقف_الأكاديمي']; ?>">
                        </div>
                    </div>
                    
                    <h4 style="margin: 20px 0 10px; color: #2c3e50;">تقديرات المواد</h4>
                    <div class="form-row">
                        <?php foreach ($subjects as $subject): ?>
                        <div class="form-group">
                            <label><?php echo $subject; ?></label>
                            <select name="<?php echo str_replace([' ', '&'], '_', $subject); ?>">
                                <option value="">--</option>
                                <option value="A" <?php echo ($edit_student['المواد'][$subject] ?? '') == 'A' ? 'selected' : ''; ?>>A</option>
                                <option value="B+" <?php echo ($edit_student['المواد'][$subject] ?? '') == 'B+' ? 'selected' : ''; ?>>B+</option>
                                <option value="B" <?php echo ($edit_student['المواد'][$subject] ?? '') == 'B' ? 'selected' : ''; ?>>B</option>
                                <option value="C" <?php echo ($edit_student['المواد'][$subject] ?? '') == 'C' ? 'selected' : ''; ?>>C</option>
                                <option value="F" <?php echo ($edit_student['المواد'][$subject] ?? '') == 'F' ? 'selected' : ''; ?>>F</option>
                            </select>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div style="margin-top: 20px; text-align: left;">
                        <a href="admin_dashboard.php" class="close-btn">إلغاء</a>
                        <button type="submit" name="edit_student" class="btn btn-success">حفظ التعديلات</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- جدول الطلاب -->
        <div class="students-table">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الرقم الجامعي</th>
                        <th>الاسم</th>
                        <th>التقدير</th>
                        <th>عدد الرسوب</th>
                        <th>الموقف الأكاديمي</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($studentsDatabase as $index => $student): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $student['الرقم_الجامعي']; ?></td>
                        <td><?php echo $student['الاسم']; ?></td>
                        <td class="<?php 
                            $grade = $student['التقدير'] ?? '';
                            if ($grade === 'F' || $grade === '0.0') echo 'grade-F';
                            elseif ($grade === 'A') echo 'grade-A';
                            elseif (strpos($grade, 'B') !== false) echo 'grade-B';
                            elseif ($grade === 'C') echo 'grade-C';
                        ?>"><?php echo $grade; ?></td>
                        <td><?php echo $student['عدد_الرسوب'] ?? '0'; ?></td>
                        <td><?php echo $student['الموقف_الأكاديمي'] ?? 'غير محدد'; ?></td>
                        <td class="action-btns">
                            <a href="?edit=<?php echo $student['الرقم_الجامعي']; ?>" class="edit-btn">✏️ تعديل</a>
                            <a href="?delete=<?php echo $student['الرقم_الجامعي']; ?>" class="delete-btn" onclick="return confirm('هل أنت متأكد من حذف هذا الطالب؟')">🗑️ حذف</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        function openAddModal() {
            document.getElementById('addStudentModal').classList.add('active');
        }
        
        function closeAddModal() {
            document.getElementById('addStudentModal').classList.remove('active');
        }
        
        function exportData() {
            // تحويل البيانات إلى CSV
            let csv = "الرقم الجامعي,الاسم,التقدير,عدد الرسوب,الموقف الأكاديمي\n";
            <?php foreach ($studentsDatabase as $student): ?>
            csv += "<?php echo $student['الرقم_الجامعي']; ?>,<?php echo $student['الاسم']; ?>,<?php echo $student['التقدير']; ?>,<?php echo $student['عدد_الرسوب']; ?>,<?php echo $student['الموقف_الأكاديمي']; ?>\n";
            <?php endforeach; ?>
            
            // تحميل الملف
            const blob = new Blob(["\uFEFF" + csv], { type: 'text/csv;charset=utf-8;' }); // إضافة BOM لدعم العربية
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'students_data.csv';
            a.click();
        }
        
        // إغلاق النافذة عند الضغط على ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
            }
        });
    </script>
</body>
</html>