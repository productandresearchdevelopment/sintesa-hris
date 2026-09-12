<?php

use App\Controllers\Admins\Appraisals\AppraisalEmployee;
use App\Controllers\Admins\Appraisals\AppraisalEmployeeQuestion;
use App\Controllers\Admins\Appraisals\AppraisalEmployeeSummary;
use App\Controllers\Admins\Appraisals\AppraisalPeriod;
use App\Controllers\Admins\Appraisals\AppraisalPeriodOrganization;
use App\Controllers\Admins\Appraisals\AppraisalQuestion;
use App\Controllers\Admins\Appraisals\AppraisalQuestionCategory;
use App\Controllers\Admins\Appraisals\AppraisalQuestionTemplate;
use App\Controllers\Admins\Bulletins\Bulletin;
use App\Controllers\Admins\Bulletins\BulletinCategory;
use App\Controllers\Admins\Cities\City;
use App\Controllers\Admins\Companies\Company;
use App\Controllers\Admins\Offices\Office;
use App\Controllers\Admins\Divisions\Division;
use App\Controllers\Admins\FileManagers\FileManager;
use App\Controllers\Admins\Helpdesks\Helpdesk;
use App\Controllers\Admins\Helpdesks\HelpdeskAnswer;
use App\Controllers\Admins\Helpdesks\HelpdeskCategory;
use App\Controllers\Admins\Organizations\Organization;
use App\Controllers\Admins\Employees\Employee;
use App\Controllers\Admins\GlobalDatas\GlobalData;
use App\Controllers\Admins\Placements\Placement;
use App\Controllers\Front\Attendances\Attendance;
use App\Controllers\Front\Dashboards\Dashboard;
use App\Controllers\Front\Leaves\Leave;
use App\Controllers\Front\Employees\EmployeeRequest;
use App\Controllers\Front\Leaves\Type;
use App\Controllers\Front\Notifications\Notification;
use App\Http\Controllers\Main;
use Illuminate\Support\Facades\Route;

Main::routes();

