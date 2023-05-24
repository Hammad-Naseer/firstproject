<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller']     =  'login';
$route['404_override']           =  'user/error404';
$route['translate_uri_dashes']   =   FALSE;
$route['forgot-password']        =  'login/forgot_password';
$route['forgot-password-mobile'] =  'login/forgot_password_mobile';
$route['my_testing']             =  'admin/dashboard';
$route['sms_testing']            =  "login/opensms";
$route['sms_testing_trigger']    =  "login/smstesting";
$route['email_testing']          =  "login/openemail";
$route['institute/(:any)']       =  "login/institute_page/$1";
$route['email_testing_trigger']  =  "login/email_testing";
$route['std_dashboard']          =  "student/parents/dashboard";

//parent portal mobile webservices
$route['mobile_webservices/verify_email']  =  "Mobile_APIs/Mobile_Webservices/verify_email";
$route['mobile_webservices/verify_code']  =  "Mobile_APIs/Mobile_Webservices/verify_code";
$route['mobile_webservices/update_password']  =  "Mobile_APIs/Mobile_Webservices/update_password";


$route['mobile_webservices/authenticate']  =  "Mobile_APIs/Mobile_Webservices/authenticate";
$route['mobile_webservices/kids_list_per_parents']  =  "Mobile_APIs/Mobile_Webservices/kids_list_per_parents";
$route['mobile_webservices/index']  =  "Mobile_APIs/Mobile_Webservices/index";
$route['mobile_webservices/noticeboard']  =  "Mobile_APIs/Mobile_Webservices/noticeboard";
$route['mobile_webservices/noticeboard_all']  =  "Mobile_APIs/Mobile_Webservices/noticeboard_all";
$route['mobile_webservices/invoice']  =  "Mobile_APIs/Mobile_Webservices/invoice";
$route['mobile_webservices/invoice_cart_proceed']  =  "Mobile_APIs/Mobile_Webservices/invoice_cart_proceed";
$route['mobile_webservices/circulars']  =  "Mobile_APIs/Mobile_Webservices/circulars";
$route['mobile_webservices/cart_proceed']  =  "Mobile_APIs/Mobile_Webservices/cart_proceed";
$route['mobile_webservices/diary']  =  "Mobile_APIs/Mobile_Webservices/diary";
$route['mobile_webservices/getdiarycontent']  =  "Mobile_APIs/Mobile_Webservices/getdiarycontent";
$route['mobile_webservices/downloaddiarycontent']  =  "Mobile_APIs/Mobile_Webservices/downloaddiarycontent";
$route['mobile_webservices/class_routine']  =  "Mobile_APIs/Mobile_Webservices/class_routine";
$route['mobile_webservices/manage_attendance']  =  "Mobile_APIs/Mobile_Webservices/manage_attendance";
$route['mobile_webservices/attendence_summary']  =  "Mobile_APIs/Mobile_Webservices/attendence_summary";
$route['mobile_webservices/subjects']  =  "Mobile_APIs/Mobile_Webservices/subjects";
$route['mobile_webservices/popup']  =  "Mobile_APIs/Mobile_Webservices/popup"; 
$route['mobile_webservices/exam_routine']  =  "Mobile_APIs/Mobile_Webservices/exam_routine";
$route['mobile_webservices/teacher_list']  =  "Mobile_APIs/Mobile_Webservices/teacher_list";
$route['mobile_webservices/message']  =  "Mobile_APIs/Mobile_Webservices/message";
$route['mobile_webservices/message_send']  =  "Mobile_APIs/Mobile_Webservices/message_send";
$route['mobile_webservices/manage_leaves']  =  "Mobile_APIs/Mobile_Webservices/manage_leaves";
$route['mobile_webservices/leavereqst']  =  "Mobile_APIs/Mobile_Webservices/leavereqst";
$route['mobile_webservices/marks']  =  "Mobile_APIs/Mobile_Webservices/marks";
$route['mobile_webservices/get_exam_result']  =  "Mobile_APIs/Mobile_Webservices/get_exam_result";
$route['mobile_webservices/checkfile']  =  "Mobile_APIs/Mobile_Webservices/checkfile";
$route['mobile_webservices/view_print_chalan']  =  "Mobile_APIs/Mobile_Webservices/view_print_chalan";
$route['mobile_webservices/view_challan/(:any)/(:any)/(:any)/(:any)']  =  "Mobile_APIs/Mobile_Webservices/view_challan";
$route['mobile_webservices/download_challan1']  =  "Mobile_APIs/Mobile_Webservices/download_challan1";
$route['mobile_webservices/download_challan']  =  "Mobile_APIs/Mobile_Webservices/download_challan";
$route['mobile_webservices/test']  =  "Mobile_APIs/Mobile_Webservices/test";

