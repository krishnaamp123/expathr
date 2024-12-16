<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
// USER
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\ProfileUserController;
use App\Http\Controllers\User\WorkLocationController;
use App\Http\Controllers\User\EmergencyController;
use App\Http\Controllers\User\AboutController;
use App\Http\Controllers\User\LanguageController;
use App\Http\Controllers\User\WorkFieldController;
use App\Http\Controllers\User\EducationController;
use App\Http\Controllers\User\ProjectController;
use App\Http\Controllers\User\OrganizationController;
use App\Http\Controllers\User\VolunteerController;
use App\Http\Controllers\User\ExperienceController;
use App\Http\Controllers\User\CertificationController;
use App\Http\Controllers\User\SkillController;
use App\Http\Controllers\User\VacancyController;
use App\Http\Controllers\User\SourceController;
// ADMIN
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\QuestionAdminController;
use App\Http\Controllers\Admin\HrJobCategoryAdminController;
use App\Http\Controllers\Admin\HrJobAdminController;
use App\Http\Controllers\Admin\FormAdminController;
use App\Http\Controllers\Admin\UserHrjobAdminController;
use App\Http\Controllers\Admin\AnswerAdminController;
use App\Http\Controllers\Admin\InterviewAdminController;
use App\Http\Controllers\Admin\UserInterviewAdminController;

Route::get('/', function () {
    return view('welcome');
});

//AUTH
Route::get('/login',[AuthController::class,'getLogin'])->name('login');
Route::post('/login',[AuthController::class,'postLogin'])->name('postLogin');
Route::get('/register', [AuthController::class, 'getRegister'])->name('getRegister');
Route::post('/register', [AuthController::class, 'postRegister'])->name('postRegister');
Route::post('/logout',[AuthController::class,'postLogout'])->name('postLogout');

