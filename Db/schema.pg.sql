-- ============================================================
-- PostgreSQL schema for Open Shelf Digital Library
-- Usage:
--   psql -U postgres -d digital_library -f Db/schema.pg.sql
-- ============================================================

SET client_encoding = 'UTF8';

-- Drop tables in reverse dependency order (safe re-run)
DROP TABLE IF EXISTS "iL_AccountRequests" CASCADE;
DROP TABLE IF EXISTS "iL_receivedMails"   CASCADE;
DROP TABLE IF EXISTS "iL_sendMails"       CASCADE;
DROP TABLE IF EXISTS "iL_notifications"   CASCADE;
DROP TABLE IF EXISTS "iL_messages"        CASCADE;
DROP TABLE IF EXISTS "iL_PasswordResets"  CASCADE;
DROP TABLE IF EXISTS "iL_Subscriptions"   CASCADE;
DROP TABLE IF EXISTS "iL_Reccomendations" CASCADE;
DROP TABLE IF EXISTS "iL_LibraryOperations" CASCADE;
DROP TABLE IF EXISTS "iL_Fines"           CASCADE;
DROP TABLE IF EXISTS "iL_ChargesRates"    CASCADE;
DROP TABLE IF EXISTS "iL_Books"           CASCADE;
DROP TABLE IF EXISTS "iL_BookCategories"  CASCADE;
DROP TABLE IF EXISTS "iL_Librarians"      CASCADE;
DROP TABLE IF EXISTS "iL_Students"        CASCADE;
DROP TABLE IF EXISTS "iL_sudo"            CASCADE;

-- ─────────────────────────────────────────────────────────────
-- iL_BookCategories
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_BookCategories" (
  "bc_id"   SERIAL PRIMARY KEY,
  "bc_code" VARCHAR(200) NOT NULL DEFAULT '',
  "bc_name" VARCHAR(200) NOT NULL DEFAULT '',
  "bc_desc" TEXT         NOT NULL DEFAULT ''
);

-- ─────────────────────────────────────────────────────────────
-- iL_Books
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_Books" (
  "b_id"          SERIAL PRIMARY KEY,
  "b_title"       VARCHAR(200) NOT NULL DEFAULT '',
  "b_author"      VARCHAR(200) NOT NULL DEFAULT '',
  "b_isbn_no"     VARCHAR(200) NOT NULL DEFAULT '',
  "b_publisher"   VARCHAR(200) NOT NULL DEFAULT '',
  "b_coverimage"  VARCHAR(200) NOT NULL DEFAULT '',
  "bc_id"         VARCHAR(200) NOT NULL DEFAULT '',
  "bc_name"       VARCHAR(200) NOT NULL DEFAULT '',
  "b_status"      VARCHAR(200) NOT NULL DEFAULT '',
  "created_at"    TIMESTAMPTZ  NOT NULL DEFAULT NOW(),
  "b_summary"     TEXT         NOT NULL DEFAULT '',
  "b_copies"      VARCHAR(200) NOT NULL DEFAULT ''
);

-- ─────────────────────────────────────────────────────────────
-- iL_ChargesRates
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_ChargesRates" (
  "cr_id"     SERIAL PRIMARY KEY,
  "cr_code"   VARCHAR(200) NOT NULL DEFAULT '',
  "cr_name"   VARCHAR(200) NOT NULL DEFAULT '',
  "cr_amount" VARCHAR(200) NOT NULL DEFAULT ''
);

-- ─────────────────────────────────────────────────────────────
-- iL_Fines
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_Fines" (
  "f_id"            SERIAL PRIMARY KEY,
  "f_type"          VARCHAR(200) NOT NULL DEFAULT '',
  "f_amt"           VARCHAR(200) NOT NULL DEFAULT '',
  "f_payment_code"  VARCHAR(200) NOT NULL DEFAULT '',
  "s_id"            VARCHAR(200) NOT NULL DEFAULT '',
  "s_number"        VARCHAR(200) NOT NULL DEFAULT '',
  "s_name"          VARCHAR(200) NOT NULL DEFAULT '',
  "f_status"        VARCHAR(200) NOT NULL DEFAULT '',
  "f_checksum"      VARCHAR(200) NOT NULL DEFAULT '',
  "created_at"      TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- ─────────────────────────────────────────────────────────────