$route['mobile_webservices/notifications']  =  "Mobile_APIs/Mobile_Webservices/getNotifications";
$route['mobile_webservices/countnotifications']  =  "Mobile_APIs/Mobile_Webservices/countNotifications";
$route['mobile_webservices/readnotifications']  =  "Mobile_APIs/Mobile_Webservices/readNotifications";
$route['mobile_webservices/policies']  =  "Mobile_APIs/Mobile_Webservices/school_policies";
$route['mobile_webservices/get_subject_sallybus']  =  "Mobile_APIs/Mobile_Webservices/get_subject_sallybuss";
$route['mobile_webservices/return_syllabus_view/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)']  =  "Mobile_APIs/Mobile_Webservices/return_syllabus_view";
$route['mobile_webservices/get_single_subject_syllabus_in_webappview']  =  "Mobile_APIs/Mobile_Webservices/get_single_subject_syllabus_in_webappview";
$route['mobile_webservices/get_per_student_teacher']  =  "Mobile_APIs/Mobile_Webservices/get_per_student_teacher";
$route['mobile_webservices/logout']  =  "Mobile_APIs/Mobile_Webservices/Logout";
$route['mobile_webservices/getdatafornotification']  =  "Mobile_APIs/Mobile_Webservices/getDataForNotification";

//teacher portal mobile webservices
$route['mobile_webservices_teacher/index']  =  "Mobile_APIs/Mobile_Webservices_Teacher/index";
$route['mobile_webservices_teacher/authenticate']  =  "Mobile_APIs/Mobile_Webservices_Teacher/authenticate";
$route['mobile_webservices_teacher/noticeboard']  =  "Mobile_APIs/Mobile_Webservices_Teacher/noticeboard";
$route['mobile_webservices_teacher/circulars']  =  "Mobile_APIs/Mobile_Webservices_Teacher/circulars";
$route['mobile_webservices_teacher/staff_circular']  =  "Mobile_APIs/Mobile_Webservices_Teacher/staff_circular";



$route['mobile_webservices_teacher/my_class_routine']  =  "Mobile_APIs/Mobile_Webservices_Teacher/my_class_routine";
$route['mobile_webservices_teacher/class_routine']  =  "Mobile_APIs/Mobile_Webservices_Teacher/class_routine";
$route['mobile_webservices_teacher/get_all_sections']  =  "Mobile_APIs/Mobile_Webservices_Teacher/get_all_sections";
$route['mobile_webservices_teacher/manage_attendance_student']  =  "Mobile_APIs/Mobile_Webservices_Teacher/manage_attendance_student";
$route['mobile_webservices_teacher/teacher_attendance_summary']  =  "Mobile_APIs/Mobile_Webservices_Teacher/teacher_attendance_summary";
$route['mobile_webservices_teacher/view_stud_attendance']  =  "Mobile_APIs/Mobile_Webservices_Teacher/view_stud_attendance";
$route['mobile_webservices_teacher/get_my_classes_sections']  =  "Mobile_APIs/Mobile_Webservices_Teacher/get_my_classes_sections";
$route['mobile_webservices_teacher/student_attendance_summary']  =  "Mobile_APIs/Mobile_Webservices_Teacher/student_attendance_summary";
$route['mobile_webservices_teacher/get_section_student']  =  "Mobile_APIs/Mobile_Webservices_Teacher/get_section_student";
$route['mobile_webservices_teacher/get_students_for_attendance']  =  "Mobile_APIs/Mobile_Webservices_Teacher/get_students_for_attendance";
$route['mobile_webservices_teacher/apply_attendence']  =  "Mobile_APIs/Mobile_Webservices_Teacher/apply_attendence";
$route['mobile_webservices_teacher/manage_leaves']  =  "Mobile_APIs/Mobile_Webservices_Teacher/manage_leaves";
$route['mobile_webservices_teacher/diary']  =  "Mobile_APIs/Mobile_Webservices_Teacher/diary";
$route['mobile_webservices_teacher/getsubjectdiary']  =  "Mobile_APIs/Mobile_Webservices_Teacher/getsubjectdiary";
$route['mobile_webservices_teacher/get_diary_data']  =  "Mobile_APIs/Mobile_Webservices_Teacher/get_diary_data";
$route['mobile_webservices_teacher/view_students_diary_assigned']  =  "Mobile_APIs/Mobile_Webservices_Teacher/view_students_diary_assigned";
$route['mobile_webservices_teacher/get_teacher_sections']  =  "Mobile_APIs/Mobile_Webservices_Teacher/get_teacher_sections";
$route['mobile_webservices_teacher/get_section_student_subject']  =  "Mobile_APIs/Mobile_Webservices_Teacher/get_section_student_subject";
$route['mobile_webservices_teacher/edit_diary']  =  "Mobile_APIs/Mobile_Webservices_Teacher/edit_diary";
$route['mobile_webservices_teacher/get_acad_checkboxes']  =  "Mobile_APIs/Mobile_Webservices_Teacher/get_acad_checkboxes";
$route['mobile_webservices_teacher/check_planner_diary_edit']  =  "Mobile_APIs/Mobile_Webservices_Teacher/check_planner_diary_edit";
$route['mobile_webservices_teacher/assignment_details']  =  "Mobile_APIs/Mobile_Webservices_Teacher/assignment_details";
$route['mobile_webservices_teacher/view_notes']  =  "Mobile_APIs/Mobile_Webservices_Teacher/view_notes";



$route['check_out'] = 'attendance/check_out';


$route['error/server'] = 'Error_Controller/error_server';
