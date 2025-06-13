<?php

use Illuminate\Support\Facades\Route;

/** ACL */

use App\Http\Controllers\Admin\Acl\ModuleCategoryController;
use App\Http\Controllers\Admin\Acl\ModuleController;
use App\Http\Controllers\Admin\Acl\RoleController;
use App\Http\Controllers\Admin\Acl\AdminUserController;
use App\Http\Controllers\Admin\PortfolioAnalysis\WebsiteContactEmailsController;
use App\Http\Controllers\Admin\Resume\ResumeAboutSectionsController;
use App\Http\Controllers\Admin\Resume\ResumeEducationSectionsController;
use App\Http\Controllers\Admin\Resume\ResumeExperienceSectionsController;
use App\Http\Controllers\Admin\Resume\ResumeGeneratePdfController;
use App\Http\Controllers\Admin\Resume\ResumeHappySectionsController;
use App\Http\Controllers\Admin\Resume\ResumeHeaderSectionsController;
use App\Http\Controllers\Admin\Resume\ResumeSidebarSectionsController;
use App\Http\Controllers\Admin\Resume\ResumeInterestSectionsController;
use App\Http\Controllers\Admin\Resume\ResumeLanguageSectionsController;
use App\Http\Controllers\Admin\Resume\ResumeLiveLinkSectionsController;
use App\Http\Controllers\Admin\Resume\ResumePortfolioSettingsController;
use App\Http\Controllers\Admin\Resume\ResumeProjectCategoriesController;
use App\Http\Controllers\Admin\Resume\ResumeProjectSectionsController;
use App\Http\Controllers\Admin\Resume\ResumeServiceSectionsController;
use App\Http\Controllers\Admin\Resume\ResumeSkillSectionsController;
use App\Http\Controllers\Admin\Resume\ResumeTestimonialSectionsController;
use App\Http\Controllers\Admin\Test\TestController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\LanguagesController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\Admin\PortfolioAnalysis\WebsiteContactsController;
use App\Http\Controllers\Admin\PortfolioAnalysis\WebsiteContactServicesController;
use Illuminate\Support\Facades\Artisan;

// Route::get('', function () {
//     return view('adminLogin');
// });

Route::get('test-mail', [TestController::class, 'testMail']);

Route::get('', [AdminLoginController::class, 'index']);
Route::get('login', [AdminLoginController::class, 'showLogin'])->name('login');
Route::post('login', [AdminLoginController::class, 'doLogin']);
Route::get('logout', [AdminLoginController::class, 'logout'])->name('logout');

Route::get('resume-pdf', [TestController::class, 'resumePdf'])->name('resume.pdf');

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return 'Cache Cleared Successfully'; //Return anything
});