-- iL_Librarians
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_Librarians" (
  "l_id"         SERIAL PRIMARY KEY,
  "l_name"       VARCHAR(200) NOT NULL DEFAULT '',
  "l_number"     VARCHAR(200) NOT NULL DEFAULT '',
  "l_email"      VARCHAR(200) NOT NULL DEFAULT '',
  "l_pwd"        VARCHAR(200) NOT NULL DEFAULT '',
  "l_bio"        TEXT         NOT NULL DEFAULT '',
  "l_phone"      VARCHAR(200) NOT NULL DEFAULT '',
  "l_adr"        VARCHAR(200) NOT NULL DEFAULT '',
  "l_dpic"       VARCHAR(200) NOT NULL DEFAULT '',
  "l_acc_status" VARCHAR(200) NOT NULL DEFAULT '',
  "created_at"   TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- ─────────────────────────────────────────────────────────────
-- iL_LibraryOperations
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_LibraryOperations" (
  "lo_id"            SERIAL PRIMARY KEY,
  "lo_type"          VARCHAR(200) NOT NULL DEFAULT '',
  "lo_number"        VARCHAR(200) NOT NULL DEFAULT '',
  "s_id"             VARCHAR(200) NOT NULL DEFAULT '',
  "s_name"           VARCHAR(200) NOT NULL DEFAULT '',
  "s_number"         VARCHAR(200) NOT NULL DEFAULT '',
  "l_id"             VARCHAR(200) NOT NULL DEFAULT '',
  "l_name"           VARCHAR(200) NOT NULL DEFAULT '',
  "l_number"         VARCHAR(200) NOT NULL DEFAULT '',
  "bc_id"            VARCHAR(200) NOT NULL DEFAULT '',
  "bc_name"          VARCHAR(200) NOT NULL DEFAULT '',
  "b_id"             VARCHAR(200) NOT NULL DEFAULT '',
  "b_title"          VARCHAR(200) NOT NULL DEFAULT '',
  "b_isbn_no"        VARCHAR(200) NOT NULL DEFAULT '',
  "created_at"       TIMESTAMPTZ  NOT NULL DEFAULT NOW(),
  "lo_checksum"      VARCHAR(200) NOT NULL DEFAULT '',
  "lo_status"        VARCHAR(200) NOT NULL DEFAULT '',
  "lo_return_date"   VARCHAR(200) NOT NULL DEFAULT ''
);

-- ─────────────────────────────────────────────────────────────
-- iL_messages
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_messages" (
  "id"          SERIAL PRIMARY KEY,
  "sender"      VARCHAR(200) NOT NULL DEFAULT '',
  "receiver"    VARCHAR(200) NOT NULL DEFAULT '',
  "content"     TEXT         NOT NULL DEFAULT '',
  "sender_id"   VARCHAR(200) NOT NULL DEFAULT '',
  "receiver_id" VARCHAR(200) NOT NULL DEFAULT '',
  "send_at"     TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- ─────────────────────────────────────────────────────────────
-- iL_notifications
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_notifications" (
  "id"         SERIAL PRIMARY KEY,
  "content"    TEXT         NOT NULL DEFAULT '',
  "user_id"    VARCHAR(200) NOT NULL DEFAULT '',
  "created_at" TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- ─────────────────────────────────────────────────────────────
-- iL_PasswordResets
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_PasswordResets" (
  "pr_id"        SERIAL PRIMARY KEY,
  "pr_useremail" VARCHAR(200) NOT NULL DEFAULT '',
  "pr_usertype"  VARCHAR(200) NOT NULL DEFAULT '',
  "pr_dummypwd"  VARCHAR(200) NOT NULL DEFAULT '',
  "pr_token"     VARCHAR(200) NOT NULL DEFAULT '',
  "pr_status"    VARCHAR(200) NOT NULL DEFAULT '',
  "created_at"   TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- ─────────────────────────────────────────────────────────────
-- iL_Reccomendations
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_Reccomendations" (
  "iR_id"        SERIAL PRIMARY KEY,
  "iR_Booktitle" VARCHAR(200) NOT NULL DEFAULT '',
  "iR_author"    VARCHAR(200) NOT NULL DEFAULT '',
  "iR_desc"      TEXT         NOT NULL DEFAULT ''
);

-- ─────────────────────────────────────────────────────────────
-- iL_receivedMails
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_receivedMails" (
  "rm_id"            SERIAL PRIMARY KEY,
  "sm_id"            VARCHAR(200) NOT NULL DEFAULT '',
  "sm_title"         VARCHAR(200) NOT NULL DEFAULT '',
  "sm_receiverNo"    VARCHAR(200) NOT NULL DEFAULT '',
  "sm_senderNo"      VARCHAR(200) NOT NULL DEFAULT '',
  "sm_senderName"    VARCHAR(200) NOT NULL DEFAULT '',
  "sm_receiverName"  VARCHAR(200) NOT NULL DEFAULT '',
  "sm_reply"         TEXT         NOT NULL DEFAULT '',
  "created_at"       TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- ─────────────────────────────────────────────────────────────