//PASSWORD RESET
Route::get('password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'reset'])->name('password.update');

//EMAIL VERIFICATION
Route::get('email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice')->middleware('auth');
Route::post('email/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify')->middleware(['signed']);

    Route::middleware(['verified'])->group(function () {
        Route::middleware(['applicant'])->group(function () {
            Route::get('/dashboarduser', [DashboardUserController::class, 'getDashboardUser'])->name('getDashboardUser');

            //PROFILE USER
            Route::get('/profile', [ProfileUserController::class, 'getProfile'])->name('getProfile');
            Route::get('/profile/profileuser/edit/{id}', [ProfileUserController::class, 'editProfile'])->name('editProfile');
            Route::put('/profile/profileuser/update/{id}', [ProfileUserController::class, 'updateProfile'])->name('updateProfile');

            //WORK LOCATION
            Route::get('/profile/worklocation/add', [WorkLocationController::class, 'addWorkLocation'])->name('addWorkLocation');
            Route::post('/profile/worklocation/store', [WorkLocationController::class, 'storeWorkLocation'])->name('storeWorkLocation');
            Route::get('/profile/worklocation/edit/{id}', [WorkLocationController::class, 'editWorkLocation'])->name('editWorkLocation');
            Route::put('/profile/worklocation/update/{id}', [WorkLocationController::class, 'updateWorkLocation'])->name('updateWorkLocation');
            Route::delete('/profile/worklocation/destroy/{id}', [WorkLocationController::class, 'destroyWorkLocation'])->name('destroyWorkLocation');

            //EMERGENCY
            Route::get('/profile/emergency/add', [EmergencyController::class, 'addEmergency'])->name('addEmergency');
            Route::post('/profile/emergency/store', [EmergencyController::class, 'storeEmergency'])->name('storeEmergency');
            Route::get('/profile/emergency/edit/{id}', [EmergencyController::class, 'editEmergency'])->name('editEmergency');
            Route::put('/profile/emergency/update/{id}', [EmergencyController::class, 'updateEmergency'])->name('updateEmergency');
            Route::delete('/profile/emergency/destroy/{id}', [EmergencyController::class, 'destroyEmergency'])->name('destroyEmergency');

            //ABOUT
            Route::get('/profile/about/add', [AboutController::class, 'addAbout'])->name('addAbout');
            Route::post('/profile/about/store', [AboutController::class, 'storeAbout'])->name('storeAbout');
            Route::get('/profile/about/edit/{id}', [AboutController::class, 'editAbout'])->name('editAbout');
            Route::put('/profile/about/update/{id}', [AboutController::class, 'updateAbout'])->name('updateAbout');
            Route::delete('/profile/about/destroy/{id}', [AboutController::class, 'destroyAbout'])->name('destroyAbout');

            //LANGUAGE
            Route::get('/profile/language/add', [LanguageController::class, 'addLanguage'])->name('addLanguage');
            Route::post('/profile/language/store', [LanguageController::class, 'storeLanguage'])->name('storeLanguage');
            Route::get('/profile/language/edit/{id}', [LanguageController::class, 'editLanguage'])->name('editLanguage');
            Route::put('/profile/language/update/{id}', [LanguageController::class, 'updateLanguage'])->name('updateLanguage');
            Route::delete('/profile/language/destroy/{id}', [LanguageController::class, 'destroyLanguage'])->name('destroyLanguage');

            //WORK FIELD
            Route::get('/profile/workfield/add', [WorkFieldController::class, 'addWorkField'])->name('addWorkField');
            Route::post('/profile/workfield/store', [WorkFieldController::class, 'storeWorkField'])->name('storeWorkField');
            Route::get('/profile/workfield/edit/{id}', [WorkFieldController::class, 'editWorkField'])->name('editWorkField');
            Route::put('/profile/workfield/update/{id}', [WorkFieldController::class, 'updateWorkField'])->name('updateWorkField');
            Route::delete('/profile/workfield/destroy/{id}', [WorkFieldController::class, 'destroyWorkField'])->name('destroyWorkField');

            //EDUCATION
            Route::get('/profile/education/add', [EducationController::class, 'addEducation'])->name('addEducation');
            Route::post('/profile/education/store', [EducationController::class, 'storeEducation'])->name('storeEducation');
            Route::get('/profile/education/edit/{id}', [EducationController::class, 'editEducation'])->name('editEducation');
            Route::put('/profile/education/update/{id}', [EducationController::class, 'updateEducation'])->name('updateEducation');
            Route::delete('/profile/education/destroy/{id}', [EducationController::class, 'destroyEducation'])->name('destroyEducation');

            //PROJECT
            Route::get('/profile/project/add', [ProjectController::class, 'addProject'])->name('addProject');
            Route::post('/profile/project/store', [ProjectController::class, 'storeProject'])->name('storeProject');
            Route::get('/profile/project/edit/{id}', [ProjectController::class, 'editProject'])->name('editProject');
            Route::put('/profile/project/update/{id}', [ProjectController::class, 'updateProject'])->name('updateProject');
            Route::delete('/profile/project/destroy/{id}', [ProjectController::class, 'destroyProject'])->name('destroyProject');

            //ORGANIZATION
            Route::get('/profile/organization/add', [OrganizationController::class, 'addOrganization'])->name('addOrganization');
            Route::post('/profile/organization/store', [OrganizationController::class, 'storeOrganization'])->name('storeOrganization');
            Route::get('/profile/organization/edit/{id}', [OrganizationController::class, 'editOrganization'])->name('editOrganization');
            Route::put('/profile/organization/update/{id}', [OrganizationController::class, 'updateOrganization'])->name('updateOrganization');
            Route::delete('/profile/organization/destroy/{id}', [OrganizationController::class, 'destroyOrganization'])->name('destroyOrganization');

            //VOLUNTEER
            Route::get('/profile/volunteer/add', [VolunteerController::class, 'addVolunteer'])->name('addVolunteer');
            Route::post('/profile/volunteer/store', [VolunteerController::class, 'storeVolunteer'])->name('storeVolunteer');
            Route::get('/profile/volunteer/edit/{id}', [VolunteerController::class, 'editVolunteer'])->name('editVolunteer');
            Route::put('/profile/volunteer/update/{id}', [VolunteerController::class, 'updateVolunteer'])->name('updateVolunteer');
            Route::delete('/profile/volunteer/destroy/{id}', [VolunteerController::class, 'destroyVolunteer'])->name('destroyVolunteer');

            //EXPERIENCE
            Route::get('/profile/experience/add', [ExperienceController::class, 'addExperience'])->name('addExperience');
            Route::post('/profile/experience/store', [ExperienceController::class, 'storeExperience'])->name('storeExperience');
            Route::get('/profile/experience/edit/{id}', [ExperienceController::class, 'editExperience'])->name('editExperience');
            Route::put('/profile/experience/update/{id}', [ExperienceController::class, 'updateExperience'])->name('updateExperience');
            Route::delete('/profile/experience/destroy/{id}', [ExperienceController::class, 'destroyExperience'])->name('destroyExperience');

            //CERTIFICATION
            Route::get('/profile/certification/add', [CertificationController::class, 'addCertification'])->name('addCertification');
            Route::post('/profile/certification/store', [CertificationController::class, 'storeCertification'])->name('storeCertification');
            Route::get('/profile/certification/edit/{id}', [CertificationController::class, 'editCertification'])->name('editCertification');
            Route::put('/profile/certification/update/{id}', [CertificationController::class, 'updateCertification'])->name('updateCertification');
            Route::delete('/profile/certification/destroy/{id}', [CertificationController::class, 'destroyCertification'])->name('destroyCertification');

            //SKILL
            Route::get('/profile/skill/add', [SkillController::class, 'addSkill'])->name('addSkill');
            Route::post('/profile/skill/store', [SkillController::class, 'storeSkill'])->name('storeSkill');
            Route::get('/profile/skill/edit/{id}', [SkillController::class, 'editSkill'])->name('editSkill');
            Route::put('/profile/skill/update/{id}', [SkillController::class, 'updateSkill'])->name('updateSkill');
            Route::delete('/profile/skill/destroy/{id}', [SkillController::class, 'destroySkill'])->name('destroySkill');

            //SOURCE
            Route::get('/profile/source/add', [SourceController::class, 'addSource'])->name('addSource');
            Route::post('/profile/source/store', [SourceController::class, 'storeSource'])->name('storeSource');
            Route::get('/profile/source/edit/{id}', [SourceController::class, 'editSource'])->name('editSource');
            Route::put('/profile/source/update/{id}', [SourceController::class, 'updateSource'])->name('updateSource');
            Route::delete('/profile/source/destroy/{id}', [SourceController::class, 'destroySource'])->name('destroySource');

            //VACANCY
            Route::get('/user/job/get', [VacancyController::class, 'getVacancy'])->name('getVacancy');
            Route::post('/user/job/store', [VacancyController::class, 'storeVacancy'])->name('storeVacancy');
            Route::delete('/user/job/destroy/{id}', [VacancyController::class, 'destroyVacancy'])->name('destroyVacancy');
            Route::get('/user/myjob/get', [VacancyController::class, 'getMyVacancy'])->name('getMyVacancy');
            Route::post('/user/myjob/store-answers', [VacancyController::class, 'storeMyAnswer'])->name('storeMyAnswer');
            Route::get('/user/myjob/interview', [VacancyController::class, 'getIntervieww'])->name('getIntervieww');
        });


        Route::middleware(['admin'])->group(function () {
            Route::get('/dashboardadmin', [DashboardAdminController::class, 'getDashboardAdmin'])->name('getDashboardAdmin');

            // USER
            Route::get('/user', [UserAdminController::class, 'getUser'])->name('getUser');
            Route::get('/user/create', [UserAdminController::class, 'addUser'])->name('addUser');
            Route::post('/user/create', [UserAdminController::class, 'storeUser'])->name('storeUser');
            Route::get('/user/update/{id}', [UserAdminController::class, 'editUser'])->name('editUser');
            Route::put('/user/update/{id}', [UserAdminController::class, 'updateUser'])->name('updateUser');
            Route::delete('/user/destroy/{id}', [UserAdminController::class, 'destroyUser'])->name('destroyUser');
            Route::get('/user/{id}/pdf', [UserAdminController::class, 'generatePdf'])->name('profile.pdf');

            // QUESTION
            Route::get('/question', [QuestionAdminController::class, 'getQuestion'])->name('getQuestion');
            Route::get('/question/create', [QuestionAdminController::class, 'addQuestion'])->name('addQuestion');
            Route::post('/question/create', [QuestionAdminController::class, 'storeQuestion'])->name('storeQuestion');
            Route::get('/question/update/{id}', [QuestionAdminController::class, 'editQuestion'])->name('editQuestion');
            Route::put('/question/update/{id}', [QuestionAdminController::class, 'updateQuestion'])->name('updateQuestion');
            Route::delete('/question/destroy/{id}', [QuestionAdminController::class, 'destroyQuestion'])->name('destroyQuestion');

            // JOB CATEGORY
            Route::get('/jobcategory', [HrjobCategoryAdminController::class, 'getHrjobCategory'])->name('getHrjobCategory');
            Route::get('/jobcategory/create', [HrjobCategoryAdminController::class, 'addHrjobCategory'])->name('addHrjobCategory');
            Route::post('/jobcategory/create', [HrjobCategoryAdminController::class, 'storeHrjobCategory'])->name('storeHrjobCategory');
            Route::get('/jobcategory/update/{id}', [HrjobCategoryAdminController::class, 'editHrjobCategory'])->name('editHrjobCategory');
            Route::put('/jobcategory/update/{id}', [HrjobCategoryAdminController::class, 'updateHrjobCategory'])->name('updateHrjobCategory');
            Route::delete('/jobcategory/destroy/{id}', [HrjobCategoryAdminController::class, 'destroyHrjobCategory'])->name('destroyHrjobCategory');

            // JOB
            Route::get('/job', [HrjobAdminController::class, 'getHrjob'])->name('getHrjob');
            Route::get('/job/create', [HrjobAdminController::class, 'addHrjob'])->name('addHrjob');
            Route::post('/job/create', [HrjobAdminController::class, 'storeHrjob'])->name('storeHrjob');
            Route::get('/job/update/{id}', [HrjobAdminController::class, 'editHrjob'])->name('editHrjob');
            Route::put('/job/update/{id}', [HrjobAdminController::class, 'updateHrjob'])->name('updateHrjob');
            Route::delete('/job/destroy/{id}', [HrjobAdminController::class, 'destroyHrjob'])->name('destroyHrjob');

            // FORM
            Route::get('/form', [FormAdminController::class, 'getForm'])->name('getForm');
            Route::get('/form/create', [FormAdminController::class, 'addForm'])->name('addForm');
            Route::post('/form/create', [FormAdminController::class, 'storeForm'])->name('storeForm');
            Route::get('/form/update/{id}', [FormAdminController::class, 'editForm'])->name('editForm');
            Route::put('/form/update/{id}', [FormAdminController::class, 'updateForm'])->name('updateForm');
            Route::delete('/form/destroy/{id}', [FormAdminController::class, 'destroyForm'])->name('destroyForm');

            // USERHRJOB
            Route::get('/userjob', [UserHrjobAdminController::class, 'getUserHrjob'])->name('getUserHrjob');
            Route::get('/userjob/create', [UserHrjobAdminController::class, 'addUserHrjob'])->name('addUserHrjob');
            Route::post('/userjob/create', [UserHrjobAdminController::class, 'storeUserHrjob'])->name('storeUserHrjob');
            Route::get('/userjob/update/{id}', [UserHrjobAdminController::class, 'editUserHrjob'])->name('editUserHrjob');
            Route::put('/userjob/update/{id}', [UserHrjobAdminController::class, 'updateUserHrjob'])->name('updateUserHrjob');
            Route::delete('/userjob/destroy/{id}', [UserHrjobAdminController::class, 'destroyUserHrjob'])->name('destroyUserHrjob');
            Route::post('/userjob/update-status/{id}', [UserHrjobAdminController::class, 'updateStatus'])->name('updateStatus');

            // ANSWER
            Route::get('/answer', [AnswerAdminController::class, 'getAnswer'])->name('getAnswer');
            Route::get('/answer/create', [AnswerAdminController::class, 'addAnswer'])->name('addAnswer');
            Route::post('/answer/create', [AnswerAdminController::class, 'storeAnswer'])->name('storeAnswer');
            Route::get('/answer/update/{id}', [AnswerAdminController::class, 'editAnswer'])->name('editAnswer');
            Route::put('/answer/update/{id}', [AnswerAdminController::class, 'updateAnswer'])->name('updateAnswer');
            Route::delete('/answer/destroy/{id}', [AnswerAdminController::class, 'destroyAnswer'])->name('destroyAnswer');

            // INTERVIEW
            Route::get('/interview', [InterviewAdminController::class, 'getInterview'])->name('getInterview');
            Route::get('/interview/create', [InterviewAdminController::class, 'addInterview'])->name('addInterview');
            Route::get('/userjob/interview/create', [InterviewAdminController::class, 'addUserHrjobInterview'])->name('addUserHrjobInterview');
            Route::post('/interview/create', [InterviewAdminController::class, 'storeInterview'])->name('storeInterview');
            Route::get('/interview/update/{id}', [InterviewAdminController::class, 'editInterview'])->name('editInterview');
            Route::get('/userjob/interview/update/{id}', [InterviewAdminController::class, 'editUserHrjobInterview'])->name('editUserHrjobInterview');
            Route::put('/interview/update/{id}', [InterviewAdminController::class, 'updateInterview'])->name('updateInterview');
            Route::delete('/interview/destroy/{id}', [InterviewAdminController::class, 'destroyInterview'])->name('destroyInterview');
            Route::delete('/userjob/interview/destroy/{id}', [InterviewAdminController::class, 'destroyUserHrjobInterview'])->name('destroyUserHrjobInterview');
            Route::get('/interview/update/rating/{id}', [InterviewAdminController::class, 'editRating'])->name('editRating');
            Route::get('/userjob/interview/update/rating/{id}', [InterviewAdminController::class, 'editUserHrjobRating'])->name('editUserHrjobRating');
            Route::put('/interview/update/rating/{id}', [InterviewAdminController::class, 'updateRating'])->name('updateRating');
            Route::get('/interview/export', [InterviewAdminController::class, 'exportInterview'])->name('exportInterview');
            Route::get('/interview/dateexport', [InterviewAdminController::class, 'exportdateInterview'])->name('exportdateInterview');


            // USER INTERVIEW
            Route::get('/userinterview', [UserInterviewAdminController::class, 'getUserInterview'])->name('getUserInterview');
            Route::get('/userinterview/create', [UserInterviewAdminController::class, 'addUserInterview'])->name('addUserInterview');
            Route::get('/userjob/userinterview/create', [UserInterviewAdminController::class, 'addUserHrjobUserInterview'])->name('addUserHrjobUserInterview');
            Route::post('/userinterview/create', [UserInterviewAdminController::class, 'storeUserInterview'])->name('storeUserInterview');
            Route::get('/userinterview/update/{id}', [UserInterviewAdminController::class, 'editUserInterview'])->name('editUserInterview');
            Route::get('/userjob/userinterview/update/{id}', [UserInterviewAdminController::class, 'editUserHrjobUserInterview'])->name('editUserHrjobUserInterview');
            Route::put('/userinterview/update/{id}', [UserInterviewAdminController::class, 'updateUserInterview'])->name('updateUserInterview');
            Route::delete('/userinterview/destroy/{id}', [UserInterviewAdminController::class, 'destroyUserInterview'])->name('destroyUserInterview');
            Route::delete('/userjob/userinterview/destroy/{id}', [UserInterviewAdminController::class, 'destroyUserHrjobUserInterview'])->name('destroyUserHrjobUserInterview');
            Route::get('/userinterview/update/rating/{id}', [UserInterviewAdminController::class, 'editUserRating'])->name('editUserRating');
            Route::get('/userjob/userinterview/update/rating/{id}', [UserInterviewAdminController::class, 'editUserHrjobUserRating'])->name('editUserHrjobUserRating');
            Route::put('/userinterview/update/rating/{id}', [UserInterviewAdminController::class, 'updateUserRating'])->name('updateUserRating');
            Route::get('/userinterview/export', [UserInterviewAdminController::class, 'exportUserInterview'])->name('exportUserInterview');
            Route::get('/userinterview/dateexport', [UserInterviewAdminController::class, 'exportdateUserInterview'])->name('exportdateUserInterview');

        });
    });
});