Route::middleware(['auth', 'roles'])->group(function () {

    Route::prefix('bulletin')->name('bulletin.')->group(function () {
        Route::controller(BulletinCategory::class)->prefix('category')->name('category.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/data', 'data')->name('data');
            Route::get('/get/{id?}', 'get')->name('get');
            Route::get('/view/{id?}', 'view')->name('view');
            Route::post('/push/{id?}', 'push')->name('push');
            Route::put('/restore', 'restore')->name('restore');
            Route::delete('/delete', 'delete')->name('delete');
            Route::delete('/forcedelete', 'forceDelete')->name('forcedelete');
            Route::get('/data/organizations/{categoryId?}', 'dataOrganizations')->name('data.organization');
            Route::put('/set/organization/{categoryId}', 'setOrganization')->name('set.organization');
        });
        Route::controller(Bulletin::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/data', 'data')->name('data');
            Route::get('/view/{id?}', 'view')->name('view');
            Route::post('/create', 'create')->name('create');
            Route::put('/update', 'edit')->name('update');
            Route::put('/set/pin', 'setIsPin')->name('set.pin');
            Route::delete('/delete', 'delete')->name('delete');
            Route::put('/restore', 'restore')->name('restore');
            Route::delete('/forcedelete', 'forcedelete')->name('forcedelete');
            Route::get('/data/{id?}', 'get')->name('get');
        });
    });

    Route::prefix('helpdesk')->name('helpdesk.')->group(function () {
        Route::controller(Helpdesk::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/data', 'data')->name('data');
            Route::get('/data/organizations/{helpdeskId?}', 'dataOrganizations')->name('data.organization');
            Route::put('/set/organization/{helpdeskId}', 'setOrganization')->name('set.organization');
            Route::delete('/destroy', 'destroy')->name('destroy');
            Route::delete('/forcedelete', 'forcedelete')->name('forcedelete');
            Route::put('/restore', 'restore')->name('restore');
            Route::put('closed', 'closed')->name('closed');
            Route::put('unclosed', 'unclosed')->name('unclosed');
            Route::get('/get/{id?}', 'get')->name('get');
            Route::get('/view/{id?}', 'show')->name('show');
            Route::put('/{id}', 'update')->name('update');
            Route::get('/{organization_id}/data-category', 'data_category')->name('data_category');
        });

        Route::prefix('answer')->name('answer.')->group(function () {
            Route::controller(HelpdeskAnswer::class)->group(function () {
                Route::post('/', 'store')->name('store');
                Route::delete('/destroy', 'destroy')->name('destroy');
                Route::get('/get/{id?}', 'get')->name('get');
                Route::get('/get/helpdesk/{id?}', 'getAllByHelpdeskId')->name('get.helpdesk');
                Route::put('/edit/{id}', 'update')->name('update');
            });
        });

        Route::prefix('category')->name('category.')->group(function () {
            Route::controller(HelpdeskCategory::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/data', 'data')->name('data');
                Route::get('/data/organizations/{categoryId?}', 'dataOrganizations')->name('data.organization');
                Route::put('/restore', 'restore')->name('restore');
                Route::delete('/delete', 'delete')->name('destroy');
                Route::delete('/forcedelete', 'forceDelete')->name('forcedelete');
                Route::put('/{id}', 'update')->name('update');
                Route::put('/set/organization/{categoryId}', 'setOrganization')->name('set.organization');
            });
        });
    });

    Route::prefix('organization')->name('organization.')->group(function () {
        Route::controller(Organization::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('create');
            Route::get('/data', 'data')->name('data');
            Route::get('/path/{id}', 'path')->name('path');
            Route::delete('/restore', 'restore')->name('restore');
            Route::delete('/delete', 'delete')->name('delete');
            Route::delete('/forcedelete', 'forceDelete')->name('forcedelete');
            Route::put('/{id}', 'update')->name('update');
            Route::put('/move/{mode}', 'move')->name('move');
            Route::put('/set/division/{organizationId}', 'setDivision')->name('set.division');
        });
    });

    Route::prefix('filemanager')->name('filemanager.')->group(function () {

        Route::controller(FileManager::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/data', 'data')->name('data');
            Route::get('/data/organizations/{filemanagerId?}', 'dataOrganizations')->name('data.organization');
            Route::get('/data/users/{filemanagerId?}', 'dataUsers')->name('data.user');
            Route::get('/view/{file_id?}', 'view')->name('view');
            Route::get('/get/{id?}', 'get')->name('get');
            Route::get('/download/{id}', 'downloadFile')->name('download');
            Route::post('/downloadMultiple', 'downloadMultipleFiles')->name('downloadMultiple');
            Route::get('/downloadZip/{filename}', 'downloadZip')->name('downloadZip');
            Route::post('/push/file', 'pushFile')->name('push.file');
            Route::post('/push/link', 'pushLink')->name('push.link');
            Route::post('/set/name', 'setName')->name('set.name');
            Route::post('/set/tag', 'setTag')->name('set.tag');
            Route::post('/set/user', 'setUser')->name('set.user');
            Route::delete('/remove/user', 'removeUser')->name('remove.user');
            Route::delete('/restore', 'restore')->name('restore');
            Route::delete('/delete', 'delete')->name('delete');
            Route::delete('/forcedelete', 'forceDelete')->name('forcedelete');
            Route::put('/update/files', 'updateFile')->name('update.file');
            Route::put('/update/{id}/link', 'updateLink')->name('update.link');
            Route::put('/set/organization/{filemanagerId}', 'setOrganization')->name('set.organization');
            Route::put('/update/{id}/user', 'updateUsers')->name('update.users');


            Route::prefix('folder')->name('folder.')->group(function () {
                Route::get('/data', 'dataFolder')->name('data');
                Route::post('/push/{id?}', 'pushFolder')->name('push');
                Route::delete('/restore', 'restoreFolder')->name('restore');
                Route::delete('/delete', 'deleteFolder')->name('delete');
                Route::delete('/forcedelete', 'forceDeleteFolder')->name('forcedelete');
            });
        });
    });

    Route::prefix('division')->name('division.')->group(function () {
        Route::controller(Division::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/data', 'data')->name('data');
            Route::post('/create', 'create')->name('create');
            Route::put('/update', 'edit')->name('update');
            Route::delete('/delete', 'delete')->name('delete');
            Route::put('/restore', 'restore')->name('restore');
            Route::delete('/forcedelete', 'forcedelete')->name('forcedelete');
            Route::get('/data/{id?}', 'get')->name('get');
        });
    });

    Route::prefix('company')->name('company.')->group(function () {
        Route::controller(Company::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/data', 'data')->name('data');
            Route::post('/create', 'create')->name('create');
            Route::put('/update', 'edit')->name('update');
            Route::delete('/delete', 'delete')->name('delete');
            Route::put('/restore', 'restore')->name('restore');
            Route::delete('/forcedelete', 'forcedelete')->name('forcedelete');
            Route::get('/data/{id?}', 'get')->name('get');
        });
    });

    Route::prefix('office')->name('office.')->group(function () {
        Route::controller(Office::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/data', 'data')->name('data');
            Route::post('/create', 'create')->name('create');
            Route::put('/update', 'edit')->name('update');
            Route::delete('/delete', 'delete')->name('delete');
            Route::put('/restore', 'restore')->name('restore');
            Route::delete('/forcedelete', 'forcedelete')->name('forcedelete');
            Route::get('/data/{id?}', 'get')->name('get');
        });
    });

    Route::prefix('employee')->name('employee.')->group(function () {
        Route::controller(Employee::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/data', 'data')->name('data');
            Route::get('/view/{id?}', 'view')->name('view');
            Route::post('/create', 'create')->name('create');
            Route::put('/update', 'edit')->name('update');
            Route::delete('/delete', 'delete')->name('delete');
            Route::put('/restore', 'restore')->name('restore');
            Route::delete('/forcedelete', 'forcedelete')->name('forcedelete');
            Route::get('/data/{id?}', 'get')->name('get');
            Route::get('/export/excel', 'exportExcel')->name('export.excel');
            Route::get('/export/format/import', 'importFormat')->name('export.excel.format.import');
            Route::get('/export/format/import/contract', 'importFormatContract')->name('export.excel.format.import.contract');
            Route::get('/export/format/import/career', 'importFormatCareer')->name('export.excel.format.import.career');
            Route::get('/export/format/import/training', 'importFormatTraining')->name('export.excel.format.import.training');
            Route::get('/export/format/import/job-experience', 'importFormatJobExperience')->name('export.excel.format.import.job-experience');
            Route::get('/export/format/import/family', 'importFormatFamily')->name('export.excel.format.import.family');
            Route::get('/export/format/import/education', 'importFormatEducation')->name('export.excel.format.import.education');
            Route::get('/export/format/import/citizen', 'importFormatCitizen')->name('export.excel.format.import.citizen');
            Route::post('/import', 'importData')->name('import');
            Route::post('/import/contract', 'importDataContract')->name('import.contract');
            Route::post('/import/career', 'importDataCareer')->name('import.career');
            Route::post('/import/training', 'importDataTraining')->name('import.training');
            Route::post('/import/job-experience', 'importDataJobExperience')->name('import.job-experience');
            Route::post('/import/family', 'importDataFamily')->name('import.family');
            Route::post('/import/education', 'importDataEducation')->name('import.education');
            Route::post('/import/citizen', 'importDataCitizen')->name('import.citizen');
            Route::get('/export/pdf/{id}', 'exportPdf')->name('export.pdf');
            Route::post('/export/pdf/multiple', 'exportPdfMultiple')->name('export.pdf.multiple');
            Route::get('/downloadZip/{filename}', 'downloadZip')->name('downloadZip');
        });

        Route::prefix('request')->name('request.')->group(function () {
            Route::controller(EmployeeRequest::class)->group(function () {
                Route::post('/create', 'create')->name('create');
                Route::put('/update', 'edit')->name('update');
                Route::put('/approve', 'approve')->name('approve');
                Route::put('/reject', 'reject')->name('reject');
                Route::delete('/delete', 'delete')->name('delete');
                Route::get('/data/{employId}', 'data')->name('data');
                Route::get('/get/{id}', 'get')->name('get');
                Route::get('/getByEmployeeId/{id}', 'getByEmployeeId')->name('get.by.employee');
                Route::get('/export/pdf/{id}', 'exportPdf')->name('export.pdf');
            });
        });
    });

    Route::prefix('globaldata')->name('globaldata.')->group(function () {
        Route::controller(GlobalData::class)->group(function () {
            Route::get('/data', 'data')->name('data');
        });
    });

    Route::prefix('placement')->name('placement.')->group(function () {
        Route::controller(Placement::class)->group(function () {
            Route::get('/data', 'data')->name('data');
        });
    });

    Route::prefix('city')->name('city.')->group(function () {
        Route::controller(City::class)->group(function () {
            Route::get('/data', 'data')->name('data');
        });
    });

    Route::prefix('appraisal')->name('appraisal.')->group(function () {
        Route::prefix('question')->name('question.')->group(function () {
            Route::controller(AppraisalQuestion::class)->group(function () {
                Route::get('/data', 'data')->name('data');
                Route::get('/view/{id?}', 'view')->name('view');
            });

            Route::controller(AppraisalQuestionCategory::class)->prefix('category')->name('category.')->group(function () {
                Route::get('/data', 'data')->name('data');
            });

            Route::controller(AppraisalQuestionTemplate::class)->prefix('template')->name('template.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/index-mobile', 'index_mobile')->name('index.mobile');
                Route::get('/data', 'data')->name('data');
                Route::get('/data/organizations/{template}', 'dataOrganizations')->name('data.organization');
                Route::get('/view/{id?}', 'view')->name('view');
                Route::post('/create', 'create')->name('create');
                Route::put('/update', 'edit')->name('update');
                Route::put('/set/archived', 'setArchived')->name('set.archived');
                Route::put('/set/organization/{template}', 'setOrganization')->name('set.organization');
                Route::post('/front/evaluator', 'frontEvaluator')->name('front.evaluator');
                Route::get('/export/pdf/{employeeId}', 'frontExportPdf')->name('front.export.pdf');
                Route::delete('/delete', 'delete')->name('delete');
                Route::put('/restore', 'restore')->name('restore');
                Route::delete('/forcedelete', 'forcedelete')->name('forcedelete');
                Route::get('/data/{id?}', 'get')->name('get');
                Route::get('/export/excel', 'exportExcel')->name('export.excel');
                Route::get('/export/format/import', 'importFormat')->name('export.excel.format.import');
                Route::post('/import', 'importData')->name('import');
            });
        });

        Route::prefix('period')->name('period.')->group(function () {
            Route::controller(AppraisalPeriod::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/data', 'data')->name('data');
                Route::get('/view/{id?}', 'view')->name('view');
                Route::post('/create', 'create')->name('create');
                Route::put('/update', 'update')->name('update');
                Route::delete('/delete', 'delete')->name('delete');
                Route::put('/restore', 'restore')->name('restore');
                Route::delete('/forcedelete', 'forcedelete')->name('forcedelete');
                Route::get('/data/{id?}', 'get')->name('get');
            });

            Route::controller(AppraisalPeriodOrganization::class)->prefix('organization')->name('organization.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/data', 'data')->name('data');
                Route::get('/data/{id?}', 'get')->name('get');
                Route::put('/set/template', 'setTemplate')->name('set.template');
            });
        });

        Route::prefix('employee')->name('employee.')->group(function () {
            Route::controller(AppraisalEmployee::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/data', 'data')->name('data');
                Route::get('/data/employee', 'data_employee')->name('data.employee');
                Route::get('/view/{id?}', 'view')->name('view');
                Route::post('/create', 'create')->name('create');
                Route::put('/update', 'update')->name('update');
                Route::delete('/delete', 'delete')->name('delete');
                Route::put('/restore', 'restore')->name('restore');
                Route::delete('/forcedelete', 'forcedelete')->name('forcedelete');
                Route::get('/data/{id?}', 'get')->name('get');
                Route::get('/export/excel', 'exportExcel')->name('export.excel');
            });

            Route::controller(AppraisalEmployeeQuestion::class)->prefix('question')->name('question.')->group(function () {
                Route::get('/data', 'data')->name('data');
                Route::get('/data/{id?}', 'get')->name('get');
            });

            Route::controller(AppraisalEmployeeSummary::class)->prefix('summary')->name('summary.')->group(function () {
                Route::get('/data', 'data')->name('data');
                Route::get('/data/{id?}', 'get')->name('get');
            });
        });
    });

    // FRONT
    Route::prefix('notification')->name('notification.')->group(function () {
        Route::controller(Notification::class)->group(function () {
            Route::get('count', 'countAll')->name('count');
            Route::get('read/{id}', 'markAsRead')->name('read');
            Route::get('read-all/{module?}', 'markAllAsReadByModule')->name('read.all.module');
        });
    });

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::controller(Dashboard::class)->group(function () {
            Route::get('/', 'index')->name('index');
        });
    });

    Route::prefix('leave')->name('leave.')->group(function () {
        Route::controller(Leave::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/data', 'data')->name('data');
            Route::get('/export/excel', 'exportExcel')->name('export.excel');
            Route::get('/data/{id?}', 'get')->name('get');
            Route::post('/push/{id?}', 'push')->name('push');
            Route::post('/approve/{id}', 'approve')->name('approve');
            Route::post('/reject/{id}', 'reject')->name('reject');
            Route::post('/cancel/{id}', 'cancel')->name('cancel');
        });

        Route::prefix('type')->name('type.')->group(function () {
            Route::controller(Type::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/data', 'data')->name('data');
                Route::get('/data/{id?}', 'get')->name('get');
                Route::post('/push/{id?}', 'push')->name('push');
                Route::delete('/delete', 'delete')->name('delete');
            });
        });
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/mobile', function () {
            return view('_front.profile.mobile', ['user' => auth()->user()]);
        })->name('mobile');
    });

    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::controller(Attendance::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/report', 'report')->name('report');
            Route::post('/submit', 'submit')->name('submit');
            Route::post('/store', 'store')->name('store');
            Route::post('/update', 'update')->name('update');
            Route::get('/export/excel', 'exportExcel')->name('export.excel');
            Route::get('/check/{type}', 'check')->name('check');
        });
    });
});

Route::get('/under-maintenance', function () {
    return view('_front.under_maintance');
})->name('under.maintenance');