-- iL_sendMails
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_sendMails" (
  "sm_id"           SERIAL PRIMARY KEY,
  "sm_title"        VARCHAR(200) NOT NULL DEFAULT '',
  "sm_senderNo"     VARCHAR(200) NOT NULL DEFAULT '',
  "sm_receiverNo"   VARCHAR(200) NOT NULL DEFAULT '',
  "sm_senderName"   VARCHAR(200) NOT NULL DEFAULT '',
  "sm_receiverName" VARCHAR(200) NOT NULL DEFAULT '',
  "sm_sentMail"     TEXT         NOT NULL DEFAULT '',
  "created_at"      TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- ─────────────────────────────────────────────────────────────
-- iL_Students
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_Students" (
  "s_id"         SERIAL PRIMARY KEY,
  "s_name"       VARCHAR(200) NOT NULL DEFAULT '',
  "s_number"     VARCHAR(200) NOT NULL DEFAULT '',
  "s_email"      VARCHAR(200) NOT NULL DEFAULT '',
  "s_pwd"        VARCHAR(200) NOT NULL DEFAULT '',
  "s_sex"        VARCHAR(200) NOT NULL DEFAULT '',
  "s_phone"      VARCHAR(200) NOT NULL DEFAULT '',
  "s_bio"        TEXT         NOT NULL DEFAULT '',
  "s_adr"        VARCHAR(200) NOT NULL DEFAULT '',
  "s_dpic"       VARCHAR(200) NOT NULL DEFAULT '',
  "s_acc_status" VARCHAR(200) NOT NULL DEFAULT '',
  "created_at"   TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- ─────────────────────────────────────────────────────────────
-- iL_Subscriptions
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_Subscriptions" (
  "s_id"         SERIAL PRIMARY KEY,
  "s_title"      VARCHAR(200) NOT NULL DEFAULT '',
  "s_code"       VARCHAR(200) NOT NULL DEFAULT '',
  "s_category"   VARCHAR(200) NOT NULL DEFAULT '',
  "s_desc"       TEXT         NOT NULL DEFAULT '',
  "s_cover_img"  VARCHAR(200) NOT NULL DEFAULT '',
  "s_created_at" TIMESTAMPTZ  NOT NULL DEFAULT NOW(),
  "s_file"       VARCHAR(200) NOT NULL DEFAULT '',
  "s_publisher"  VARCHAR(200) NOT NULL DEFAULT '',
  "s_year"       VARCHAR(200) NOT NULL DEFAULT ''
);

-- ─────────────────────────────────────────────────────────────
-- iL_sudo  (administrators)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_sudo" (
  "id"          SERIAL PRIMARY KEY,
  "username"    VARCHAR(200) NOT NULL DEFAULT '',
  "number"      VARCHAR(200) NOT NULL DEFAULT '',
  "email"       VARCHAR(200) NOT NULL DEFAULT '',
  "password"    VARCHAR(200) NOT NULL DEFAULT '',
  "profile_pic" VARCHAR(200) NOT NULL DEFAULT '',
  "bio"         TEXT         NOT NULL DEFAULT '',
  "phone"       VARCHAR(200) NOT NULL DEFAULT '',
  "created_at"  TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- ─────────────────────────────────────────────────────────────
-- iL_AccountRequests
-- ─────────────────────────────────────────────────────────────
CREATE TABLE "iL_AccountRequests" (
  "ar_id"       SERIAL PRIMARY KEY,
  "ar_name"     VARCHAR(200) NOT NULL DEFAULT '',
  "ar_email"    VARCHAR(200) NOT NULL DEFAULT '',
  "ar_phone"    VARCHAR(200) NOT NULL DEFAULT '',
  "ar_password" VARCHAR(255) NOT NULL DEFAULT '',
  "ar_role"     VARCHAR(20)  NOT NULL DEFAULT '',
  "ar_gender"   VARCHAR(20)  NOT NULL DEFAULT '',
  "ar_address"  VARCHAR(255) NOT NULL DEFAULT '',
  "ar_status"   VARCHAR(20)  NOT NULL DEFAULT 'Pending',
  "created_at"  TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

CREATE INDEX "ar_status_created_at" ON "iL_AccountRequests" ("ar_status", "created_at");
CREATE INDEX "ar_email_idx"         ON "iL_AccountRequests" ("ar_email");
