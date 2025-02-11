<?php

    require "vendor/autoload.php";

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // $database = new Database();
    // $database->connect();

    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basepath = "/eduwebbackend";
    $requestedUri = str_replace($basepath , '' , $path);

    // echo $requestedUri;

    switch($requestedUri){
        case '/registration.php':
            require "src/routes/registration.php";
            break;
        case '/login.php':
            require "src/routes/login.php";
            break;
        case '/upload-video.php':
            require './src/routes/UploadVideo.php';
            break;
        case'/admin-login.php':
            require './src/routes/adminLogin.php';
            break;
        case'/userlist.php':
            require './src/routes/getAllUsers.php';
            break;
        case '/studentlist.php':
            require './src/routes/student-list.php';
            break;
        case'/teacherlist.php':
            require './src/routes/teacherList.php';
            break;
        case'/recentuser.php':
            require './src/routes/recentUsers.php';
            break;
        case'/getstatinfo.php':
            require './src/routes/statCounter.php';
            break;
        case'/update-profile.php':
            require './src/routes/updateprofile.php';
            break;
        case '/user-info.php':
            require './src/routes/user-info.php';
            break;
        case '/all-category.php':
            require './src/routes/getCategories.php'; // getting all course category
            break;
        case '/publish-course.php':
            require './src/routes/publish-course.php'; // teacher publishing his course
            break;
        case '/mycourse-list.php':
            require './src/routes/mycourseList.php'; // teacher getting his own uploaded course
            break;
        case '/teacher-single-course-view.php';
            require './src/routes/teacherSingleCourse.php'; // teacher viewing his own course video
            break;
        case '/user-showed-course-list.php';
            require './src/routes/userShowedCourseList.php'; // showing course list to users
            break;
        case '/enroll-course.php';
            require './src/routes/enroll-course.php'; // user enrolling course
            break;
        case '/user-enrolled-course.php';
            require './src/routes/myEnrolledCourse.php'; // user viewing his enrolled course
            break;
        case '/user-requested-course-for-show.php';
            require './src/routes/userSelectedCourseToShow.php'; // user viewing his own course to see the video
            break;
        case '/update-last-activity.php';
            require './src/routes/updateLastActivity.php'; // updating users last activity in database
            break;
        case '/course-list-from-admin.php';
            require './src/routes/courseListForAdmin.php'; // access the course list from admin dashboard
            break;
        case '/admin-recent-course-list.php';
            require './src/routes/recentCourseListForAdmin.php'; // getting recent users from admin dashboard
            break;
        case '/publisher-delete-course.php';
            require './src/routes/publisherDeleteCourse.php'; // publisher deleting his own course
            break;
        case '/admin-delete-course.php';
            require './src/routes/adminCourseDelete.php'; // admin will delet course from admin dashboard
            break;
        case '/publisher-update-course.php';
            require './src/routes/publisherEditCourse.php'; // update course the publisher
            break;
        case '/reset-password-request.php';
            require './src/routes/requestResetPassword.php'; // request password reset and get reset link
            break;
        case '/confirm-reset-password.php';
            require './src/routes/confirmResetPassword.php'; // confirm the reset password and set a new password
            break;
        default:
            echo "Page not found";
            break;
    }

    

?>