Route::group(['middleware' => ['XSS', 'Admin']], function () {

    Route::get('logout', [AdminLoginController::class, 'logout']);
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    //ACL
    Route::get('acl/module-categories', [ModuleCategoryController::class, 'index'])->name('acl.module.category.listing');
    Route::get('acl/module-categories/show/{id}', [ModuleCategoryController::class, 'show'])->name('acl.module.category.show');
    Route::get('acl/module-categories/edit/{id}', [ModuleCategoryController::class, 'edit'])->name('acl.module.category.edit');
    Route::post('acl/module-categories/edit/{id}', [ModuleCategoryController::class, 'update']);
    Route::get('acl/module-categories/add', [ModuleCategoryController::class, 'create'])->name('acl.module.category.add');
    Route::post('acl/module-categories/add', [ModuleCategoryController::class, 'store']);
    Route::get('acl/module-categories/search', [ModuleCategoryController::class, 'searchModuleCategory']);
    Route::get('acl/module-categories/delete/{id}', [ModuleCategoryController::class, 'destroy'])->name('acl.module.category.delete');
    Route::get('acl/module-categories/do-edit/{id}/{display_order}', [ModuleCategoryController::class, 'updateDisplayOrder']);

    Route::get('acl/module', [ModuleController::class, 'index'])->name('acl.module.listing');
    Route::get('acl/module/show/{id}', [ModuleController::class, 'show'])->name('acl.module.show');
    Route::get('acl/module/edit/{id}', [ModuleController::class, 'edit'])->name('acl.module.edit');
    Route::post('acl/module/edit/{id}', [ModuleController::class, 'update']);
    Route::get('acl/module/add', [ModuleController::class, 'create'])->name('acl.module.add');
    Route::post('acl/module/add', [ModuleController::class, 'store']);
    Route::get('acl/module/search', [ModuleController::class, 'searchModule']);
    Route::get('acl/module/delete/{id}', [ModuleController::class, 'destroy'])->name('acl.module.delete');
    Route::get('acl/module/do-edit/{id}/{display_order}', [ModuleController::class, 'updateDisplayOrder']);

    Route::get('acl/role', [RoleController::class, 'index'])->name('acl/role');
    Route::get('acl/role/show/{id}', [RoleController::class, 'show'])->name('acl.role.show');
    Route::get('acl/role/edit/{id}', [RoleController::class, 'edit'])->name('acl.role.edit');
    Route::post('acl/role/edit/{id}', [RoleController::class, 'update']);
    Route::get('acl/role/add', [RoleController::class, 'create'])->name('acl.role.add');
    Route::post('acl/role/add', [RoleController::class, 'store']);
    Route::get('acl/role/delete/{id}', [RoleController::class, 'destroy'])->name('acl.role.delete');
    Route::get('acl/role/search', [RoleController::class, 'searchRole']);
    Route::get('acl/role/do-edit/{id}/{display_order}', [RoleController::class, 'updateDisplayOrder']);

    Route::get('acl/users', [AdminUserController::class, 'index'])->name('acl/users');
    Route::get('acl/users/show/{id}', [AdminUserController::class, 'show'])->name('acl.user.show');
    Route::get('acl/users/edit/{id}', [AdminUserController::class, 'edit'])->name('acl.user.edit');
    Route::post('acl/users/edit/{id}', [AdminUserController::class, 'update']);
    Route::get('acl/users/add', [AdminUserController::class, 'create'])->name('acl.user.add');
    Route::post('acl/users/add', [AdminUserController::class, 'store']);
    Route::get('acl/users/delete/{id}', [AdminUserController::class, 'destroy'])->name('acl.user.delete');
    Route::get('acl/users/change/{id}', [AdminUserController::class, 'change'])->name('acl.user.change');
    Route::get('profile', [AdminUserController::class, 'profile'])->name('profile');

    //Employee

    Route::get('employees', [EmployeesController::class, 'index'])->name('employees');
    Route::get('employee/add', [EmployeesController::class, 'create'])->name('employee.create');
    Route::post('employee/add', [EmployeesController::class, 'store']);
    Route::get('employee/edit/{id}', [EmployeesController::class, 'edit'])->name('employee.edit');
    Route::get('employee/show/{id}', [EmployeesController::class, 'show']);
    Route::post('employee/edit/{id}', [EmployeesController::class, 'update']);
    Route::get('employee/status-change/{id}', [EmployeesController::class, 'change'])->name('employee.change');
    Route::get('employee/delete/{id}', [EmployeesController::class, 'destroy'])->name('employee.delete');

    //Languages
    Route::get('languages', [LanguagesController::class, 'index'])->name('languages');
    Route::get('language/add', [LanguagesController::class, 'create'])->name('language-add');
    Route::post('language/add', [LanguagesController::class, 'store'])->name('language-store');
    Route::get('language/edit/{id}', [LanguagesController::class, 'edit'])->name('language-edit');
    Route::post('language/edit/{id}', [LanguagesController::class, 'update'])->name('language-update');
    Route::POST('language-update-status', [LanguagesController::class, 'updateLanguageStatus']);
    Route::post('language/delete', [LanguagesController::class, 'deleteLanguage'])->name('language-delete');


    // Categories

    Route::get('categories', [CategoriesController::class, 'index'])->name('categories');
    Route::get('category/add', [CategoriesController::class, 'create'])->name('category.create');
    Route::post('category/add', [CategoriesController::class, 'store'])->name('category.store');
    Route::get('category/edit/{id}', [CategoriesController::class, 'edit'])->name('category.edit');
    Route::post('category/edit/{id}', [CategoriesController::class, 'update'])->name('category.update');
    Route::post('category/change-status/{id}', [CategoriesController::class, 'StatusChange'])->name('category.update');
    Route::post('category/destroy/{id}', [CategoriesController::class, 'destroy'])->name('category.destroy');
    Route::post('fetch-category-content-by-language', [CategoriesController::class, 'fetchCategoryContentByLanguage'])->name('fetchCategoryContentByLanguage');

    /** Resume Builder */

    // Header Sections
    Route::get('resume/header-sections', [ResumeHeaderSectionsController::class, 'index'])->name('resume.header]_sections');
    Route::post('resume/header-sections/store', [ResumeHeaderSectionsController::class, 'store'])->name('resume.header]_sections.store');

    // Sidebar Sections
    Route::get('resume/sidebar-sections', [ResumeSidebarSectionsController::class, 'index'])->name('resume.sidebar_sections');
    Route::post('resume/sidebar-sections/store', [ResumeSidebarSectionsController::class, 'store'])->name('resume.sidebar_sections.store');

    // About Sections
    Route::get('resume/about-sections', [ResumeAboutSectionsController::class, 'index'])->name('resume.about_sections');
    Route::post('resume/about-sections/store', [ResumeAboutSectionsController::class, 'store'])->name('resume.about_sections.store');

    // About Sections
    Route::get('resume/happy-sections', [ResumeHappySectionsController::class, 'index'])->name('resume.happy_sections');
    Route::post('resume/happy-sections/store', [ResumeHappySectionsController::class, 'store'])->name('resume.happy_sections.store');

    Route::get('resume/portfolio-settings', [ResumePortfolioSettingsController::class, 'generalSection'])->name('resume.portfolio_settings.general_section');
    Route::get('resume/portfolio-settings/email-section', [ResumePortfolioSettingsController::class, 'emailSection'])->name('resume.portfolio_settings.email_section');
    Route::get('resume/portfolio-settings/about-section', [ResumePortfolioSettingsController::class, 'aboutSection'])->name('resume.portfolio_settings.about_section');
    Route::get('resume/portfolio-settings/skill-section', [ResumePortfolioSettingsController::class, 'skillSection'])->name('resume.portfolio_settings.skill_section');
    Route::get('resume/portfolio-settings/resume-section', [ResumePortfolioSettingsController::class, 'resumeSection'])->name('resume.portfolio_settings.resume_section');
    Route::get('resume/portfolio-settings/portfolio-section', [ResumePortfolioSettingsController::class, 'portfolioSection'])->name('resume.portfolio_settings.portfolio_section');
    Route::get('resume/portfolio-settings/service-section', [ResumePortfolioSettingsController::class, 'serviceSection'])->name('resume.portfolio_settings.service_section');
    Route::get('resume/portfolio-settings/testimonial-section', [ResumePortfolioSettingsController::class, 'testimonialSection'])->name('resume.portfolio_settings.testimonial_section');
    Route::get('resume/portfolio-settings/contact-section', [ResumePortfolioSettingsController::class, 'contactSection'])->name('resume.portfolio_settings.contact_section');
    Route::post('resume/portfolio-settings', [ResumePortfolioSettingsController::class, 'store'])->name('resume.portfolio_settings.store');

    // Skill Section
    Route::get('resume/skill-sections', [ResumeSkillSectionsController::class, 'index'])->name('resume.skill_sections');
    Route::get('resume/skill-sections/add', [ResumeSkillSectionsController::class, 'create'])->name('resume.skill_sections.store');
    Route::post('resume/skill-sections/add', [ResumeSkillSectionsController::class, 'store'])->name('resume.skill_sections.store');
    Route::get('resume/skill-sections/edit/{id}', [ResumeSkillSectionsController::class, 'edit'])->name('resume.skill_sections.edit');
    Route::post('resume/skill-sections/edit/{id}', [ResumeSkillSectionsController::class, 'update']);
    Route::get('resume/skill-sections/status-change/{id}', [ResumeSkillSectionsController::class, 'change'])->name('resume.skill_sections.change');
    Route::get('resume/skill-sections/sorting/{id}/{sort_number}', [ResumeSkillSectionsController::class, 'sorting'])->name('resume.skill_sections.sorting');
    Route::get('resume/skill-sections/delete/{id}', [ResumeSkillSectionsController::class, 'destroy'])->name('resume.skill_sections.delete');

    // Skill Section
    Route::get('resume/project-categories', [ResumeProjectCategoriesController::class, 'index'])->name('resume.skill_sections');
    Route::get('resume/project-categories/add', [ResumeProjectCategoriesController::class, 'create'])->name('resume.skill_sections.store');
    Route::post('resume/project-categories/add', [ResumeProjectCategoriesController::class, 'store'])->name('resume.skill_sections.store');
    Route::get('resume/project-categories/edit/{id}', [ResumeProjectCategoriesController::class, 'edit'])->name('resume.skill_sections.edit');
    Route::post('resume/project-categories/edit/{id}', [ResumeProjectCategoriesController::class, 'update']);
    Route::get('resume/project-categories/status-change/{id}', [ResumeProjectCategoriesController::class, 'change'])->name('resume.skill_sections.change');
    Route::get('resume/project-categories/sorting/{id}/{sort_number}', [ResumeProjectCategoriesController::class, 'sorting'])->name('resume.skill_sections.sorting');
    Route::get('resume/project-categories/delete/{id}', [ResumeProjectCategoriesController::class, 'destroy'])->name('resume.skill_sections.delete');


    // Interest Section
    Route::get('resume/interest-sections', [ResumeInterestSectionsController::class, 'index'])->name('resume.interest_sections');
    Route::get('resume/interest-sections/add', [ResumeInterestSectionsController::class, 'create'])->name('resume.interest_sections.store');
    Route::post('resume/interest-sections/add', [ResumeInterestSectionsController::class, 'store'])->name('resume.interest_sections.store');
    Route::get('resume/interest-sections/edit/{id}', [ResumeInterestSectionsController::class, 'edit'])->name('resume.interest_sections.edit');
    Route::post('resume/interest-sections/edit/{id}', [ResumeInterestSectionsController::class, 'update']);
    Route::get('resume/interest-sections/status-change/{id}', [ResumeInterestSectionsController::class, 'change'])->name('resume.interest_sections.change');
    Route::get('resume/interest-sections/sorting/{id}/{sort_number}', [ResumeInterestSectionsController::class, 'sorting'])->name('resume.interest_sections.sorting');
    Route::get('resume/interest-sections/delete/{id}', [ResumeInterestSectionsController::class, 'destroy'])->name('resume.interest_sections.delete');

    // language Section
    Route::get('resume/language-sections', [ResumeLanguageSectionsController::class, 'index'])->name('resume.language_sections');
    Route::get('resume/language-sections/add', [ResumeLanguageSectionsController::class, 'create'])->name('resume.language_sections.store');
    Route::post('resume/language-sections/add', [ResumeLanguageSectionsController::class, 'store'])->name('resume.language_sections.store');
    Route::get('resume/language-sections/edit/{id}', [ResumeLanguageSectionsController::class, 'edit'])->name('resume.language_sections.edit');
    Route::post('resume/language-sections/edit/{id}', [ResumeLanguageSectionsController::class, 'update']);
    Route::get('resume/language-sections/status-change/{id}', [ResumeLanguageSectionsController::class, 'change'])->name('resume.language_sections.change');
    Route::get('resume/language-sections/sorting/{id}/{sort_number}', [ResumeLanguageSectionsController::class, 'sorting'])->name('resume.language_sections.sorting');
    Route::get('resume/language-sections/delete/{id}', [ResumeLanguageSectionsController::class, 'destroy'])->name('resume.language_sections.delete');

    // Education Section
    Route::get('resume/education-sections', [ResumeEducationSectionsController::class, 'index'])->name('resume.education_sections');
    Route::get('resume/education-sections/add', [ResumeEducationSectionsController::class, 'create'])->name('resume.education_sections.store');
    Route::post('resume/education-sections/add', [ResumeEducationSectionsController::class, 'store'])->name('resume.education_sections.store');
    Route::get('resume/education-sections/edit/{id}', [ResumeEducationSectionsController::class, 'edit'])->name('resume.education_sections.edit');
    Route::post('resume/education-sections/edit/{id}', [ResumeEducationSectionsController::class, 'update']);
    Route::get('resume/education-sections/status-change/{id}', [ResumeEducationSectionsController::class, 'change'])->name('resume.education_sections.change');
    Route::get('resume/education-sections/sorting/{id}/{sort_number}', [ResumeEducationSectionsController::class, 'sorting'])->name('resume.education_sections.sorting');
    Route::get('resume/education-sections/delete/{id}', [ResumeEducationSectionsController::class, 'destroy'])->name('resume.education_sections.delete');

    // Experience Section
    Route::get('resume/experience-sections', [ResumeExperienceSectionsController::class, 'index'])->name('resume.experience_sections');
    Route::get('resume/experience-sections/add', [ResumeExperienceSectionsController::class, 'create'])->name('resume.experience_sections.store');
    Route::post('resume/experience-sections/add', [ResumeExperienceSectionsController::class, 'store'])->name('resume.experience_sections.store');
    Route::get('resume/experience-sections/edit/{id}', [ResumeExperienceSectionsController::class, 'edit'])->name('resume.experience_sections.edit');
    Route::post('resume/experience-sections/edit/{id}', [ResumeExperienceSectionsController::class, 'update']);
    Route::get('resume/experience-sections/status-change/{id}', [ResumeExperienceSectionsController::class, 'change'])->name('resume.experience_sections.change');
    Route::get('resume/experience-sections/sorting/{id}/{sort_number}', [ResumeExperienceSectionsController::class, 'sorting'])->name('resume.experience_sections.sorting');
    Route::get('resume/experience-sections/delete/{id}', [ResumeExperienceSectionsController::class, 'destroy'])->name('resume.experience_sections.delete');
    Route::get('resume/experience-sections/descriptions/add/{id}', [ResumeExperienceSectionsController::class, 'addDescriptions'])->name('resume.experience_section.add_descriptions');
    Route::post('resume/experience-sections/descriptions/add/{id}', [ResumeExperienceSectionsController::class, 'storeDescriptions'])->name('resume.experience_section.store_descriptions');

    // Live Link Sections
    Route::get('resume/live-link-sections', [ResumeLiveLinkSectionsController::class, 'index'])->name('resume.live_link_sections');
    Route::get('resume/live-link-sections/add', [ResumeLiveLinkSectionsController::class, 'create'])->name('resume.live_link_sections.store');
    Route::post('resume/live-link-sections/add', [ResumeLiveLinkSectionsController::class, 'store'])->name('resume.live_link_sections.store');
    Route::get('resume/live-link-sections/edit/{id}', [ResumeLiveLinkSectionsController::class, 'edit'])->name('resume.live_link_sections.edit');
    Route::post('resume/live-link-sections/edit/{id}', [ResumeLiveLinkSectionsController::class, 'update']);
    Route::get('resume/live-link-sections/status-change/{id}', [ResumeLiveLinkSectionsController::class, 'change'])->name('resume.live_link_sections.change');
    Route::get('resume/live-link-sections/sorting/{id}/{sort_number}', [ResumeLiveLinkSectionsController::class, 'sorting'])->name('resume.live_link_sections.sorting');
    Route::get('resume/live-link-sections/delete/{id}', [ResumeLiveLinkSectionsController::class, 'destroy'])->name('resume.live_link_sections.delete');

    // Live Link Sections
    Route::get('resume/testimonial-sections', [ResumeTestimonialSectionsController::class, 'index'])->name('resume.live_link_sections');
    Route::get('resume/testimonial-sections/add', [ResumeTestimonialSectionsController::class, 'create'])->name('resume.live_link_sections.store');
    Route::post('resume/testimonial-sections/add', [ResumeTestimonialSectionsController::class, 'store'])->name('resume.live_link_sections.store');
    Route::get('resume/testimonial-sections/edit/{id}', [ResumeTestimonialSectionsController::class, 'edit'])->name('resume.live_link_sections.edit');
    Route::post('resume/testimonial-sections/edit/{id}', [ResumeTestimonialSectionsController::class, 'update']);
    Route::get('resume/testimonial-sections/status-change/{id}', [ResumeTestimonialSectionsController::class, 'change'])->name('resume.live_link_sections.change');
    Route::get('resume/testimonial-sections/sorting/{id}/{sort_number}', [ResumeTestimonialSectionsController::class, 'sorting'])->name('resume.live_link_sections.sorting');
    Route::get('resume/testimonial-sections/delete/{id}', [ResumeTestimonialSectionsController::class, 'destroy'])->name('resume.live_link_sections.delete');

    // Project Sections
    Route::get('resume/project-sections', [ResumeProjectSectionsController::class, 'index'])->name('resume.project_sections');
    Route::get('resume/project-sections/add', [ResumeProjectSectionsController::class, 'create'])->name('resume.project_sections.store');
    Route::post('resume/project-sections/add', [ResumeProjectSectionsController::class, 'store'])->name('resume.project_sections.store');
    Route::get('resume/project-sections/edit/{id}', [ResumeProjectSectionsController::class, 'edit'])->name('resume.project_sections.edit');
    Route::post('resume/project-sections/edit/{id}', [ResumeProjectSectionsController::class, 'update']);
    Route::get('resume/project-sections/status-change/{id}', [ResumeProjectSectionsController::class, 'change'])->name('resume.project_sections.change');
    Route::get('resume/project-sections/sorting/{id}/{sort_number}', [ResumeProjectSectionsController::class, 'sorting'])->name('resume.project_sections.sorting');
    Route::get('resume/project-sections/delete/{id}', [ResumeProjectSectionsController::class, 'destroy'])->name('resume.project_sections.delete');
    Route::get('resume/project-sections/descriptions/add/{id}', [ResumeProjectSectionsController::class, 'addDescriptions'])->name('resume.project_sections.add_descriptions');
    Route::post('resume/project-sections/descriptions/add/{id}', [ResumeProjectSectionsController::class, 'storeDescriptions'])->name('resume.project_sections.store_descriptions');
    Route::get('resume/project-sections/images/add/{id}', [ResumeProjectSectionsController::class, 'addImages'])->name('resume.project_sections.add_images');
    Route::post('resume/project-sections/images/add/{id}', [ResumeProjectSectionsController::class, 'storeImages'])->name('resume.project_sections.store_images');
    Route::get('resume/project-sections/images/delete/{id}', [ResumeProjectSectionsController::class, 'deleteProjectImage'])->name('resume.project_sections.store_images');

    // Project Sections
    Route::get('resume/service-sections', [ResumeServiceSectionsController::class, 'index'])->name('resume.project_sections');
    Route::get('resume/service-sections/add', [ResumeServiceSectionsController::class, 'create'])->name('resume.project_sections.store');
    Route::post('resume/service-sections/add', [ResumeServiceSectionsController::class, 'store'])->name('resume.project_sections.store');
    Route::get('resume/service-sections/edit/{id}', [ResumeServiceSectionsController::class, 'edit'])->name('resume.project_sections.edit');
    Route::post('resume/service-sections/edit/{id}', [ResumeServiceSectionsController::class, 'update']);
    Route::get('resume/service-sections/status-change/{id}', [ResumeServiceSectionsController::class, 'change'])->name('resume.project_sections.change');
    Route::get('resume/service-sections/sorting/{id}/{sort_number}', [ResumeServiceSectionsController::class, 'sorting'])->name('resume.project_sections.sorting');
    Route::get('resume/service-sections/delete/{id}', [ResumeServiceSectionsController::class, 'destroy'])->name('resume.project_sections.delete');
    Route::get('resume/service-sections/descriptions/add/{id}', [ResumeServiceSectionsController::class, 'addDescriptions'])->name('resume.project_sections.add_descriptions');
    Route::post('resume/service-sections/descriptions/add/{id}', [ResumeServiceSectionsController::class, 'storeDescriptions'])->name('resume.project_sections.store_descriptions');
    Route::get('resume/service-sections/images/add/{id}', [ResumeServiceSectionsController::class, 'addImages'])->name('resume.project_sections.add_images');
    Route::post('resume/service-sections/images/add/{id}', [ResumeServiceSectionsController::class, 'storeImages'])->name('resume.project_sections.store_images');
    Route::get('resume/service-sections/images/delete/{id}', [ResumeServiceSectionsController::class, 'deleteProjectImage'])->name('resume.project_sections.store_images');
    // Route::get('resume/project-sections/detail/{id}', [ResumeProjectSectionsController::class, 'detail'])->name('resume.project_sections.add_images');

    // Portfolio Analysis
    Route::get('portfolio-analysis/website-contacts', [WebsiteContactsController::class, 'index'])->name('portfolio_analysis.website_contacts');
    Route::get('portfolio-analysis/website-contacts/detail/{id}', [WebsiteContactsController::class, 'detail'])->name('portfolio_analysis.website_contacts.detail');

    // Portfolio Analysis - Contact Services
    Route::get('portfolio-analysis/website-contact-services', [WebsiteContactServicesController::class, 'index'])->name('portfolio_analysis.website_contact_services');
    Route::get('portfolio-analysis/website-contact-services/add', [WebsiteContactServicesController::class, 'create'])->name('portfolio_analysis.website_contact_services.add');
    Route::post('portfolio-analysis/website-contact-services/add', [WebsiteContactServicesController::class, 'store'])->name('portfolio_analysis.website_contact_services.store');
    Route::get('portfolio-analysis/website-contact-services/edit/{id}', [WebsiteContactServicesController::class, 'edit'])->name('portfolio_analysis.website_contact_services.edit');
    Route::post('portfolio-analysis/website-contact-services/edit/{id}', [WebsiteContactServicesController::class, 'update']);
    Route::get('portfolio-analysis/website-contact-services/status-change/{id}', [WebsiteContactServicesController::class, 'change'])->name('portfolio_analysis.website_contact_services.change');
    Route::get('portfolio-analysis/website-contact-services/sorting/{id}/{sort_number}', [WebsiteContactServicesController::class, 'sorting'])->name('portfolio_analysis.website_contact_services.sorting');
    Route::get('portfolio-analysis/website-contact-services/delete/{id}', [WebsiteContactServicesController::class, 'destroy'])->name('portfolio_analysis.website_contact_services.delete');

    // Portfolio Analysis - Contact Emails
    Route::get('portfolio-analysis/website-contact-emails', [WebsiteContactEmailsController::class, 'index'])->name('portfolio_analysis.website_contact_emails');
});
