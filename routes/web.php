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
use App\Http\Controllers\User\VacancyController;
use App\Http\Controllers\User\SourceController;
use App\Http\Controllers\User\LinkController;
use App\Http\Controllers\User\WorkSkillController;
use App\Http\Controllers\User\ReferenceController;
// ADMIN
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\OutletAdminController;
use App\Http\Controllers\Admin\HrJobCategoryAdminController;
use App\Http\Controllers\Admin\HrJobAdminController;
use App\Http\Controllers\Admin\FormAdminController;
use App\Http\Controllers\Admin\FormHrjobAdminController;
use App\Http\Controllers\Admin\UserHrjobAdminController;
use App\Http\Controllers\Admin\UserAnswerAdminController;
use App\Http\Controllers\Admin\InterviewAdminController;
use App\Http\Controllers\Admin\UserInterviewAdminController;
use App\Http\Controllers\Admin\UserHrjobHistoryAdminController;
use App\Http\Controllers\Admin\SkillTestAdminController;
use App\Http\Controllers\Admin\PhoneScreenAdminController;
use App\Http\Controllers\Admin\SkillAdminController;
use App\Http\Controllers\Admin\ReferenceCheckAdminController;
use App\Http\Controllers\Admin\OfferingAdminController;


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
    Route::get('/', function () {
        // return view('welcome');
    });
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

            //REFERENCE
            Route::get('/profile/reference/add', [ReferenceController::class, 'addReference'])->name('addReference');
            Route::post('/profile/reference/store', [ReferenceController::class, 'storeReference'])->name('storeReference');
            Route::get('/profile/reference/edit/{id}', [ReferenceController::class, 'editReference'])->name('editReference');
            Route::put('/profile/reference/update/{id}', [ReferenceController::class, 'updateReference'])->name('updateReference');
            Route::delete('/profile/reference/destroy/{id}', [ReferenceController::class, 'destroyReference'])->name('destroyReference');

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

            //LINK
            Route::get('/profile/link/add', [LinkController::class, 'addLink'])->name('addLink');
            Route::post('/profile/link/store', [LinkController::class, 'storeLink'])->name('storeLink');
            Route::get('/profile/link/edit/{id}', [LinkController::class, 'editLink'])->name('editLink');
            Route::put('/profile/link/update/{id}', [LinkController::class, 'updateLink'])->name('updateLink');
            Route::delete('/profile/link/destroy/{id}', [LinkController::class, 'destroyLink'])->name('destroyLink');

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

            //WORK SKILL
            Route::get('/profile/skill/add', [WorkSkillController::class, 'addWorkSkill'])->name('addWorkSkill');
            Route::post('/profile/skill/store', [WorkSkillController::class, 'storeWorkSkill'])->name('storeWorkSkill');
            Route::get('/profile/skill/edit/{id}', [WorkSkillController::class, 'editWorkSkill'])->name('editWorkSkill');
            Route::put('/profile/skill/update/{id}', [WorkSkillController::class, 'updateWorkSkill'])->name('updateWorkSkill');
            Route::delete('/profile/skill/destroy/{id}', [WorkSkillController::class, 'destroyWorkSkill'])->name('destroyWorkSkill');

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
            Route::put('/user/myjob/interview/{interview}/confirm', [VacancyController::class, 'confirmArrival'])->name('confirmArrival');
            Route::put('/user/myjob/userinterview/{userinterview}/confirm', [VacancyController::class, 'confirmUserArrival'])->name('confirmUserArrival');
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

            // OUTLET
            Route::get('/outlet', [OutletAdminController::class, 'getOutlet'])->name('getOutlet');
            Route::get('/outlet/create', [OutletAdminController::class, 'addOutlet'])->name('addOutlet');
            Route::post('/outlet/create', [OutletAdminController::class, 'storeOutlet'])->name('storeOutlet');
            Route::get('/outlet/update/{id}', [OutletAdminController::class, 'editOutlet'])->name('editOutlet');
            Route::put('/outlet/update/{id}', [OutletAdminController::class, 'updateOutlet'])->name('updateOutlet');
            Route::delete('/outlet/destroy/{id}', [OutletAdminController::class, 'destroyOutlet'])->name('destroyOutlet');

            // SKILL
            Route::get('/skill', [SkillAdminController::class, 'getSkill'])->name('getSkill');
            Route::get('/skill/create', [SkillAdminController::class, 'addSkill'])->name('addSkill');
            Route::post('/skill/create', [SkillAdminController::class, 'storeSkill'])->name('storeSkill');
            Route::get('/skill/update/{id}', [SkillAdminController::class, 'editSkill'])->name('editSkill');
            Route::put('/skill/update/{id}', [SkillAdminController::class, 'updateSkill'])->name('updateSkill');
            Route::delete('/skill/destroy/{id}', [SkillAdminController::class, 'destroySkill'])->name('destroySkill');

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
            Route::post('/job/update-is-ended/{id}', [HrjobAdminController::class, 'updateIsEnded'])->name('updateIsEnded');

            // FORM
            Route::get('/form', [FormAdminController::class, 'getForm'])->name('getForm');
            Route::get('/form/create', [FormAdminController::class, 'addForm'])->name('addForm');
            Route::post('/form/create', [FormAdminController::class, 'storeForm'])->name('storeForm');
            Route::get('/form/update/{id}', [FormAdminController::class, 'editForm'])->name('editForm');
            Route::put('/form/update/{id}', [FormAdminController::class, 'updateForm'])->name('updateForm');
            Route::delete('/form/destroy/{id}', [FormAdminController::class, 'destroyForm'])->name('destroyForm');

            // FORM JOB
            Route::get('/formjob', [FormHrjobAdminController::class, 'getFormHrjob'])->name('getFormHrjob');
            Route::get('/formjob/create', [FormHrjobAdminController::class, 'addFormHrjob'])->name('addFormHrjob');
            Route::post('/formjob/create', [FormHrjobAdminController::class, 'storeFormHrjob'])->name('storeFormHrjob');
            Route::get('/formjob/update/{id}', [FormHrjobAdminController::class, 'editFormHrjob'])->name('editFormHrjob');
            Route::put('/formjob/update/{id}', [FormHrjobAdminController::class, 'updateFormHrjob'])->name('updateFormHrjob');
            Route::delete('/formjob/destroy/{id}', [FormHrjobAdminController::class, 'destroyFormHrjob'])->name('destroyFormHrjob');

            // USERHRJOB
            Route::get('/userjob', [UserHrjobAdminController::class, 'getUserHrjob'])->name('getUserHrjob');
            Route::get('/userjob/create', [UserHrjobAdminController::class, 'addUserHrjob'])->name('addUserHrjob');
            Route::post('/userjob/create', [UserHrjobAdminController::class, 'storeUserHrjob'])->name('storeUserHrjob');
            Route::get('/userjob/update/{id}', [UserHrjobAdminController::class, 'editUserHrjob'])->name('editUserHrjob');
            Route::put('/userjob/update/{id}', [UserHrjobAdminController::class, 'updateUserHrjob'])->name('updateUserHrjob');
            Route::delete('/userjob/destroy/{id}', [UserHrjobAdminController::class, 'destroyUserHrjob'])->name('destroyUserHrjob');
            Route::post('/userjob/update-status/{id}', [UserHrjobAdminController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/bulk-reject-status', [UserHrjobAdminController::class, 'bulkRejectStatus'])->name('bulkRejectStatus');
            Route::get('/userjob/export', [UserHrjobAdminController::class, 'exportUserHrjob'])->name('exportUserHrjob');
            Route::get('/userjob/dateexport', [UserHrjobAdminController::class, 'exportdateUserHrjob'])->name('exportdateUserHrjob');
            Route::get('/modal/interview', [UserHrjobAdminController::class, 'getInterviewModal'])->name('getInterviewModal');
            Route::get('/modal/userinterview', [UserHrjobAdminController::class, 'getUserInterviewModal'])->name('getUserInterviewModal');


            // USERHRJOBHISTORY
            Route::get('/userjob/history', [UserHrjobHistoryAdminController::class, 'getUserHrjobHistory'])->name('getUserHrjobHistory');
            Route::delete('/userjob/history/destroy/{id}', [UserHrjobHistoryAdminController::class, 'destroyUserHrjobHistory'])->name('destroyUserHrjobHistory');

            // ANSWER
            Route::get('/useranswer', [UserAnswerAdminController::class, 'getUserAnswer'])->name('getUserAnswer');
            Route::delete('/useranswer/destroy/{id}', [UserAnswerAdminController::class, 'destroyUserAnswer'])->name('destroyUserAnswer');

            // INTERVIEW
            Route::get('/interview', [InterviewAdminController::class, 'getInterview'])->name('getInterview');
            Route::post('/interview/create', [InterviewAdminController::class, 'storeInterview'])->name('storeInterview');
            Route::put('/interview/update/{id}', [InterviewAdminController::class, 'updateInterview'])->name('updateInterview');
            Route::delete('/interview/destroy/{id}', [InterviewAdminController::class, 'destroyInterview'])->name('destroyInterview');
            Route::put('/interview/update/rating/{id}', [InterviewAdminController::class, 'updateRating'])->name('updateRating');
            Route::get('/interview/export', [InterviewAdminController::class, 'exportInterview'])->name('exportInterview');
            Route::get('/interview/dateexport', [InterviewAdminController::class, 'exportdateInterview'])->name('exportdateInterview');

            // USER INTERVIEW
            Route::get('/userinterview', [UserInterviewAdminController::class, 'getUserInterview'])->name('getUserInterview');
            Route::post('/userinterview/create', [UserInterviewAdminController::class, 'storeUserInterview'])->name('storeUserInterview');
            Route::put('/userinterview/update/{id}', [UserInterviewAdminController::class, 'updateUserInterview'])->name('updateUserInterview');
            Route::delete('/userinterview/destroy/{id}', [UserInterviewAdminController::class, 'destroyUserInterview'])->name('destroyUserInterview');
            Route::put('/userinterview/update/rating/{id}', [UserInterviewAdminController::class, 'updateUserRating'])->name('updateUserRating');
            Route::get('/userinterview/export', [UserInterviewAdminController::class, 'exportUserInterview'])->name('exportUserInterview');
            Route::get('/userinterview/dateexport', [UserInterviewAdminController::class, 'exportdateUserInterview'])->name('exportdateUserInterview');

            // SKILL TEST
            Route::get('/skilltest', [SkillTestAdminController::class, 'getSkillTest'])->name('getSkillTest');
            Route::post('/skilltest/create', [SkillTestAdminController::class, 'storeSkillTest'])->name('storeSkillTest');
            Route::put('/skilltest/update/{id}', [SkillTestAdminController::class, 'updateSkillTest'])->name('updateSkillTest');
            Route::delete('/skilltest/destroy/{id}', [SkillTestAdminController::class, 'destroySkillTest'])->name('destroySkillTest');
            Route::get('/skilltest/export', [SkillTestAdminController::class, 'exportSkillTest'])->name('exportSkillTest');
            Route::get('/skilltest/dateexport', [SkillTestAdminController::class, 'exportdateSkillTest'])->name('exportdateSkillTest');

            // PHONE SCREEN
            Route::get('/phonescreen', [PhoneScreenAdminController::class, 'getPhoneScreen'])->name('getPhoneScreen');
            Route::post('/phonescreen/create', [PhoneScreenAdminController::class, 'storePhoneScreen'])->name('storePhoneScreen');
            Route::put('/phonescreen/update/{id}', [PhoneScreenAdminController::class, 'updatePhoneScreen'])->name('updatePhoneScreen');
            Route::delete('/phonescreen/destroy/{id}', [PhoneScreenAdminController::class, 'destroyPhoneScreen'])->name('destroyPhoneScreen');
            Route::get('/phonescreen/export', [PhoneScreenAdminController::class, 'exportPhoneScreen'])->name('exportPhoneScreen');
            Route::get('/phonescreen/dateexport', [PhoneScreenAdminController::class, 'exportdatePhoneScreen'])->name('exportdatePhoneScreen');

            // REFERENCE CHECK
            Route::get('/referencecheck', [ReferenceCheckAdminController::class, 'getReferenceCheck'])->name('getReferenceCheck');
            Route::post('/referencecheck/create', [ReferenceCheckAdminController::class, 'storeReferenceCheck'])->name('storeReferenceCheck');
            Route::put('/referencecheck/update/{id}', [ReferenceCheckAdminController::class, 'updateReferenceCheck'])->name('updateReferenceCheck');
            Route::delete('/referencecheck/destroy/{id}', [ReferenceCheckAdminController::class, 'destroyReferenceCheck'])->name('destroyReferenceCheck');
            Route::get('/referencecheck/export', [ReferenceCheckAdminController::class, 'exportReferenceCheck'])->name('exportReferenceCheck');
            Route::get('/referencecheck/dateexport', [ReferenceCheckAdminController::class, 'exportdateReferenceCheck'])->name('exportdateReferenceCheck');

            // OFFERING
            Route::get('/offering', [OfferingAdminController::class, 'getOffering'])->name('getOffering');
            Route::post('/offering/create', [OfferingAdminController::class, 'storeOffering'])->name('storeOffering');
            Route::post('/offering/update/{id}', [OfferingAdminController::class, 'updateOffering'])->name('updateOffering');
            Route::delete('/offering/destroy/{id}', [OfferingAdminController::class, 'destroyOffering'])->name('destroyOffering');
            Route::get('/offering/export', [OfferingAdminController::class, 'exportOffering'])->name('exportOffering');
            Route::get('/offering/dateexport', [OfferingAdminController::class, 'exportdateOffering'])->name('exportdateOffering');

        });
    });
});
