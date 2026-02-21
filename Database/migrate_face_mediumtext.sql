-- ============================================================
-- MIGRASI: Perbesar kolom face_descriptor ke MEDIUMTEXT
-- Kolom TEXT hanya ~64KB, tidak cukup untuk base64 foto wajah
-- MEDIUMTEXT mampu menyimpan hingga 16MB
-- 
-- Jalankan sekali di production server:
--   mysql -u USER -p DATABASE < migrate_face_mediumtext.sql
-- Atau via phpMyAdmin: Import file ini
-- ============================================================

ALTER TABLE `employees`
  MODIFY COLUMN `face_descriptor` MEDIUMTEXT COLLATE utf8mb4_unicode_ci;

SELECT 'Migration OK: face_descriptor is now MEDIUMTEXT' AS status;
