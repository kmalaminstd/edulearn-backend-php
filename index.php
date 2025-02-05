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
            require './src/routes/getCategories.php';
            break;
        case '/publish-course.php':
            require './src/routes/publish-course.php';
            break;
        case '/mycourse-list.php':
            require './src/routes/mycourseList.php';
            break;
        case '/teacher-single-course-view.php';
            require './src/routes/teacherSingleCourse.php';
            break;
        case '/user-showed-course-list.php';
            require './src/routes/userShowedCourseList.php';
            break;
        case '/enroll-course.php';
            require './src/routes/enroll-course.php';
            break;
        case '/user-enrolled-course.php';
            require './src/routes/myEnrolledCourse.php';
            break;
        case '/user-requested-course-for-show.php';
            require './src/routes/userSelectedCourseToShow.php';
            break;
        case '/update-last-activity.php';
            require './src/routes/updateLastActivity.php';
            break;
        case '/course-list-from-admin.php';
            require './src/routes/courseListForAdmin.php'; // access the course list from admin dashboard
            break;
        case '/admin-recent-course-list.php';
            require './src/routes/recentCourseListForAdmin.php';
            break;
        default:
            echo "Page not found";
            break;
    }

    

?>