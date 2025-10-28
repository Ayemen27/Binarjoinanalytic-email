-- ═══════════════════════════════════════════════════════════════════════════════════════
-- PostgreSQL Database Export
-- Converted from MySQL dump
-- Conversion Date: 2025-10-28 03:29:49
-- ═══════════════════════════════════════════════════════════════════════════════════════

-- تعطيل المحفزات والقيود أثناء الاستيراد
SET session_replication_role = 'replica';
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;

BEGIN;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 25, 2025 at 08:25 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.4

--
-- Database: "tempmail"
--

-- --------------------------------------------------------

--
-- Table structure for table "admins"
--

CREATE TABLE "admins" (
  "id" BIGSERIAL NOT NULL,
  "firstname" VARCHAR(255) DEFAULT NULL,
  "lastname" VARCHAR(255) DEFAULT NULL,
  "email" VARCHAR(255) NOT NULL,
  "avatar" VARCHAR(255) DEFAULT NULL,
  "password" VARCHAR(255) NOT NULL,
  "remember_token" VARCHAR(100) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "ads"
--

CREATE TABLE "ads" (
  "id" BIGSERIAL NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "shortcode" VARCHAR(255) NOT NULL,
  "code" TEXT,
  "status" BOOLEAN NOT NULL DEFAULT TRUE,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

--
-- Dumping data for table "ads"
--

INSERT INTO "ads" ("id", "name", "shortcode", "code", "status", "created_at", "updated_at") VALUES
(1, 'header left ad ', 'header_left', '<img src="https://placehold.co/250x250" >', TRUE, NULL, '2024-10-07 00:57:31'),
(3, 'header right ad ', 'header_right', '<img src="https://placehold.co/250x250" >', TRUE, NULL, '2024-10-07 00:57:36'),
(4, 'header bottom ad ', 'header_bottom', '<img src="https://placehold.co/720x90" >', TRUE, NULL, '2024-10-07 03:15:58'),
(5, 'mailbox left ad ', 'mailbox_left', '<img src="https://placehold.co/200x660" >', TRUE, NULL, '2024-10-07 03:16:10'),
(6, 'mailbox right ad ', 'mailbox_right', '<img src="https://placehold.co/200x660" >', TRUE, NULL, '2024-10-07 03:19:42'),
(7, 'mailbox top ad ', 'mailbox_top', '<img src="https://placehold.co/720x90" >', TRUE, NULL, '2024-10-07 03:16:21'),
(9, 'mailbox bottom ad ', 'mailbox_bottom', '<img src="https://placehold.co/720x90" >', TRUE, NULL, '2024-10-07 03:16:26'),
(10, 'Post top ad ', 'post_top', '<img src="https://placehold.co/720x90" >', TRUE, NULL, '2024-10-07 03:16:32'),
(11, 'Post bottom ad ', 'post_bottom', '<img src="https://placehold.co/720x90" >', TRUE, NULL, '2024-10-07 03:16:36'),
(12, 'Post sidebar ad ', 'post_sidebar', '<img src="https://placehold.co/350x350" >', TRUE, NULL, '2024-10-07 03:16:49'),
(13, 'sticky ad', 'sticky_ad', '<img src="https://placehold.co/720x90" >', TRUE, NULL, '2024-10-07 16:49:02');

-- --------------------------------------------------------

--
-- Table structure for table "blog_categories"
--

CREATE TABLE "blog_categories" (
  "id" BIGSERIAL NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "slug" VARCHAR(255) NOT NULL,
  "lang" VARCHAR(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "blog_posts"
--

CREATE TABLE "blog_posts" (
  "id" BIGSERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  "description" TEXT NOT NULL,
  "slug" VARCHAR(255) NOT NULL,
  "content" TEXT NOT NULL,
  "image" VARCHAR(255) DEFAULT NULL,
  "small_image" VARCHAR(255) DEFAULT NULL,
  "status" int NOT NULL DEFAULT '1',
  "views" int NOT NULL DEFAULT '0',
  "meta_description" TEXT,
  "meta_title" VARCHAR(255) DEFAULT NULL,
  "tags" VARCHAR(255) DEFAULT NULL,
  "lang" VARCHAR(255) NOT NULL,
  "category_id" BIGINT NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "cache"
--

CREATE TABLE "cache" (
  "key" VARCHAR(255) NOT NULL,
  "value" TEXT NOT NULL,
  "expiration" int NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "cache_locks"
--

CREATE TABLE "cache_locks" (
  "key" VARCHAR(255) NOT NULL,
  "owner" VARCHAR(255) NOT NULL,
  "expiration" int NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "coupons"
--

-- --------------------------------------------------------

--
-- Table structure for table "domains"
--

CREATE TABLE "domains" (
  "id" BIGSERIAL NOT NULL,
  "domain" VARCHAR(255) NOT NULL,
  "status" int NOT NULL DEFAULT '0',
  "type" int NOT NULL DEFAULT '0',
  "user_id" BIGINT DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "email_templates"
--

CREATE TABLE "email_templates" (
  "id" BIGSERIAL NOT NULL,
  "alias" VARCHAR(255) NOT NULL,
  "subject" VARCHAR(255) NOT NULL,
  "shortcodes" TEXT,
  "body" TEXT NOT NULL,
  "status" int NOT NULL DEFAULT '1',
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

--
-- Dumping data for table "email_templates"
--

INSERT INTO "email_templates" ("id", "alias", "subject", "shortcodes", "body", "status", "created_at", "updated_at") VALUES
(1, 'email_verification', 'Verify Your Email Address', '{"{{link}}": "The verification link the user must click",\n  "{{website_name}}": "The name of the website",\n  "{{website_url}}": "The URL of the website"\n}', '<h5><strong>Hello!</strong></h5><p>Please click the link below to verify your email address.</p><p style="text-align:center;"><a href="{{link}}" target="_blank" rel="noopener noreferrer">Verify Email Address</a></p><p>If you did not create an account, no further action is required.</p><p>Regards,</p><p>{{website_name}}<br>{{website_url}}</p><hr><p>If you''re having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:<br><a href="{{link}}">{{link}}</a></p>', 1, '2024-09-25 01:38:57', '2024-09-30 00:38:17'),
(2, 'reset_password', 'Reset Your Password', '{\n  "{{link}}": "The password reset link",\n  "{{expiry_time}}": "The expiration time of the password reset link",\n  "{{website_name}}": "The name of the website",\n  "{{website_url}}": "The URL of the website"\n}', '<h3><strong>Hello!</strong></h3><p>You are receiving this email because we received a password reset request for your account.</p><p style="text-align:center;"><a href="{{link}}">Reset Password</a></p><p>This password reset link will expire in {{expiry_time}} minutes.</p><p>If you did not request a password reset, no further action is required.</p><p>Regards,</p><p>{{website_name}}<br>{{website_url}}</p>', 1, '2024-09-25 01:38:57', '2024-09-26 00:39:09'),
(3, 'contact_us', 'New Message from {{fullname}} via Contact Us Form', '{\r\n  "{{fullname}}": "Sender''s name",\r\n  "{{email}}": "Sender''s email",\r\n  "{{message}}": "Submitted message",\r\n  "{{subject}}": "Submitted subject",\r\n  "{{ip}}": "Sender''s IP address",\r\n  "{{country}}": "Sender''s country",\r\n  "{{user_agent}}": "Sender''s browser and device information",\r\n  "{{user_id}}": "Logged-in user''s ID (if applicable)",\r\n  "{{website_name}}": "Website name",\r\n  "{{website_url}}": "Website URL"\r\n}', '<p>Hello Admin, <br><br>You have received a new message from the contact us form. Here are the details:</p><p><br><strong>Full Name:</strong> {{fullname}} <br><strong>Email:</strong> {{email}} <br><strong>Subject:</strong> {{subject}} <br><strong>Message:</strong><br>{{message}} <br><strong>IP Address:</strong> {{ip}} <br><strong>Country:</strong> {{country}} <br><strong>User ID:</strong> {{user_id}} <br><strong>Browser/Device Info:</strong> {{user_agent}}</p><p><br><br>This message was sent from <a href="{{website_url}}"><strong>{{website_name}}</strong></a> .<br><br>Please respond as soon as possible.</p><p> </p>', 1, '2024-09-25 01:38:57', '2024-09-26 01:41:49'),
(4, 'user_added_domain', 'New Domain Added by {{user_fullname}}', '{   "{{user_fullname}}": "The name of the user who added the domain",   "{{user_email}}": "The email address of the user who added the domain",   "{{domain_name}}": "The newly added domain name",   "{{domain_url}}": "The URL of the newly added domain",   "{{admin_panel_url}}": "The URL of the admin panel for reviewing the domain",   "{{website_name}}": "The name of the website",   "{{website_url}}": "The URL of the website" }', '<p>Hello Admin, <br><br>A user has added a new domain. Please review the details below: <br><br><strong>User Name:</strong> {{user_fullname}} <br><strong>User Email:</strong> {{user_email}} <br><strong>Domain Name:</strong> {{domain_name}} <br><strong>Domain URL:</strong> <a href="{{domain_url}}">{{domain_url}}</a> <br><br>You can check the details in the admin panel: <a href="{{admin_panel_url}}">{{admin_panel_url}}</a>.<br><br>This message was sent from <strong>{{website_name}}</strong> (<a href="{{website_url}}">{{website_url}}</a>).<br><br>Thank you!</p>', 1, '2024-09-30 00:31:32', '2024-09-30 01:04:58'),
(5, 'domain_accepted', 'Your Domain {{domain_name}} has been Accepted', '{\n            "{{user_fullname}}": "The name of the user who added the domain",\n            "{{domain_name}}": "The newly added domain name",\n            "{{domain_url}}": "The URL of the newly added domain",\n            "{{status}}": "The status of the domain (Accepted or Rejected)",\n            "{{website_name}}": "The name of the website",\n            "{{website_url}}": "The URL of the website"\n          }', '<p>Hello {{user_fullname}}, <br><br>We are pleased to inform you that your domain has been accepted. Please find the details below: <br><br><strong>Domain Name:</strong> {{domain_name}} <br><strong>Domain URL:</strong> <a href="{{domain_url}}">{{domain_url}}</a> <br><br>You can now use your domain with our services. If you have any questions, feel free to contact us. <br><br>This message was sent from <strong>{{website_name}}</strong> (<a href="{{website_url}}">{{website_url}}</a>).<br><br>Thank you!</p>', 1, '2024-09-30 00:31:32', '2024-09-30 01:05:15'),
(6, 'domain_rejected', 'Your Domain {{domain_name}} has been Rejected', '{\n            "{{user_fullname}}": "The name of the user who added the domain",\n            "{{domain_name}}": "The newly added domain name",\n            "{{domain_url}}": "The URL of the newly added domain",\n            "{{status}}": "The status of the domain (Accepted or Rejected)",\n            "{{website_name}}": "The name of the website",\n            "{{website_url}}": "The URL of the website"\n          }', '<p>Hello {{user_fullname}}, <br><br>We regret to inform you that your domain submission has been rejected. Please review the details below: <br><br><strong>Domain Name:</strong> {{domain_name}} <br><strong>Domain URL:</strong> <a href="{{domain_url}}">{{domain_url}}</a> <br><br><strong>Reason:</strong>  Unfortunately, your domain did not meet our criteria. Please review our guidelines.  <strong>    </strong><br><br>If you have any questions or would like to know more, please contact us. <br><br>This message was sent from <strong>{{website_name}}</strong> (<a href="{{website_url}}">{{website_url}}</a>).<br><br>Thank you!</p><p> </p>', 1, '2024-09-30 00:31:32', '2024-09-30 01:07:38');

-- --------------------------------------------------------

--
-- Table structure for table "failed_jobs"
--

CREATE TABLE "failed_jobs" (
  "id" BIGSERIAL NOT NULL,
  "uuid" VARCHAR(255) NOT NULL,
  "connection" TEXT NOT NULL,
  "queue" TEXT NOT NULL,
  "payload" TEXT NOT NULL,
  "exception" TEXT NOT NULL,
  "failed_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------

--
-- Table structure for table "faqs"
--

CREATE TABLE "faqs" (
  "id" BIGSERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  "content" TEXT,
  "lang" VARCHAR(255) NOT NULL,
  "position" int NOT NULL DEFAULT '0',
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "translate_id" VARCHAR(255) DEFAULT NULL
);

--
-- Dumping data for table "faqs"
--

INSERT INTO "faqs" ("id", "title", "content", "lang", "position", "created_at", "updated_at", "translate_id") VALUES
(1, 'What is a temporary email address?', 'A temporary email address is a disposable, short-term email that allows you to receive messages without exposing your personal email. It''s perfect for signing up for websites or services without worrying about spam', 'en', 0, '2024-12-09 02:46:51', '2024-12-09 02:46:51', '17ad66a8467ca'),
(2, 'How long does a temporary email address last?', 'The lifespan of a temporary email address varies. Typically, it lasts between 10 minutes to a few hours, but some services allow you to extend its duration.', 'en', 0, '2024-12-09 02:47:16', '2024-12-09 02:47:16', '67ad69a8467ca'),
(3, 'Is it safe to use a temporary email address?', 'Yes, temporary emails are designed to protect your privacy. They prevent spam and phishing attempts from reaching your personal inbox and don’t store your data.', 'en', 0, '2024-12-09 02:47:39', '2024-12-09 02:47:39', '67ad66a8467c8'),
(4, 'Can I use a temporary email address for attachments?', 'Absolutely! Temporary email addresses support receiving attachments. You can securely download files sent to your temp email.', 'en', 0, '2024-12-09 02:47:56', '2024-12-09 02:47:56', '67ad66a8467cq'),
(5, 'Do I need to register to use the service?', 'No registration is required. Temporary email services are designed to be quick, anonymous, and hassle-free.', 'en', 0, '2024-12-09 02:48:35', '2024-12-09 02:48:35', '67ad66a8467cn');

-- --------------------------------------------------------

--
-- Table structure for table "features"
--

CREATE TABLE "features" (
  "id" BIGSERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  "icon" VARCHAR(255) NOT NULL,
  "content" TEXT,
  "lang" VARCHAR(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "translate_id" VARCHAR(255) DEFAULT NULL
);

--
-- Dumping data for table "features"
--

INSERT INTO "features" ("id", "title", "icon", "content", "lang", "created_at", "updated_at", "translate_id") VALUES
(1, '100% Safe', '<i class="fas fa-lock"></i>', 'Protect your privacy by keeping spam and unwanted emails out of your personal inbox', 'en', '2024-12-09 02:38:41', '2024-12-09 02:38:41', '67fd66cb38fff'),
(2, 'Instant Access', '<i class="fas fa-bolt"></i>', 'Receive emails instantly with real-time inbox updates—no delays, no refresh required.', 'en', '2024-12-09 02:39:45', '2024-12-09 02:39:45', '67ad66cb30ff0'),
(3, 'Custom Domains', '<i class="fas fa-globe"></i>', 'Choose from multiple email domains to create a unique and secure temporary email address.', 'en', '2024-12-09 02:40:27', '2024-12-09 02:40:27', '67af66cb38ff0'),
(4, 'Simple & Free', '<i class="fas fa-envelope"></i>', 'Create temporary email addresses in just a few clicks. No registration required, and it’s always free to use!', 'en', '2024-12-09 02:41:29', '2024-12-09 02:41:29', '6aad66cb38ff0'),
(5, 'Unlimited Usage', '<i class="fa-solid fa-infinity"></i>', 'Create as many temporary emails as you need—no limits, no hidden fees, and no restrictions on usage.', 'en', '2024-12-09 02:43:56', '2024-12-09 02:45:59', '67ad63cb38ff0'),
(6, 'Favorites Feature', '<i class="fa-solid fa-star"></i>', 'Save important messages to your favorites for quick and easy access whenever you need them.', 'en', '2024-12-09 02:44:42', '2025-02-16 23:56:55', '67ad66cb38ff1');

-- --------------------------------------------------------

--
-- Table structure for table "imaps"
--

CREATE TABLE "imaps" (
  "id" BIGSERIAL NOT NULL,
  "tag" VARCHAR(255) NOT NULL,
  "host" VARCHAR(255) DEFAULT NULL,
  "username" VARCHAR(255) DEFAULT NULL,
  "password" VARCHAR(255) DEFAULT NULL,
  "encryption" VARCHAR(255) DEFAULT NULL,
  "validate_certificates" int DEFAULT NULL,
  "port" int DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

--
-- Dumping data for table "imaps"
--

INSERT INTO "imaps" ("id", "tag", "host", "username", "password", "encryption", "validate_certificates", "port", "created_at", "updated_at") VALUES
(1, 'main', NULL, NULL, NULL, 'ssl', 0, 993, NULL, '2025-02-24 15:36:03');

-- --------------------------------------------------------

--
-- Table structure for table "jobs"
--

CREATE TABLE "jobs" (
  "id" BIGSERIAL NOT NULL,
  "queue" VARCHAR(255) NOT NULL,
  "payload" TEXT NOT NULL,
  "attempts" SMALLINT NOT NULL,
  "reserved_at" INTEGER DEFAULT NULL,
  "available_at" INTEGER NOT NULL,
  "created_at" INTEGER NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "job_batches"
--

CREATE TABLE "job_batches" (
  "id" VARCHAR(255) NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "total_jobs" int NOT NULL,
  "pending_jobs" int NOT NULL,
  "failed_jobs" int NOT NULL,
  "failed_job_ids" TEXT NOT NULL,
  "options" TEXT,
  "cancelled_at" int DEFAULT NULL,
  "created_at" int NOT NULL,
  "finished_at" int DEFAULT NULL
);

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table "languages"
--

CREATE TABLE "languages" (
  "id" BIGSERIAL NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "code" VARCHAR(255) NOT NULL,
  "direction" int NOT NULL DEFAULT '0',
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

--
-- Dumping data for table "languages"
--

INSERT INTO "languages" ("id", "name", "code", "direction", "created_at", "updated_at") VALUES
(1, 'english', 'en', 0, NULL, '2024-10-18 16:36:37');

-- --------------------------------------------------------

--
-- Table structure for table "logs"
--

CREATE TABLE "logs" (
  "id" BIGSERIAL NOT NULL,
  "key" VARCHAR(255) NOT NULL,
  "value" TEXT,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "menus"
--

CREATE TABLE "menus" (
  "id" BIGSERIAL NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "icon" VARCHAR(255) DEFAULT NULL,
  "url" VARCHAR(255) DEFAULT NULL,
  "lang" VARCHAR(255) NOT NULL,
  "position" int NOT NULL DEFAULT '0',
  "parent" int NOT NULL DEFAULT '0',
  "type" int NOT NULL DEFAULT '0',
  "is_external" int NOT NULL DEFAULT '0',
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "messages"
--

CREATE TABLE "messages" (
  "id" BIGSERIAL NOT NULL,
  "message_id" VARCHAR(255) NOT NULL,
  "from_email" TEXT,
  "subject" TEXT,
  "from" TEXT,
  "to" TEXT,
  "receivedAt" VARCHAR(255) NOT NULL,
  "source" VARCHAR(255) NOT NULL,
  "attachments" TEXT,
  "user_id" BIGINT NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "migrations"
--

CREATE TABLE "migrations" (
  "id" SERIAL NOT NULL,
  "migration" VARCHAR(255) NOT NULL,
  "batch" int NOT NULL
);

--
-- Dumping data for table "migrations"
--

INSERT INTO "migrations" ("id", "migration", "batch") VALUES
(4, '0001_01_01_000000_create_users_table', 1),
(5, '0001_01_01_000001_create_cache_table', 1),
(6, '0001_01_01_000002_create_jobs_table', 1),
(7, '2023_09_20_214006_create_admins_table', 1),
(8, '2023_09_20_235600_create_settings_table', 1),
(9, '2023_09_20_171537_create_languages_table', 2),
(10, '2023_09_20_214159_create_blog_categories_table', 2),
(12, '2023_09_20_214259_create_features_table', 2),
(13, '2023_09_20_214310_create_faqs_table', 2),
(14, '2023_09_20_214341_create_plugins_table', 2),
(15, '2023_09_20_214414_create_translates_table', 2),
(16, '2023_09_20_214437_create_menus_table', 2),
(17, '2023_09_20_214556_create_themes_table', 2),
(18, '2023_09_20_214605_create_seo_table', 2),
(19, '2023_09_20_214739_create_ads_table', 2),
(21, '2023_09_20_214208_create_blog_posts_table', 3),
(22, '2023_09_20_214128_create_domains_table', 4),
(30, '2023_09_28_231827_create_plans_table', 5),
(31, '2023_09_28_231828_create_plan_features_table', 5),
(32, '2023_09_28_231829_create_plan_subscriptions_table', 5),
(33, '2023_09_28_231831_create_plan_subscription_usage_table', 5),
(34, '2023_10_11_150843_create_trash_mails_table', 5),
(35, '2023_11_13_011144_create_imaps_table', 5),
(38, '2023_09_20_214539_create_sections_table', 6),
(40, '2023_09_20_214509_create_messages_table', 7),
(41, '2024_08_16_180603_create_licenses_table', 8),
(42, '2024_08_16_190927_create_products_table', 8),
(47, '2024_09_15_161222_create_notifications_table', 9),
(48, '2024_09_25_021652_create_email_templates_table', 10),
(49, '2023_09_21_004358_create_pages_table', 11),
(50, '2024_10_03_231402_create_statistics_table', 12),
(51, '2024_12_17_010041_create_personal_access_tokens_table', 13),
(54, '2025_01_25_050733_create_logs_table', 14),
(55, '2025_02_12_175910_create_translation_jobs_table', 15),
(58, '2025_02_13_041759_add_translate_id_to_features_table', 16),
(59, '2025_02_13_041816_add_translate_id_to_faqs_table', 16);

-- --------------------------------------------------------

--
-- Table structure for table "notifications"
--

CREATE TABLE "notifications" (
  "id" BIGSERIAL NOT NULL,
  "user_id" BIGINT DEFAULT NULL,
  "to_admin" BOOLEAN NOT NULL DEFAULT FALSE,
  "message" TEXT NOT NULL,
  "icon" VARCHAR(255) DEFAULT NULL,
  "is_read" BOOLEAN NOT NULL DEFAULT FALSE,
  "action" VARCHAR(255) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "pages"
--

CREATE TABLE "pages" (
  "id" BIGSERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  "slug" VARCHAR(255) NOT NULL,
  "content" TEXT,
  "meta_description" TEXT,
  "meta_title" VARCHAR(255) DEFAULT NULL,
  "views" int NOT NULL DEFAULT '0',
  "lang" VARCHAR(255) NOT NULL,
  "status" int NOT NULL DEFAULT '1',
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "password_reset_tokens"
--

CREATE TABLE "password_reset_tokens" (
  "email" VARCHAR(255) NOT NULL,
  "token" VARCHAR(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "personal_access_tokens"
--

CREATE TABLE "personal_access_tokens" (
  "id" BIGSERIAL NOT NULL,
  "tokenable_type" VARCHAR(255) NOT NULL,
  "tokenable_id" BIGINT NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "token" VARCHAR(64) NOT NULL,
  "abilities" TEXT,
  "last_used_at" timestamp NULL DEFAULT NULL,
  "expires_at" timestamp NULL DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "plans"
--

CREATE TABLE "plans" (
  "id" SERIAL NOT NULL,
  "tag" VARCHAR(255) NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "description" VARCHAR(255) DEFAULT NULL,
  "is_active" BOOLEAN NOT NULL DEFAULT TRUE,
  "price" decimal(8,2) NOT NULL DEFAULT '0.00',
  "signup_fee" decimal(8,2) NOT NULL DEFAULT '0.00',
  "currency" VARCHAR(3) NOT NULL,
  "trial_period" INTEGER NOT NULL DEFAULT '0',
  "trial_interval" VARCHAR(255) NOT NULL DEFAULT 'day',
  "trial_mode" VARCHAR(255) NOT NULL DEFAULT 'outside',
  "grace_period" INTEGER NOT NULL DEFAULT '0',
  "grace_interval" VARCHAR(255) NOT NULL DEFAULT 'day',
  "invoice_period" INTEGER NOT NULL DEFAULT '1',
  "invoice_interval" VARCHAR(255) NOT NULL DEFAULT 'month',
  "tier" INTEGER NOT NULL DEFAULT '0',
  "is_lifetime" BOOLEAN NOT NULL DEFAULT FALSE,
  "is_featured" BOOLEAN NOT NULL DEFAULT FALSE,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "deleted_at" timestamp NULL DEFAULT NULL
);

--
-- Dumping data for table "plans"
--

INSERT INTO "plans" ("id", "tag", "name", "description", "is_active", "price", "signup_fee", "currency", "trial_period", "trial_interval", "trial_mode", "grace_period", "grace_interval", "invoice_period", "invoice_interval", "tier", "is_lifetime", "is_featured", "created_at", "updated_at", "deleted_at") VALUES
(1, 'guest', 'Guest Plan', 'For small businesses', TRUE, '0.00', '0.00', 'USD', 0, 'day', 'outside', 0, 'day', 1, 'month', 1, TRUE, FALSE, '2023-09-28 21:23:16', '2023-09-28 21:23:16', NULL),
(2, 'free', 'Free Plan', 'For small businesses', TRUE, '0.00', '0.00', 'USD', 0, 'day', 'outside', 0, 'day', 1, 'year', 1, TRUE, FALSE, '2023-09-28 21:26:40', '2023-09-28 21:26:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table "plan_features"
--

CREATE TABLE "plan_features" (
  "id" SERIAL NOT NULL,
  "tag" VARCHAR(255) NOT NULL,
  "plan_id" INTEGER NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "description" VARCHAR(255) DEFAULT NULL,
  "value" VARCHAR(255) NOT NULL,
  "is_unlimited" BOOLEAN NOT NULL DEFAULT FALSE,
  "resettable_period" INTEGER NOT NULL DEFAULT '0',
  "resettable_interval" VARCHAR(255) NOT NULL DEFAULT 'month',
  "sort_order" INTEGER NOT NULL DEFAULT '0',
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

--
-- Dumping data for table "plan_features"
--

INSERT INTO "plan_features" ("id", "tag", "plan_id", "name", "description", "value", "is_unlimited", "resettable_period", "resettable_interval", "sort_order", "created_at", "updated_at") VALUES
(8, 'domains', 1, 'Custom domains', NULL, '-1', FALSE, 0, 'month', 0, '2023-10-04 17:11:48', '2023-10-04 17:11:48'),
(9, 'storage', 1, 'Messages storage (days)', NULL, '1', FALSE, 0, 'month', 0, '2023-10-04 17:11:48', '2023-10-04 17:11:48'),
(10, 'history', 1, 'History size', NULL, '-1', FALSE, 0, 'month', 0, '2023-10-04 17:11:48', '2025-01-16 23:02:47'),
(11, 'messages', 1, 'Save Messages', NULL, '0', FALSE, 0, 'month', 0, '2023-10-04 17:11:48', '2023-10-04 17:11:48'),
(12, 'ads', 1, 'No Ads', NULL, '1', FALSE, 0, 'month', 0, '2023-10-04 17:11:48', '2025-02-16 23:44:41'),
(13, 'attachments', 1, 'See Attachments', NULL, '0', FALSE, 0, 'month', 0, '2023-10-04 17:11:48', '2025-01-16 22:39:57'),
(14, 'premium_domains', 1, 'Premium Domains', NULL, '0', FALSE, 0, 'month', 0, '2023-10-04 17:11:48', '2024-12-22 02:09:13'),
(15, 'domains', 2, 'Custom domains', NULL, '-1', FALSE, 0, 'month', 0, '2023-10-04 17:12:10', '2025-02-17 19:47:31'),
(16, 'storage', 2, 'Messages storage (days)', NULL, '5', FALSE, 0, 'month', 0, '2023-10-04 17:12:10', '2023-10-04 17:12:10'),
(17, 'history', 2, 'History size', NULL, '-1', FALSE, 0, 'month', 0, '2023-10-04 17:12:10', '2024-10-07 23:53:05'),
(18, 'messages', 2, 'Save Messages', NULL, '-1', FALSE, 0, 'month', 0, '2023-10-04 17:12:10', '2025-02-24 22:04:10'),
(19, 'ads', 2, 'No Ads', NULL, '1', FALSE, 0, 'month', 0, '2023-10-04 17:12:10', '2025-01-18 01:50:47'),
(20, 'attachments', 2, 'See Attachments', NULL, '1', FALSE, 0, 'month', 0, '2023-10-04 17:12:10', '2024-07-31 06:34:36'),
(21, 'premium_domains', 2, 'Premium Domains', NULL, '1', FALSE, 0, 'month', 0, '2023-10-04 17:12:10', '2023-10-12 08:39:48');

-- --------------------------------------------------------

--
-- Table structure for table "plan_subscriptions"
--

CREATE TABLE "plan_subscriptions" (
  "id" SERIAL NOT NULL,
  "tag" VARCHAR(255) NOT NULL,
  "subscriber_type" VARCHAR(255) NOT NULL,
  "subscriber_id" BIGINT NOT NULL,
  "plan_id" INTEGER DEFAULT NULL,
  "name" VARCHAR(255) DEFAULT NULL,
  "description" VARCHAR(255) DEFAULT NULL,
  "price" decimal(8,2) NOT NULL DEFAULT '0.00',
  "currency" VARCHAR(3) NOT NULL,
  "trial_period" INTEGER NOT NULL DEFAULT '0',
  "trial_interval" VARCHAR(255) NOT NULL DEFAULT 'day',
  "grace_period" INTEGER NOT NULL DEFAULT '0',
  "grace_interval" VARCHAR(255) NOT NULL DEFAULT 'day',
  "invoice_period" INTEGER NOT NULL DEFAULT '1',
  "invoice_interval" VARCHAR(255) NOT NULL DEFAULT 'month',
  "payment_method" VARCHAR(255) DEFAULT 'free',
  "tier" INTEGER NOT NULL DEFAULT '0',
  "trial_ends_at" timestamp NULL DEFAULT NULL,
  "starts_at" timestamp NULL DEFAULT NULL,
  "ends_at" timestamp NULL DEFAULT NULL,
  "cancels_at" timestamp NULL DEFAULT NULL,
  "canceled_at" timestamp NULL DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "plan_subscription_usage"
--

CREATE TABLE "plan_subscription_usage" (
  "id" SERIAL NOT NULL,
  "subscription_id" INTEGER NOT NULL,
  "feature_id" INTEGER NOT NULL,
  "used" INTEGER NOT NULL,
  "valid_until" timestamp NULL DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "plugins"
--

CREATE TABLE "plugins" (
  "id" BIGSERIAL NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "unique_name" VARCHAR(255) NOT NULL,
  "tag" VARCHAR(255) DEFAULT NULL,
  "logo" VARCHAR(255) DEFAULT NULL,
  "url" VARCHAR(255) DEFAULT NULL,
  "description" TEXT,
  "action" VARCHAR(255) DEFAULT NULL,
  "code" TEXT,
  "version" VARCHAR(255) DEFAULT NULL,
  "license" VARCHAR(255) DEFAULT NULL,
  "status" BOOLEAN NOT NULL DEFAULT FALSE,
  "is_featured" BOOLEAN NOT NULL DEFAULT FALSE,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

--
-- Dumping data for table "plugins"
--

INSERT INTO "plugins" ("id", "name", "unique_name", "tag", "logo", "url", "description", "action", "code", "version", "license", "status", "is_featured", "created_at", "updated_at") VALUES
(1, 'hCaptcha', 'hcaptcha', 'security', 'assets/img/plugins/hcaptcha.png', 'https://www.lobage.com/hcaptcha', 'Protect forms from spam using hCaptcha''s advanced bot-detection technology', NULL, '{"hcaptcha_site_key":{"title":"Site Key","value":"","type":"input","placeholder":"","env":true},"hcaptcha_secret_key":{"title":"Secret Key","value":"","type":"input","placeholder":"","env":true}}', '1.0', NULL, FALSE, FALSE, NULL, '2025-02-17 16:00:52'),
(2, 'ReCaptcha', 'recaptcha', 'security', 'assets/img/plugins/recaptcha.png', 'https://www.google.com/recaptcha/admin/create', 'Google''s ReCaptcha secures forms by distinguishing between bots and humans', NULL, '{"RECAPTCHA_SITE_KEY":{"title":"Site Key","value":"","type":"input","placeholder":"","env":true},"RECAPTCHA_SECRET_KEY":{"title":"Secret Key","value":"","type":"input","placeholder":"","env":true}}', '1.0', NULL, FALSE, FALSE, NULL, '2024-10-12 03:19:26'),
(3, 'Facebook Login', 'facebook_login', 'auth', 'assets/img/plugins/facebook_login.png', 'https://developers.facebook.com/', 'Allow users to log in to your site using their Facebook credentials', NULL, '{"facebook_app_id":{"title":"Facebook App ID","value":"","type":"input","placeholder":"","env":true},"facebook_app_secret":{"title":"Facebook App Secret","value":"","type":"input","placeholder":"","env":true},"facebook_redirect":{"title":"Callback URL","route_name":"social.callback","route_params":["facebook"],"type":"input","placeholder":"","env":true,"disabled":true,"is_route":true,"value":"https:\\/\\/test.lobage.com\\/auth\\/facebook\\/callback"}}', '1.0', NULL, FALSE, FALSE, NULL, '2025-02-28 00:36:43'),
(4, 'Google Login', 'google_login', 'auth', 'assets/img/plugins/google_login.png', 'https://console.cloud.google.com/apis/dashboard', 'Integrate Google login for secure and convenient user authentication', NULL, '{"google_app_id":{"title":"Google App ID","value":"","type":"input","placeholder":"","env":true},"google_app_secret":{"title":"Google App Secret","value":"","type":"input","placeholder":"","env":true},"google_redirect":{"title":"Callback URL","route_name":"social.callback","route_params":["google"],"type":"input","placeholder":"","disabled":true,"is_route":true,"env":true,"value":"https:\\/\\/test.lobage.com\\/auth\\/google\\/callback"}}', '1.0', NULL, FALSE, FALSE, NULL, '2025-02-28 00:46:55'),
(5, 'Disqus Comments ', 'disqus', 'others', 'assets/img/plugins/disqus.png', 'https://disqus.com/admin/', 'Embed Disqus to let users engage and comment on your content', NULL, '{"shortname":{"title":"Your Disqus Shortname","value":"","type":"input","placeholder":"","env":false}}', '1.0', NULL, FALSE, FALSE, NULL, '2024-10-12 12:25:05'),
(6, 'Facebook Comments', 'facebook_comments', 'others', 'assets/img/plugins/facebook_comments.png', 'https://developers.facebook.com/docs/plugins/comments/', 'Allow users to comment on your site using their Facebook profiles', NULL, '{"app_id":{"title":"APP ID","value":"","type":"input","placeholder":"","env":false},"number_of_comment":{"title":"Number of Comments","value":"5","type":"input","placeholder":"5","env":false}}', '1.0', NULL, FALSE, FALSE, NULL, '2024-10-12 12:25:07'),
(7, 'Trustip', 'trustip', 'security', 'assets/img/plugins/trustip.png', 'https://www.lobage.com/trustip', 'Analyze IP addresses to detect fraud, spam, VPNs, proxies, and TOR for enhanced security\n', NULL, '{"TRUSTIP_API_KEY":{"title":"Your trustip Key","value":"","type":"input","placeholder":"","env":true}}', '1.0', NULL, FALSE, FALSE, NULL, '2025-03-01 01:25:55'),
(8, 'Contact Form', 'contact', 'support', 'assets/img/plugins/contact.png', 'https://www.lobage.com/jotform', 'Easy-to-use contact form to receive messages directly on your email', NULL, '{\n  "type": {\n    "title": "Form Type",\n    "value": "default",\n    "type": "select",\n    \n    "options": [\n      {\n        "title": "Default",\n        "value": "default"\n      },\n      {\n        "title": "Iframe",\n        "value": "iframe"\n      }\n    ]\n  },\n  \n  "iframe": {\n    "title": "Iframe Code",\n    "value": null,\n    "type": "textarea",\n    "placeholder": "Only required if ''Iframe'' type is selected",\n    "env": false,\n    "skip_validation": true,\n    "info": "Paste the contents of your contact iframe code here.",\n    "alert": "If you select ''Default'', you need to set up the SMTP settings.",\n    "alert_type": "info"\n  }\n}\n', '1.0', NULL, FALSE, FALSE, NULL, '2024-10-15 01:58:00'),
(9, 'Google analytics', 'google_analytics', 'analytics', 'assets/img/plugins/google_analytics.png', 'https://analytics.google.com/analytics/web/', 'Track and analyze website traffic and user behavior with Google Analytics', NULL, '{"measurement_id":{"title":"Measurement Id","value":"","type":"input","placeholder":"G-XXXXXXXXXX","env":false}}', '1.0', NULL, FALSE, FALSE, NULL, '2024-10-12 12:26:14'),
(10, 'Google Tag Manager', 'google_tag', 'analytics', 'assets/img/plugins/google_tag.png', 'https://tagmanager.google.com/', 'Manage marketing tags without modifying code through Google Tag Manager', NULL, '{"container_id":{"title":"Container Id","value":"","type":"input","placeholder":"GTM-XXXXXXX","env":false,"info":"In Google Tag Manager, click <strong>Workspace<\\/strong>. Your Container ID will appear near the top of the window, formatted as <strong>GTM-XXXXXX<\\/strong>."}}', '1.0', NULL, FALSE, FALSE, NULL, '2025-03-01 01:16:36'),
(11, 'Custom Code\r\n', 'custom_code', 'others', 'assets/img/plugins/custom_code.png', NULL, ' Add custom code snippets to your site, injected directly into the header', NULL, '{"custom_code":{"title":"Custom Code","value":null,"type":"textarea","placeholder":"","env":false,"skip_validation":true,"info":"Paste the snippet of code provided by the tool or service above, and it will be injected into the <strong>&lt;head&gt;<\\/strong> section of your website."}}', '1.0', NULL, FALSE, FALSE, NULL, '2025-03-02 05:25:24'),
(12, 'Google AdSense', 'google_adsense', 'marketing', 'assets/img/plugins/google_adsense.png', 'https://adsense.google.com/start/', 'Integrate AdSense to manage advertising directly on your website', NULL, '{"adsense":{"title":"Content ","value":null,"type":"textarea","placeholder":"","env":false,"skip_validation":true,"info":"Paste the contents of your ads.txt file with the publisher ID in the box above to manage the sellers that are allowed to advertize on your site.","alert":"To verify that you published your file correctly, check that you successfully see your file''s content when you access the ads.txt URL (https:\\/\\/example.com\\/ads.txt) in your web browser.","alert_type":"info"}}', '1.0', NULL, FALSE, FALSE, NULL, '2025-03-02 05:40:20'),
(13, 'Robots.txt', 'robots', 'marketing', 'assets/img/plugins/robots.png', 'https://www.lobage.com/seranking', 'Customize how search engines crawl your site using the Robots.txt file', NULL, '{"robots":{"title":"Robots.txt Content","value":"User-agent: *\\r\\nAllow: \\/\\r\\n\\r\\nSitemap: https:\\/\\/your-site.com\\/sitemap.xml","type":"textarea","placeholder":"Enter your robots.txt content here...","env":false,"info":"Paste the contents of your robots.txt file in the box above to control how search engines crawl your site.","alert":"To verify that your robots.txt file is published correctly, check that you can access it at https:\\/\\/example.com\\/robots.txt in your web browser.","skip_validation":true,"alert_type":"info"}}', '1.0', NULL, FALSE, FALSE, NULL, '2025-03-02 05:24:00'),
(14, 'Sitemap', 'sitemap', 'marketing', 'assets/img/plugins/sitemap.png', 'https://www.xml-sitemaps.com/', 'Generate and manage an XML sitemap for better search engine indexing', NULL, NULL, '1.0', NULL, FALSE, FALSE, NULL, '2025-02-09 23:43:35'),
(15, 'Hotjar', 'hotjar', 'analytics', 'assets/img/plugins/hotjar.png', 'https://www.lobage.com/hotjar', 'Collect heatmaps and feedback to improve your site''s user experience', NULL, '{"site_id":{"title":"Site Id","value":"","type":"input","placeholder":"1234567","env":false}}', '1.0', NULL, FALSE, FALSE, NULL, '2024-10-12 14:55:27'),
(16, 'ReCaptcha invisible', 'recaptcha_invisible', 'security', 'assets/img/plugins/recaptcha_invisible.png', 'https://www.google.com/recaptcha/admin/create', 'Secure mailbox with Google''s invisible ReCaptcha, detecting bots silently', NULL, '{"ROCAPTCHA_SITEKEY_INVISIBLE":{"title":"Site Key","value":"","type":"input","placeholder":"","env":true},"ROCAPTCHA_SECRET_INVISIBLE":{"title":"Secret Key","value":"","type":"input","placeholder":"","env":true}}', '1.0', NULL, FALSE, FALSE, NULL, '2024-11-28 07:40:31'),
(17, 'Tawk.to', 'tawk', 'support', 'assets/img/plugins/tawk.png', 'https://www.tawk.to/', 'Free live chat tool to communicate with site visitors in real time', NULL, '{"property_id":{"title":"Property ID","value":"","type":"input","placeholder":"1234567","env":false}}', '1.0', NULL, FALSE, FALSE, NULL, '2025-03-01 01:31:22'),
(18, 'Graph Comment', 'graphcomment', 'others', 'assets/img/plugins/graphcomment.png', 'https://www.graphcomment.com/en', 'Engage users with social discussions using the GraphComment platform', NULL, '{"unique_id":{"title":"Your Unique id","value":"","type":"input","placeholder":"","env":false}}', '1.0', NULL, FALSE, FALSE, NULL, '2025-02-09 23:48:58'),
(19, 'Instant Translation', 'translate', 'marketing', 'assets/img/notifications/translate.png', 'https://www.lobage.com/instant-translation', 'Enhance your website’s accessibility and reach a global audience with our instant translation', NULL, '{"translate_key":{"title":"Your License Key","value":"","type":"input","placeholder":"XXXX-XXXXX-XXXXX-XXXXX","env":true,"info":"You need to buy a plan ,and enter your license key"}}', '1.0', NULL, FALSE, TRUE, NULL, '2025-02-28 13:55:35');

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table "sections"
--

CREATE TABLE "sections" (
  "id" BIGSERIAL NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  "status" int NOT NULL DEFAULT '0',
  "position" int NOT NULL DEFAULT '0',
  "type" VARCHAR(255) DEFAULT NULL,
  "content" TEXT,
  "lang" VARCHAR(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

--
-- Dumping data for table "sections"
--

INSERT INTO "sections" ("id", "name", "title", "status", "position", "type", "content", "lang", "created_at", "updated_at") VALUES
(4, 'features', 'Features', 1, 2, 'theme', NULL, 'en', NULL, '2025-02-17 00:04:31'),
(5, 'faqs', 'Faqs', 1, 3, 'theme', NULL, 'en', NULL, '2025-02-17 00:04:31'),
(6, 'posts', 'Posts', 1, 4, 'theme', NULL, 'en', NULL, '2025-02-17 00:04:31'),
(13, 'get_in_touch', 'Call to action', 1, 5, 'theme', NULL, 'en', '2024-09-23 00:25:21', '2025-02-17 00:05:01');

-- --------------------------------------------------------

--
-- Table structure for table "seo"
--

CREATE TABLE "seo" (
  "id" BIGSERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  "description" TEXT,
  "keyword" TEXT,
  "author" VARCHAR(255) DEFAULT NULL,
  "image" VARCHAR(255) DEFAULT NULL,
  "lang" VARCHAR(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

--
-- Dumping data for table "seo"
--

INSERT INTO "seo" ("id", "title", "description", "keyword", "author", "image", "lang", "created_at", "updated_at") VALUES
(1, 'Trash Mails', 'Trash Mails Description', 'Trash Mails,temp', 'Lobage', NULL, 'en', '2024-07-09 03:57:17', '2025-02-17 00:22:54');

-- --------------------------------------------------------

--
-- Table structure for table "sessions"
--

CREATE TABLE "sessions" (
  "id" VARCHAR(255) NOT NULL,
  "user_id" BIGINT DEFAULT NULL,
  "ip_address" VARCHAR(45) DEFAULT NULL,
  "user_agent" TEXT,
  "payload" TEXT NOT NULL,
  "last_activity" int NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "settings"
--

CREATE TABLE "settings" (
  "id" BIGSERIAL NOT NULL,
  "key" VARCHAR(255) NOT NULL,
  "value" TEXT,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

--
-- Dumping data for table "settings"
--

INSERT INTO "settings" ("id", "key", "value", "created_at", "updated_at") VALUES
(1, 'site_name', 'Trash mails', NULL, '2025-02-20 02:41:05'),
(2, 'site_url', NULL, NULL, '2025-02-08 14:15:20'),
(3, 'logo', 'assets/themes/basic/img/logo.png?t=1740191850', NULL, '2025-02-22 02:37:30'),
(4, 'dark_logo', 'assets/themes/basic/img/dark_logo.png?t=1740191850', NULL, '2025-02-22 02:37:30'),
(5, 'favicon', 'assets/themes/basic/img/favicon.png?t=1731802180', NULL, '2024-11-17 02:09:40'),
(6, 'version', '1.0', NULL, NULL),
(7, 'theme', 'loody', NULL, NULL),
(8, 'license_key', '', NULL, NULL),
(9, 'date_format', '', NULL, NULL),
(10, 'timezone', 'Africa/Casablanca', NULL, '2025-02-08 21:54:40'),
(11, 'main_color', '#000000', NULL, NULL),
(12, 'secondary_color', '#000000', NULL, NULL),
(13, 'colors', '{"primary_color":"#793ef1","secondary_color":"#ff4d12","text_color":"#212121","background_color":"#f9f9f9"}', NULL, '2025-02-22 02:36:57'),
(14, 'https_force', '0', NULL, NULL),
(15, 'enable_registration', '1', NULL, '2024-10-03 21:28:08'),
(16, 'enable_verification', '0', NULL, '2024-10-03 01:29:42'),
(17, 'enable_cookie', '1', NULL, '2025-02-16 21:56:34'),
(18, 'default_currency', 'USD', NULL, NULL),
(19, 'default_language', 'en', NULL, '2024-08-26 02:06:23'),
(20, 'mail_mailer', 'smtp', NULL, '2025-01-14 22:37:30'),
(21, 'mail_host', 'your-smtp.host', NULL, NULL),
(22, 'mail_port', '587', NULL, NULL),
(23, 'mail_username', 'smtp-username', NULL, NULL),
(24, 'mail_password', 'password', NULL, NULL),
(25, 'mail_encryption', 'ssl', NULL, '2024-07-08 21:42:40'),
(26, 'mail_from_address', 'admin@yoursite.com', NULL, '2024-10-15 01:51:51'),
(27, 'mail_from_name', 'Your Site Name', NULL, '2024-07-08 21:42:40'),
(28, 'hide_default_lang', '1', NULL, '2024-10-03 21:28:22'),
(29, 'enable_preloader', '1', NULL, NULL),
(30, 'privacy_policy', 'https://lobage.com/', NULL, '2025-02-08 21:54:40'),
(31, 'terms_of_service', 'https://lobage.com/', NULL, '2025-02-08 21:54:40'),
(32, 'cookie_policy', 'https://lobage.com/', NULL, '2025-02-08 21:54:40'),
(33, 'captcha', 'none', NULL, '2025-02-06 13:25:03'),
(34, 'captcha_login', '1', NULL, '2024-09-07 01:20:32'),
(35, 'captcha_register', '1', NULL, '2024-09-26 19:38:28'),
(36, 'captcha_contact', '1', NULL, '2024-09-24 20:58:09'),
(37, 'captcha_rest_password', '1', NULL, '2024-09-26 19:38:28'),
(38, 'captcha_admin', '0', NULL, '2025-02-04 16:47:05'),
(39, 'forbidden_ids', 'admin', NULL, '2024-12-22 03:09:20'),
(40, 'allowed_files', 'txt,sql,png,zip,jpg,pdf,doc,docx,xls,xlsx,ppt,pptx,xps,dxf,ai,psd,eps,ps,svg,ttf,rar,tar,gzip,mp3,mpeg,wav,ogg,jpeg,gif,bmp,tif,webm,mpeg4,3gpp,mov,avi,mpegs,wmv,flx', NULL, '2025-02-24 14:50:52'),
(41, 'fetch_messages', '5', NULL, '2025-02-24 14:45:13'),
(42, 'email_length', '5', NULL, '2024-10-17 01:19:23'),
(43, 'fake_emails', '0', NULL, '2024-10-17 01:19:23'),
(44, 'fake_messages', '0', NULL, '2024-10-17 01:19:23'),
(45, 'api_key', NULL, NULL, '2025-01-06 03:36:34'),
(46, 'time_unit', 'day', '2024-08-31 18:02:19', '2024-10-17 01:19:23'),
(47, 'email_lifetime', '5', '2024-08-31 18:02:19', '2025-02-17 02:51:30'),
(48, 'history_retention_days', '2', '2024-08-31 18:02:19', '2024-10-17 01:19:23'),
(49, 'enable_blog', '1', '2024-09-04 01:24:03', '2024-12-16 21:36:19'),
(50, 'popular_post_order_by', 'views', '2024-09-04 01:24:03', '2024-12-16 21:40:25'),
(51, 'total_popular_posts_home', '3', '2024-09-04 01:24:03', '2024-12-16 21:39:44'),
(52, 'total_posts_per_page', '6', '2024-09-04 01:24:03', '2024-12-16 21:36:48'),
(53, 'cronjob_key', NULL, '2024-10-04 23:03:19', '2024-10-04 23:37:43'),
(54, 'cronjob_last_time', '', '2024-10-04 23:03:19', '2025-02-21 06:03:43'),
(55, 'imap_retention_days', '5', '2024-10-04 23:03:19', '2024-10-17 01:19:23'),
(56, 'mail_to_address', 'contact@gmail.com', '2024-10-04 23:03:19', '2024-10-15 01:51:51'),
(57, 'imap_messages', '42', '2024-10-13 23:42:33', '2024-10-14 21:59:16'),
(58, 'call_to_action', 'https://lobage.com/', '2024-11-04 16:10:51', '2025-02-08 21:54:40'),
(59, 'enable_api', '0', '2025-01-06 03:30:11', '2025-01-06 04:01:55'),
(60, 'is_support_expired', '0', '2025-02-06 10:24:15', '2025-02-23 21:38:39'),
(61, 'last_logo_upload', '0', '2025-02-20 00:17:00', '2025-02-20 00:17:00'),
(62, 'enable_maintenance', 'true', '2025-03-06 15:13:18', '2025-03-06 17:45:09'),
(63, 'maintenance_title', 'Site Under Maintenance', '2025-03-06 15:13:18', '2025-03-06 15:58:39'),
(64, 'maintenance_message', 'We are currently performing scheduled maintenance to improve our services. We apologize for any inconvenience this may cause and thank you for your understanding. Please check back shortly!', '2025-03-06 15:13:18', '2025-03-06 15:58:39');

-- --------------------------------------------------------

--
-- Table structure for table "statistics"
--

CREATE TABLE "statistics" (
  "id" BIGSERIAL NOT NULL,
  "key" VARCHAR(255) NOT NULL,
  "value" TEXT,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "themes"
--

CREATE TABLE "themes" (
  "id" BIGSERIAL NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "unique_name" VARCHAR(255) NOT NULL,
  "logo" VARCHAR(255) DEFAULT NULL,
  "dark_logo" VARCHAR(255) DEFAULT NULL,
  "favicon" VARCHAR(255) DEFAULT NULL,
  "version" VARCHAR(255) DEFAULT NULL,
  "demo" VARCHAR(255) DEFAULT NULL,
  "description" TEXT,
  "image" VARCHAR(255) DEFAULT NULL,
  "status" BOOLEAN NOT NULL DEFAULT FALSE,
  "custom_css" TEXT,
  "custom_js" TEXT,
  "colors" TEXT,
  "images" TEXT,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

--
-- Dumping data for table "themes"
--

INSERT INTO "themes" ("id", "name", "unique_name", "logo", "dark_logo", "favicon", "version", "demo", "description", "image", "status", "custom_css", "custom_js", "colors", "images", "created_at", "updated_at") VALUES
(1, 'Basic', 'basic', 'assets/themes/basic/img/logo.png?t=1740191850', 'assets/themes/basic/img/dark_logo.png?t=1740191850', 'assets/themes/basic/img/favicon.png?t=1731802180', '1.0', NULL, 'Basic Theme', 'https://ik.imagekit.io/FiverrQuickView/trashmails.webp', TRUE, NULL, NULL, '{"primary_color":"#793ef1","primary_opacity":"#44189a","secondary_color":"#ff4d12","text_color":"#212121","background_color":"#f9f9f9","footer_background_color":"#192132","secondary_text_color":"#ffffff"}', NULL, NULL, '2025-02-24 14:27:12');

-- --------------------------------------------------------

--
-- Table structure for table "translates"
--

CREATE TABLE "translates" (
  "id" BIGSERIAL NOT NULL,
  "collection" VARCHAR(255) NOT NULL DEFAULT 'general',
  "key" TEXT NOT NULL,
  "value" TEXT,
  "type" BOOLEAN NOT NULL DEFAULT FALSE,
  "lang" VARCHAR(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

--
-- Dumping data for table "translates"
--

INSERT INTO "translates" ("id", "collection", "key", "value", "type", "lang", "created_at", "updated_at") VALUES
(1, 'general', 'Register', 'Sign up', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(2, 'general', 'Login', 'Login', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(3, 'general', 'Homepage Title', 'Get Your Free Temporary Email Address at {{site_name}}', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-13 12:45:56'),
(4, 'general', 'Homepage first description', '{{site_name}} provides free temporary email addresses to protect your privacy online.', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-20 02:48:52'),
(5, 'general', 'landing', 'Landing', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(6, 'general', 'Refresh ', 'Refresh', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(7, 'general', 'Delete', 'Delete', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(8, 'general', 'QR Code', 'QR Code', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(9, 'general', 'History', 'History', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-27 19:42:18'),
(10, 'general', 'Change', 'Change', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(11, 'general', 'Homepage second description', '100% Free - No Subscription Needed', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(12, 'general', 'Emails Created', 'Emails Created', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(13, 'general', 'Messages Received', 'Messages Received', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(14, 'general', 'Scan the QR Code', 'Scan the QR Code', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(15, 'general', 'Use this QR code to quickly open your inbox on any compatible device', 'Use this QR code to quickly open your inbox on any compatible device', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(16, 'general', 'Back to Inbox', 'Back to Inbox', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(17, 'general', 'Change Email', 'Change Email', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(18, 'general', 'Email Alias', 'Email Alias', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(19, 'general', 'Random Name', 'Random Name', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(20, 'general', 'Domain', 'Domain', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(21, 'general', 'Premium Domains', 'Premium Domains', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(22, 'general', 'Free Domains', 'Free Domains', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(23, 'general', 'Update Email Address', 'Update Email Address', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(24, 'general', 'Email History', 'Email History', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(25, 'general', 'View your email history', 'View your email history', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(26, 'general', 'Type to search ... ', 'Type to search ...', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(27, 'general', 'Emails in History', 'Emails in History', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(28, 'general', 'Sender', 'Sender', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(29, 'general', 'Subject', 'Subject', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(30, 'general', 'Time', 'Time', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(31, 'general', 'Your inbox is empty', 'Your inbox is empty', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(32, 'general', 'Waiting for incoming emails', 'Waiting for incoming emails', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(33, 'general', 'Features', 'Features', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(34, 'general', 'Features Title', 'Why Choose Our Temp Mail?', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(35, 'general', 'Features Description', 'Explore the features that make our temporary email service fast, secure, and easy to use', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(36, 'general', 'Faqs', 'Faqs', FALSE, 'en', '2024-11-28 06:55:16', '2025-01-31 17:44:17'),
(37, 'general', 'Faqs Title', 'Frequently Asked Questions', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(38, 'general', 'Faqs Description', 'Find answers to common questions about our temporary email service and how to use it effectively', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(39, 'general', 'Popular Posts', 'Popular Posts', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(40, 'general', 'Popular Posts Text', 'Popular Posts Text', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(41, 'general', 'Popular Posts Description ', 'Popular Posts Description', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(42, 'general', 'Sign Up Now', 'Sign Up Now', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(43, 'general', 'Sign Up to Get Access to Exclusive Features', 'Sign Up to Get Access to Exclusive Features', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(44, 'general', 'Register Now', 'Register Now', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(45, 'general', 'copyright', '{{site_name}} © {{copyright_year}} – All Rights Reserved', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-28 23:40:11'),
(46, 'general', 'Not found emails with', 'Not found emails with', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(47, 'general', 'History is empty', 'History is empty', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(48, 'general', 'Active', 'Active', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(49, 'general', 'Current', 'Current', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(50, 'general', 'Choose', 'Choose', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(55, 'general', 'Please Wait', 'Please Wait', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(56, 'general', 'New', 'New', FALSE, 'en', '2024-11-28 06:55:16', '2025-02-12 00:48:48'),
(60, 'auth', 'Create Your Account', 'Create Your Account', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(61, 'auth', 'Please fill in the details below to create your account.', 'Please fill in the details below to create your account.', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(62, 'auth', 'First Name', 'First Name', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(63, 'auth', 'Last Name', 'Last Name', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(64, 'auth', 'Email Address', 'Email Address', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(65, 'auth', 'Password', 'Password', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(66, 'auth', 'Register', 'Register', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(67, 'auth', 'Or sign up with', 'Or sign up with', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(68, 'auth', 'Continue with Google', 'Continue with Google', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(69, 'auth', 'Continue with Facebook', 'Continue with Facebook', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(70, 'auth', 'Already have an account?', 'Already have an account?', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(71, 'auth', 'Log in here', 'Log in here', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(72, 'auth', 'By signing up, you agree to our', 'By signing up, you agree to our', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(73, 'auth', 'Terms of Service', 'Terms of Service', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(74, 'auth', 'and', 'and', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(75, 'auth', 'Privacy Policy', 'Privacy Policy', FALSE, 'en', '2024-11-28 06:55:33', '2025-02-12 00:51:22'),
(76, 'auth', 'Welcome Back!', 'Welcome Back!', FALSE, 'en', '2024-11-28 06:56:12', '2025-02-12 00:51:22'),
(77, 'auth', 'Please log in to your account to continue.', 'Please log in to your account to continue.', FALSE, 'en', '2024-11-28 06:56:12', '2025-02-12 00:51:22'),
(78, 'auth', 'Remember Me', 'Remember Me', FALSE, 'en', '2024-11-28 06:56:12', '2025-02-12 00:51:22'),
(79, 'auth', 'Forgot Password?', 'Forgot Password?', FALSE, 'en', '2024-11-28 06:56:12', '2025-02-12 00:51:22'),
(80, 'auth', 'Log In', 'Log In', FALSE, 'en', '2024-11-28 06:56:12', '2025-02-12 00:51:22'),
(81, 'auth', 'Or log in with', 'Or log in with', FALSE, 'en', '2024-11-28 06:56:12', '2025-02-12 00:51:22'),
(82, 'auth', 'Don’t have an account?', 'Don’t have an account?', FALSE, 'en', '2024-11-28 06:56:12', '2025-02-12 00:51:22'),
(83, 'auth', 'Sign up now', 'Sign up now', FALSE, 'en', '2024-11-28 06:56:12', '2025-02-12 00:51:22'),
(84, 'auth', 'Reset Your Password', 'Reset Your Password', FALSE, 'en', '2024-11-28 06:56:13', '2025-02-12 00:51:22'),
(85, 'auth', 'Enter your email address and we will send you a link to reset your password.', 'Enter your email address and we will send you a link to reset your password.', FALSE, 'en', '2024-11-28 06:56:13', '2025-02-12 00:51:22'),
(86, 'auth', 'Send Password Reset Link', 'Send Password Reset Link', FALSE, 'en', '2024-11-28 06:56:13', '2025-02-12 00:51:22'),
(87, 'auth', 'Remembered your password?', 'Remembered your password?', FALSE, 'en', '2024-11-28 06:56:13', '2025-02-12 00:51:22'),
(88, 'general', 'Delete Item', 'Delete Item', FALSE, 'en', '2024-11-28 07:00:47', '2025-02-12 00:50:12'),
(89, 'general', 'Are you sure you want to delete this item? This action cannot be undone', 'Are you sure you want to delete this item? This action cannot be undone', FALSE, 'en', '2024-11-28 07:00:47', '2025-02-12 00:50:12'),
(90, 'general', 'Not Found', 'Not Found', FALSE, 'en', '2024-11-28 07:03:43', '2025-02-12 00:50:12'),
(91, 'general', 'The page you are looking for could not be found.', 'The page you are looking for could not be found.', FALSE, 'en', '2024-11-28 07:03:43', '2025-02-12 00:50:12'),
(92, 'general', 'Go Home', 'Go Home', FALSE, 'en', '2024-11-28 07:03:43', '2025-02-12 00:50:12'),
(94, 'general', 'Inactive', 'Inactive', FALSE, 'en', '2024-11-28 07:04:22', '2025-02-12 00:50:12'),
(95, 'general', 'Cron Job executed successfully', 'Cron Job executed successfully', FALSE, 'en', '2024-11-28 07:04:59', '2025-02-12 00:50:12'),
(96, 'general', 'Server Error', 'Server Error', FALSE, 'en', '2024-11-28 07:22:42', '2025-02-12 00:50:12'),
(97, 'general', 'Whoops, something went wrong on our servers.', 'Whoops, something went wrong on our servers.', FALSE, 'en', '2024-11-28 07:22:42', '2025-02-12 00:50:12'),
(98, 'alerts', 'Robot verification failed, please try again', 'Robot verification failed, please try again', FALSE, 'en', '2024-11-28 07:24:17', '2025-02-17 19:29:40'),
(99, 'alerts', 'Captcha response is missing, please try again', 'Captcha response is missing, please try again', FALSE, 'en', '2024-11-28 07:31:38', '2025-02-17 19:29:40'),
(100, 'general', 'Draft', 'Draft', FALSE, 'en', '2024-12-09 01:20:13', '2025-02-12 00:50:12'),
(101, 'general', 'Published', 'Published', FALSE, 'en', '2024-12-09 01:20:13', '2025-02-12 00:50:12'),
(102, 'general', 'Published on', 'Published on', FALSE, 'en', '2024-12-09 03:22:41', '2025-02-12 00:48:48'),
(103, 'general', 'Category', 'Category', FALSE, 'en', '2024-12-09 03:22:41', '2025-02-12 00:48:48'),
(104, 'general', 'views', 'views', FALSE, 'en', '2024-12-09 03:22:42', '2025-02-12 00:48:48'),
(105, 'general', 'Tags:', 'Tags:', FALSE, 'en', '2024-12-09 03:22:42', '2025-02-12 00:48:48'),
(106, 'general', 'Categories', 'Categories', FALSE, 'en', '2024-12-09 03:22:42', '2025-02-12 00:48:48'),
(107, 'seo', 'Blog', 'Blog', FALSE, 'en', '2024-12-09 03:24:24', '2025-02-12 00:51:42'),
(108, 'general', 'Blog', 'Blog', FALSE, 'en', '2024-12-09 03:24:24', '2025-02-12 00:48:48'),
(109, 'general', 'Page Expired', 'Page Expired', FALSE, 'en', '2024-12-09 21:27:18', '2025-02-12 00:50:12'),
(110, 'general', 'The page has expired. Please refresh and try again.', 'The page has expired. Please refresh and try again.', FALSE, 'en', '2024-12-09 21:27:18', '2025-02-12 00:50:12'),
(111, 'general', 'Pending', 'Pending', FALSE, 'en', '2024-12-18 11:24:14', '2025-02-12 00:50:12'),
(112, 'general', 'Rejected', 'Rejected', FALSE, 'en', '2024-12-18 11:24:14', '2025-02-12 00:50:12'),
(113, 'general', 'Approved', 'Approved', FALSE, 'en', '2024-12-18 11:24:14', '2025-02-12 00:50:12'),
(114, 'general', 'Dashboard', 'Dashboard', FALSE, 'en', '2024-12-21 01:00:51', '2025-02-12 00:48:48'),
(115, 'general', 'Welcome back,{{user}} 👋', 'Welcome back,{{user}} 👋', FALSE, 'en', '2024-12-21 01:00:57', '2025-02-12 00:50:12'),
(116, 'seo', 'Dashboard', 'Dashboard', FALSE, 'en', '2024-12-21 01:00:57', '2025-02-12 00:51:42'),
(119, 'general', 'Favorite Messages', 'Favorite Messages', FALSE, 'en', '2024-12-21 01:00:57', '2025-02-12 00:50:12'),
(120, 'general', 'Domains', 'Domains', FALSE, 'en', '2024-12-21 01:00:57', '2025-02-12 00:50:12'),
(121, 'general', 'Emails Created By You', 'Emails Created By You', FALSE, 'en', '2024-12-21 01:00:57', '2025-02-12 00:50:12'),
(122, 'general', 'Last Email History', 'Last Email History', FALSE, 'en', '2024-12-21 01:00:57', '2025-02-12 00:50:12'),
(124, 'general', 'My Inbox', 'My Inbox', FALSE, 'en', '2024-12-21 01:00:57', '2025-02-12 00:50:12'),
(125, 'general', 'Settings', 'Settings', FALSE, 'en', '2024-12-21 01:00:57', '2025-02-12 00:50:12'),
(126, 'general', 'Forbidden', 'Forbidden', FALSE, 'en', '2024-12-22 02:29:33', '2025-02-12 00:50:12'),
(127, 'general', 'Sorry, you do not have permission to access this page.', 'Sorry, you do not have permission to access this page.', FALSE, 'en', '2024-12-22 02:29:33', '2025-02-12 00:50:12'),
(128, 'general', 'Back To Home', 'Back To Home', FALSE, 'en', '2024-12-28 02:15:08', '2025-02-12 00:48:48'),
(129, 'general', 'Delete Message', 'Delete Message', FALSE, 'en', '2024-12-28 02:15:08', '2025-02-12 00:48:48'),
(130, 'general', 'Sign up to download attachments', 'Sign up to download attachments', FALSE, 'en', '2024-12-28 02:15:08', '2025-02-12 00:48:48'),
(132, 'general', 'Download', 'Download', FALSE, 'en', '2024-12-28 02:15:08', '2025-02-12 00:48:48'),
(133, 'alerts', 'Unable to retrieve emails at this time. Please try Again', 'Unable to retrieve emails at this time. Please try Again', FALSE, 'en', '2025-01-13 06:36:19', '2025-02-17 19:29:40'),
(134, 'alerts', 'Please log in to continue', 'Please log in to continue', FALSE, 'en', '2025-01-16 22:38:52', '2025-02-17 19:29:40'),
(135, 'seo', 'Messages', 'Messages', FALSE, 'en', '2025-01-18 00:16:13', '2025-02-12 00:51:42'),
(136, 'seo', 'Domains', 'Domains', FALSE, 'en', '2025-01-18 00:16:31', '2025-02-12 00:51:42'),
(137, 'general', 'Domain Name', 'Domain Name', FALSE, 'en', '2025-01-18 00:16:31', '2025-02-12 00:50:12'),
(138, 'general', 'Status', 'Status', FALSE, 'en', '2025-01-18 00:16:31', '2025-02-12 00:50:12'),
(139, 'general', 'Created At', 'Created At', FALSE, 'en', '2025-01-18 00:16:31', '2025-02-12 00:50:12'),
(140, 'general', 'Actions', 'Actions', FALSE, 'en', '2025-01-18 00:16:31', '2025-02-12 00:50:12'),
(141, 'general', 'Showing', 'Showing', FALSE, 'en', '2025-01-18 00:16:31', '2025-02-12 00:50:12'),
(142, 'general', 'to', 'to', FALSE, 'en', '2025-01-18 00:16:31', '2025-02-12 00:50:12'),
(143, 'general', 'of', 'of', FALSE, 'en', '2025-01-18 00:16:31', '2025-02-12 00:50:12'),
(144, 'general', 'entries', 'entries', FALSE, 'en', '2025-01-18 00:16:31', '2025-02-12 00:50:12'),
(145, 'general', 'Add New Domain', 'Add New Domain', FALSE, 'en', '2025-01-18 00:18:32', '2025-02-12 00:50:12'),
(146, 'seo', 'Add New Domain', 'Add New Domain', FALSE, 'en', '2025-01-18 00:18:35', '2025-02-12 00:51:42'),
(148, 'general', 'Add Domain Without "https://" , "/" ', 'Add Domain Without "https://" , "/"', FALSE, 'en', '2025-01-18 00:18:35', '2025-02-12 00:50:12'),
(149, 'html', 'How To Setup A Custom Domain', '<div class="table-inner "><div class="table-responsive"><figure class="table"><table class="table table-hover align-middle"><thead><tr><th class="text-center">Name</th><th class="text-center">Value</th><th class="text-center">Priority</th></tr></thead><tbody><tr><td class="text-center">MX</td><td class="text-center">YOUR Mail Server</td><td class="d-flex justify-content-center">10</td></tr></tbody></table></figure></div></div>', FALSE, 'en', '2025-01-18 00:18:35', '2025-02-17 19:38:00'),
(150, 'alerts', 'You do not have access,Please upgrade your account', 'You do not have access,Please upgrade your account', FALSE, 'en', '2025-01-18 00:18:40', '2025-02-17 19:29:40'),
(151, 'general', 'My Domains', 'My Domains', FALSE, 'en', '2025-01-18 00:18:45', '2025-02-12 00:48:48'),
(607, 'general', 'Comments:', 'Comments:', FALSE, 'en', '2025-02-09 23:47:48', '2025-02-12 00:48:48'),
(609, 'general', 'Please enable JavaScript to view the comments', 'Please enable JavaScript to view the comments', FALSE, 'en', '2025-02-09 23:47:48', '2025-02-12 00:48:48'),
(611, 'seo', 'Profile', 'Profile', FALSE, 'en', '2025-02-10 15:36:21', '2025-02-12 00:51:42'),
(613, 'auth', 'Profile', 'Profile', FALSE, 'en', '2025-02-10 15:36:21', '2025-02-12 00:51:22'),
(615, 'auth', 'Avatar', 'Avatar', FALSE, 'en', '2025-02-10 15:36:21', '2025-02-12 00:51:22'),
(617, 'auth', 'Click to Upload', 'Click to Upload', FALSE, 'en', '2025-02-10 15:36:21', '2025-02-12 00:51:22'),
(619, 'auth', 'Email', 'Email', FALSE, 'en', '2025-02-10 15:36:21', '2025-02-12 00:51:22'),
(621, 'auth', 'Security', 'Security', FALSE, 'en', '2025-02-10 15:36:21', '2025-02-12 00:51:22'),
(623, 'auth', 'Current Password', 'Current Password', FALSE, 'en', '2025-02-10 15:36:21', '2025-02-12 00:51:22'),
(625, 'auth', 'New Password', 'New Password', FALSE, 'en', '2025-02-10 15:36:21', '2025-02-12 00:51:22'),
(627, 'auth', 'Confirm Password', 'Confirm Password', FALSE, 'en', '2025-02-10 15:36:21', '2025-02-12 00:51:22'),
(879, 'seo', 'Contact Us', 'Contact Us', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-12 00:51:42'),
(881, 'alerts', 'This action is not allowed in demo mode.', 'This action is not allowed in demo mode.', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(883, 'alerts', 'Thank you for reaching out to us! Your message has been received, and we will get back to you shortly', 'Thank you for reaching out to us! Your message has been received, and we will get back to you shortly', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(885, 'alerts', 'The domain has been added successfully', 'The domain has been added successfully', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(887, 'alerts', 'The domain has been deleted successfully', 'The domain has been deleted successfully', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(889, 'alerts', 'The Message removed successfully from Favorite', 'The Message removed successfully from Favorite', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(891, 'alerts', 'You have reached the limit for favoriting messages', 'You have reached the limit for favoriting messages', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(893, 'alerts', 'The Message added successfully to Favorite.', 'The Message added successfully to Favorite.', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(895, 'alerts', 'An error occurred. Please reload the page and try again', 'An error occurred. Please reload the page and try again', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(897, 'alerts', 'The message has been deleted successfully', 'The message has been deleted successfully', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(899, 'alerts', 'Recaptcha verification failed, please try again later', 'Recaptcha verification failed, please try again later', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(901, 'alerts', 'Something went wrong please try again', 'Something went wrong please try again', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(903, 'alerts', 'please try again', 'please try again', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(905, 'alerts', 'Your account has been suspended or banned. Please contact support for further assistance.', 'Your account has been suspended or banned. Please contact support for further assistance.', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(907, 'alerts', 'We could not complete the process, please try again letter', 'We could not complete the process, please try again letter', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-17 19:29:40'),
(909, 'general', 'Complete', 'Complete', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-12 00:50:12'),
(911, 'general', 'Canceled', 'Canceled', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-12 00:50:12'),
(913, 'general', 'Subscribed', 'Subscribed', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-12 00:50:12'),
(915, 'general', 'Not Subscribed', 'Not Subscribed', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-12 00:50:12'),
(917, 'general', 'We use cookies to enhance your browsing experience. By using this site, you consent to our cookie policy.', 'We use cookies to enhance your browsing experience. By using this site, you consent to our cookie policy.', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-12 00:48:48'),
(919, 'general', 'I Accept', 'I Accept', FALSE, 'en', '2025-02-10 20:22:35', '2025-02-12 00:48:48'),
(16725, 'alerts', 'Updated successfully', 'Updated successfully.', FALSE, 'en', '2025-02-16 20:38:24', '2025-02-17 19:29:40'),
(16728, 'alerts', 'Deleted successfully.', 'Deleted successfully.', FALSE, 'en', '2025-02-16 20:38:24', '2025-02-17 19:29:40'),
(16731, 'alerts', 'Added successfully', 'Added successfully', FALSE, 'en', '2025-02-16 20:38:24', '2025-02-17 19:29:40'),
(16734, 'general', 'Save', 'Save', FALSE, 'en', '2025-02-16 21:56:30', '2025-02-17 19:27:48'),
(16737, 'general', 'Do you accept cookies?', 'Do you accept cookies?', FALSE, 'en', '2025-02-16 21:56:38', '2025-02-17 19:19:59'),
(16740, 'general', 'More', 'More', FALSE, 'en', '2025-02-16 21:56:38', '2025-02-17 19:20:03'),
(16747, 'auth', 'Email Verification Required', 'Email Verification Required', FALSE, 'en', '2025-02-17 02:33:59', '2025-02-17 19:28:50'),
(16748, 'auth', 'Please verify your email address to continue.', 'Please verify your email address to continue.', FALSE, 'en', '2025-02-17 02:33:59', '2025-02-17 19:28:50'),
(16749, 'auth', 'Didn’t receive the email?', 'Didn’t receive the email?', FALSE, 'en', '2025-02-17 02:33:59', '2025-02-17 19:28:50'),
(16750, 'auth', 'request another', 'request another', FALSE, 'en', '2025-02-17 02:33:59', '2025-02-17 19:28:50'),
(16751, 'auth', 'You want to ', 'You want to', FALSE, 'en', '2025-02-17 02:33:59', '2025-02-17 19:28:50'),
(16752, 'auth', 'logout?', 'logout?', FALSE, 'en', '2025-02-17 02:33:59', '2025-02-17 19:28:50'),
(16753, 'auth', 'A fresh verification link has been sent to your email address.', 'A fresh verification link has been sent to your email address.', FALSE, 'en', '2025-02-17 02:35:15', '2025-02-17 19:28:50'),
(16754, 'general', 'No Domains Available', 'No Domains Available', FALSE, 'en', '2025-02-17 02:35:48', '2025-02-17 19:27:48'),
(16755, 'general', 'It looks like there are no domains to display. Please check back later.', 'It looks like there are no domains to display. Please check back later.', FALSE, 'en', '2025-02-17 02:35:48', '2025-02-17 19:27:48'),
(16756, 'validation', 'The :attribute field must be accepted.', 'The :attribute field must be accepted.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16757, 'validation', 'The :attribute field must be accepted when :other is :value.', 'The :attribute field must be accepted when :other is :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16758, 'validation', 'The :attribute field must be a valid URL.', 'The :attribute field must be a valid URL.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16759, 'validation', 'The :attribute field must be a date after :date.', 'The :attribute field must be a date after :date.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16760, 'validation', 'The :attribute field must be a date after or equal to :date.', 'The :attribute field must be a date after or equal to :date.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16761, 'validation', 'The :attribute field must only contain letters.', 'The :attribute field must only contain letters.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16762, 'validation', 'The :attribute field must only contain letters, numbers, dashes, and underscores.', 'The :attribute field must only contain letters, numbers, dashes, and underscores.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16763, 'validation', 'The :attribute field must only contain letters and numbers.', 'The :attribute field must only contain letters and numbers.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16764, 'validation', 'The :attribute field must be an array.', 'The :attribute field must be an array.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16765, 'validation', 'The :attribute field must only contain single-byte alphanumeric characters and symbols.', 'The :attribute field must only contain single-byte alphanumeric characters and symbols.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16766, 'validation', 'The :attribute field must be a date before :date.', 'The :attribute field must be a date before :date.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16767, 'validation', 'The :attribute field must be a date before or equal to :date.', 'The :attribute field must be a date before or equal to :date.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16768, 'validation', 'The :attribute field must have between :min and :max items.', 'The :attribute field must have between :min and :max items.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16769, 'validation', 'The :attribute field must be between :min and :max kilobytes.', 'The :attribute field must be between :min and :max kilobytes.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16770, 'validation', 'The :attribute field must be between :min and :max.', 'The :attribute field must be between :min and :max.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16771, 'validation', 'The :attribute field must be between :min and :max characters.', 'The :attribute field must be between :min and :max characters.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16772, 'validation', 'The :attribute field must be true or false.', 'The :attribute field must be true or false.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16773, 'validation', 'The :attribute field contains an unauthorized value.', 'The :attribute field contains an unauthorized value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16774, 'validation', 'The :attribute field confirmation does not match.', 'The :attribute field confirmation does not match.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16775, 'validation', 'The :attribute field is missing a required value.', 'The :attribute field is missing a required value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16776, 'validation', 'The password is incorrect.', 'The password is incorrect.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16777, 'validation', 'The :attribute field must be a valid date.', 'The :attribute field must be a valid date.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16778, 'validation', 'The :attribute field must be a date equal to :date.', 'The :attribute field must be a date equal to :date.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16779, 'validation', 'The :attribute field must match the format :format.', 'The :attribute field must match the format :format.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16780, 'validation', 'The :attribute field must have :decimal decimal places.', 'The :attribute field must have :decimal decimal places.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16781, 'validation', 'The :attribute field must be declined.', 'The :attribute field must be declined.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16782, 'validation', 'The :attribute field must be declined when :other is :value.', 'The :attribute field must be declined when :other is :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16783, 'validation', 'The :attribute field and :other must be different.', 'The :attribute field and :other must be different.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16784, 'validation', 'The :attribute field must be :digits digits.', 'The :attribute field must be :digits digits.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16785, 'validation', 'The :attribute field must be between :min and :max digits.', 'The :attribute field must be between :min and :max digits.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16786, 'validation', 'The :attribute field has invalid image dimensions.', 'The :attribute field has invalid image dimensions.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16787, 'validation', 'The :attribute field has a duplicate value.', 'The :attribute field has a duplicate value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16788, 'validation', 'The :attribute field must not end with one of the following: :values.', 'The :attribute field must not end with one of the following: :values.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16789, 'validation', 'The :attribute field must not start with one of the following: :values.', 'The :attribute field must not start with one of the following: :values.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16790, 'validation', 'The :attribute field must be a valid email address.', 'The :attribute field must be a valid email address.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16791, 'validation', 'The :attribute field must end with one of the following: :values.', 'The :attribute field must end with one of the following: :values.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16792, 'validation', 'The selected :attribute is invalid.', 'The selected :attribute is invalid.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16793, 'validation', 'The :attribute field must have one of the following extensions: :values.', 'The :attribute field must have one of the following extensions: :values.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16794, 'validation', 'The :attribute field must be a file.', 'The :attribute field must be a file.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16795, 'validation', 'The :attribute field must have a value.', 'The :attribute field must have a value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16796, 'validation', 'The :attribute field must have more than :value items.', 'The :attribute field must have more than :value items.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16797, 'validation', 'The :attribute field must be greater than :value kilobytes.', 'The :attribute field must be greater than :value kilobytes.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16798, 'validation', 'The :attribute field must be greater than :value.', 'The :attribute field must be greater than :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16799, 'validation', 'The :attribute field must be greater than :value characters.', 'The :attribute field must be greater than :value characters.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16800, 'validation', 'The :attribute field must have :value items or more.', 'The :attribute field must have :value items or more.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16801, 'validation', 'The :attribute field must be greater than or equal to :value kilobytes.', 'The :attribute field must be greater than or equal to :value kilobytes.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16802, 'validation', 'The :attribute field must be greater than or equal to :value.', 'The :attribute field must be greater than or equal to :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16803, 'validation', 'The :attribute field must be greater than or equal to :value characters.', 'The :attribute field must be greater than or equal to :value characters.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16804, 'validation', 'The :attribute field must be a valid hexadecimal color.', 'The :attribute field must be a valid hexadecimal color.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16805, 'validation', 'The :attribute field must be an image.', 'The :attribute field must be an image.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16806, 'validation', 'The :attribute field must exist in :other.', 'The :attribute field must exist in :other.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16807, 'validation', 'The :attribute field must be an integer.', 'The :attribute field must be an integer.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16808, 'validation', 'The :attribute field must be a valid IP address.', 'The :attribute field must be a valid IP address.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16809, 'validation', 'The :attribute field must be a valid IPv4 address.', 'The :attribute field must be a valid IPv4 address.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16810, 'validation', 'The :attribute field must be a valid IPv6 address.', 'The :attribute field must be a valid IPv6 address.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16811, 'validation', 'The :attribute field must be a valid JSON string.', 'The :attribute field must be a valid JSON string.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16812, 'validation', 'The :attribute field must be a list.', 'The :attribute field must be a list.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16813, 'validation', 'The :attribute field must be lowercase.', 'The :attribute field must be lowercase.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16814, 'validation', 'The :attribute field must have less than :value items.', 'The :attribute field must have less than :value items.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16815, 'validation', 'The :attribute field must be less than :value kilobytes.', 'The :attribute field must be less than :value kilobytes.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16816, 'validation', 'The :attribute field must be less than :value.', 'The :attribute field must be less than :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16817, 'validation', 'The :attribute field must be less than :value characters.', 'The :attribute field must be less than :value characters.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16818, 'validation', 'The :attribute field must not have more than :value items.', 'The :attribute field must not have more than :value items.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16819, 'validation', 'The :attribute field must be less than or equal to :value kilobytes.', 'The :attribute field must be less than or equal to :value kilobytes.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16820, 'validation', 'The :attribute field must be less than or equal to :value.', 'The :attribute field must be less than or equal to :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16821, 'validation', 'The :attribute field must be less than or equal to :value characters.', 'The :attribute field must be less than or equal to :value characters.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16822, 'validation', 'The :attribute field must be a valid MAC address.', 'The :attribute field must be a valid MAC address.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16823, 'validation', 'The :attribute field must not have more than :max items.', 'The :attribute field must not have more than :max items.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16824, 'validation', 'The :attribute field must not be greater than :max kilobytes.', 'The :attribute field must not be greater than :max kilobytes.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16825, 'validation', 'The :attribute field must not be greater than :max.', 'The :attribute field must not be greater than :max.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16826, 'validation', 'The :attribute field must not be greater than :max characters.', 'The :attribute field must not be greater than :max characters.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16827, 'validation', 'The :attribute field must not have more than :max digits.', 'The :attribute field must not have more than :max digits.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16828, 'validation', 'The :attribute field must be a file of type: :values.', 'The :attribute field must be a file of type: :values.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16829, 'validation', 'The :attribute field must have at least :min items.', 'The :attribute field must have at least :min items.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16830, 'validation', 'The :attribute field must be at least :min kilobytes.', 'The :attribute field must be at least :min kilobytes.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16831, 'validation', 'The :attribute field must be at least :min.', 'The :attribute field must be at least :min.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16832, 'validation', 'The :attribute field must be at least :min characters.', 'The :attribute field must be at least :min characters.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16833, 'validation', 'The :attribute field must have at least :min digits.', 'The :attribute field must have at least :min digits.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16834, 'validation', 'The :attribute field must be missing.', 'The :attribute field must be missing.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16835, 'validation', 'The :attribute field must be missing when :other is :value.', 'The :attribute field must be missing when :other is :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16836, 'validation', 'The :attribute field must be missing unless :other is :value.', 'The :attribute field must be missing unless :other is :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16837, 'validation', 'The :attribute field must be missing when :values is present.', 'The :attribute field must be missing when :values is present.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16838, 'validation', 'The :attribute field must be missing when :values are present.', 'The :attribute field must be missing when :values are present.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16839, 'validation', 'The :attribute field must be a multiple of :value.', 'The :attribute field must be a multiple of :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16840, 'validation', 'The :attribute field format is invalid.', 'The :attribute field format is invalid.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16841, 'validation', 'The :attribute field must be a number.', 'The :attribute field must be a number.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16842, 'validation', 'The :attribute field must contain at least one letter.', 'The :attribute field must contain at least one letter.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16843, 'validation', 'The :attribute field must contain at least one uppercase and one lowercase letter.', 'The :attribute field must contain at least one uppercase and one lowercase letter.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16844, 'validation', 'The :attribute field must contain at least one number.', 'The :attribute field must contain at least one number.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16845, 'validation', 'The :attribute field must contain at least one symbol.', 'The :attribute field must contain at least one symbol.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16846, 'validation', 'The given :attribute has appeared in a data leak. Please choose a different :attribute.', 'The given :attribute has appeared in a data leak. Please choose a different :attribute.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16847, 'validation', 'The :attribute field must be present.', 'The :attribute field must be present.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16848, 'validation', 'The :attribute field must be present when :other is :value.', 'The :attribute field must be present when :other is :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16849, 'validation', 'The :attribute field must be present unless :other is :value.', 'The :attribute field must be present unless :other is :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16850, 'validation', 'The :attribute field must be present when :values is present.', 'The :attribute field must be present when :values is present.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16851, 'validation', 'The :attribute field must be present when :values are present.', 'The :attribute field must be present when :values are present.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16852, 'validation', 'The :attribute field is prohibited.', 'The :attribute field is prohibited.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16853, 'validation', 'The :attribute field is prohibited when :other is :value.', 'The :attribute field is prohibited when :other is :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16854, 'validation', 'The :attribute field is prohibited unless :other is in :values.', 'The :attribute field is prohibited unless :other is in :values.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16855, 'validation', 'The :attribute field prohibits :other from being present.', 'The :attribute field prohibits :other from being present.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16856, 'validation', 'The :attribute field is required.', 'The :attribute field is required.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16857, 'validation', 'The :attribute field must contain entries for: :values.', 'The :attribute field must contain entries for: :values.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16858, 'validation', 'The :attribute field is required when :other is :value.', 'The :attribute field is required when :other is :value.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16859, 'validation', 'The :attribute field is required when :other is accepted.', 'The :attribute field is required when :other is accepted.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16860, 'validation', 'The :attribute field is required when :other is declined.', 'The :attribute field is required when :other is declined.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16861, 'validation', 'The :attribute field is required unless :other is in :values.', 'The :attribute field is required unless :other is in :values.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16862, 'validation', 'The :attribute field is required when :values is present.', 'The :attribute field is required when :values is present.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16863, 'validation', 'The :attribute field is required when :values are present.', 'The :attribute field is required when :values are present.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16864, 'validation', 'The :attribute field is required when :values is not present.', 'The :attribute field is required when :values is not present.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16865, 'validation', 'The :attribute field is required when none of :values are present.', 'The :attribute field is required when none of :values are present.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16866, 'validation', 'The :attribute field must match :other.', 'The :attribute field must match :other.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16867, 'validation', 'The :attribute field must contain :size items.', 'The :attribute field must contain :size items.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16868, 'validation', 'The :attribute field must be :size kilobytes.', 'The :attribute field must be :size kilobytes.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16869, 'validation', 'The :attribute field must be :size.', 'The :attribute field must be :size.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16870, 'validation', 'The :attribute field must be :size characters.', 'The :attribute field must be :size characters.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16871, 'validation', 'The :attribute field must start with one of the following: :values.', 'The :attribute field must start with one of the following: :values.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16872, 'validation', 'The :attribute field must be a string.', 'The :attribute field must be a string.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16873, 'validation', 'The :attribute field must be a valid timezone.', 'The :attribute field must be a valid timezone.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16874, 'validation', 'The :attribute has already been taken.', 'The :attribute has already been taken.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16875, 'validation', 'The :attribute failed to upload.', 'The :attribute failed to upload.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16876, 'validation', 'The :attribute field must be uppercase.', 'The :attribute field must be uppercase.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16877, 'validation', 'The :attribute field must be a valid ULID.', 'The :attribute field must be a valid ULID.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16878, 'validation', 'The :attribute field must be a valid UUID.', 'The :attribute field must be a valid UUID.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16879, 'validation', 'Please verify that you are not a robot.', 'Please verify that you are not a robot.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16880, 'validation', 'Captcha error! try again later or contact site admin.', 'Captcha error! try again later or contact site admin.', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16881, 'validation', 'full name', 'full name', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16882, 'validation', 'username', 'username', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16883, 'validation', 'email address', 'email address', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16884, 'validation', 'first name', 'first name', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16885, 'validation', 'last name', 'last name', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16886, 'validation', 'password', 'password', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16887, 'validation', 'password confirmation', 'password confirmation', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16888, 'validation', 'subject', 'subject', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16889, 'validation', 'message', 'message', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16890, 'validation', 'key', 'key', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16891, 'validation', 'avatar', 'avatar', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16892, 'validation', 'current password', 'current password', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16893, 'validation', 'domain', 'domain', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16894, 'validation', 'city', 'city', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16895, 'validation', 'country', 'country', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16896, 'validation', 'address', 'address', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16897, 'validation', 'phone', 'phone', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16898, 'validation', 'mobile', 'mobile', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16899, 'validation', 'age', 'age', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16900, 'validation', 'sex', 'sex', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16901, 'validation', 'gender', 'gender', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16902, 'validation', 'day', 'day', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16903, 'validation', 'month', 'month', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16904, 'validation', 'year', 'year', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16905, 'validation', 'hour', 'hour', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16906, 'validation', 'minute', 'minute', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16907, 'validation', 'second', 'second', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16908, 'validation', 'title', 'title', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16909, 'validation', 'content', 'content', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16910, 'validation', 'description', 'description', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16911, 'validation', 'excerpt', 'excerpt', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16912, 'validation', 'date', 'date', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16913, 'validation', 'time', 'time', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16914, 'validation', 'available', 'available', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(16915, 'validation', 'size', 'size', FALSE, 'en', '2025-02-17 19:41:15', '2025-02-17 19:41:15'),
(19791, 'general', 'Error', 'Error', FALSE, 'en', '2025-02-19 17:41:00', '2025-02-19 23:40:31'),
(19795, 'general', 'success', 'success', FALSE, 'en', '2025-02-19 23:29:38', '2025-02-19 23:40:31');
INSERT INTO "translates" ("id", "collection", "key", "value", "type", "lang", "created_at", "updated_at") VALUES
(19796, 'general', 'Unauthorized', 'Unauthorized', FALSE, 'en', '2025-02-20 00:14:27', '2025-02-20 02:48:07'),
(19797, 'general', 'Sorry, you are not authorized to access this page.', 'Sorry, you are not authorized to access this page.', FALSE, 'en', '2025-02-20 00:14:27', '2025-02-20 02:48:07'),
(19798, 'general', 'Payment Required', 'Payment Required', FALSE, 'en', '2025-02-20 00:14:33', '2025-02-20 02:48:07'),
(19799, 'general', 'Payment is required to access this resource.', 'Payment is required to access this resource.', FALSE, 'en', '2025-02-20 00:14:33', '2025-02-20 02:48:07'),
(19800, 'general', 'Too Many Requests', 'Too Many Requests', FALSE, 'en', '2025-02-20 00:14:54', '2025-02-20 02:48:07'),
(19801, 'general', 'You have made too many requests. Please wait and try again later.', 'You have made too many requests. Please wait and try again later.', FALSE, 'en', '2025-02-20 00:14:54', '2025-02-20 02:48:07'),
(19802, 'general', 'Service Unavailable', 'Service Unavailable', FALSE, 'en', '2025-02-20 00:15:05', '2025-02-20 02:48:07'),
(19803, 'general', 'The service is currently unavailable. Please try again later.', 'The service is currently unavailable. Please try again later.', FALSE, 'en', '2025-02-20 00:15:05', '2025-02-20 02:48:07'),
(20171, 'alerts', 'These credentials do not match our records.', 'These credentials do not match our records.', FALSE, 'en', '2025-02-20 15:18:00', '2025-02-20 15:18:00'),
(20173, 'alerts', 'The provided password is incorrect.', 'The provided password is incorrect.', FALSE, 'en', '2025-02-20 15:18:00', '2025-02-20 15:18:00'),
(20175, 'alerts', 'Too many login attempts. Please try again in :seconds seconds.', 'Too many login attempts. Please try again in :seconds seconds.', FALSE, 'en', '2025-02-20 15:18:00', '2025-02-20 15:18:00'),
(31985, 'general', 'info', 'info', FALSE, 'en', '2025-02-21 05:59:39', '2025-02-21 05:59:39'),
(34127, 'general', 'Account Settings', 'Account Settings', FALSE, 'en', '2025-02-24 21:59:23', '2025-02-24 21:59:23'),
(34161, 'general', 'Logout', 'Logout', FALSE, 'en', '2025-02-24 21:59:24', '2025-02-24 21:59:24'),
(34195, 'general', 'Messages', 'Messages', FALSE, 'en', '2025-02-24 22:03:46', '2025-02-24 22:03:46'),
(34229, 'general', 'From', 'From', FALSE, 'en', '2025-02-24 22:03:57', '2025-02-24 22:03:57'),
(34263, 'general', 'received At', 'received At', FALSE, 'en', '2025-02-24 22:03:57', '2025-02-24 22:03:57'),
(34297, 'general', 'Cancel', 'Cancel', FALSE, 'en', '2025-02-24 22:04:49', '2025-02-24 22:04:49'),
(34331, 'general', 'Last updated', 'Last updated', FALSE, 'en', '2025-02-24 22:12:29', '2025-02-24 22:12:29'),
(34332, 'alerts', 'Your password has been reset.', 'Your password has been reset.', FALSE, 'en', '2025-02-27 00:44:05', '2025-02-27 02:13:30'),
(34333, 'alerts', 'We have emailed your password reset link.', 'We have emailed your password reset link.', FALSE, 'en', '2025-02-27 00:44:05', '2025-02-27 02:13:30'),
(34334, 'alerts', 'Please wait before retrying.', 'Please wait before retrying.', FALSE, 'en', '2025-02-27 00:44:05', '2025-02-27 02:13:30'),
(34335, 'alerts', 'This password reset token is invalid.', 'This password reset token is invalid.', FALSE, 'en', '2025-02-27 00:44:05', '2025-02-27 02:13:30'),
(34336, 'alerts', 'We can''t find a user with that email address.', 'We can''t find a user with that email address.', FALSE, 'en', '2025-02-27 00:44:05', '2025-02-27 02:13:30'),
(34337, 'auth', 'Please enter your email address and new password.', 'Please enter your email address and new password.', FALSE, 'en', '2025-02-27 00:52:33', '2025-02-27 02:13:30'),
(34338, 'auth', 'Confirm New Password', 'Confirm New Password', FALSE, 'en', '2025-02-27 00:52:33', '2025-02-27 02:13:30'),
(34339, 'auth', 'Update Password', 'Update Password', FALSE, 'en', '2025-02-27 00:52:33', '2025-02-27 02:13:30'),
(47047, 'auth', 'sign up agreement', 'By creating an account you agree to our {{terms}}  and {{privacy}}', FALSE, 'en', '2025-02-27 21:37:34', '2025-02-27 21:37:34'),
(47083, 'general', 'Back', 'Back', FALSE, 'en', '2025-02-28 01:12:21', '2025-02-28 23:40:11'),
(47117, 'alerts', 'Your profile has been updated successfully!', 'Your profile has been updated successfully!', FALSE, 'en', '2025-02-28 23:13:06', '2025-02-28 23:40:11'),
(47119, 'alerts', 'Your Password has been updated successfully!', 'Your Password has been updated successfully!', FALSE, 'en', '2025-02-28 23:13:30', '2025-02-28 23:40:11'),
(47121, 'general', 'Crafted with', 'Crafted with ❤️ by {{site_name}}', FALSE, 'en', '2025-02-28 23:38:40', '2025-02-28 23:40:11'),
(47123, 'alerts', 'Too many requests, Please slow down', 'Too many requests, Please slow down', FALSE, 'en', '2025-03-02 05:51:57', '2025-03-02 14:06:14'),
(47124, 'alerts', 'The Email has been successfully updated', 'The Email has been successfully updated', FALSE, 'en', '2025-03-02 09:07:40', '2025-03-02 14:06:14'),
(47125, 'alerts', 'The email has been removed', 'The email has been removed', FALSE, 'en', '2025-03-02 09:07:40', '2025-03-02 14:06:14'),
(47126, 'general', 'Remove from favorites', 'Remove from favorites', FALSE, 'en', '2025-03-02 09:07:40', '2025-03-02 14:06:14'),
(47127, 'general', 'Add to favorites', 'Add to favorites', FALSE, 'en', '2025-03-02 09:07:40', '2025-03-02 14:06:14'),
(47128, 'general', 'Contact Us', 'Contact Us', FALSE, 'en', '2025-03-03 06:43:02', '2025-03-03 13:55:39'),
(47129, 'html', 'Contact Us Content', 'Active the Contact us form in plugins', FALSE, 'en', '2025-03-03 06:43:02', '2025-03-03 13:55:39'),
(47130, 'general', 'Full Name', 'Full Name', FALSE, 'en', '2025-03-03 07:06:26', '2025-03-03 13:55:39'),
(47131, 'general', 'Email', 'Email', FALSE, 'en', '2025-03-03 07:06:26', '2025-03-03 13:55:39'),
(47132, 'general', 'Message', 'Message', FALSE, 'en', '2025-03-03 07:06:26', '2025-03-03 13:55:39'),
(47133, 'general', 'Send Your Message', 'Send Your Message', FALSE, 'en', '2025-03-03 07:06:26', '2025-03-03 13:55:39');

-- --------------------------------------------------------

--
-- Table structure for table "translation_jobs"
--

CREATE TABLE "translation_jobs" (
  "id" BIGSERIAL NOT NULL,
  "job_id" VARCHAR(255) NOT NULL,
  "total_chunks" int NOT NULL DEFAULT '0',
  "processed_chunks" int NOT NULL DEFAULT '0',
  "success_count" int NOT NULL DEFAULT '0',
  "error_count" int NOT NULL DEFAULT '0',
  "total_characters" int NOT NULL DEFAULT '0',
  "results" TEXT,
  "message" VARCHAR(255) DEFAULT NULL,
  "status" VARCHAR(255) NOT NULL DEFAULT 'pending',
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "trash_mails"
--

CREATE TABLE "trash_mails" (
  "id" BIGSERIAL NOT NULL,
  "user_id" INTEGER DEFAULT NULL,
  "email" VARCHAR(255) NOT NULL,
  "domain" VARCHAR(255) NOT NULL,
  "ip" VARCHAR(255) DEFAULT NULL,
  "fingerprint" VARCHAR(255) DEFAULT NULL,
  "expire_at" timestamp NULL DEFAULT NULL,
  "deleted_at" timestamp NULL DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "users"
--

CREATE TABLE "users" (
  "id" BIGSERIAL NOT NULL,
  "firstname" VARCHAR(255) DEFAULT NULL,
  "lastname" VARCHAR(255) DEFAULT NULL,
  "email" VARCHAR(255) DEFAULT NULL,
  "avatar" VARCHAR(255) DEFAULT NULL,
  "country" VARCHAR(255) DEFAULT NULL,
  "email_verified_at" timestamp NULL DEFAULT NULL,
  "password" VARCHAR(255) NOT NULL,
  "facebook_id" VARCHAR(255) DEFAULT NULL,
  "google_id" VARCHAR(255) DEFAULT NULL,
  "status" BOOLEAN NOT NULL DEFAULT TRUE,
  "remember_token" VARCHAR(100) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table "admins"
--
ALTER TABLE "admins" ADD CONSTRAINT "admins_pkey" PRIMARY KEY ("id");
ALTER TABLE "admins" ADD CONSTRAINT "admins_email_unique" UNIQUE ("email");

--
-- Indexes for table "ads"
--
ALTER TABLE "ads" ADD CONSTRAINT "ads_pkey" PRIMARY KEY ("id");
ALTER TABLE "ads" ADD CONSTRAINT "ads_shortcode_unique" UNIQUE ("shortcode");

--
-- Indexes for table "blog_categories"
--
ALTER TABLE "blog_categories" ADD CONSTRAINT "blog_categories_pkey" PRIMARY KEY ("id");
ALTER TABLE "blog_categories" ADD CONSTRAINT "blog_categories_slug_unique" UNIQUE ("slug");
CREATE INDEX "blog_categories_lang_foreign" ON "blog_categories" ("lang");

--
-- Indexes for table "blog_posts"
--
ALTER TABLE "blog_posts" ADD CONSTRAINT "blog_posts_pkey" PRIMARY KEY ("id");
ALTER TABLE "blog_posts" ADD CONSTRAINT "blog_posts_slug_unique" UNIQUE ("slug");
CREATE INDEX "blog_posts_lang_foreign" ON "blog_posts" ("lang");
CREATE INDEX "blog_posts_category_id_foreign" ON "blog_posts" ("category_id");

--
-- Indexes for table "cache"
--
ALTER TABLE "cache" ADD CONSTRAINT "cache_pkey" PRIMARY KEY ("key");

--
-- Indexes for table "cache_locks"
--
ALTER TABLE "cache_locks" ADD CONSTRAINT "cache_locks_pkey" PRIMARY KEY ("key");

--
-- Indexes for table "domains"
--
ALTER TABLE "domains" ADD CONSTRAINT "domains_pkey" PRIMARY KEY ("id");
ALTER TABLE "domains" ADD CONSTRAINT "domains_domain_unique" UNIQUE ("domain");
CREATE INDEX "domains_user_id_foreign" ON "domains" ("user_id");

--
-- Indexes for table "email_templates"
--
ALTER TABLE "email_templates" ADD CONSTRAINT "email_templates_pkey" PRIMARY KEY ("id");
ALTER TABLE "email_templates" ADD CONSTRAINT "email_templates_alias_unique" UNIQUE ("alias");

--
-- Indexes for table "failed_jobs"
--
ALTER TABLE "failed_jobs" ADD CONSTRAINT "failed_jobs_pkey" PRIMARY KEY ("id");
ALTER TABLE "failed_jobs" ADD CONSTRAINT "failed_jobs_uuid_unique" UNIQUE ("uuid");

--
-- Indexes for table "faqs"
--
ALTER TABLE "faqs" ADD CONSTRAINT "faqs_pkey" PRIMARY KEY ("id");
CREATE INDEX "faqs_lang_foreign" ON "faqs" ("lang");

--
-- Indexes for table "features"
--
ALTER TABLE "features" ADD CONSTRAINT "features_pkey" PRIMARY KEY ("id");
CREATE INDEX "features_lang_foreign" ON "features" ("lang");

--
-- Indexes for table "imaps"
--
ALTER TABLE "imaps" ADD CONSTRAINT "imaps_pkey" PRIMARY KEY ("id");
ALTER TABLE "imaps" ADD CONSTRAINT "imaps_tag_unique" UNIQUE ("tag");

--
-- Indexes for table "jobs"
--
ALTER TABLE "jobs" ADD CONSTRAINT "jobs_pkey" PRIMARY KEY ("id");
CREATE INDEX "jobs_queue_index" ON "jobs" ("queue");

--
-- Indexes for table "job_batches"
--
ALTER TABLE "job_batches" ADD CONSTRAINT "job_batches_pkey" PRIMARY KEY ("id");

--
-- Indexes for table "languages"
--
ALTER TABLE "languages" ADD CONSTRAINT "languages_pkey" PRIMARY KEY ("id");
ALTER TABLE "languages" ADD CONSTRAINT "languages_code_unique" UNIQUE ("code");

--
-- Indexes for table "logs"
--
ALTER TABLE "logs" ADD CONSTRAINT "logs_pkey" PRIMARY KEY ("id");

--
-- Indexes for table "menus"
--
ALTER TABLE "menus" ADD CONSTRAINT "menus_pkey" PRIMARY KEY ("id");
CREATE INDEX "menus_lang_foreign" ON "menus" ("lang");

--
-- Indexes for table "messages"
--
ALTER TABLE "messages" ADD CONSTRAINT "messages_pkey" PRIMARY KEY ("id");
CREATE INDEX "messages_user_id_foreign" ON "messages" ("user_id");

--
-- Indexes for table "migrations"
--
ALTER TABLE "migrations" ADD CONSTRAINT "migrations_pkey" PRIMARY KEY ("id");

--
-- Indexes for table "notifications"
--
ALTER TABLE "notifications" ADD CONSTRAINT "notifications_pkey" PRIMARY KEY ("id");

--
-- Indexes for table "pages"
--
ALTER TABLE "pages" ADD CONSTRAINT "pages_pkey" PRIMARY KEY ("id");
ALTER TABLE "pages" ADD CONSTRAINT "pages_slug_unique" UNIQUE ("slug");
CREATE INDEX "pages_lang_foreign" ON "pages" ("lang");

--
-- Indexes for table "password_reset_tokens"
--
ALTER TABLE "password_reset_tokens" ADD CONSTRAINT "password_reset_tokens_pkey" PRIMARY KEY ("email");

--
-- Indexes for table "personal_access_tokens"
--
ALTER TABLE "personal_access_tokens" ADD CONSTRAINT "personal_access_tokens_pkey" PRIMARY KEY ("id");
ALTER TABLE "personal_access_tokens" ADD CONSTRAINT "personal_access_tokens_token_unique" UNIQUE ("token");
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" ON "personal_access_tokens" ("tokenable_type","tokenable_id");

--
-- Indexes for table "plans"
--
ALTER TABLE "plans" ADD CONSTRAINT "plans_pkey" PRIMARY KEY ("id");
ALTER TABLE "plans" ADD CONSTRAINT "plans_tag_unique" UNIQUE ("tag");

--
-- Indexes for table "plan_features"
--
ALTER TABLE "plan_features" ADD CONSTRAINT "plan_features_pkey" PRIMARY KEY ("id");
ALTER TABLE "plan_features" ADD CONSTRAINT "plan_features_tag_plan_id_unique" UNIQUE ("tag","plan_id");
CREATE INDEX "plan_features_plan_id_foreign" ON "plan_features" ("plan_id");

--
-- Indexes for table "plan_subscriptions"
--
ALTER TABLE "plan_subscriptions" ADD CONSTRAINT "plan_subscriptions_pkey" PRIMARY KEY ("id");
ALTER TABLE "plan_subscriptions" ADD CONSTRAINT "unique_plan_subscription" UNIQUE ("tag","subscriber_id","subscriber_type");
CREATE INDEX "plan_subscriptions_subscriber_type_subscriber_id_index" ON "plan_subscriptions" ("subscriber_type","subscriber_id");
CREATE INDEX "plan_subscriptions_plan_id_foreign" ON "plan_subscriptions" ("plan_id");

--
-- Indexes for table "plan_subscription_usage"
--
ALTER TABLE "plan_subscription_usage" ADD CONSTRAINT "plan_subscription_usage_pkey" PRIMARY KEY ("id");
ALTER TABLE "plan_subscription_usage" ADD CONSTRAINT "plan_subscription_usage_subscription_id_feature_id_unique" UNIQUE ("subscription_id","feature_id");
CREATE INDEX "plan_subscription_usage_feature_id_foreign" ON "plan_subscription_usage" ("feature_id");

--
-- Indexes for table "plugins"
--
ALTER TABLE "plugins" ADD CONSTRAINT "plugins_pkey" PRIMARY KEY ("id");

--
-- Indexes for table "sections"
--
ALTER TABLE "sections" ADD CONSTRAINT "sections_pkey" PRIMARY KEY ("id");
CREATE INDEX "sections_lang_foreign" ON "sections" ("lang");

--
-- Indexes for table "seo"
--
ALTER TABLE "seo" ADD CONSTRAINT "seo_pkey" PRIMARY KEY ("id");
CREATE INDEX "seo_lang_foreign" ON "seo" ("lang");

--
-- Indexes for table "sessions"
--
ALTER TABLE "sessions" ADD CONSTRAINT "sessions_pkey" PRIMARY KEY ("id");
CREATE INDEX "sessions_user_id_index" ON "sessions" ("user_id");
CREATE INDEX "sessions_last_activity_index" ON "sessions" ("last_activity");

--
-- Indexes for table "settings"
--
ALTER TABLE "settings" ADD CONSTRAINT "settings_pkey" PRIMARY KEY ("id");

--
-- Indexes for table "statistics"
--
ALTER TABLE "statistics" ADD CONSTRAINT "statistics_pkey" PRIMARY KEY ("id");

--
-- Indexes for table "themes"
--
ALTER TABLE "themes" ADD CONSTRAINT "themes_pkey" PRIMARY KEY ("id");
ALTER TABLE "themes" ADD CONSTRAINT "themes_unique_name_unique" UNIQUE ("unique_name");

--
-- Indexes for table "translates"
--
ALTER TABLE "translates" ADD CONSTRAINT "translates_pkey" PRIMARY KEY ("id");
CREATE INDEX "translates_lang_foreign" ON "translates" ("lang");

--
-- Indexes for table "translation_jobs"
--
ALTER TABLE "translation_jobs" ADD CONSTRAINT "translation_jobs_pkey" PRIMARY KEY ("id");
ALTER TABLE "translation_jobs" ADD CONSTRAINT "translation_jobs_job_id_unique" UNIQUE ("job_id");

--
-- Indexes for table "trash_mails"
--
ALTER TABLE "trash_mails" ADD CONSTRAINT "trash_mails_pkey" PRIMARY KEY ("id");

--
-- Indexes for table "users"
--
ALTER TABLE "users" ADD CONSTRAINT "users_pkey" PRIMARY KEY ("id");
ALTER TABLE "users" ADD CONSTRAINT "email" UNIQUE ("email");

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
--

--
-- Constraints for dumped tables
--

--
-- Constraints for table "blog_categories"
--
ALTER TABLE "blog_categories" ADD CONSTRAINT "blog_categories_lang_foreign" FOREIGN KEY ("lang") REFERENCES "languages" ("code") ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table "blog_posts"
--
ALTER TABLE "blog_posts" ADD CONSTRAINT "blog_posts_category_id_foreign" FOREIGN KEY ("category_id") REFERENCES "blog_categories" ("id") ON DELETE CASCADE;
ALTER TABLE "blog_posts" ADD CONSTRAINT "blog_posts_lang_foreign" FOREIGN KEY ("lang") REFERENCES "languages" ("code") ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table "domains"
--
ALTER TABLE "domains" ADD CONSTRAINT "domains_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table "faqs"
--
ALTER TABLE "faqs" ADD CONSTRAINT "faqs_lang_foreign" FOREIGN KEY ("lang") REFERENCES "languages" ("code") ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table "features"
--
ALTER TABLE "features" ADD CONSTRAINT "features_lang_foreign" FOREIGN KEY ("lang") REFERENCES "languages" ("code") ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table "menus"
--
ALTER TABLE "menus" ADD CONSTRAINT "menus_lang_foreign" FOREIGN KEY ("lang") REFERENCES "languages" ("code") ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table "messages"
--
ALTER TABLE "messages" ADD CONSTRAINT "messages_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table "pages"
--
ALTER TABLE "pages" ADD CONSTRAINT "pages_lang_foreign" FOREIGN KEY ("lang") REFERENCES "languages" ("code") ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table "plan_features"
--
ALTER TABLE "plan_features" ADD CONSTRAINT "plan_features_plan_id_foreign" FOREIGN KEY ("plan_id") REFERENCES "plans" ("id") ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table "plan_subscriptions"
--
ALTER TABLE "plan_subscriptions" ADD CONSTRAINT "plan_subscriptions_plan_id_foreign" FOREIGN KEY ("plan_id") REFERENCES "plans" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table "plan_subscription_usage"
--
ALTER TABLE "plan_subscription_usage" ADD CONSTRAINT "plan_subscription_usage_feature_id_foreign" FOREIGN KEY ("feature_id") REFERENCES "plan_features" ("id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "plan_subscription_usage" ADD CONSTRAINT "plan_subscription_usage_subscription_id_foreign" FOREIGN KEY ("subscription_id") REFERENCES "plan_subscriptions" ("id") ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table "sections"
--
ALTER TABLE "sections" ADD CONSTRAINT "sections_lang_foreign" FOREIGN KEY ("lang") REFERENCES "languages" ("code") ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table "seo"
--
ALTER TABLE "seo" ADD CONSTRAINT "seo_lang_foreign" FOREIGN KEY ("lang") REFERENCES "languages" ("code") ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table "translates"
--
ALTER TABLE "translates" ADD CONSTRAINT "translates_lang_foreign" FOREIGN KEY ("lang") REFERENCES "languages" ("code") ON DELETE CASCADE ON UPDATE CASCADE;


-- إعادة تفعيل المحفزات والقيود
SET session_replication_role = 'origin';

COMMIT;

-- ═══════════════════════════════════════════════════════════════════════════════════════
-- اكتمل الاستيراد بنجاح
-- ═══════════════════════════════════════════════════════════════════════════════════════
