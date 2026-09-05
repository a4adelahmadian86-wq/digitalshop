-- ============================================================
-- فروشگاه فایل: ساختار دسته‌بندی پیشنهادی
-- MySQL 5.7+/8.x
--
-- پیش‌فرض: جدول categories دارای ستون‌های زیر است:
-- id, parent_id, name, slug, level, sort_order, status
--
-- اگر نام جدول/ستون‌های شما متفاوت است، فقط نام‌ها را اصلاح کنید.
-- این اسکریپت ID والد را از روی slug پیدا می‌کند و به AUTO_INCREMENT
-- وابسته نیست.
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

START TRANSACTION;

-- ------------------------------------------------------------
-- گروه‌های اصلی
-- ------------------------------------------------------------
INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'کتاب و PDF', 'books-pdf', 0, 1, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'books-pdf'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'پروژه و تحقیقات دانشگاهی', 'academic-projects', 0, 2, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'academic-projects'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'پاورپوینت و ارائه', 'powerpoint', 0, 3, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'powerpoint'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'مدرسه و آموزش', 'school-education', 0, 4, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'school-education'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'آزمون و نمونه سوال', 'exams-tests', 0, 5, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'exams-tests'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'استخدام و آزمون استخدامی', 'employment', 0, 6, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'employment'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'رشته‌های دانشگاهی', 'academic-fields', 0, 7, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'academic-fields'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'پزشکی و علوم سلامت', 'medical-health', 0, 8, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'medical-health'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'مهندسی و معماری', 'engineering', 0, 9, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'engineering'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'حقوق و قرارداد', 'legal-contracts', 0, 10, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'legal-contracts'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'فرم و فایل اداری', 'forms-documents', 0, 11, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'forms-documents'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'کسب‌وکار و کارآفرینی', 'business-entrepreneurship', 0, 12, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'business-entrepreneurship'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'حسابداری و مالی', 'accounting-finance', 0, 13, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'accounting-finance'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'پرسشنامه و ابزار پژوهش', 'questionnaires', 0, 14, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'questionnaires'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'روانشناسی و توسعه فردی', 'psychology-self-development', 0, 15, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'psychology-self-development'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'برنامه‌نویسی', 'programming', 0, 16, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'programming'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'هوش مصنوعی', 'artificial-intelligence', 0, 17, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'artificial-intelligence'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'وردپرس و وب', 'wordpress-web', 0, 18, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'wordpress-web'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'اکسل و فایل‌های کاربردی', 'excel-tools', 0, 19, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'excel-tools'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'رزومه و شغل', 'resume-career', 0, 20, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'resume-career'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'گرافیک و فایل‌های لایه‌باز', 'graphic-design', 0, 21, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'graphic-design'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'تولید محتوا', 'content-social-media', 0, 22, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'content-social-media'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'زبان‌های خارجی', 'languages', 0, 23, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'languages'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'فنی و حرفه‌ای', 'technical-skills', 0, 24, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'technical-skills'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'کشاورزی و دامپروری', 'agriculture-livestock', 0, 25, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'agriculture-livestock'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'هنر، ورزش و محتوای عمومی', 'arts-sports-general', 0, 26, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'arts-sports-general'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'آیین‌نامه و رانندگی', 'driving', 0, 27, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'driving'
);

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT NULL, 'خانه و سبک زندگی', 'lifestyle', 0, 28, 1
WHERE NOT EXISTS (
    SELECT 1 FROM categories WHERE slug = 'lifestyle'
);

