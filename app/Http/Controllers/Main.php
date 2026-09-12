<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\SystemModels\Auth\User;
use App\SystemModels\Auth\Module;
use Illuminate\Support\Facades\Hash;
use App\Libraries\FileUpload;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Systems\User as AuthUser;
use App\Http\Controllers\Systems\Module as AuthModule;
use App\Http\Controllers\Systems\Role as AuthRole;
use App\Http\Controllers\Systems\App as AuthApp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail as Mailer;
use App\Mail\SendEmailActivationLink;
use App\SystemModels\Auth\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Main extends Controller
{
    public function main(Request $request)
    {
        $user  = $request->user();
        $roleData = Role::find($user->role_id)->home;
        $homePage = Module::select('route', 'text')->find($roleData);

        // if ($user->role->name !== 'DEVELOPER' && $user->role->name !== 'SUPERADMIN') {
        $view = isMobile() ? '_front.home.index' : 'templates.index';
        return view($view, [
            'user' => $user,
            'homePage' => $homePage ? $homePage : ['route' => 'dashboard.index', 'text' => 'dashboard'],
            'treeMenu' => Module::tree($user->role_id),
        ]);
        // } else {
        // return view('templates.index', [
        //     'user' => $user,
        //     'treeMenu' => Module::tree($user->role_id),
        //     'homePage' => $homePage ? $homePage : ['route' => 'dashboard.index', 'text' => 'dashboard'],
        // ]);
        // }
    }

    public function profileGet(Request $request)
    {
        $user = $request->user()->load(['role', 'organization', 'employee']);
        return $user;
    }

    public function profileEdit(Request $request)
    {
        $user  = $request->user();
        $input = [
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address
        ];

        $valid = Validator::make($input, [
            'username' => "required|unique:auth_user,username,$user->id,id",
            'name' => 'required',
            'email' => "required|email|unique:auth_user,email,$user->id,id",
            'address' => 'nullable|string'
        ]);

        if ($valid->fails()) {
            return ['success' => false, 'message' => $valid->errors()->first()];
        }

        if ($user->email != $request->email) {
            $token = Str::random(60);
            $input['email_validation_code'] = $token;
            $input['remember_token'] = $token;
            $input['email_validation_sent_at'] = Carbon::now();
            $input['email_validation_at'] = null;

            $activationLink = url('/email/activate/' . $token . '?email=' . urlencode($request->email));

            Mailer::to($request->email)->send(new SendEmailActivationLink($activationLink, $user));
        }

        $user->update($input);

        return ['success' => true, 'message' => 'Success', 'data' => $user];
    }

    public function profilePassword(Request $request)
    {
        $user = $request->user();
        if ($request->old && $request->new && $request->confirm) {
            if ($request->new == $request->confirm) {
                if (Hash::check($request->old, $user->password)) {
                    $password = $request->new;
                    if (strlen($request->new) < 8) return ['success' => false, 'message' => 'passwordm min 8 character'];
                    else if (!preg_match('@[A-Z]@', $password)) return ['success' => false, 'message' => 'password must have a uppercase'];
                    else if (!preg_match('@[a-z]@', $password)) return ['success' => false, 'message' => 'password must have a lowercase'];
                    else if (!preg_match('@[0-9]@', $password)) return ['success' => false, 'message' => 'password must have a number'];
                    else {
                        $user->update(['password' => Hash::make($password)]);
                        return ['success' => true, 'message' => 'Success!'];
                    }
                }
                return ['success' => false, 'message' => 'Please check your old password'];
            }
            return ['success' => false, 'message' => 'Please check your confirm password'];
        }
        return ['success' => false, 'message' => 'Please check your password'];
    }

    public function uploadPhoto(Request $request)
    {
        $user = $request->user();

        $file = $request->file('photo');
        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'No photo file selected or uploaded.',
            ], 400);
        }

        if (!$file->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'File upload error: ' . $file->getErrorMessage(),
            ], 422);
        }

        try {
            if ($user->photo_id) {
                FileUpload::removeFileById([$user->photo_id]);
            }

            $upload = FileUpload::upload('photo', 'user-profile');

            if ($upload) {
                $user->update(['photo_id' => $upload]);

                return response()->json([
                    'success' => true,
                    'data'    => $user,
                    'message' => 'Profile picture updated successfully!',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error uploading photo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing photo: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to upload photo. Please try again.',
        ], 500);
    }

    public function file(Request $request, $id = null)
    {
        if ($path = fileUri($id)) {
            return redirect($path);
        }
        return abort(404);
    }

    public function emailValidation(Request $request, $uid = null, $code = null)
    {
        if ($user = User::find($uid)) {
            if ($user->email_validation_code) {
                if (sha1($user->email_validation_code) == $code) {
                    $user->update([
                        'email_validation_code' => null,
                        'email_validation_sent_at' => null,
                        'email_validation_at' => date('Y-m-d H:i:s'),
                    ]);
                    return redirect(route('main'));
                } else abort(403, 'Validation Rejected');
            } else return redirect(route('main'));
        }
        abort(403, 'Validation Rejected');
    }

    public function deploy(Request $request, $key)
    {
        if (config('app.deploy_secret') == $key) {
            $deployScriptPath = base_path('deploy.sh');

            if (!file_exists($deployScriptPath)) {
                return response()->json(['message' => 'Deployment script not found'], 404);
            }

            $output = [];
            $return_var = 0;

            exec("sh $deployScriptPath 2>&1", $output, $return_var);

            if ($return_var === 0) {
                return response()->json(['message' => 'Deployment successful!', 'output' => $output], 200);
            } else {
                return response()->json(['message' => 'Deployment failed!', 'output' => $output], 500);
            }
        } else {
            return abort(403, 'Unauthorized, Access Denied');
        }
    }

    public static function routes()
    {
        Route::controller(LoginController::class)->group(function () {
            Route::get('/login', 'loginForm')->name('login.form');
            Route::post('/login', 'login')->name('login');
            Route::get('/logout', 'logout')->name('logout');
        });

        Route::prefix('verification')->name('verification.')->group(function () {
            Route::controller(VerificationController::class)->group(function () {
                Route::get('/notice', 'notice')->name('notice');
                Route::post('/check', 'check')->name('check');
                Route::post('/resend', 'resend')->name('resend');
            });
        });


        Route::prefix('password')->name('password.')->group(function () {
            Route::controller(ForgotPasswordController::class)->group(function () {
                Route::get('/reset', 'showLinkRequestForm')->name('request');
                Route::post('/email', 'sendResetLinkEmail')->name('email');
            });
            Route::controller(ResetPasswordController::class)->group(function () {
                Route::get('/reset/{token}', 'showResetForm')->name('reset');
                Route::post('/reset', 'reset')->name('update');
            });
        });

        // ROUTE PROFILE VIEW & UPDATE ---------------------------------------------------------------------------------
        Route::controller(Main::class)->group(function () {
            Route::get('/validation/email/api/{uid?}/{code?}', 'emailValidation')->name('validation.email.api');
            Route::get('/file/{id?}', 'file')->name('file');

            Route::middleware(['auth'])->group(function () {
                Route::get('/', 'main')->name('main');
                Route::prefix('profile')->name('profile.')->group(function () {
                    Route::get('/data', 'profileGet')->name('data');
                    Route::post('/edit', 'profileEdit')->name('edit');
                    Route::post('/password', 'profilePassword')->name('password');
                    Route::post('/upload/photo', 'uploadPhoto')->name('upload');
                    Route::post('/update/property/main', 'updatePropertyMain')->name('update.property.main');
                    Route::post('/update/notif/{type?}', 'updateNotif')->name('update.notif');
                });
            });
        });

        // SYSTEM ADMINISTRATOR ----------------------------------------------------------------------------------------
        Route::middleware(['auth', 'roles'])->group(function () {

            Route::prefix('system')->name('auth.')->group(function () {

                Route::get('/deploy/{key}', '\App\Http\Controllers\Main@deploy')->name('deploy');

                Route::controller(AuthUser::class)->prefix('user')->group(function () {
                    Route::get('/', 'index')->name('user');
                    Route::name('user.')->group(function () {
                        Route::get('/data', 'data')->name('data');
                        Route::get('/get/{id?}', 'get')->name('get');
                        Route::get('/view/{id?}', 'view')->name('view');
                        Route::post('/push/{id?}', 'push')->name('push');
                        Route::post('/send/email/validation', 'sendEmailValidation')->name('send.email.validation');
                        Route::put('/set/role', 'setRole')->name('set.role');
                        Route::put('/set/password', 'setPassword')->name('set.password');
                        Route::get('/import/format', 'importFormat')->name('import.format');
                        Route::post('/import/data', 'importData')->name('import.data');
                        Route::put('/restore', 'restore')->name('restore');
                        Route::delete('/delete', 'delete')->name('delete');
                        Route::delete('/forcedelete', 'forceDelete')->name('forcedelete');
                    });
                });

                Route::controller(AuthModule::class)->prefix('module')->group(function () {
                    Route::get('/', 'index')->name('module');
                    Route::name('module.')->group(function () {
                        Route::get('/data', 'data')->name('data');
                        Route::get('/icon', 'icon')->name('icon');
                        Route::post('/upload/icon', 'uploadIcon')->name('upload.icon');
                        Route::post('/create', 'create')->name('create');
                        Route::put('/update/{id}', 'update')->name('update');
                        Route::delete('/delete', 'delete')->name('delete');
                        Route::put('/set/roles/{id}', 'updateRoles')->name('update.roles');
                        Route::put('/set/field/{field}', 'updateField')->name('update.field');
                        Route::put('/move/{mode}', 'move')->name('move');
                    });
                });

                Route::controller(AuthRole::class)->prefix('role')->group(function () {
                    Route::get('/', 'index')->name('role');
                    Route::name('role.')->group(function () {
                        Route::get('/data', 'dataRoles')->name('data');
                        Route::get('/data/modules/{role}', 'dataModules')->name('data.module');
                        Route::post('/create', 'create')->name('create');
                        Route::put('/update/{id}', 'update')->name('update');
                        Route::delete('/delete/{id}', 'delete')->name('delete');
                        Route::put('/set/home/{role}', 'setHome')->name('set.home');
                        Route::put('/set/module/{role}', 'setAuth')->name('set.auth');
                    });
                });

                Route::controller(AuthApp::class)->prefix('app')->group(function () {
                    Route::name('app.')->group(function () {
                        Route::get('/data/{roleId?}', 'data')->name('data');
                        Route::put('/set/role/{roleId}', 'setRole')->name('set.role');
                    });
                });
            });
        });
    }
}
