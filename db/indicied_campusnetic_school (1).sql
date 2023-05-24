-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2023 at 10:44 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `indicied_campusnetic_school`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_planner`
--

CREATE TABLE `academic_planner` (
  `planner_id` int(11) NOT NULL,
  `title` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `detail` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `objective` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `assesment` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `requirements` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `required_time` int(3) NOT NULL,
  `start` date NOT NULL,
  `subject_id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `attachment` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `academic_planner_diary`
--

CREATE TABLE `academic_planner_diary` (
  `a_p_d_id` int(11) NOT NULL,
  `diary_id` int(11) NOT NULL,
  `planner_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acadmic_year`
--

CREATE TABLE `acadmic_year` (
  `academic_year_id` int(11) NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `detail` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL,
  `order_num` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT '1 = Completed , 2 = Current , 3 = Upcoming',
  `is_closed` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `account_transection`
--

CREATE TABLE `account_transection` (
  `transection_id` int(11) NOT NULL,
  `title` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `voucher_num` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `method` int(2) NOT NULL,
  `amount` int(10) NOT NULL,
  `date` date NOT NULL,
  `coa_id` int(10) NOT NULL,
  `detail` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  `receipt_num` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `isprocessed` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `account_transection_bk`
--

CREATE TABLE `account_transection_bk` (
  `transection_id` int(11) NOT NULL,
  `title` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `voucher_num` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `method` int(2) NOT NULL,
  `amount` int(10) NOT NULL,
  `date` date NOT NULL,
  `coa_id` int(10) NOT NULL,
  `detail` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  `receipt_num` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `isprocessed` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `action_logs`
--

CREATE TABLE `action_logs` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `allownces`
--

CREATE TABLE `allownces` (
  `allownce_id` int(11) NOT NULL,
  `allownce_title` varchar(50) NOT NULL,
  `allownce_percentage` int(5) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `assessment_id` int(11) NOT NULL,
  `assessment_type` int(1) DEFAULT NULL,
  `teacher_id` int(11) NOT NULL,
  `system_class` int(11) DEFAULT NULL,
  `system_subject` int(11) DEFAULT NULL,
  `system_chapter` varchar(100) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `assessment_title` varchar(250) NOT NULL,
  `yearly_term_id` int(11) NOT NULL,
  `total_marks` int(3) NOT NULL,
  `inserted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_completed` int(1) NOT NULL DEFAULT 0,
  `is_assigned` int(1) NOT NULL DEFAULT 0,
  `mcq_questions` int(3) DEFAULT NULL,
  `true_false_questions` int(3) DEFAULT NULL,
  `fill_in_the_blanks_questions` int(3) DEFAULT NULL,
  `short_questions` int(3) DEFAULT NULL,
  `long_questions` int(3) DEFAULT NULL,
  `pictorial_questions` int(3) DEFAULT NULL,
  `match_questions` int(3) DEFAULT NULL,
  `drawing_questions` int(3) DEFAULT NULL,
  `remarks` varchar(500) NOT NULL,
  `total_attempts` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_attendance`
--

CREATE TABLE `assessment_attendance` (
  `assessment_attendance_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `inserted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `remarks` varchar(500) NOT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_audience`
--

CREATE TABLE `assessment_audience` (
  `audience_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `is_submitted` int(1) DEFAULT 0,
  `number_of_attempts` int(3) DEFAULT 0,
  `assessment_date` datetime DEFAULT NULL,
  `start_time` varchar(10) NOT NULL,
  `end_time` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_matching_solution`
--

CREATE TABLE `assessment_matching_solution` (
  `assessment_matching_solution_id` int(11) NOT NULL,
  `assessment_solution_id` int(11) NOT NULL,
  `matching_question_option_id` int(11) NOT NULL,
  `option_number` int(3) DEFAULT NULL,
  `option_marks_obtained` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_questions`
--

CREATE TABLE `assessment_questions` (
  `question_id` int(11) NOT NULL,
  `question_type_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `question_text` varchar(1000) CHARACTER SET utf8mb4 NOT NULL,
  `question_total_marks` int(3) NOT NULL,
  `right_answer_key` text DEFAULT NULL,
  `wrong_answer_marks` int(3) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_result`
--

CREATE TABLE `assessment_result` (
  `assessment_result_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `total_marks` int(3) NOT NULL,
  `obtained_marks` int(3) NOT NULL,
  `grade_id` int(1) DEFAULT NULL,
  `result_date` datetime NOT NULL DEFAULT current_timestamp(),
  `remarks` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_solution`
--

CREATE TABLE `assessment_solution` (
  `assessment_solution_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` varchar(1000) NOT NULL,
  `obtained_marks` int(2) DEFAULT NULL,
  `drawing_sheet_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 undefined , 1 present , 2  absent, 3  leave',
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_staff`
--

CREATE TABLE `attendance_staff` (
  `attend_staff_id` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 undefined , 1 present , 2  absent',
  `staff_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_staff_timing`
--

CREATE TABLE `attendance_staff_timing` (
  `attendance_staff_timing_id` int(11) NOT NULL,
  `attend_staff_id` int(11) NOT NULL,
  `check_in` varchar(255) DEFAULT NULL,
  `check_out` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_timing`
--

CREATE TABLE `attendance_timing` (
  `id` int(11) NOT NULL,
  `attendance_id` int(11) NOT NULL,
  `check_in` varchar(255) DEFAULT NULL,
  `check_out` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_type`
--

CREATE TABLE `attendance_type` (
  `attendance_id` int(10) NOT NULL,
  `school_id` int(10) NOT NULL,
  `login_type` tinyint(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bank_account`
--

CREATE TABLE `bank_account` (
  `bank_account_id` int(11) NOT NULL,
  `bank_name` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bank_address` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `branch_name` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `branch_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `account_title` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `account_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `account_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `iban_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `coa_fee_cash_receipt` int(11) NOT NULL,
  `coa_fee_check_receipt` int(11) NOT NULL,
  `coa_cash_receipt` int(11) NOT NULL,
  `coa_check_receipt` int(11) NOT NULL,
  `coa_cash_payment` int(11) NOT NULL,
  `coa_check_payment` int(11) NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bank_cheque_books`
--

CREATE TABLE `bank_cheque_books` (
  `b_c_b_id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `batch_number` varchar(100) NOT NULL,
  `start_cheque_number` int(10) NOT NULL,
  `end_cheque_number` int(10) NOT NULL,
  `current_cheque_number` int(10) DEFAULT 0,
  `status` int(1) NOT NULL,
  `inserted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bank_payment`
--

CREATE TABLE `bank_payment` (
  `bank_payment_id` int(11) NOT NULL,
  `voucher_number` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `voucher_date` date NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `debit_coa_id` int(11) DEFAULT NULL,
  `status` tinyint(2) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `date_posted` date NOT NULL,
  `date_submitted` date NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `voucher_type` int(1) NOT NULL COMMENT '1 = Supplier Paymet , 2 = Other Payment',
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bank_payment_details`
--

CREATE TABLE `bank_payment_details` (
  `bank_payment_details_id` int(11) NOT NULL,
  `bank_payment_id` int(11) NOT NULL,
  `purchase_voucher_id` int(10) DEFAULT NULL,
  `cheque_number` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bank_from_id` int(11) NOT NULL,
  `amount` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bank_receipt`
--

CREATE TABLE `bank_receipt` (
  `bank_receipt_id` int(11) NOT NULL,
  `voucher_number` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `voucher_date` date NOT NULL,
  `depositor_id` int(11) NOT NULL,
  `credit_coa_id` int(11) DEFAULT NULL,
  `status` tinyint(2) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `date_posted` date NOT NULL,
  `date_submitted` date NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `voucher_type` int(2) NOT NULL COMMENT '1 = Depositer , 2 = Other'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bank_receipt_details`
--

CREATE TABLE `bank_receipt_details` (
  `bank_receipt_details_id` int(11) NOT NULL,
  `bank_receipt_id` int(11) NOT NULL,
  `method` tinyint(2) NOT NULL,
  `deposit_slip_number` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `deposit_bank_id` int(11) NOT NULL,
  `amount` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `bookId` varchar(100) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `book_title` varchar(200) NOT NULL,
  `isbn_no` varchar(100) DEFAULT NULL,
  `shelf_no` varchar(50) DEFAULT NULL,
  `edition` varchar(100) DEFAULT NULL,
  `volume` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `book_type` enum('1','2') NOT NULL DEFAULT '1',
  `ebook_file` varchar(200) DEFAULT NULL,
  `book_cover` varchar(200) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `add_book_date` datetime NOT NULL DEFAULT current_timestamp(),
  `book_added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `book_issue`
--

CREATE TABLE `book_issue` (
  `book_issue_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `book_issue_date` datetime NOT NULL,
  `book_return_date` datetime NOT NULL,
  `actual_return_date` datetime DEFAULT NULL,
  `fine` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `issued_by` int(11) NOT NULL,
  `returned_by` int(11) DEFAULT NULL,
  `inserted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `book_reserve_request`
--

CREATE TABLE `book_reserve_request` (
  `brr_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_login_detail_id` int(11) NOT NULL,
  `book_collect_date` date NOT NULL,
  `status` enum('0','1','2') DEFAULT '0',
  `inserted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_monthly_chalan`
--

CREATE TABLE `bulk_monthly_chalan` (
  `b_m_c_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `school_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `c_c_f_id` int(11) NOT NULL,
  `fee_month` int(2) NOT NULL,
  `fee_year` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_request`
--

CREATE TABLE `bulk_request` (
  `bulk_req_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `pro_section_id` int(11) NOT NULL,
  `pro_academic_year_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `school_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `c_c_f_id` int(11) NOT NULL,
  `confirmed_by` int(11) NOT NULL,
  `confirm_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cash_payment`
--

CREATE TABLE `cash_payment` (
  `cash_payment_id` int(11) NOT NULL,
  `voucher_number` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `voucher_date` date NOT NULL,
  `status` tinyint(2) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `date_posted` date NOT NULL,
  `date_submitted` date NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cash_payment_details`
--

CREATE TABLE `cash_payment_details` (
  `cash_payment_details_id` int(11) NOT NULL,
  `cash_payment_id` int(11) NOT NULL,
  `expense_coa_id` int(11) NOT NULL,
  `description` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cash_receipt`
--

CREATE TABLE `cash_receipt` (
  `cash_receipt_id` int(11) NOT NULL,
  `voucher_number` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `voucher_date` date NOT NULL,
  `depositor_id` int(11) NOT NULL,
  `credit_coa_id` int(11) DEFAULT NULL,
  `status` tinyint(2) NOT NULL,
  `voucher_type` int(2) NOT NULL COMMENT '1 = Depositer , 2 = Other',
  `posted_by` int(11) NOT NULL,
  `date_posted` date NOT NULL,
  `date_submitted` date NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cash_receipt_details`
--

CREATE TABLE `cash_receipt_details` (
  `cash_receipt_details_id` int(11) NOT NULL,
  `cash_receipt_id` int(11) NOT NULL,
  `description` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cash_voucher_settings`
--

CREATE TABLE `cash_voucher_settings` (
  `cash_voucher_settings_id` int(11) NOT NULL,
  `cash_voucher_type` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `coa_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `chalan_archive`
--

CREATE TABLE `chalan_archive` (
  `chalan_archive_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `month_year` varchar(20) NOT NULL,
  `chalan_status` int(1) NOT NULL,
  `chalan_generated_date` date NOT NULL,
  `chalan_issue_date` date NOT NULL,
  `chalan_deleted_by` int(11) NOT NULL,
  `chalan_deleted_date` datetime NOT NULL DEFAULT current_timestamp(),
  `chalan_deleted_type` int(1) NOT NULL COMMENT '1 = Classwise , 2 = Student Wise'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chalan_settings`
--

CREATE TABLE `chalan_settings` (
  `chalan_setting_id` int(10) UNSIGNED NOT NULL,
  `school_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bank_details` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `terms` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `coa_id` int(11) NOT NULL,
  `account_number` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `account_head` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `account_type` int(2) NOT NULL,
  `school_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_account_types`
--

CREATE TABLE `chart_of_account_types` (
  `coa_type_id` int(11) NOT NULL,
  `coa_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `coa_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `chat_relation`
--

CREATE TABLE `chat_relation` (
  `chat_rel_id` int(11) NOT NULL,
  `send_id` int(11) NOT NULL,
  `rec_id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `chat_id` varchar(255) NOT NULL,
  `row_datetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `row_status` varchar(255) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `circular`
--

CREATE TABLE `circular` (
  `circular_id` int(11) NOT NULL,
  `circular_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `circular` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `section_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `create_timestamp` date NOT NULL,
  `attachment` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `sms_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `circular_staff`
--

CREATE TABLE `circular_staff` (
  `circular_staff_id` int(11) NOT NULL,
  `circular_title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `circular` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `staff_id` int(11) NOT NULL,
  `circular_date` date NOT NULL,
  `attachment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `sms_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `city_location`
--

CREATE TABLE `city_location` (
  `location_id` int(11) NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `city_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `city_location_archive`
--

CREATE TABLE `city_location_archive` (
  `location_archive_id` int(11) NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `city_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `location_id` int(11) NOT NULL,
  `archive_date` datetime NOT NULL DEFAULT current_timestamp(),
  `archive_by` int(11) NOT NULL,
  `action` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name_numeric` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `teacher_id` int(11) NOT NULL,
  `order_by` int(3) NOT NULL,
  `strength` int(5) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `departments_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_chalan_discount`
--

CREATE TABLE `class_chalan_discount` (
  `c_c_dis_id` int(11) NOT NULL,
  `c_c_f_id` int(11) NOT NULL,
  `order_num` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `value` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_chalan_fee`
--

CREATE TABLE `class_chalan_fee` (
  `c_c_fee_id` int(11) NOT NULL,
  `c_c_f_id` int(11) NOT NULL,
  `fee_type_id` int(11) NOT NULL,
  `order_num` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_chalan_form`
--

CREATE TABLE `class_chalan_form` (
  `c_c_f_id` int(11) NOT NULL,
  `title` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `detail` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `type` int(2) NOT NULL,
  `school_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `due_days` int(2) NOT NULL,
  `late_fee_fine` int(11) DEFAULT NULL,
  `late_fee_type` enum('1','2') DEFAULT NULL,
  `parent_c_c_f_id` int(11) NOT NULL,
  `previous_c_c_f_id` int(11) NOT NULL,
  `version` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `archive_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_message`
--

CREATE TABLE `class_message` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_routine_id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `class_routine`
--

CREATE TABLE `class_routine` (
  `class_routine_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `day` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `period_no` int(3) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `c_rout_sett_id` int(11) NOT NULL,
  `subject_components` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `duration` int(3) NOT NULL,
  `period_start_time` time NOT NULL,
  `period_end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_routine_settings`
--

CREATE TABLE `class_routine_settings` (
  `c_rout_sett_id` int(10) NOT NULL,
  `no_of_periods` int(3) NOT NULL,
  `period_duration` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `start_time` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `end_time` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `assembly_duration` int(3) NOT NULL,
  `break_duration` int(3) NOT NULL DEFAULT 0,
  `break_after_period` int(3) NOT NULL DEFAULT 0,
  `break_duration_after_every_period` int(2) DEFAULT NULL,
  `school_id` int(10) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `yearly_terms_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `period_duration_type` tinyint(1) NOT NULL COMMENT '1=same,2=multiple'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_section`
--

CREATE TABLE `class_section` (
  `section_id` int(11) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(1) NOT NULL,
  `school_id` int(11) NOT NULL,
  `order_num` int(3) DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  `discription` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deductions`
--

CREATE TABLE `deductions` (
  `deduction_id` int(11) NOT NULL,
  `deduction_title` varchar(50) NOT NULL,
  `deduction_percentage` int(5) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `credit_coa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `departments_id` int(11) NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `discription` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `department_head` int(11) NOT NULL,
  `order_num` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deposit`
--

CREATE TABLE `deposit` (
  `deposit_id` int(11) NOT NULL,
  `depositor_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `deposit_date` date NOT NULL,
  `status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `depositor`
--

CREATE TABLE `depositor` (
  `depositor_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `name` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `contact_no` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `location_id` int(11) NOT NULL,
  `address` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nationality` int(11) NOT NULL,
  `attachment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_type` tinyint(2) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `coa_cash_deposit` int(11) NOT NULL,
  `coa_bank_deposit` int(11) NOT NULL,
  `coa_capital_asset` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deposit_bk`
--

CREATE TABLE `deposit_bk` (
  `deposit_id` int(11) NOT NULL,
  `depositor_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `deposit_date` date NOT NULL,
  `status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `designation_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `is_teacher` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diary`
--

CREATE TABLE `diary` (
  `diary_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `assign_date` date NOT NULL,
  `due_date` date NOT NULL,
  `task` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL,
  `is_assigned` tinyint(1) NOT NULL,
  `is_submitted` tinyint(1) NOT NULL DEFAULT 0,
  `submission_date` datetime NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `admin_approvel` enum('0','1') DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `diary_attachments`
--

CREATE TABLE `diary_attachments` (
  `id` int(11) NOT NULL,
  `diary_student_id` int(11) NOT NULL,
  `answer_attachment` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `diary_audio`
--

CREATE TABLE `diary_audio` (
  `dairy_audio_id` int(11) NOT NULL,
  `diary_id` int(11) NOT NULL,
  `audio` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `diary_student`
--

CREATE TABLE `diary_student` (
  `diary_student_id` int(11) NOT NULL,
  `diary_id` int(11) NOT NULL DEFAULT 0,
  `student_id` int(11) NOT NULL DEFAULT 0,
  `school_id` int(11) NOT NULL DEFAULT 0,
  `is_submitted` tinyint(2) NOT NULL,
  `submission_date` datetime NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `is_viewed` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `answer_attachment` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `answer_text` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_list`
--

CREATE TABLE `discount_list` (
  `discount_id` int(11) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `percent` int(3) NOT NULL,
  `status` int(1) NOT NULL,
  `school_id` int(11) NOT NULL,
  `issue_dr_coa_id` int(11) NOT NULL,
  `issue_cr_coa_id` int(11) NOT NULL,
  `receive_dr_coa_id` int(11) NOT NULL,
  `receive_cr_coa_id` int(11) NOT NULL,
  `cancel_dr_coa_id` int(11) NOT NULL,
  `cancel_cr_coa_id` int(11) NOT NULL,
  `generate_dr_coa_id` int(11) NOT NULL,
  `generate_cr_coa_id` int(11) NOT NULL,
  `is_percentage` int(1) NOT NULL,
  `fee_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_layout_settings`
--

CREATE TABLE `email_layout_settings` (
  `email_layout_id` int(11) NOT NULL,
  `school_name` varchar(80) NOT NULL,
  `address` varchar(150) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `terms` text NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `email_temp_id` int(11) NOT NULL,
  `email_title` varchar(80) NOT NULL,
  `email_subject` varchar(100) NOT NULL,
  `email_content` text NOT NULL,
  `email_template_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_factors`
--

CREATE TABLE `evaluation_factors` (
  `id` int(2) NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_ratings`
--

CREATE TABLE `evaluation_ratings` (
  `misc_id` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `detail` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events_annoucments`
--

CREATE TABLE `events_annoucments` (
  `event_id` int(11) NOT NULL,
  `event_title` varchar(255) DEFAULT NULL,
  `event_details` text DEFAULT NULL,
  `event_start_date` date DEFAULT NULL,
  `event_end_date` date DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `active_inactive` enum('0','1') DEFAULT NULL,
  `event_status` set('0','1','2') NOT NULL DEFAULT '0' COMMENT '0 = Not Assign , 1 = Assigned, 2 = Expire'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events_annoucments_details`
--

CREATE TABLE `events_annoucments_details` (
  `event_detail_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `response_text` text DEFAULT NULL,
  `response_status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0  = Nothing,1 = Yes,2 = No',
  `response_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `exam_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `comment` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `end_date` date NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `yearly_terms_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_routine`
--

CREATE TABLE `exam_routine` (
  `exam_routine_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `time_start` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time_end` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `exam_date` date NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `total_marks` int(11) NOT NULL,
  `is_submitted` tinyint(1) NOT NULL,
  `date_submitted` datetime NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `is_approved` tinyint(1) NOT NULL,
  `approved_by` int(11) NOT NULL,
  `date_approved` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_weightage`
--

CREATE TABLE `exam_weightage` (
  `weightage_id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `yearly_term_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `exam_percentage` varchar(100) DEFAULT NULL,
  `assessment_percentage` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fee_types`
--

CREATE TABLE `fee_types` (
  `fee_type_id` int(11) NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `order_num` int(2) NOT NULL,
  `status` int(1) NOT NULL,
  `school_id` int(11) NOT NULL,
  `issue_dr_coa_id` int(11) NOT NULL,
  `issue_cr_coa_id` int(11) NOT NULL,
  `receive_dr_coa_id` int(11) NOT NULL,
  `receive_cr_coa_id` int(11) NOT NULL,
  `cancel_dr_coa_id` int(11) NOT NULL,
  `cancel_cr_coa_id` int(11) NOT NULL,
  `generate_dr_coa_id` int(11) NOT NULL,
  `generate_cr_coa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `financial_reports_settings`
--

CREATE TABLE `financial_reports_settings` (
  `fin_rep_setting_id` int(11) NOT NULL,
  `settings_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `coa_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_reports_settings_bk`
--

CREATE TABLE `financial_reports_settings_bk` (
  `fin_rep_setting_id` int(11) NOT NULL,
  `income_stmt_sales` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `income_stmt_income` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `income_stmt_expense` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `balance_sheet_assets` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `balance_sheet_liabilities` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `balance_sheet_capital` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `coa_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `grade_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `grade_point` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mark_from` int(11) NOT NULL,
  `mark_upto` int(11) NOT NULL,
  `comment` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(2) NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_rights`
--

CREATE TABLE `group_rights` (
  `group_rights_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holiday`
--

CREATE TABLE `holiday` (
  `holiday_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `sms_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `carrer_level` varchar(30) NOT NULL,
  `qualifications` varchar(30) NOT NULL,
  `experience` varchar(50) NOT NULL,
  `job_type` int(11) NOT NULL,
  `job_location` varchar(50) NOT NULL,
  `job_description` text NOT NULL,
  `job_posting_date` date NOT NULL,
  `job_end_date` date NOT NULL,
  `job_status` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `job_application_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mob_num` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `address` text NOT NULL,
  `attachment` varchar(50) NOT NULL,
  `school_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entry`
--

CREATE TABLE `journal_entry` (
  `journal_entry_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `entry_date` datetime NOT NULL,
  `detail` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `debit` int(11) NOT NULL,
  `credit` int(11) NOT NULL,
  `entry_type` tinyint(2) NOT NULL COMMENT '1 - fee issue , 2 - fee cancel , 3 - fee recieve , 4 - Late Fee Fine , 11 - add bank-receipt-voucher , 12 - update bank-receipt-voucher, 13 - add bank-payment-voucher, 14 - add cash-payment-voucher, 15 = Add Cash Reciept Voucher, 16 = Update Cash Reciept Voucher , 17 - update cash-payment-voucher, 18 = Add Purchase Voucher , 19 = Update Purchase Voucher, 20 = Tax Entry, 21 = Journal Entry , 22 = salary posting',
  `type_id` int(11) NOT NULL,
  `coa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `journal_voucher`
--

CREATE TABLE `journal_voucher` (
  `journal_voucher_id` int(11) NOT NULL,
  `voucher_number` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `voucher_date` date NOT NULL,
  `status` tinyint(2) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `date_posted` date NOT NULL,
  `date_submitted` date NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `journal_voucher_details`
--

CREATE TABLE `journal_voucher_details` (
  `journal_voucher_details_id` int(11) NOT NULL,
  `journal_voucher_id` int(11) NOT NULL,
  `debit_coa_id` int(5) NOT NULL,
  `credit_coa_id` int(5) NOT NULL,
  `amount` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `attachment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leave_category`
--

CREATE TABLE `leave_category` (
  `leave_category_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leave_staff`
--

CREATE TABLE `leave_staff` (
  `leave_staff_id` int(11) NOT NULL,
  `leave_category_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `start_date` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `end_date` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `reason` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `request_date` date NOT NULL,
  `process_date` date NOT NULL,
  `process_by` int(11) NOT NULL,
  `requested_by` int(11) NOT NULL,
  `proof_doc` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `yearly_terms_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leave_student`
--

CREATE TABLE `leave_student` (
  `request_id` int(11) NOT NULL,
  `leave_category_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `start_date` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `end_date` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `reason` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `request_date` date NOT NULL,
  `process_date` date NOT NULL,
  `approved_upto_date` date DEFAULT NULL,
  `final_end_date` date DEFAULT NULL,
  `proof_doc` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `process_by` int(11) NOT NULL,
  `requested_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lecture_notes`
--

CREATE TABLE `lecture_notes` (
  `notes_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `notes_title` varchar(250) NOT NULL,
  `inserted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_assigned` int(1) NOT NULL DEFAULT 0,
  `description` varchar(500) NOT NULL,
  `remarks` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lecture_notes_audience`
--

CREATE TABLE `lecture_notes_audience` (
  `audience_id` int(11) NOT NULL,
  `notes_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lecture_notes_documents`
--

CREATE TABLE `lecture_notes_documents` (
  `notes_document_id` int(11) NOT NULL,
  `notes_id` int(11) NOT NULL,
  `document_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `library_members`
--

CREATE TABLE `library_members` (
  `library_member_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `library_membership_id` varchar(30) NOT NULL,
  `membership_fee` int(11) NOT NULL,
  `inserted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `marks_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `attendance` int(11) NOT NULL DEFAULT 0,
  `comment` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marks_components`
--

CREATE TABLE `marks_components` (
  `marks_components_id` int(11) NOT NULL,
  `marks_id` int(11) NOT NULL,
  `subject_component_id` int(11) NOT NULL,
  `marks_obtained` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `matching_question_option`
--

CREATE TABLE `matching_question_option` (
  `matching_question_option_id` int(3) NOT NULL,
  `question_id` int(3) NOT NULL,
  `option_number` varchar(3) DEFAULT NULL,
  `left_side_text` varchar(50) DEFAULT NULL,
  `right_side_text` varchar(50) DEFAULT NULL,
  `right_answer` varchar(4) DEFAULT NULL,
  `option_marks` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `sender_type` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `body` varchar(255) NOT NULL,
  `InsertedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT NULL,
  `IsViewed` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `messages_id` int(10) NOT NULL,
  `messages` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `student_id` int(10) NOT NULL,
  `teacher_id` int(10) NOT NULL,
  `messages_type` int(2) NOT NULL COMMENT '0=teacher 1=parent',
  `message_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_message_id` int(11) NOT NULL,
  `previous_message_id` int(11) NOT NULL,
  `subject_id` int(10) NOT NULL,
  `is_viewed` int(2) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `sent_by` int(11) NOT NULL DEFAULT 0 COMMENT 'user_login_detail_id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `miscellaneous_settings_bk`
--

CREATE TABLE `miscellaneous_settings_bk` (
  `misc_id` int(11) NOT NULL,
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `detail` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `misc_challan_coa_settings`
--

CREATE TABLE `misc_challan_coa_settings` (
  `misc_settings_id` int(11) NOT NULL,
  `type` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `school_id` int(11) NOT NULL,
  `issue_dr_coa_id` int(11) NOT NULL,
  `issue_cr_coa_id` int(11) NOT NULL,
  `receive_dr_coa_id` int(11) NOT NULL,
  `receive_cr_coa_id` int(11) NOT NULL,
  `cancel_dr_coa_id` int(11) NOT NULL,
  `cancel_cr_coa_id` int(11) NOT NULL,
  `generate_dr_coa_id` int(11) NOT NULL,
  `generate_cr_coa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mobile_device_id`
--

CREATE TABLE `mobile_device_id` (
  `id` int(11) NOT NULL,
  `mobile_device` varchar(255) NOT NULL,
  `user_login_id` varchar(255) NOT NULL,
  `islogin` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `noticeboard`
--

CREATE TABLE `noticeboard` (
  `notice_id` int(11) NOT NULL,
  `notice_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `notice` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `create_timestamp` date NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `type` int(1) NOT NULL,
  `is_active` int(1) NOT NULL,
  `sms_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_consumer`
--

CREATE TABLE `payment_consumer` (
  `id` int(11) NOT NULL,
  `consumer_id` int(11) NOT NULL,
  `challan_id` varchar(100) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `TranFee` varchar(6) DEFAULT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `InvoiceNumber` varchar(100) DEFAULT NULL,
  `PaymentCode` varchar(50) DEFAULT NULL,
  `PaymentMethod` varchar(60) DEFAULT NULL,
  `IsFeeApplied` varchar(10) DEFAULT NULL,
  `InvoiceAmount` varchar(10) DEFAULT NULL,
  `IsPaid` int(1) DEFAULT NULL,
  `Inserted_at` datetime DEFAULT NULL,
  `Updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

CREATE TABLE `policies` (
  `policies_id` int(11) NOT NULL,
  `title` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `document_num` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `version_num` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `approved_by` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `approval_date` date NOT NULL,
  `last_update_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `detail` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL,
  `policy_category_id` int(11) DEFAULT NULL,
  `is_active` int(1) NOT NULL,
  `staff_p` int(1) NOT NULL,
  `student_p` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `policy_category`
--

CREATE TABLE `policy_category` (
  `policy_category_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_voucher`
--

CREATE TABLE `purchase_voucher` (
  `purchase_voucher_id` int(11) NOT NULL,
  `voucher_number` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `voucher_date` date NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `date_posted` date NOT NULL,
  `date_submitted` date NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `payment_status` int(1) NOT NULL DEFAULT 0 COMMENT '0 - Unpaid , 1 = Paid',
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_voucher_details`
--

CREATE TABLE `purchase_voucher_details` (
  `purchase_voucher_details_id` int(11) NOT NULL,
  `purchase_voucher_id` int(11) NOT NULL,
  `bill_number` varchar(50) DEFAULT NULL,
  `description` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `debit_coa_id` int(5) NOT NULL,
  `attachment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `question_option_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_number` varchar(10) DEFAULT NULL,
  `option_text` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `question_types`
--

CREATE TABLE `question_types` (
  `question_type_id` int(11) NOT NULL,
  `question_type_description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `salary_voucher_settings`
--

CREATE TABLE `salary_voucher_settings` (
  `salary_voucher_setting_id` int(11) NOT NULL,
  `salary_type` varchar(30) NOT NULL,
  `coa_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `school`
--

CREATE TABLE `school` (
  `school_id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `folder_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `contact_person` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `designation` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slogan` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `detail` varchar(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `city_id` int(13) NOT NULL,
  `school_regist_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sys_sch_id` int(11) NOT NULL,
  `attendance_method` int(11) DEFAULT NULL,
  `diary_approval` int(11) DEFAULT NULL COMMENT '1=teacher,2=Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `school_archive`
--

CREATE TABLE `school_archive` (
  `school_archive_id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `folder_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `contact_person` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `designation` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slogan` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `detail` varchar(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `city_id` int(13) NOT NULL,
  `school_regist_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sys_sch_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `archive_date` datetime NOT NULL DEFAULT current_timestamp(),
  `archive_by` int(11) NOT NULL,
  `action` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `school_coa`
--

CREATE TABLE `school_coa` (
  `school_coa_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `coa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `school_count`
--

CREATE TABLE `school_count` (
  `school_count_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `chalan_form_number` int(11) NOT NULL,
  `bank_receipt_number` int(11) NOT NULL,
  `cash_receipt_number` int(11) NOT NULL,
  `bank_payment_number` int(11) NOT NULL,
  `cash_payment_number` int(11) NOT NULL,
  `journal_voucher_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `school_discount_list`
--

CREATE TABLE `school_discount_list` (
  `school_discount_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `school_fee_types`
--

CREATE TABLE `school_fee_types` (
  `school_fee_type_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `fee_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `school_notifications`
--

CREATE TABLE `school_notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` varchar(30) NOT NULL,
  `url` varchar(100) NOT NULL,
  `inserted_at` datetime DEFAULT NULL,
  `text` varchar(100) NOT NULL,
  `is_viewed` int(1) NOT NULL DEFAULT 0,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sch_admission_inquiries`
--

CREATE TABLE `sch_admission_inquiries` (
  `s_a_i_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `father_name` varchar(30) NOT NULL,
  `class_id` int(11) NOT NULL,
  `mobile_no` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `address` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `school_id` int(11) NOT NULL,
  `s_a_i_status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sch_general_inquiries`
--

CREATE TABLE `sch_general_inquiries` (
  `s_g_i_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `mobile_no` varchar(20) NOT NULL,
  `inquiry_type` int(11) NOT NULL,
  `inquiry_description` varchar(1000) NOT NULL,
  `school_id` int(11) NOT NULL,
  `s_g_i_status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `session_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `class_id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `session_bk`
--

CREATE TABLE `session_bk` (
  `session_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `class_id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sms_count`
--

CREATE TABLE `sms_count` (
  `id` int(3) NOT NULL,
  `sms_count` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_settings`
--

CREATE TABLE `sms_settings` (
  `sms_f_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `sms_section` int(2) NOT NULL COMMENT '1 = challan recieve , 2 = assessment assign , 3 = diary assign , 4 = challan issue , 5 = student attednace , 6 = general inquiry , 7 = job section , 8 = fee recovery remonder , 9 = students creds send , 10 = circulars , 11 = staff circulars , 12 = manage student leave , 13 = manage staff leave , 14 = staff attendance ,  15 = school vacations , 16 = birthday wishes',
  `sms_status` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) DEFAULT NULL,
  `email_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sms_templates`
--

CREATE TABLE `sms_templates` (
  `sms_temp_id` int(11) NOT NULL,
  `sms_title` varchar(80) NOT NULL,
  `sms_content` varchar(500) NOT NULL,
  `sms_template_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `system_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `user_login_detail_id` int(11) NOT NULL,
  `staff_login_detail_id` int(11) NOT NULL,
  `id_no` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `religion` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `blood_group` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_address` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permanent_address` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `phone_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emergency_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `staff_image` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `employee_code` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `periods_per_day` int(11) NOT NULL,
  `periods_per_week` int(11) NOT NULL,
  `barcode_image` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `id_file` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `id_type` tinyint(2) NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nationality` int(11) NOT NULL,
  `joining_date` date NOT NULL,
  `experience_month` int(2) NOT NULL,
  `experience_year` int(3) NOT NULL,
  `hours_per_day` tinyint(3) NOT NULL,
  `hours_per_week` tinyint(3) NOT NULL,
  `hours_per_month` tinyint(3) NOT NULL,
  `regular_daily_rate` int(11) NOT NULL,
  `regular_hourly_rate` int(11) NOT NULL,
  `overtime_daily_rate` int(11) NOT NULL,
  `overtime_hourly_rate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_evaluation`
--

CREATE TABLE `staff_evaluation` (
  `staff_eval_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `remarks` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `answers` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL,
  `evaluation_date` date NOT NULL,
  `attachment` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_evaluation_answers`
--

CREATE TABLE `staff_evaluation_answers` (
  `staf_eval_ans_id` int(11) NOT NULL,
  `staff_eval_id` int(11) NOT NULL,
  `staff_eval_form_id` int(11) NOT NULL,
  `answers` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `remarks` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_evaluation_questions`
--

CREATE TABLE `staff_evaluation_questions` (
  `staff_eval_form_id` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `factor` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_in_out`
--

CREATE TABLE `staff_in_out` (
  `s_io_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `io_date` date NOT NULL,
  `io_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_leave_settings`
--

CREATE TABLE `staff_leave_settings` (
  `staff_leave_settings_id` int(11) NOT NULL,
  `leave_category_id` int(11) NOT NULL,
  `monthly_limit` tinyint(2) NOT NULL,
  `yearly_limit` tinyint(2) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_payroll_settings`
--

CREATE TABLE `staff_payroll_settings` (
  `s_p_s_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `gross_salary` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staff_salary_allownces`
--

CREATE TABLE `staff_salary_allownces` (
  `s_s_a_id` int(11) NOT NULL,
  `s_s_s_id` int(11) NOT NULL,
  `allownce_id` int(5) NOT NULL,
  `allownce_amount` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staff_salary_deductions`
--

CREATE TABLE `staff_salary_deductions` (
  `s_s_d_id` int(11) NOT NULL,
  `s_s_s_id` int(11) NOT NULL,
  `deduction_id` int(5) NOT NULL,
  `deduction_amount` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staff_salary_slip`
--

CREATE TABLE `staff_salary_slip` (
  `s_s_s_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `basic_salary` float NOT NULL,
  `earned_salary` float NOT NULL,
  `total_days` int(5) NOT NULL,
  `house_rent_allownce` float NOT NULL,
  `medical_allownce` float NOT NULL,
  `income_tax_deduction` float NOT NULL,
  `net_salary` float NOT NULL,
  `month` varchar(10) NOT NULL,
  `year` varchar(10) NOT NULL,
  `school_id` int(5) NOT NULL,
  `is_paid` enum('0','1') NOT NULL DEFAULT '0',
  `date_generated` date NOT NULL,
  `generatd_by` int(5) NOT NULL,
  `date_paid` date DEFAULT NULL,
  `paid_by` int(5) DEFAULT NULL,
  `is_posted` tinyint(4) NOT NULL DEFAULT 0,
  `posted_by` int(5) DEFAULT NULL,
  `date_posted` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `coa_id` int(11) NOT NULL,
  `purchase_voucher_details_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `gr_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `religion` int(2) DEFAULT NULL,
  `address` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `roll` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `student_status` int(2) DEFAULT NULL,
  `previou_school` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `std_activities` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `form_num` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adm_date` date DEFAULT NULL,
  `id_no` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mob_num` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emg_num` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bd_group` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `disability` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_file` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `reg_num` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `system_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `barcode_image` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `p_address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `system_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `adm_term_id` int(11) DEFAULT NULL,
  `academic_year_id` int(11) DEFAULT NULL,
  `pro_academic_year_id` int(11) DEFAULT NULL,
  `pro_section_id` int(11) DEFAULT NULL,
  `bulk_req_id` int(11) DEFAULT NULL,
  `is_readmission` int(1) DEFAULT NULL,
  `is_transfered` int(1) DEFAULT NULL,
  `is_installment` int(1) DEFAULT NULL,
  `id_type` tinyint(2) DEFAULT NULL,
  `nationality` int(11) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `date_confirmed` datetime DEFAULT NULL,
  `confirmed_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(2) DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `student_category_id` int(11) DEFAULT NULL,
  `is_login_created` int(2) NOT NULL DEFAULT 0 COMMENT '1=login created ,0=not created',
  `user_login_detail_id` int(11) DEFAULT NULL,
  `std_withdarwal_reason` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_archive`
--

CREATE TABLE `student_archive` (
  `student_archive_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `religion` int(2) NOT NULL,
  `address` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `section_id` int(11) NOT NULL,
  `roll` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `student_status` int(2) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `image` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `form_num` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `adm_date` date NOT NULL,
  `id_no` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mob_num` int(20) NOT NULL,
  `emg_num` int(20) NOT NULL,
  `bd_group` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `disability` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `id_file` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `reg_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `system_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `location_id` int(11) NOT NULL,
  `barcode_image` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `p_address` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `system_date` datetime NOT NULL,
  `student_id` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `adm_term_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `pro_academic_year_id` int(11) NOT NULL,
  `pro_section_id` int(11) NOT NULL,
  `bulk_req_id` int(11) NOT NULL,
  `is_readmission` int(1) NOT NULL,
  `is_transfered` int(1) NOT NULL,
  `is_installment` int(1) NOT NULL,
  `id_type` tinyint(2) NOT NULL,
  `nationality` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `added_by` int(11) NOT NULL,
  `date_confirmed` datetime NOT NULL,
  `confirmed_by` int(11) NOT NULL,
  `is_deleted` tinyint(2) NOT NULL,
  `date_deleted` datetime NOT NULL,
  `deleted_by` int(11) NOT NULL,
  `student_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_category`
--

CREATE TABLE `student_category` (
  `student_category_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_chalan_detail`
--

CREATE TABLE `student_chalan_detail` (
  `s_c_d_id` int(11) NOT NULL,
  `s_c_f_id` int(11) NOT NULL,
  `fee_type_title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  `type_id` int(11) NOT NULL,
  `related_s_c_d_id` int(11) NOT NULL DEFAULT 0,
  `coa_id` int(11) NOT NULL,
  `issue_dr_coa_id` int(11) NOT NULL,
  `issue_cr_coa_id` int(11) NOT NULL,
  `receive_dr_coa_id` int(11) NOT NULL,
  `receive_cr_coa_id` int(11) NOT NULL,
  `cancel_dr_coa_id` int(11) NOT NULL,
  `cancel_cr_coa_id` int(11) NOT NULL,
  `generate_dr_coa_id` int(11) NOT NULL,
  `generate_cr_coa_id` int(11) NOT NULL,
  `fee_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_chalan_form`
--

CREATE TABLE `student_chalan_form` (
  `s_c_f_id` int(11) NOT NULL,
  `c_c_f_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `issue_date` datetime NOT NULL,
  `issued_by` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1 = Generate, 4 = Issue , 5 = Recieve',
  `payment_date` date NOT NULL,
  `fee_month_year` date NOT NULL,
  `generated_by` int(11) NOT NULL,
  `generation_date` datetime NOT NULL,
  `approval_date` datetime NOT NULL,
  `approved_by` int(11) NOT NULL,
  `received_date` datetime NOT NULL,
  `received_by` int(11) NOT NULL,
  `chalan_form_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `actual_amount` int(11) NOT NULL,
  `received_amount` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `father_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `bar_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_logo` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_address` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_terms` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_bank_detail` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `section_id` int(11) NOT NULL,
  `section` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `department` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `form_type` int(11) NOT NULL,
  `due_days` int(11) NOT NULL,
  `comment` varchar(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `is_bulk` int(1) NOT NULL,
  `bulk_req_id` int(11) NOT NULL,
  `roll` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `is_processed` int(1) NOT NULL,
  `arrears` int(11) NOT NULL,
  `arrears_status` int(1) NOT NULL,
  `is_cancelled` tinyint(2) NOT NULL,
  `cancelled_by` int(11) NOT NULL,
  `cancel_date` datetime NOT NULL,
  `reg_num` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mob_num` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `system_id` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `student_status` int(2) NOT NULL,
  `id_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `s_c_f_month` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `s_c_f_year` int(5) NOT NULL,
  `latefee_fine_status` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_evaluation`
--

CREATE TABLE `student_evaluation` (
  `stud_eval_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `remarks` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `answers` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `attachment` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `evaluated_by` int(2) NOT NULL COMMENT '1=admin, 2=teacher',
  `who_evaluated` int(11) NOT NULL COMMENT 'user_id of teacher or admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_evaluation_answers`
--

CREATE TABLE `student_evaluation_answers` (
  `std_eval_ans_id` int(11) NOT NULL,
  `stud_eval_id` int(11) NOT NULL,
  `eval_id` int(11) NOT NULL,
  `answers` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `remarks` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_evaluation_questions`
--

CREATE TABLE `student_evaluation_questions` (
  `eval_id` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `school_id` int(11) NOT NULL,
  `type` int(2) NOT NULL,
  `factor` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_fee_settings`
--

CREATE TABLE `student_fee_settings` (
  `fee_settings_id` int(11) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `discount_amount_type` enum('1','0') DEFAULT NULL COMMENT '''1 = Value AND 0 = Percentage'' ',
  `month` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `year` int(5) NOT NULL,
  `academic_year_id` int(11) DEFAULT NULL,
  `fee_type` tinyint(2) NOT NULL,
  `fee_type_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `settings_type` tinyint(2) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `comments` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_bulk` tinyint(2) NOT NULL,
  `std_m_fee_settings_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_monthly_fee_settings`
--

CREATE TABLE `student_monthly_fee_settings` (
  `std_m_fee_settings_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `fee_month` tinyint(2) NOT NULL,
  `fee_year` int(4) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `generated_by` int(11) NOT NULL,
  `generation_date` datetime NOT NULL,
  `approved_by` int(11) NOT NULL,
  `approval_date` datetime NOT NULL,
  `issued_by` int(11) NOT NULL,
  `issue_date` datetime NOT NULL,
  `comments` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `b_m_c_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_m_discount`
--

CREATE TABLE `student_m_discount` (
  `s_m_d_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_m_discount_bk`
--

CREATE TABLE `student_m_discount_bk` (
  `s_m_d_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_m_installment`
--

CREATE TABLE `student_m_installment` (
  `s_m_i_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(5) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `fee_type_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_parent`
--

CREATE TABLE `student_parent` (
  `s_p_id` int(11) NOT NULL,
  `user_login_detail_id` int(11) NOT NULL,
  `p_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_code` int(5) DEFAULT NULL,
  `contact` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `occupation` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_file` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `id_type` tinyint(2) DEFAULT NULL,
  `nationality` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_relation`
--

CREATE TABLE `student_relation` (
  `relation_id` int(11) NOT NULL,
  `student_id` int(10) NOT NULL,
  `s_p_id` int(10) NOT NULL,
  `relation` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_withdrawal`
--

CREATE TABLE `student_withdrawal` (
  `std_withdraw_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `s_c_f_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `requested_by` int(11) NOT NULL,
  `request_date` datetime NOT NULL,
  `confirm_by` int(11) NOT NULL,
  `confirm_date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `subj_categ_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjectwise_attendance`
--

CREATE TABLE `subjectwise_attendance` (
  `attendance_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 undefined , 1 present , 2  absent, 3  leave',
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `subject_category`
--

CREATE TABLE `subject_category` (
  `subj_categ_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_components`
--

CREATE TABLE `subject_components` (
  `subject_component_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `percentage` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `subject_sallybus`
--

CREATE TABLE `subject_sallybus` (
  `subject_sallybus_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `sallybus_type` enum('1','2','3','4') NOT NULL COMMENT '1 = Text 2 = Document (ppt,docx,pdf,image) 3 = Video From YouTube 4 = Video From Vimeo',
  `sallybus_data` text NOT NULL,
  `status` int(1) NOT NULL,
  `inserted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subject_section`
--

CREATE TABLE `subject_section` (
  `subject_section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `periods_per_day` int(11) NOT NULL,
  `periods_per_week` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `subject_teacher`
--

CREATE TABLE `subject_teacher` (
  `subject_teacher_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `substitute_teacher`
--

CREATE TABLE `substitute_teacher` (
  `substitute_teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `period_no` int(11) NOT NULL,
  `date` date NOT NULL,
  `staff_id` int(11) NOT NULL,
  `substitute_of` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `name` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `contact_no` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `location_id` int(11) NOT NULL,
  `address` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ntn_number` int(10) DEFAULT NULL,
  `filing_status` int(1) DEFAULT NULL COMMENT '1 = Active , 2 = Inactive , 3 = Tax Free',
  `supplier_type` int(1) DEFAULT NULL COMMENT '1 = Supplier , 2 = Services',
  `supplier_percentage` int(3) DEFAULT NULL,
  `description` varchar(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nationality` int(11) NOT NULL,
  `attachment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_type` tinyint(2) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `coa_cash_payment` int(11) NOT NULL,
  `coa_bank_payment` int(11) NOT NULL,
  `coa_purchase_voucher` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `swap`
--

CREATE TABLE `swap` (
  `swap_id` int(11) NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `swap_date` date NOT NULL,
  `comments` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `swap_detail`
--

CREATE TABLE `swap_detail` (
  `swap_detail_id` int(11) NOT NULL,
  `swap_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `swap_type` tinyint(1) NOT NULL COMMENT 'from=1,to=2',
  `c_rout_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `day` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `period_no` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `task_city`
--

CREATE TABLE `task_city` (
  `cities_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `task_students`
--

CREATE TABLE `task_students` (
  `city_id` int(100) NOT NULL,
  `student_marks` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_planner_activity`
--

CREATE TABLE `teacher_planner_activity` (
  `id` int(11) NOT NULL,
  `planner_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `inserted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `school_id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `insertion_type` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_rating`
--

CREATE TABLE `teacher_rating` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `rating` int(2) NOT NULL,
  `rating_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_role`
--

CREATE TABLE `teacher_role` (
  `teacher_role_id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_role_bk`
--

CREATE TABLE `teacher_role_bk` (
  `teacher_role_id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `time_table_subject_teacher`
--

CREATE TABLE `time_table_subject_teacher` (
  `time_table_subject_id` int(11) NOT NULL,
  `class_routine_id` int(11) NOT NULL,
  `subject_teacher_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `todo_list`
--

CREATE TABLE `todo_list` (
  `todo_list_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `todo_title` text NOT NULL,
  `todo_content` text NOT NULL,
  `todo_add_date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_student`
--

CREATE TABLE `transfer_student` (
  `transfer_id` int(11) NOT NULL,
  `from_branch` int(11) NOT NULL,
  `to_branch` int(11) NOT NULL,
  `request_date` datetime NOT NULL,
  `transfer_date` datetime NOT NULL,
  `requested_by` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `completed_by` int(11) NOT NULL,
  `completed_date` datetime NOT NULL,
  `transfered_by` int(11) NOT NULL,
  `reason` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `from_section` int(11) NOT NULL,
  `to_section` int(11) NOT NULL,
  `s_c_f_id` int(11) NOT NULL,
  `r_s_c_f_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `user_activity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `activity_type` int(11) NOT NULL,
  `activity_type_id` int(11) NOT NULL,
  `activity_date` datetime NOT NULL DEFAULT current_timestamp(),
  `activity_ip` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `activity_detail` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_bk`
--

CREATE TABLE `user_activity_bk` (
  `user_activity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `activity_type` int(11) NOT NULL,
  `activity_type_id` int(11) NOT NULL,
  `activity_date` datetime NOT NULL DEFAULT current_timestamp(),
  `activity_ip` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `activity_detail` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `user_group_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `type` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_rights`
--

CREATE TABLE `user_rights` (
  `user_rights_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `user_group_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_themes`
--

CREATE TABLE `user_themes` (
  `id` int(11) NOT NULL,
  `user_login_id` int(11) NOT NULL,
  `theme` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `virtual_class`
--

CREATE TABLE `virtual_class` (
  `id` int(10) NOT NULL,
  `class_routine_id` int(5) NOT NULL,
  `virtual_class_name` varchar(150) NOT NULL,
  `virtual_class_id` varchar(150) NOT NULL,
  `virtual_class_join` varchar(300) NOT NULL,
  `vc_start_time` datetime NOT NULL DEFAULT current_timestamp(),
  `vc_end_time` datetime DEFAULT NULL,
  `Current_Days` date NOT NULL,
  `platform_id` int(1) DEFAULT NULL COMMENT '1 for bbb , 2 for jitsi'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `virtual_class_student`
--

CREATE TABLE `virtual_class_student` (
  `id` int(10) NOT NULL,
  `class_routine_id` int(5) NOT NULL,
  `virtual_class_id` varchar(30) NOT NULL,
  `virtual_class_name` varchar(250) NOT NULL,
  `student_id` int(5) NOT NULL,
  `student_name` varchar(30) NOT NULL,
  `parent_id` int(5) NOT NULL,
  `vc_start_time` datetime NOT NULL DEFAULT current_timestamp(),
  `vc_end_time` datetime DEFAULT NULL,
  `virtual_class_join` varchar(300) NOT NULL,
  `Current_Day` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yearly_terms`
--

CREATE TABLE `yearly_terms` (
  `yearly_terms_id` int(11) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `detail` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `school_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `order_num` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `is_closed` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_planner`
--
ALTER TABLE `academic_planner`
  ADD PRIMARY KEY (`planner_id`);

--
-- Indexes for table `academic_planner_diary`
--
ALTER TABLE `academic_planner_diary`
  ADD PRIMARY KEY (`a_p_d_id`);

--
-- Indexes for table `acadmic_year`
--
ALTER TABLE `acadmic_year`
  ADD PRIMARY KEY (`academic_year_id`);

--
-- Indexes for table `account_transection`
--
ALTER TABLE `account_transection`
  ADD PRIMARY KEY (`transection_id`);

--
-- Indexes for table `account_transection_bk`
--
ALTER TABLE `account_transection_bk`
  ADD PRIMARY KEY (`transection_id`);

--
-- Indexes for table `action_logs`
--
ALTER TABLE `action_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allownces`
--
ALTER TABLE `allownces`
  ADD PRIMARY KEY (`allownce_id`);

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`assessment_id`);

--
-- Indexes for table `assessment_attendance`
--
ALTER TABLE `assessment_attendance`
  ADD PRIMARY KEY (`assessment_attendance_id`);

--
-- Indexes for table `assessment_audience`
--
ALTER TABLE `assessment_audience`
  ADD PRIMARY KEY (`audience_id`);

--
-- Indexes for table `assessment_matching_solution`
--
ALTER TABLE `assessment_matching_solution`
  ADD PRIMARY KEY (`assessment_matching_solution_id`);

--
-- Indexes for table `assessment_questions`
--
ALTER TABLE `assessment_questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `assessment_result`
--
ALTER TABLE `assessment_result`
  ADD PRIMARY KEY (`assessment_result_id`);

--
-- Indexes for table `assessment_solution`
--
ALTER TABLE `assessment_solution`
  ADD PRIMARY KEY (`assessment_solution_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `attendance_staff`
--
ALTER TABLE `attendance_staff`
  ADD PRIMARY KEY (`attend_staff_id`);

--
-- Indexes for table `attendance_staff_timing`
--
ALTER TABLE `attendance_staff_timing`
  ADD PRIMARY KEY (`attendance_staff_timing_id`);

--
-- Indexes for table `attendance_timing`
--
ALTER TABLE `attendance_timing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_type`
--
ALTER TABLE `attendance_type`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `bank_account`
--
ALTER TABLE `bank_account`
  ADD PRIMARY KEY (`bank_account_id`);

--
-- Indexes for table `bank_cheque_books`
--
ALTER TABLE `bank_cheque_books`
  ADD PRIMARY KEY (`b_c_b_id`);

--
-- Indexes for table `bank_payment`
--
ALTER TABLE `bank_payment`
  ADD PRIMARY KEY (`bank_payment_id`);

--
-- Indexes for table `bank_payment_details`
--
ALTER TABLE `bank_payment_details`
  ADD PRIMARY KEY (`bank_payment_details_id`);

--
-- Indexes for table `bank_receipt`
--
ALTER TABLE `bank_receipt`
  ADD PRIMARY KEY (`bank_receipt_id`);

--
-- Indexes for table `bank_receipt_details`
--
ALTER TABLE `bank_receipt_details`
  ADD PRIMARY KEY (`bank_receipt_details_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `book_issue`
--
ALTER TABLE `book_issue`
  ADD PRIMARY KEY (`book_issue_id`);

--
-- Indexes for table `book_reserve_request`
--
ALTER TABLE `book_reserve_request`
  ADD PRIMARY KEY (`brr_id`);

--
-- Indexes for table `bulk_monthly_chalan`
--
ALTER TABLE `bulk_monthly_chalan`
  ADD PRIMARY KEY (`b_m_c_id`);

--
-- Indexes for table `bulk_request`
--
ALTER TABLE `bulk_request`
  ADD PRIMARY KEY (`bulk_req_id`);

--
-- Indexes for table `cash_payment`
--
ALTER TABLE `cash_payment`
  ADD PRIMARY KEY (`cash_payment_id`);

--
-- Indexes for table `cash_payment_details`
--
ALTER TABLE `cash_payment_details`
  ADD PRIMARY KEY (`cash_payment_details_id`);

--
-- Indexes for table `cash_receipt`
--
ALTER TABLE `cash_receipt`
  ADD PRIMARY KEY (`cash_receipt_id`);

--
-- Indexes for table `cash_receipt_details`
--
ALTER TABLE `cash_receipt_details`
  ADD PRIMARY KEY (`cash_receipt_details_id`);

--
-- Indexes for table `cash_voucher_settings`
--
ALTER TABLE `cash_voucher_settings`
  ADD PRIMARY KEY (`cash_voucher_settings_id`);

--
-- Indexes for table `chalan_archive`
--
ALTER TABLE `chalan_archive`
  ADD PRIMARY KEY (`chalan_archive_id`);

--
-- Indexes for table `chalan_settings`
--
ALTER TABLE `chalan_settings`
  ADD PRIMARY KEY (`chalan_setting_id`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`coa_id`);

--
-- Indexes for table `chart_of_account_types`
--
ALTER TABLE `chart_of_account_types`
  ADD PRIMARY KEY (`coa_type_id`);

--
-- Indexes for table `chat_relation`
--
ALTER TABLE `chat_relation`
  ADD PRIMARY KEY (`chat_rel_id`);

--
-- Indexes for table `circular`
--
ALTER TABLE `circular`
  ADD PRIMARY KEY (`circular_id`);

--
-- Indexes for table `circular_staff`
--
ALTER TABLE `circular_staff`
  ADD PRIMARY KEY (`circular_staff_id`);

--
-- Indexes for table `city_location`
--
ALTER TABLE `city_location`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `city_location_archive`
--
ALTER TABLE `city_location_archive`
  ADD PRIMARY KEY (`location_archive_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `class_chalan_discount`
--
ALTER TABLE `class_chalan_discount`
  ADD PRIMARY KEY (`c_c_dis_id`);

--
-- Indexes for table `class_chalan_fee`
--
ALTER TABLE `class_chalan_fee`
  ADD PRIMARY KEY (`c_c_fee_id`);

--
-- Indexes for table `class_chalan_form`
--
ALTER TABLE `class_chalan_form`
  ADD PRIMARY KEY (`c_c_f_id`);

--
-- Indexes for table `class_message`
--
ALTER TABLE `class_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_routine`
--
ALTER TABLE `class_routine`
  ADD PRIMARY KEY (`class_routine_id`);

--
-- Indexes for table `class_routine_settings`
--
ALTER TABLE `class_routine_settings`
  ADD PRIMARY KEY (`c_rout_sett_id`);

--
-- Indexes for table `class_section`
--
ALTER TABLE `class_section`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `deductions`
--
ALTER TABLE `deductions`
  ADD PRIMARY KEY (`deduction_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`departments_id`);

--
-- Indexes for table `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`deposit_id`);

--
-- Indexes for table `depositor`
--
ALTER TABLE `depositor`
  ADD PRIMARY KEY (`depositor_id`);

--
-- Indexes for table `deposit_bk`
--
ALTER TABLE `deposit_bk`
  ADD PRIMARY KEY (`deposit_id`);

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`designation_id`);

--
-- Indexes for table `diary`
--
ALTER TABLE `diary`
  ADD PRIMARY KEY (`diary_id`);

--
-- Indexes for table `diary_attachments`
--
ALTER TABLE `diary_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diary_audio`
--
ALTER TABLE `diary_audio`
  ADD PRIMARY KEY (`dairy_audio_id`);

--
-- Indexes for table `diary_student`
--
ALTER TABLE `diary_student`
  ADD PRIMARY KEY (`diary_student_id`);

--
-- Indexes for table `discount_list`
--
ALTER TABLE `discount_list`
  ADD PRIMARY KEY (`discount_id`);

--
-- Indexes for table `email_layout_settings`
--
ALTER TABLE `email_layout_settings`
  ADD PRIMARY KEY (`email_layout_id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`email_temp_id`);

--
-- Indexes for table `evaluation_factors`
--
ALTER TABLE `evaluation_factors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_ratings`
--
ALTER TABLE `evaluation_ratings`
  ADD PRIMARY KEY (`misc_id`);

--
-- Indexes for table `events_annoucments`
--
ALTER TABLE `events_annoucments`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `events_annoucments_details`
--
ALTER TABLE `events_annoucments_details`
  ADD PRIMARY KEY (`event_detail_id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`exam_id`);

--
-- Indexes for table `exam_routine`
--
ALTER TABLE `exam_routine`
  ADD PRIMARY KEY (`exam_routine_id`);

--
-- Indexes for table `exam_weightage`
--
ALTER TABLE `exam_weightage`
  ADD PRIMARY KEY (`weightage_id`);

--
-- Indexes for table `fee_types`
--
ALTER TABLE `fee_types`
  ADD PRIMARY KEY (`fee_type_id`);

--
-- Indexes for table `financial_reports_settings`
--
ALTER TABLE `financial_reports_settings`
  ADD PRIMARY KEY (`fin_rep_setting_id`);

--
-- Indexes for table `financial_reports_settings_bk`
--
ALTER TABLE `financial_reports_settings_bk`
  ADD PRIMARY KEY (`fin_rep_setting_id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`grade_id`);

--
-- Indexes for table `group_rights`
--
ALTER TABLE `group_rights`
  ADD PRIMARY KEY (`group_rights_id`);

--
-- Indexes for table `holiday`
--
ALTER TABLE `holiday`
  ADD PRIMARY KEY (`holiday_id`),
  ADD UNIQUE KEY `holiday_id` (`holiday_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`job_application_id`);

--
-- Indexes for table `journal_entry`
--
ALTER TABLE `journal_entry`
  ADD PRIMARY KEY (`journal_entry_id`);

--
-- Indexes for table `journal_voucher`
--
ALTER TABLE `journal_voucher`
  ADD PRIMARY KEY (`journal_voucher_id`);

--
-- Indexes for table `journal_voucher_details`
--
ALTER TABLE `journal_voucher_details`
  ADD PRIMARY KEY (`journal_voucher_details_id`);

--
-- Indexes for table `leave_category`
--
ALTER TABLE `leave_category`
  ADD PRIMARY KEY (`leave_category_id`);

--
-- Indexes for table `leave_staff`
--
ALTER TABLE `leave_staff`
  ADD PRIMARY KEY (`leave_staff_id`);

--
-- Indexes for table `leave_student`
--
ALTER TABLE `leave_student`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `lecture_notes`
--
ALTER TABLE `lecture_notes`
  ADD PRIMARY KEY (`notes_id`);

--
-- Indexes for table `lecture_notes_audience`
--
ALTER TABLE `lecture_notes_audience`
  ADD PRIMARY KEY (`audience_id`);

--
-- Indexes for table `lecture_notes_documents`
--
ALTER TABLE `lecture_notes_documents`
  ADD PRIMARY KEY (`notes_document_id`);

--
-- Indexes for table `library_members`
--
ALTER TABLE `library_members`
  ADD PRIMARY KEY (`library_member_id`);

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`marks_id`);

--
-- Indexes for table `marks_components`
--
ALTER TABLE `marks_components`
  ADD PRIMARY KEY (`marks_components_id`),
  ADD UNIQUE KEY `marks_components_id` (`marks_components_id`);

--
-- Indexes for table `matching_question_option`
--
ALTER TABLE `matching_question_option`
  ADD PRIMARY KEY (`matching_question_option_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messages_id`);

--
-- Indexes for table `miscellaneous_settings_bk`
--
ALTER TABLE `miscellaneous_settings_bk`
  ADD PRIMARY KEY (`misc_id`);

--
-- Indexes for table `misc_challan_coa_settings`
--
ALTER TABLE `misc_challan_coa_settings`
  ADD PRIMARY KEY (`misc_settings_id`);

--
-- Indexes for table `mobile_device_id`
--
ALTER TABLE `mobile_device_id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `noticeboard`
--
ALTER TABLE `noticeboard`
  ADD PRIMARY KEY (`notice_id`);

--
-- Indexes for table `payment_consumer`
--
ALTER TABLE `payment_consumer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `policies`
--
ALTER TABLE `policies`
  ADD PRIMARY KEY (`policies_id`);

--
-- Indexes for table `policy_category`
--
ALTER TABLE `policy_category`
  ADD PRIMARY KEY (`policy_category_id`);

--
-- Indexes for table `purchase_voucher`
--
ALTER TABLE `purchase_voucher`
  ADD PRIMARY KEY (`purchase_voucher_id`);

--
-- Indexes for table `purchase_voucher_details`
--
ALTER TABLE `purchase_voucher_details`
  ADD PRIMARY KEY (`purchase_voucher_details_id`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`question_option_id`);

--
-- Indexes for table `question_types`
--
ALTER TABLE `question_types`
  ADD PRIMARY KEY (`question_type_id`);

--
-- Indexes for table `salary_voucher_settings`
--
ALTER TABLE `salary_voucher_settings`
  ADD PRIMARY KEY (`salary_voucher_setting_id`);

--
-- Indexes for table `school`
--
ALTER TABLE `school`
  ADD PRIMARY KEY (`school_id`);

--
-- Indexes for table `school_archive`
--
ALTER TABLE `school_archive`
  ADD PRIMARY KEY (`school_archive_id`);

--
-- Indexes for table `school_coa`
--
ALTER TABLE `school_coa`
  ADD PRIMARY KEY (`school_coa_id`);

--
-- Indexes for table `school_count`
--
ALTER TABLE `school_count`
  ADD PRIMARY KEY (`school_count_id`);

--
-- Indexes for table `school_discount_list`
--
ALTER TABLE `school_discount_list`
  ADD PRIMARY KEY (`school_discount_id`);

--
-- Indexes for table `school_fee_types`
--
ALTER TABLE `school_fee_types`
  ADD PRIMARY KEY (`school_fee_type_id`);

--
-- Indexes for table `school_notifications`
--
ALTER TABLE `school_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sch_admission_inquiries`
--
ALTER TABLE `sch_admission_inquiries`
  ADD PRIMARY KEY (`s_a_i_id`);

--
-- Indexes for table `sch_general_inquiries`
--
ALTER TABLE `sch_general_inquiries`
  ADD PRIMARY KEY (`s_g_i_id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `session_bk`
--
ALTER TABLE `session_bk`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `sms_count`
--
ALTER TABLE `sms_count`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_settings`
--
ALTER TABLE `sms_settings`
  ADD PRIMARY KEY (`sms_f_id`);

--
-- Indexes for table `sms_templates`
--
ALTER TABLE `sms_templates`
  ADD PRIMARY KEY (`sms_temp_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `staff_evaluation`
--
ALTER TABLE `staff_evaluation`
  ADD PRIMARY KEY (`staff_eval_id`);

--
-- Indexes for table `staff_evaluation_answers`
--
ALTER TABLE `staff_evaluation_answers`
  ADD PRIMARY KEY (`staf_eval_ans_id`);

--
-- Indexes for table `staff_evaluation_questions`
--
ALTER TABLE `staff_evaluation_questions`
  ADD PRIMARY KEY (`staff_eval_form_id`);

--
-- Indexes for table `staff_in_out`
--
ALTER TABLE `staff_in_out`
  ADD PRIMARY KEY (`s_io_id`);

--
-- Indexes for table `staff_leave_settings`
--
ALTER TABLE `staff_leave_settings`
  ADD PRIMARY KEY (`staff_leave_settings_id`);

--
-- Indexes for table `staff_payroll_settings`
--
ALTER TABLE `staff_payroll_settings`
  ADD PRIMARY KEY (`s_p_s_id`);

--
-- Indexes for table `staff_salary_allownces`
--
ALTER TABLE `staff_salary_allownces`
  ADD PRIMARY KEY (`s_s_a_id`);

--
-- Indexes for table `staff_salary_deductions`
--
ALTER TABLE `staff_salary_deductions`
  ADD PRIMARY KEY (`s_s_d_id`);

--
-- Indexes for table `staff_salary_slip`
--
ALTER TABLE `staff_salary_slip`
  ADD PRIMARY KEY (`s_s_s_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `student_archive`
--
ALTER TABLE `student_archive`
  ADD PRIMARY KEY (`student_archive_id`);

--
-- Indexes for table `student_category`
--
ALTER TABLE `student_category`
  ADD PRIMARY KEY (`student_category_id`);

--
-- Indexes for table `student_chalan_detail`
--
ALTER TABLE `student_chalan_detail`
  ADD PRIMARY KEY (`s_c_d_id`);

--
-- Indexes for table `student_chalan_form`
--
ALTER TABLE `student_chalan_form`
  ADD PRIMARY KEY (`s_c_f_id`);

--
-- Indexes for table `student_evaluation`
--
ALTER TABLE `student_evaluation`
  ADD PRIMARY KEY (`stud_eval_id`);

--
-- Indexes for table `student_evaluation_answers`
--
ALTER TABLE `student_evaluation_answers`
  ADD PRIMARY KEY (`std_eval_ans_id`);

--
-- Indexes for table `student_evaluation_questions`
--
ALTER TABLE `student_evaluation_questions`
  ADD PRIMARY KEY (`eval_id`);

--
-- Indexes for table `student_fee_settings`
--
ALTER TABLE `student_fee_settings`
  ADD PRIMARY KEY (`fee_settings_id`);

--
-- Indexes for table `student_monthly_fee_settings`
--
ALTER TABLE `student_monthly_fee_settings`
  ADD PRIMARY KEY (`std_m_fee_settings_id`);

--
-- Indexes for table `student_m_discount`
--
ALTER TABLE `student_m_discount`
  ADD PRIMARY KEY (`s_m_d_id`);

--
-- Indexes for table `student_m_discount_bk`
--
ALTER TABLE `student_m_discount_bk`
  ADD PRIMARY KEY (`s_m_d_id`);

--
-- Indexes for table `student_m_installment`
--
ALTER TABLE `student_m_installment`
  ADD PRIMARY KEY (`s_m_i_id`);

--
-- Indexes for table `student_parent`
--
ALTER TABLE `student_parent`
  ADD PRIMARY KEY (`s_p_id`);

--
-- Indexes for table `student_relation`
--
ALTER TABLE `student_relation`
  ADD PRIMARY KEY (`relation_id`);

--
-- Indexes for table `student_withdrawal`
--
ALTER TABLE `student_withdrawal`
  ADD PRIMARY KEY (`std_withdraw_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `subjectwise_attendance`
--
ALTER TABLE `subjectwise_attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `subject_category`
--
ALTER TABLE `subject_category`
  ADD PRIMARY KEY (`subj_categ_id`);

--
-- Indexes for table `subject_components`
--
ALTER TABLE `subject_components`
  ADD PRIMARY KEY (`subject_component_id`);

--
-- Indexes for table `subject_sallybus`
--
ALTER TABLE `subject_sallybus`
  ADD PRIMARY KEY (`subject_sallybus_id`);

--
-- Indexes for table `subject_section`
--
ALTER TABLE `subject_section`
  ADD PRIMARY KEY (`subject_section_id`);

--
-- Indexes for table `subject_teacher`
--
ALTER TABLE `subject_teacher`
  ADD PRIMARY KEY (`subject_teacher_id`);

--
-- Indexes for table `substitute_teacher`
--
ALTER TABLE `substitute_teacher`
  ADD PRIMARY KEY (`substitute_teacher_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `swap`
--
ALTER TABLE `swap`
  ADD PRIMARY KEY (`swap_id`);

--
-- Indexes for table `swap_detail`
--
ALTER TABLE `swap_detail`
  ADD PRIMARY KEY (`swap_detail_id`);

--
-- Indexes for table `teacher_planner_activity`
--
ALTER TABLE `teacher_planner_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_rating`
--
ALTER TABLE `teacher_rating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_role`
--
ALTER TABLE `teacher_role`
  ADD PRIMARY KEY (`teacher_role_id`);

--
-- Indexes for table `teacher_role_bk`
--
ALTER TABLE `teacher_role_bk`
  ADD PRIMARY KEY (`teacher_role_id`);

--
-- Indexes for table `time_table_subject_teacher`
--
ALTER TABLE `time_table_subject_teacher`
  ADD PRIMARY KEY (`time_table_subject_id`);

--
-- Indexes for table `todo_list`
--
ALTER TABLE `todo_list`
  ADD PRIMARY KEY (`todo_list_id`);

--
-- Indexes for table `transfer_student`
--
ALTER TABLE `transfer_student`
  ADD PRIMARY KEY (`transfer_id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`user_activity_id`);

--
-- Indexes for table `user_activity_bk`
--
ALTER TABLE `user_activity_bk`
  ADD PRIMARY KEY (`user_activity_id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`user_group_id`);

--
-- Indexes for table `user_rights`
--
ALTER TABLE `user_rights`
  ADD PRIMARY KEY (`user_rights_id`);

--
-- Indexes for table `user_themes`
--
ALTER TABLE `user_themes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `virtual_class`
--
ALTER TABLE `virtual_class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `virtual_class_student`
--
ALTER TABLE `virtual_class_student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yearly_terms`
--
ALTER TABLE `yearly_terms`
  ADD PRIMARY KEY (`yearly_terms_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_planner`
--
ALTER TABLE `academic_planner`
  MODIFY `planner_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `academic_planner_diary`
--
ALTER TABLE `academic_planner_diary`
  MODIFY `a_p_d_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acadmic_year`
--
ALTER TABLE `acadmic_year`
  MODIFY `academic_year_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_transection`
--
ALTER TABLE `account_transection`
  MODIFY `transection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_transection_bk`
--
ALTER TABLE `account_transection_bk`
  MODIFY `transection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `action_logs`
--
ALTER TABLE `action_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allownces`
--
ALTER TABLE `allownces`
  MODIFY `allownce_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assessment_attendance`
--
ALTER TABLE `assessment_attendance`
  MODIFY `assessment_attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assessment_audience`
--
ALTER TABLE `assessment_audience`
  MODIFY `audience_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assessment_matching_solution`
--
ALTER TABLE `assessment_matching_solution`
  MODIFY `assessment_matching_solution_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assessment_questions`
--
ALTER TABLE `assessment_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assessment_result`
--
ALTER TABLE `assessment_result`
  MODIFY `assessment_result_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assessment_solution`
--
ALTER TABLE `assessment_solution`
  MODIFY `assessment_solution_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_staff`
--
ALTER TABLE `attendance_staff`
  MODIFY `attend_staff_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_staff_timing`
--
ALTER TABLE `attendance_staff_timing`
  MODIFY `attendance_staff_timing_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_timing`
--
ALTER TABLE `attendance_timing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_type`
--
ALTER TABLE `attendance_type`
  MODIFY `attendance_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_account`
--
ALTER TABLE `bank_account`
  MODIFY `bank_account_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_cheque_books`
--
ALTER TABLE `bank_cheque_books`
  MODIFY `b_c_b_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_payment`
--
ALTER TABLE `bank_payment`
  MODIFY `bank_payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_payment_details`
--
ALTER TABLE `bank_payment_details`
  MODIFY `bank_payment_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_receipt`
--
ALTER TABLE `bank_receipt`
  MODIFY `bank_receipt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_receipt_details`
--
ALTER TABLE `bank_receipt_details`
  MODIFY `bank_receipt_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `book_issue`
--
ALTER TABLE `book_issue`
  MODIFY `book_issue_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `book_reserve_request`
--
ALTER TABLE `book_reserve_request`
  MODIFY `brr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulk_monthly_chalan`
--
ALTER TABLE `bulk_monthly_chalan`
  MODIFY `b_m_c_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulk_request`
--
ALTER TABLE `bulk_request`
  MODIFY `bulk_req_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_payment`
--
ALTER TABLE `cash_payment`
  MODIFY `cash_payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_payment_details`
--
ALTER TABLE `cash_payment_details`
  MODIFY `cash_payment_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_receipt`
--
ALTER TABLE `cash_receipt`
  MODIFY `cash_receipt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_receipt_details`
--
ALTER TABLE `cash_receipt_details`
  MODIFY `cash_receipt_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_voucher_settings`
--
ALTER TABLE `cash_voucher_settings`
  MODIFY `cash_voucher_settings_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chalan_archive`
--
ALTER TABLE `chalan_archive`
  MODIFY `chalan_archive_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chalan_settings`
--
ALTER TABLE `chalan_settings`
  MODIFY `chalan_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `coa_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chart_of_account_types`
--
ALTER TABLE `chart_of_account_types`
  MODIFY `coa_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_relation`
--
ALTER TABLE `chat_relation`
  MODIFY `chat_rel_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `circular`
--
ALTER TABLE `circular`
  MODIFY `circular_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `circular_staff`
--
ALTER TABLE `circular_staff`
  MODIFY `circular_staff_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city_location`
--
ALTER TABLE `city_location`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city_location_archive`
--
ALTER TABLE `city_location_archive`
  MODIFY `location_archive_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_chalan_discount`
--
ALTER TABLE `class_chalan_discount`
  MODIFY `c_c_dis_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_chalan_fee`
--
ALTER TABLE `class_chalan_fee`
  MODIFY `c_c_fee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_chalan_form`
--
ALTER TABLE `class_chalan_form`
  MODIFY `c_c_f_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_message`
--
ALTER TABLE `class_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_routine`
--
ALTER TABLE `class_routine`
  MODIFY `class_routine_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_routine_settings`
--
ALTER TABLE `class_routine_settings`
  MODIFY `c_rout_sett_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_section`
--
ALTER TABLE `class_section`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deductions`
--
ALTER TABLE `deductions`
  MODIFY `deduction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `departments_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposit`
--
ALTER TABLE `deposit`
  MODIFY `deposit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `depositor`
--
ALTER TABLE `depositor`
  MODIFY `depositor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposit_bk`
--
ALTER TABLE `deposit_bk`
  MODIFY `deposit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `designation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diary`
--
ALTER TABLE `diary`
  MODIFY `diary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diary_attachments`
--
ALTER TABLE `diary_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diary_audio`
--
ALTER TABLE `diary_audio`
  MODIFY `dairy_audio_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diary_student`
--
ALTER TABLE `diary_student`
  MODIFY `diary_student_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_list`
--
ALTER TABLE `discount_list`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_layout_settings`
--
ALTER TABLE `email_layout_settings`
  MODIFY `email_layout_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `email_temp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluation_factors`
--
ALTER TABLE `evaluation_factors`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluation_ratings`
--
ALTER TABLE `evaluation_ratings`
  MODIFY `misc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events_annoucments`
--
ALTER TABLE `events_annoucments`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events_annoucments_details`
--
ALTER TABLE `events_annoucments_details`
  MODIFY `event_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_routine`
--
ALTER TABLE `exam_routine`
  MODIFY `exam_routine_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_weightage`
--
ALTER TABLE `exam_weightage`
  MODIFY `weightage_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_types`
--
ALTER TABLE `fee_types`
  MODIFY `fee_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_reports_settings`
--
ALTER TABLE `financial_reports_settings`
  MODIFY `fin_rep_setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_reports_settings_bk`
--
ALTER TABLE `financial_reports_settings_bk`
  MODIFY `fin_rep_setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_rights`
--
ALTER TABLE `group_rights`
  MODIFY `group_rights_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holiday`
--
ALTER TABLE `holiday`
  MODIFY `holiday_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `job_application_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entry`
--
ALTER TABLE `journal_entry`
  MODIFY `journal_entry_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_voucher`
--
ALTER TABLE `journal_voucher`
  MODIFY `journal_voucher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_voucher_details`
--
ALTER TABLE `journal_voucher_details`
  MODIFY `journal_voucher_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_category`
--
ALTER TABLE `leave_category`
  MODIFY `leave_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_staff`
--
ALTER TABLE `leave_staff`
  MODIFY `leave_staff_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_student`
--
ALTER TABLE `leave_student`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lecture_notes`
--
ALTER TABLE `lecture_notes`
  MODIFY `notes_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lecture_notes_audience`
--
ALTER TABLE `lecture_notes_audience`
  MODIFY `audience_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lecture_notes_documents`
--
ALTER TABLE `lecture_notes_documents`
  MODIFY `notes_document_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_members`
--
ALTER TABLE `library_members`
  MODIFY `library_member_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marks`
--
ALTER TABLE `marks`
  MODIFY `marks_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marks_components`
--
ALTER TABLE `marks_components`
  MODIFY `marks_components_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matching_question_option`
--
ALTER TABLE `matching_question_option`
  MODIFY `matching_question_option_id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `messages_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `miscellaneous_settings_bk`
--
ALTER TABLE `miscellaneous_settings_bk`
  MODIFY `misc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `misc_challan_coa_settings`
--
ALTER TABLE `misc_challan_coa_settings`
  MODIFY `misc_settings_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mobile_device_id`
--
ALTER TABLE `mobile_device_id`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `noticeboard`
--
ALTER TABLE `noticeboard`
  MODIFY `notice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_consumer`
--
ALTER TABLE `payment_consumer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `policies_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `policy_category`
--
ALTER TABLE `policy_category`
  MODIFY `policy_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_voucher`
--
ALTER TABLE `purchase_voucher`
  MODIFY `purchase_voucher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_voucher_details`
--
ALTER TABLE `purchase_voucher_details`
  MODIFY `purchase_voucher_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `question_option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question_types`
--
ALTER TABLE `question_types`
  MODIFY `question_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_voucher_settings`
--
ALTER TABLE `salary_voucher_settings`
  MODIFY `salary_voucher_setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school`
--
ALTER TABLE `school`
  MODIFY `school_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_archive`
--
ALTER TABLE `school_archive`
  MODIFY `school_archive_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_coa`
--
ALTER TABLE `school_coa`
  MODIFY `school_coa_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_count`
--
ALTER TABLE `school_count`
  MODIFY `school_count_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_discount_list`
--
ALTER TABLE `school_discount_list`
  MODIFY `school_discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_fee_types`
--
ALTER TABLE `school_fee_types`
  MODIFY `school_fee_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_notifications`
--
ALTER TABLE `school_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sch_admission_inquiries`
--
ALTER TABLE `sch_admission_inquiries`
  MODIFY `s_a_i_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sch_general_inquiries`
--
ALTER TABLE `sch_general_inquiries`
  MODIFY `s_g_i_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `session_bk`
--
ALTER TABLE `session_bk`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_settings`
--
ALTER TABLE `sms_settings`
  MODIFY `sms_f_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_templates`
--
ALTER TABLE `sms_templates`
  MODIFY `sms_temp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_evaluation`
--
ALTER TABLE `staff_evaluation`
  MODIFY `staff_eval_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_evaluation_answers`
--
ALTER TABLE `staff_evaluation_answers`
  MODIFY `staf_eval_ans_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_evaluation_questions`
--
ALTER TABLE `staff_evaluation_questions`
  MODIFY `staff_eval_form_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_in_out`
--
ALTER TABLE `staff_in_out`
  MODIFY `s_io_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_leave_settings`
--
ALTER TABLE `staff_leave_settings`
  MODIFY `staff_leave_settings_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_payroll_settings`
--
ALTER TABLE `staff_payroll_settings`
  MODIFY `s_p_s_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_salary_allownces`
--
ALTER TABLE `staff_salary_allownces`
  MODIFY `s_s_a_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_salary_deductions`
--
ALTER TABLE `staff_salary_deductions`
  MODIFY `s_s_d_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_salary_slip`
--
ALTER TABLE `staff_salary_slip`
  MODIFY `s_s_s_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_archive`
--
ALTER TABLE `student_archive`
  MODIFY `student_archive_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_category`
--
ALTER TABLE `student_category`
  MODIFY `student_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_chalan_detail`
--
ALTER TABLE `student_chalan_detail`
  MODIFY `s_c_d_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_chalan_form`
--
ALTER TABLE `student_chalan_form`
  MODIFY `s_c_f_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_evaluation`
--
ALTER TABLE `student_evaluation`
  MODIFY `stud_eval_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_evaluation_answers`
--
ALTER TABLE `student_evaluation_answers`
  MODIFY `std_eval_ans_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_evaluation_questions`
--
ALTER TABLE `student_evaluation_questions`
  MODIFY `eval_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_fee_settings`
--
ALTER TABLE `student_fee_settings`
  MODIFY `fee_settings_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_monthly_fee_settings`
--
ALTER TABLE `student_monthly_fee_settings`
  MODIFY `std_m_fee_settings_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_m_discount`
--
ALTER TABLE `student_m_discount`
  MODIFY `s_m_d_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_m_discount_bk`
--
ALTER TABLE `student_m_discount_bk`
  MODIFY `s_m_d_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_m_installment`
--
ALTER TABLE `student_m_installment`
  MODIFY `s_m_i_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_parent`
--
ALTER TABLE `student_parent`
  MODIFY `s_p_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_relation`
--
ALTER TABLE `student_relation`
  MODIFY `relation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_withdrawal`
--
ALTER TABLE `student_withdrawal`
  MODIFY `std_withdraw_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjectwise_attendance`
--
ALTER TABLE `subjectwise_attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_category`
--
ALTER TABLE `subject_category`
  MODIFY `subj_categ_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_components`
--
ALTER TABLE `subject_components`
  MODIFY `subject_component_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_sallybus`
--
ALTER TABLE `subject_sallybus`
  MODIFY `subject_sallybus_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_section`
--
ALTER TABLE `subject_section`
  MODIFY `subject_section_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_teacher`
--
ALTER TABLE `subject_teacher`
  MODIFY `subject_teacher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `substitute_teacher`
--
ALTER TABLE `substitute_teacher`
  MODIFY `substitute_teacher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `swap`
--
ALTER TABLE `swap`
  MODIFY `swap_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `swap_detail`
--
ALTER TABLE `swap_detail`
  MODIFY `swap_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_planner_activity`
--
ALTER TABLE `teacher_planner_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_rating`
--
ALTER TABLE `teacher_rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_role`
--
ALTER TABLE `teacher_role`
  MODIFY `teacher_role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_role_bk`
--
ALTER TABLE `teacher_role_bk`
  MODIFY `teacher_role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_table_subject_teacher`
--
ALTER TABLE `time_table_subject_teacher`
  MODIFY `time_table_subject_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `todo_list`
--
ALTER TABLE `todo_list`
  MODIFY `todo_list_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_student`
--
ALTER TABLE `transfer_student`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `user_activity_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_activity_bk`
--
ALTER TABLE `user_activity_bk`
  MODIFY `user_activity_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `user_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_rights`
--
ALTER TABLE `user_rights`
  MODIFY `user_rights_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_themes`
--
ALTER TABLE `user_themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `virtual_class`
--
ALTER TABLE `virtual_class`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `virtual_class_student`
--
ALTER TABLE `virtual_class_student`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `yearly_terms`
--
ALTER TABLE `yearly_terms`
  MODIFY `yearly_terms_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