-- ------------------------------------------------------------
-- زیرگروه‌ها
-- ------------------------------------------------------------
INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کتاب الکترونیکی', 'books-pdf-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'رمان', 'books-pdf-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شعر', 'books-pdf-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'داستان', 'books-pdf-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کتاب کودک و نوجوان', 'books-pdf-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کتاب دانشگاهی', 'books-pdf-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کتاب درسی', 'books-pdf-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کتاب کمک‌آموزشی', 'books-pdf-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'جزوه', 'books-pdf-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'خلاصه کتاب', 'books-pdf-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'خلاصه درس', 'books-pdf-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مجلات و نشریات', 'books-pdf-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کتاب‌های زبان خارجی', 'books-pdf-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کتاب‌های عمومی', 'books-pdf-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'PDF آموزشی', 'books-pdf-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'books-pdf'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'books-pdf-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پروژه دانشجویی', 'academic-projects-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تحقیق دانشجویی', 'academic-projects-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مقاله', 'academic-projects-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ترجمه مقاله', 'academic-projects-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایان‌نامه', 'academic-projects-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پروپوزال', 'academic-projects-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'سمینار', 'academic-projects-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گزارش تحقیق', 'academic-projects-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'روش تحقیق', 'academic-projects-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پروژه عملی', 'academic-projects-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گزارش کار', 'academic-projects-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گزارش کارآموزی', 'academic-projects-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کارورزی', 'academic-projects-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'خلاصه دروس', 'academic-projects-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حل تمرین', 'academic-projects-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پروژه درسی', 'academic-projects-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پژوهش و تحقیقات', 'academic-projects-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'academic-projects'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-projects-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت دانشگاهی', 'powerpoint-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت دانش‌آموزی', 'powerpoint-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت آموزشی', 'powerpoint-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت پزشکی', 'powerpoint-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت مهندسی', 'powerpoint-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت مدیریت', 'powerpoint-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت روانشناسی', 'powerpoint-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت علوم انسانی', 'powerpoint-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت علوم پایه', 'powerpoint-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت کسب‌وکار', 'powerpoint-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت تاریخی', 'powerpoint-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت مذهبی', 'powerpoint-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت کودک', 'powerpoint-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت دفاع پایان‌نامه', 'powerpoint-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ارائه کلاسی', 'powerpoint-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'سمینار آماده', 'powerpoint-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قالب پاورپوینت', 'powerpoint-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'powerpoint'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'powerpoint-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پیش‌دبستانی', 'school-education-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ابتدایی', 'school-education-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'متوسطه اول', 'school-education-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'متوسطه دوم', 'school-education-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایه اول', 'school-education-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایه دوم', 'school-education-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایه سوم', 'school-education-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایه چهارم', 'school-education-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایه پنجم', 'school-education-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایه ششم', 'school-education-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایه هفتم', 'school-education-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایه هشتم', 'school-education-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایه نهم', 'school-education-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایه دهم', 'school-education-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایه یازدهم', 'school-education-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایه دوازدهم', 'school-education-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'امتحانات نهایی', 'school-education-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه سوال مدارس', 'school-education-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'درسنامه', 'school-education-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'جزوه مدرسه', 'school-education-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کاربرگ', 'school-education-21', 1, 21, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-21'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فعالیت و تمرین', 'school-education-22', 1, 22, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-22'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'طرح درس', 'school-education-23', 1, 23, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-23'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'طرح درس روزانه', 'school-education-24', 1, 24, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-24'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'طرح درس سالانه', 'school-education-25', 1, 25, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-25'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'محتوای آموزشی معلمان', 'school-education-26', 1, 26, 1
FROM categories p
WHERE p.slug = 'school-education'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'school-education-26'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کنکور سراسری', 'exams-tests-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کنکور کارشناسی ارشد', 'exams-tests-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کنکور دکتری', 'exams-tests-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه سوال کنکور', 'exams-tests-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آزمون آزمایشی', 'exams-tests-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه سوال دانشگاه', 'exams-tests-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه سوال پیام نور', 'exams-tests-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه سوال دانشگاه آزاد', 'exams-tests-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'امتحانات نهایی', 'exams-tests-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آزمون‌های مدارس', 'exams-tests-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آزمون فنی و حرفه‌ای', 'exams-tests-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آزمون نظام مهندسی', 'exams-tests-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آزمون وکالت', 'exams-tests-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آزمون کارشناسی رسمی', 'exams-tests-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آزمون سردفتری', 'exams-tests-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آزمون‌های تخصصی', 'exams-tests-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'exams-tests'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'exams-tests-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آموزش و پرورش', 'employment-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'دستگاه‌های اجرایی', 'employment-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'بانک‌ها', 'employment-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'وزارت نفت', 'employment-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شرکت نفت', 'employment-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شرکت گاز', 'employment-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'وزارت نیرو', 'employment-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'وزارت بهداشت', 'employment-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شهرداری', 'employment-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'استانداری', 'employment-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تأمین اجتماعی', 'employment-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شرکت‌های دولتی', 'employment-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'سوالات عمومی استخدامی', 'employment-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'سوالات تخصصی استخدامی', 'employment-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'جزوات استخدامی', 'employment-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه سوال استخدامی', 'employment-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مصاحبه استخدامی', 'employment-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گزینش', 'employment-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'سوالات روانشناسی استخدامی', 'employment-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ICDL استخدامی', 'employment-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'employment'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'employment-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مدیریت', 'academic-fields-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حسابداری', 'academic-fields-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اقتصاد', 'academic-fields-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حقوق', 'academic-fields-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'روانشناسی', 'academic-fields-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'علوم تربیتی', 'academic-fields-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'علوم اجتماعی', 'academic-fields-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'علوم سیاسی', 'academic-fields-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ادبیات', 'academic-fields-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تاریخ', 'academic-fields-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'جغرافیا', 'academic-fields-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فلسفه و منطق', 'academic-fields-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'معارف اسلامی', 'academic-fields-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کامپیوتر و IT', 'academic-fields-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'برق و مخابرات', 'academic-fields-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'عمران و نقشه‌برداری', 'academic-fields-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'معماری', 'academic-fields-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شهرسازی', 'academic-fields-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مکانیک', 'academic-fields-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'صنایع', 'academic-fields-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شیمی', 'academic-fields-21', 1, 21, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-21'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مهندسی پزشکی', 'academic-fields-22', 1, 22, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-22'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مهندسی آب', 'academic-fields-23', 1, 23, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-23'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نفت', 'academic-fields-24', 1, 24, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-24'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'معدن', 'academic-fields-25', 1, 25, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-25'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مواد و متالورژی', 'academic-fields-26', 1, 26, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-26'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نساجی', 'academic-fields-27', 1, 27, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-27'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کشاورزی', 'academic-fields-28', 1, 28, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-28'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'علوم دامی', 'academic-fields-29', 1, 29, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-29'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شیلات', 'academic-fields-30', 1, 30, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-30'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'محیط زیست', 'academic-fields-31', 1, 31, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-31'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ریاضی', 'academic-fields-32', 1, 32, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-32'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آمار', 'academic-fields-33', 1, 33, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-33'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فیزیک', 'academic-fields-34', 1, 34, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-34'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'زیست‌شناسی', 'academic-fields-35', 1, 35, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-35'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'زمین‌شناسی', 'academic-fields-36', 1, 36, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-36'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نجوم', 'academic-fields-37', 1, 37, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-37'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پزشکی', 'academic-fields-38', 1, 38, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-38'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرستاری', 'academic-fields-39', 1, 39, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-39'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مامایی', 'academic-fields-40', 1, 40, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-40'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'دندانپزشکی', 'academic-fields-41', 1, 41, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-41'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'داروسازی', 'academic-fields-42', 1, 42, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-42'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تغذیه', 'academic-fields-43', 1, 43, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-43'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'بهداشت', 'academic-fields-44', 1, 44, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-44'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تربیت بدنی', 'academic-fields-45', 1, 45, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-45'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کتابداری و علم اطلاعات', 'academic-fields-46', 1, 46, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-46'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گردشگری', 'academic-fields-47', 1, 47, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-47'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ارتباطات و روزنامه‌نگاری', 'academic-fields-48', 1, 48, 1
FROM categories p
WHERE p.slug = 'academic-fields'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'academic-fields-48'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پزشکی', 'medical-health-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرستاری', 'medical-health-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مامایی', 'medical-health-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'دندانپزشکی', 'medical-health-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'داروسازی', 'medical-health-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'علوم آزمایشگاهی', 'medical-health-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فوریت‌های پزشکی', 'medical-health-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اتاق عمل', 'medical-health-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'هوشبری', 'medical-health-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فیزیوتراپی', 'medical-health-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کاردرمانی', 'medical-health-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'بهداشت', 'medical-health-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تغذیه', 'medical-health-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'علوم توانبخشی', 'medical-health-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مقالات پزشکی', 'medical-health-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گزارش کار پزشکی', 'medical-health-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گزارش کار پرستاری', 'medical-health-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'جزوات پزشکی', 'medical-health-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پاورپوینت پزشکی', 'medical-health-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'علوم پایه پزشکی', 'medical-health-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مدیریت خدمات بهداشتی و درمانی', 'medical-health-21', 1, 21, 1
FROM categories p
WHERE p.slug = 'medical-health'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'medical-health-21'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'عمران', 'engineering-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'معماری', 'engineering-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شهرسازی', 'engineering-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نقشه‌برداری', 'engineering-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مکانیک', 'engineering-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'برق', 'engineering-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'صنایع', 'engineering-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کامپیوتر', 'engineering-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شیمی', 'engineering-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نفت', 'engineering-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'معدن', 'engineering-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مواد و متالورژی', 'engineering-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مهندسی پزشکی', 'engineering-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مهندسی آب', 'engineering-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کشاورزی', 'engineering-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نقشه و پلان', 'engineering-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'AutoCAD', 'engineering-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'Revit', 'engineering-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'SketchUp', 'engineering-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, '3ds Max', 'engineering-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'Lumion', 'engineering-21', 1, 21, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-21'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ETABS', 'engineering-22', 1, 22, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-22'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'SAFE', 'engineering-23', 1, 23, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-23'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'SAP', 'engineering-24', 1, 24, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-24'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'متره و برآورد', 'engineering-25', 1, 25, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-25'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فهرست‌بها', 'engineering-26', 1, 26, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-26'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'صورت‌وضعیت', 'engineering-27', 1, 27, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-27'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گزارش نظام مهندسی', 'engineering-28', 1, 28, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-28'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پروژه‌های مهندسی', 'engineering-29', 1, 29, 1
FROM categories p
WHERE p.slug = 'engineering'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'engineering-29'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه قرارداد', 'legal-contracts-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرارداد اجاره', 'legal-contracts-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرارداد فروش', 'legal-contracts-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرارداد کاری', 'legal-contracts-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرارداد استخدام', 'legal-contracts-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرارداد پیمانکاری', 'legal-contracts-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرارداد مشارکت', 'legal-contracts-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرارداد شراکت', 'legal-contracts-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرارداد خدمات', 'legal-contracts-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرارداد محرمانگی', 'legal-contracts-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مبایعه‌نامه', 'legal-contracts-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اظهارنامه', 'legal-contracts-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'دادخواست', 'legal-contracts-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شکواییه', 'legal-contracts-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'لایحه', 'legal-contracts-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه اسناد حقوقی', 'legal-contracts-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم‌های حقوقی', 'legal-contracts-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرارداد خرید و فروش', 'legal-contracts-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرارداد مشاوره', 'legal-contracts-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرارداد نمایندگی', 'legal-contracts-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'legal-contracts'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'legal-contracts-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم اداری', 'forms-documents-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم استخدام', 'forms-documents-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم ارزیابی', 'forms-documents-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم حضور و غیاب', 'forms-documents-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم مرخصی', 'forms-documents-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم درخواست', 'forms-documents-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم مالی', 'forms-documents-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم آموزشی', 'forms-documents-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم مدرسه', 'forms-documents-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم دانشگاه', 'forms-documents-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم پزشکی', 'forms-documents-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'چک‌لیست', 'forms-documents-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه نامه اداری', 'forms-documents-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نامه درخواست', 'forms-documents-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نامه معرفی', 'forms-documents-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نامه اشتغال', 'forms-documents-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'معرفی‌نامه', 'forms-documents-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تقدیرنامه', 'forms-documents-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم‌های سازمانی', 'forms-documents-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم قابل ویرایش Word', 'forms-documents-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم قابل چاپ', 'forms-documents-21', 1, 21, 1
FROM categories p
WHERE p.slug = 'forms-documents'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'forms-documents-21'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'طرح توجیهی', 'business-entrepreneurship-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'طرح کسب‌وکار', 'business-entrepreneurship-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'Business Plan', 'business-entrepreneurship-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'امکان‌سنجی', 'business-entrepreneurship-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مطالعات بازار', 'business-entrepreneurship-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'طرح کارآفرینی', 'business-entrepreneurship-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ایده کسب‌وکار', 'business-entrepreneurship-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'راه‌اندازی کسب‌وکار', 'business-entrepreneurship-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مدیریت کسب‌وکار', 'business-entrepreneurship-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مدیریت منابع انسانی', 'business-entrepreneurship-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مدیریت پروژه', 'business-entrepreneurship-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مدیریت استراتژیک', 'business-entrepreneurship-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'بازاریابی', 'business-entrepreneurship-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فروش و مذاکره', 'business-entrepreneurship-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'دیجیتال مارکتینگ', 'business-entrepreneurship-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'برندینگ', 'business-entrepreneurship-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تجارت الکترونیک', 'business-entrepreneurship-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کسب‌وکار اینترنتی', 'business-entrepreneurship-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فریلنسری', 'business-entrepreneurship-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تولید محتوا', 'business-entrepreneurship-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مدل کسب‌وکار', 'business-entrepreneurship-21', 1, 21, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-21'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پروژه کارآفرینی', 'business-entrepreneurship-22', 1, 22, 1
FROM categories p
WHERE p.slug = 'business-entrepreneurship'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'business-entrepreneurship-22'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حسابداری', 'accounting-finance-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حسابداری شرکت‌ها', 'accounting-finance-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حسابداری صنعتی', 'accounting-finance-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حسابداری پیمانکاری', 'accounting-finance-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حسابداری حقوق و دستمزد', 'accounting-finance-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حسابداری مالی', 'accounting-finance-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حسابداری مدیریت', 'accounting-finance-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مالیات', 'accounting-finance-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اظهارنامه مالیاتی', 'accounting-finance-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ارزش افزوده', 'accounting-finance-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'امور مالی', 'accounting-finance-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'بودجه‌بندی', 'accounting-finance-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تحلیل مالی', 'accounting-finance-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'صورت‌های مالی', 'accounting-finance-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اکسل حسابداری', 'accounting-finance-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فایل‌های حسابداری', 'accounting-finance-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم‌های مالی', 'accounting-finance-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حسابداری دولتی', 'accounting-finance-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حسابداری فروشگاه', 'accounting-finance-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حسابداری انبار', 'accounting-finance-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'accounting-finance'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'accounting-finance-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه روانشناسی', 'questionnaires-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه مدیریت', 'questionnaires-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه بازاریابی', 'questionnaires-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه منابع انسانی', 'questionnaires-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه آموزشی', 'questionnaires-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه پزشکی', 'questionnaires-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه سلامت', 'questionnaires-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه رضایت مشتری', 'questionnaires-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه رضایت شغلی', 'questionnaires-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه پایان‌نامه', 'questionnaires-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه استاندارد', 'questionnaires-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مقیاس‌های روانشناسی', 'questionnaires-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ابزار پژوهش', 'questionnaires-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم جمع‌آوری داده', 'questionnaires-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'روایی و پایایی', 'questionnaires-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فایل SPSS', 'questionnaires-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه علوم اجتماعی', 'questionnaires-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرسشنامه رفتار سازمانی', 'questionnaires-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'questionnaires'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'questionnaires-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'خودشناسی', 'psychology-self-development-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اعتمادبه‌نفس', 'psychology-self-development-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'عزت نفس', 'psychology-self-development-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'موفقیت', 'psychology-self-development-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مدیریت استرس', 'psychology-self-development-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مدیریت زمان', 'psychology-self-development-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'هدف‌گذاری', 'psychology-self-development-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'عادت‌سازی', 'psychology-self-development-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مهارت‌های زندگی', 'psychology-self-development-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'هوش هیجانی', 'psychology-self-development-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'روابط اجتماعی', 'psychology-self-development-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ازدواج', 'psychology-self-development-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'خانواده', 'psychology-self-development-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تربیت کودک', 'psychology-self-development-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرزندپروری', 'psychology-self-development-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تست‌های روانشناسی', 'psychology-self-development-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کتاب‌های روانشناسی', 'psychology-self-development-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ارتباط مؤثر', 'psychology-self-development-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'رشد فردی', 'psychology-self-development-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مهارت‌های شغلی', 'psychology-self-development-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'psychology-self-development'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'psychology-self-development-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'Python', 'programming-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'Java', 'programming-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'C', 'programming-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'C++', 'programming-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'C#', 'programming-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'PHP', 'programming-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'JavaScript', 'programming-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'TypeScript', 'programming-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'HTML', 'programming-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'CSS', 'programming-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'SQL', 'programming-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'SQL Server', 'programming-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'MySQL', 'programming-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ASP.NET', 'programming-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, '.NET', 'programming-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'Android', 'programming-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'Flutter', 'programming-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'MATLAB', 'programming-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'Delphi', 'programming-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'Visual Basic', 'programming-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'طراحی وب', 'programming-21', 1, 21, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-21'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پایگاه داده', 'programming-22', 1, 22, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-22'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'الگوریتم', 'programming-23', 1, 23, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-23'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پروژه برنامه‌نویسی', 'programming-24', 1, 24, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-24'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'سورس کد', 'programming-25', 1, 25, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-25'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اسکریپت', 'programming-26', 1, 26, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-26'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'Git و GitHub', 'programming-27', 1, 27, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-27'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شبکه و سرور', 'programming-28', 1, 28, 1
FROM categories p
WHERE p.slug = 'programming'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'programming-28'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آموزش هوش مصنوعی', 'artificial-intelligence-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ChatGPT', 'artificial-intelligence-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرامپت', 'artificial-intelligence-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مهندسی پرامپت', 'artificial-intelligence-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تولید محتوا با AI', 'artificial-intelligence-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تولید تصویر با AI', 'artificial-intelligence-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تولید ویدئو با AI', 'artificial-intelligence-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'هوش مصنوعی در کسب‌وکار', 'artificial-intelligence-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'هوش مصنوعی در آموزش', 'artificial-intelligence-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'هوش مصنوعی در برنامه‌نویسی', 'artificial-intelligence-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'یادگیری ماشین', 'artificial-intelligence-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'علم داده', 'artificial-intelligence-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تحلیل داده', 'artificial-intelligence-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ابزارهای هوش مصنوعی', 'artificial-intelligence-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پردازش زبان طبیعی', 'artificial-intelligence-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'بینایی ماشین', 'artificial-intelligence-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پروژه‌های هوش مصنوعی', 'artificial-intelligence-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'artificial-intelligence'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'artificial-intelligence-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قالب وردپرس', 'wordpress-web-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'افزونه وردپرس', 'wordpress-web-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پلاگین', 'wordpress-web-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قالب HTML', 'wordpress-web-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قالب سایت', 'wordpress-web-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اسکریپت', 'wordpress-web-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'PHP', 'wordpress-web-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'JavaScript', 'wordpress-web-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فروشگاه اینترنتی', 'wordpress-web-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'WooCommerce', 'wordpress-web-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'طراحی سایت', 'wordpress-web-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ابزارهای وب', 'wordpress-web-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قالب مدیریتی', 'wordpress-web-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قالب فروشگاهی', 'wordpress-web-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قالب شرکتی', 'wordpress-web-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'wordpress-web'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'wordpress-web-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فایل Excel', 'excel-tools-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اکسل حسابداری', 'excel-tools-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اکسل مالی', 'excel-tools-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اکسل فروش', 'excel-tools-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اکسل انبارداری', 'excel-tools-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اکسل حقوق و دستمزد', 'excel-tools-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اکسل مدیریت', 'excel-tools-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اکسل برنامه‌ریزی', 'excel-tools-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'داشبورد Excel', 'excel-tools-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمودار و گزارش', 'excel-tools-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فاکتور آماده', 'excel-tools-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مدیریت مشتری', 'excel-tools-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مدیریت پروژه', 'excel-tools-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حضور و غیاب', 'excel-tools-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'محاسبه‌گرهای Excel', 'excel-tools-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'برنامه‌ریزی مالی', 'excel-tools-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرم Excel', 'excel-tools-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'excel-tools'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'excel-tools-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'رزومه آماده', 'resume-career-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'رزومه Word', 'resume-career-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'رزومه PDF', 'resume-career-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'رزومه انگلیسی', 'resume-career-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'رزومه دانشجویی', 'resume-career-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'رزومه مهندسی', 'resume-career-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'رزومه مدیریتی', 'resume-career-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'CV', 'resume-career-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'Cover Letter', 'resume-career-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'انگیزه‌نامه', 'resume-career-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'توصیه‌نامه', 'resume-career-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'معرفی‌نامه', 'resume-career-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه نامه اشتغال', 'resume-career-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پروفایل کاری', 'resume-career-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'رزومه خلاقانه', 'resume-career-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'رزومه قابل ویرایش', 'resume-career-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'resume-career'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'resume-career-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'PSD', 'graphic-design-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'AI', 'graphic-design-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'EPS', 'graphic-design-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'SVG', 'graphic-design-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'PNG', 'graphic-design-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'JPG', 'graphic-design-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'GIF', 'graphic-design-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'وکتور', 'graphic-design-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'لوگو', 'graphic-design-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کارت ویزیت', 'graphic-design-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تراکت', 'graphic-design-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'بروشور', 'graphic-design-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پوستر', 'graphic-design-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'بنر', 'graphic-design-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کاتالوگ', 'graphic-design-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'سربرگ', 'graphic-design-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'موکاپ', 'graphic-design-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آیکون', 'graphic-design-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'اینفوگرافیک', 'graphic-design-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قالب شبکه اجتماعی', 'graphic-design-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فونت', 'graphic-design-21', 1, 21, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-21'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فایل لایه‌باز', 'graphic-design-22', 1, 22, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-22'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'الگو و طرح', 'graphic-design-23', 1, 23, 1
FROM categories p
WHERE p.slug = 'graphic-design'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'graphic-design-23'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'محتوای آماده', 'content-social-media-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تقویم محتوایی', 'content-social-media-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ایده تولید محتوا', 'content-social-media-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کپشن آماده', 'content-social-media-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'سناریوی ریلز', 'content-social-media-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'سناریوی تبلیغاتی', 'content-social-media-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قالب پست اینستاگرام', 'content-social-media-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قالب استوری', 'content-social-media-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'محتوای اینستاگرام', 'content-social-media-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'محتوای تلگرام', 'content-social-media-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'محتوای سایت', 'content-social-media-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'چک‌لیست تولید محتوا', 'content-social-media-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'بازاریابی شبکه‌های اجتماعی', 'content-social-media-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تقویم محتوایی ماهانه', 'content-social-media-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'سناریوی ویدئو', 'content-social-media-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'content-social-media'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'content-social-media-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'انگلیسی', 'languages-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آلمانی', 'languages-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فرانسه', 'languages-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ترکی', 'languages-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'عربی', 'languages-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'زبان عمومی', 'languages-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گرامر', 'languages-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'لغات', 'languages-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مکالمه', 'languages-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'IELTS', 'languages-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'TOEFL', 'languages-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آزمون زبان', 'languages-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'جزوه زبان', 'languages-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کتاب زبان', 'languages-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فلش‌کارت', 'languages-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه سوال زبان', 'languages-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ترجمه', 'languages-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'زبان تخصصی', 'languages-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آموزش زبان کودکان', 'languages-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'languages'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'languages-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه سوال فنی و حرفه‌ای', 'technical-skills-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ICDL', 'technical-skills-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'برق ساختمان', 'technical-skills-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'برق صنعتی', 'technical-skills-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'جوشکاری', 'technical-skills-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مکانیک خودرو', 'technical-skills-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تعمیرات موبایل', 'technical-skills-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تعمیرات لوازم خانگی', 'technical-skills-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شبکه', 'technical-skills-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'طراحی سایت', 'technical-skills-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گرافیک', 'technical-skills-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'خیاطی', 'technical-skills-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آرایشگری', 'technical-skills-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آشپزی', 'technical-skills-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'حسابداری', 'technical-skills-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گردشگری', 'technical-skills-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'صنایع دستی', 'technical-skills-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تعمیرات کولر', 'technical-skills-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تأسیسات', 'technical-skills-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مهارت‌های شغلی', 'technical-skills-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'technical-skills'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'technical-skills-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کشاورزی', 'agriculture-livestock-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'زراعت', 'agriculture-livestock-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'باغبانی', 'agriculture-livestock-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گلخانه', 'agriculture-livestock-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گیاهان دارویی', 'agriculture-livestock-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرورش قارچ', 'agriculture-livestock-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'دامداری', 'agriculture-livestock-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گاوداری', 'agriculture-livestock-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'گوسفندداری', 'agriculture-livestock-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مرغداری', 'agriculture-livestock-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرورش طیور', 'agriculture-livestock-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'زنبورداری', 'agriculture-livestock-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شیلات', 'agriculture-livestock-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرورش ماهی', 'agriculture-livestock-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'صنایع غذایی', 'agriculture-livestock-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'منابع طبیعی', 'agriculture-livestock-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'محیط زیست', 'agriculture-livestock-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آبیاری', 'agriculture-livestock-18', 1, 18, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-18'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'طرح توجیهی کشاورزی', 'agriculture-livestock-19', 1, 19, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-19'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'پرورش آبزیان', 'agriculture-livestock-20', 1, 20, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-20'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'دامپزشکی', 'agriculture-livestock-21', 1, 21, 1
FROM categories p
WHERE p.slug = 'agriculture-livestock'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'agriculture-livestock-21'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'هنر و گرافیک آموزشی', 'arts-sports-general-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نقاشی', 'arts-sports-general-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'طراحی', 'arts-sports-general-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'صنایع دستی', 'arts-sports-general-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تربیت بدنی', 'arts-sports-general-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ورزش', 'arts-sports-general-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'بدنسازی', 'arts-sports-general-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'برنامه تمرینی', 'arts-sports-general-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تغذیه ورزشی', 'arts-sports-general-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'محتوای مذهبی', 'arts-sports-general-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'قرآن', 'arts-sports-general-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'ادعیه', 'arts-sports-general-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'احکام', 'arts-sports-general-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'محتوای مناسبتی', 'arts-sports-general-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'محتوای فرهنگی', 'arts-sports-general-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'موسیقی و هنر', 'arts-sports-general-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-16'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فایل‌های عمومی', 'arts-sports-general-17', 1, 17, 1
FROM categories p
WHERE p.slug = 'arts-sports-general'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'arts-sports-general-17'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آیین‌نامه رانندگی', 'driving-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'driving'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'driving-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه سوال آیین‌نامه', 'driving-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'driving'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'driving-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آزمون آیین‌نامه', 'driving-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'driving'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'driving-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'علائم راهنمایی و رانندگی', 'driving-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'driving'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'driving-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آموزش رانندگی', 'driving-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'driving'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'driving-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'سوالات فنی خودرو', 'driving-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'driving'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'driving-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آیین‌نامه موتور سیکلت', 'driving-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'driving'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'driving-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'نمونه سوال موتور سیکلت', 'driving-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'driving'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'driving-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آشپزی', 'lifestyle-01', 1, 1, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-01'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'غذاهای ایرانی', 'lifestyle-02', 1, 2, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-02'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'شیرینی‌پزی', 'lifestyle-03', 1, 3, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-03'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'کیک', 'lifestyle-04', 1, 4, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-04'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'فینگر فود', 'lifestyle-05', 1, 5, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-05'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'رژیم غذایی', 'lifestyle-06', 1, 6, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-06'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'برنامه غذایی', 'lifestyle-07', 1, 7, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-07'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'خانه‌داری', 'lifestyle-08', 1, 8, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-08'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'خیاطی', 'lifestyle-09', 1, 9, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-09'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'بافتنی', 'lifestyle-10', 1, 10, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-10'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'صنایع دستی', 'lifestyle-11', 1, 11, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-11'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'دکوراسیون', 'lifestyle-12', 1, 12, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-12'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'DIY', 'lifestyle-13', 1, 13, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-13'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'تزئینات', 'lifestyle-14', 1, 14, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-14'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'مهارت‌های خانه', 'lifestyle-15', 1, 15, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-15'
  );

INSERT INTO categories
(parent_id, name, slug, level, sort_order, status)
SELECT p.id, 'آموزش‌های کاربردی', 'lifestyle-16', 1, 16, 1
FROM categories p
WHERE p.slug = 'lifestyle'
  AND NOT EXISTS (
      SELECT 1 FROM categories c WHERE c.slug = 'lifestyle-16'
  );

COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- تعداد گروه‌های اصلی و زیرگروه‌ها
-- ============================================================
-- گروه‌های اصلی: 28
-- زیرگروه‌ها: 562
-- مجموع دسته‌ها: 590
-- ============================================================
