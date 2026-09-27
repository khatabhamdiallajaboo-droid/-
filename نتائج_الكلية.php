<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام نتائج كلية شرق النيل - المختبرات الطبية</title>
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-sizing: border-box;
        }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            max-width: 1300px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0 0 5px;
            font-size: 28px;
        }
        .header h2 {
            color: #34495e;
            margin: 0;
            font-weight: normal;
            font-size: 18px;
        }
        .search-box {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }
        .search-box label {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        .search-box input {
            padding: 12px 20px;
            font-size: 16px;
            border: 2px solid #bdc3c7;
            border-radius: 8px;
            width: 300px;
            max-width: 100%;
            transition: border 0.3s;
            text-align: right;
        }
        .search-box input:focus {
            border-color: #3498db;
            outline: none;
        }
        .search-box button {
            padding: 12px 30px;
            font-size: 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        .search-box button:hover {
            background-color: #2980b9;
        }
        .result-area {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            min-height: 200px;
        }
        .error-message {
            color: #e74c3c;
            text-align: center;
            font-size: 20px;
            padding: 40px;
            background: #fceae9;
            border-radius: 10px;
        }
        .student-info {
            background: #2c3e50;
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 0;
        }
        .student-info h2 {
            margin: 0 0 10px;
            font-size: 24px;
        }
        .student-info p {
            margin: 5px 0;
            font-size: 16px;
            opacity: 0.9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }
        th {
            background-color: #3498db;
            color: white;
            padding: 12px 5px;
            font-weight: bold;
            text-align: center;
        }
        td {
            padding: 10px 5px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        /* تنسيق درجة F باللون الأحمر فقط */
        .grade-F {
            color: #e74c3c !important;
            font-weight: bold;
        }
        .grade-A { color: #27ae60; font-weight: bold; }
        .grade-B { color: #2980b9; font-weight: bold; }
        .grade-C { color: #f39c12; font-weight: bold; }
        
        .summary {
            margin-top: 30px;
            background: #ecf0f1;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 15px;
        }
        .summary-item {
            text-align: center;
            background: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            min-width: 150px;
        }
        .summary-item .label {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        .summary-item .value {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #7f8c8d;
            font-size: 14px;
        }
        .student-category {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>كلية شرق النيل - مدرسة علوم المختبرات الطبية</h1>
            <h2>نظام عرض نتائج الطلاب - الفرقة الأولى (دور أول 2022-2023)</h2>
        </div>

        <div class="search-box">
            <label for="student-id">الرقم الجامعي:</label>
            <input type="text" id="student-id" placeholder="أدخل الرقم الجامعي (مثال: 2231021850)">
            <button onclick="searchStudent()">عرض النتيجة</button>
        </div>

        <div id="resultDisplay" class="result-area">
            <p style="text-align: center; color: #7f8c8d; font-size: 18px;">👈 أدخل رقمك الجامعي واضغط على "عرض النتيجة"</p>
        </div>

        <div class="footer">
            © 2026 كلية شرق النيل - جميع الحقوق محفوظة
        </div>
    </div>

    <script>
        // قاعدة بيانات الطلاب - تحتوي على جميع الأسماء فقط مع التصنيف
        const studentsDatabase = [
    {
        "الرقم_الجامعي": "2231021850",
        "الاسم": "ابرار حاتم الخضر قرشي",
        "المواد": {
            "General & Organic": "F",
            "Biochemistry": "F",
            "Histology": "F",
            "Anatomy": "F",
            "Physiology": "F",
            "Physics": "F",
            "Biology": "F",
            "Laboratory Safety": "F",
            "Statistic": "F",
            "Mathematics": "F",
            "Arabic Language": "F",
            "English Language": "F",
            "Islamic Culture": "F",
            "Computer Science": "F"
        },
        "النسبة_المئوية": "",
        "التقدير": "0.0",
        "عدد_الرسوب": "14",
        "الموقف_الأكاديمي": "فصل بسبب الرسوب"
    },
    {
        "الرقم_الجامعي": "2211227229",
        "الاسم": "ابرار حاتم الماحي حسن",
        "المواد": {
            "General & Organic": "F",
            "Biochemistry": "C",
            "Histology": "B",
            "Anatomy": "C",
            "Physiology": "F",
            "Physics": "C",
            "Biology": "F",
            "Laboratory Safety": "C",
            "Statistic": "F",
            "Mathematics": "C",
            "Arabic Language": "F",
            "English Language": "C",
            "Islamic Culture": "F",
            "Computer Science": "F"
        },
        "النسبة_المئوية": "",
        "التقدير": "0.0",
        "عدد_الرسوب": "7",
        "الموقف_الأكاديمي": "اعاده"
    },
    {
        "الرقم_الجامعي": "2211551784",
        "الاسم": "احمد محمد الصادق مصطفى",
        "المواد": {
            "General & Organic": "A",
            "Biochemistry": "B",
            "Histology": "B+",
            "Anatomy": "A",
            "Physiology": "B",
            "Physics": "B",
            "Biology": "B",
            "Laboratory Safety": "A",
            "Statistic": "B+",
            "Mathematics": "A",
            "Arabic Language": "B+",
            "English Language": "B",
            "Islamic Culture": "A",
            "Computer Science": "B+"
        },
        "النسبة_المئوية": "82.5%",
        "التقدير": "A",
        "عدد_الرسوب": "0",
        "الموقف_الأكاديمي": "منقول"
    },
    {
        "الرقم_الجامعي": "555555555",
        "الاسم": "خطاب حمدي الله جابو نورين ",
        "المواد": {
            "General & Organic": "A",
            "Biochemistry": "A",
            "Histology": "A",
            "Anatomy": "A",
            "Physiology": "A",
            "Physics": "A",
            "Biology": "A",
            "Laboratory Safety": "A",
            "Statistic": "A",
            "Mathematics": "A",
            "Arabic Language": "A",
            "English Language": "A",
            "Islamic Culture": "A",
            "Computer Science": "A"
        },
        "النسبة_المئوية": "70%",
        "التقدير": "A",
        "عدد_الرسوب": "0",
        "الموقف_الأكاديمي": "منقول"
    }
];

        // دالة البحث عن الطالب وعرض النتيجة
        function searchStudent() {
            const studentId = document.getElementById('student-id').value.trim();
            const resultDiv = document.getElementById('resultDisplay');

            // التحقق من إدخال رقم
            if (studentId === '') {
                resultDiv.innerHTML = `<div class="error-message">❌ يرجى إدخال الرقم الجامعي</div>`;
                return;
            }

            // البحث في قاعدة البيانات
            const student = studentsDatabase.find(s => s.الرقم_الجامعي === studentId);

            if (!student) {
                resultDiv.innerHTML = `<div class="error-message">❌ الطالب برقم "${studentId}" غير موجود. يرجى التأكد من الرقم.</div>`;
                return;
            }

            // بناء HTML لعرض النتيجة
            let subjectsHTML = '';
            let materialCount = 0;
            
            // التحقق من وجود مواد وعرضها
            if (student.المواد && Object.keys(student.المواد).length > 0) {
                for (const [subject, grade] of Object.entries(student.المواد)) {
                    if (grade && grade !== '') {
                        materialCount++;
                        
                        let gradeClass = '';
                        if (grade.startsWith('A')) gradeClass = 'grade-A';
                        else if (grade.startsWith('B')) gradeClass = 'grade-B';
                        else if (grade === 'C') gradeClass = 'grade-C';
                        else if (grade === 'F') gradeClass = 'grade-F';
                        
                        subjectsHTML += `<tr><td>${subject}</td><td class="${gradeClass}">${grade}</td></tr>`;
                    }
                }
            }

            // تحديد لون التقدير النهائي
            let finalGradeClass = '';
            if (student.التقدير === 'A') finalGradeClass = 'grade-A';
            else if (student.التقدير === 'B+' || student.التقدير === 'B') finalGradeClass = 'grade-B';
            else if (student.التقدير === 'C') finalGradeClass = 'grade-C';
            else if (student.التقدير === 'F' || student.التقدير === '0.0') finalGradeClass = 'grade-F';

            const resultHTML = `
                <div class="student-info">
                    <h2>${student.الاسم}</h2>
                    <p>📌 الرقم الجامعي: ${student.الرقم_الجامعي}</p>
                    <p>📊 الفئة: <strong>${student.الفئة || 'غير محدد'}</strong></p>
                    <p>📊 الموقف الأكاديمي: <strong>${student.الموقف_الأكاديمي || 'غير محدد'}</strong></p>
                </div>
                
                <table>
                    <thead>
                        <tr><th>المادة</th><th>التقدير</th></tr>
                    </thead>
                    <tbody>
                        ${subjectsHTML || '<tr><td colspan="2" style="text-align:center">لم يتم إدخال الدرجات بعد</td></tr>'}
                    </tbody>
                </table>
                
                <div class="summary">
                    <div class="summary-item">
                        <div class="label">النسبة المئوية</div>
                        <div class="value">${student.النسبة_المئوية || '--'}</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">التقدير العام</div>
                        <div class="value ${finalGradeClass}">${student.التقدير || '--'}</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">عدد مواد الرسوب</div>
                        <div class="value">${student.عدد_الرسوب || '0'}</div>
                    </div>
                </div>
            `;

            resultDiv.innerHTML = resultHTML;
        }

        // إضافة خاصية البحث عند الضغط على Enter
        document.getElementById('student-id').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchStudent();
            }
        });

        // عرض رسالة ترحيب في الكونسول
        console.log("✅ النظام جاهز! عدد الطلاب في قاعدة البيانات: " + studentsDatabase.length);
    </script>
</body>
</html>