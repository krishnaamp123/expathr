<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardAdminController;
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
use App\Http\Controllers\Admin\UserAdminController;

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
Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify')->middleware(['signed']);
Route::post('email/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['verified'])->group(function () {
        Route::middleware(['applicant'])->group(function () {
            Route::get('/dashboarduser', [DashboardUserController::class, 'getDashboardUser'])->name('getDashboardUser');
            Route::get('/team', [DashboardUserController::class, 'getTeam'])->name('getTeam');

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


        });
    });
});
