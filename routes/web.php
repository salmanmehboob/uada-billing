<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\AlloteeController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\BillNonPeriodController;
use App\Http\Controllers\Admin\BillNonUserController;
use App\Http\Controllers\Admin\BillStampDutyController;
use App\Http\Controllers\Admin\BillViolationController;
use App\Http\Controllers\Admin\BillYearlyController;
use App\Http\Controllers\Admin\ChargesController;
use App\Http\Controllers\Admin\CombineBillController;
use App\Http\Controllers\Admin\Dashboard\SuperAdminDashboardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlotChargesController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Auth::routes();
Auth::routes(['register' => false, 'verify' => true]);
//Auth::routes(['verify' => true]);


Route::get('/get-province-by-country', [AjaxController::class, 'getProvinceByCountryAjax']);
Route::get('/get-district-by-province', [AjaxController::class, 'getDistrictByProvinceAjax']);
Route::get('/get-city-by-province', [AjaxController::class, 'getCityByProvinceAjax']);


Route::get('forget-password', [ForgotPasswordController::class, 'ForgetPassword'])->name('ForgetPasswordGet');
Route::post('forget-password', [ForgotPasswordController::class, 'ForgetPasswordStore'])->name('ForgetPasswordPost');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'ResetPassword'])->name('ResetPasswordGet');
Route::post('reset-password', [ForgotPasswordController::class, 'ResetPasswordStore'])->name('ResetPasswordPost');


// this routes for super admin
Route::middleware(['auth','active'])->group(function () {

    Route::get('our-dashboard', [SuperAdminDashboardController::class, 'index'])->name('our-dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('update-password', [AdminController::class, 'updatePassword'])->name('update-password');
    Route::put('change-password{id}', [AdminController::class, 'ChangePassword'])->name('change-password');

    //User Controllers
    Route::get('show-user', [UserController::class, 'show'])->name('show-user');
    Route::get('add-user', [UserController::class, 'index'])->name('add-user');
    Route::post('store-user', [UserController::class, 'store'])->name('store-user');
    Route::get('user/{id}/edit', [UserController::class, 'edit'])->name('edit-user');
    Route::put('update-user{id}', [UserController::class, 'update'])->name('update-user');
    Route::post('changeStatus-user', [UserController::class, 'changeStatus'])->name('changeStatus-user');
    Route::post('delete-user', [UserController::class, 'delete'])->name('delete-user');
    Route::post('changePassword', [UserController::class, 'changePassword'])->name('changePassword');


    // Setting Controller
    Route::get('settings', [SettingController::class, 'index'])->name('settings');
    Route::put('update-settings{id}', [SettingController::class, 'update'])->name('update-settings');

    // Sector Controller
    Route::get('show-sector', [SectorController::class, 'index'])->name('show-sector');
    Route::post('store-sector', [SectorController::class, 'store'])->name('store-sector');
    Route::get('get-sector', [SectorController::class, 'getSectors'])->name('get-sector');
    Route::put('update-sector', [SectorController::class, 'update'])->name('update-sector');
    Route::post('changeStatus-sector', [SectorController::class, 'changeStatus'])->name('changeStatus-sector');
    Route::post('delete-sector', [SectorController::class, 'delete'])->name('delete-sector');


    // Charge Controller
    Route::get('show-charge', [ChargesController::class, 'index'])->name('show-charge');
    Route::post('store-charge', [ChargesController::class, 'store'])->name('store-charge');
    Route::get('get-charge', [ChargesController::class, 'getCharges'])->name('get-charge');
    Route::put('update-charge', [ChargesController::class, 'update'])->name('update-charge');
    Route::post('changeStatus-charge', [ChargesController::class, 'changeStatus'])->name('changeStatus-charge');
    Route::post('delete-charge', [ChargesController::class, 'delete'])->name('delete-charge');

    // Size Controller
    Route::get('show-size', [SizeController::class, 'index'])->name('show-size');
    Route::post('store-size', [SizeController::class, 'store'])->name('store-size');
    Route::get('get-size', [SizeController::class, 'getSizes'])->name('get-size');
    Route::put('update-size', [SizeController::class, 'update'])->name('update-size');
    Route::post('changeStatus-size', [SizeController::class, 'changeStatus'])->name('changeStatus-size');
    Route::post('delete-size', [SizeController::class, 'delete'])->name('delete-size');

    // Size Controller
    Route::get('show-type', [TypeController::class, 'index'])->name('show-type');
    Route::post('store-type', [TypeController::class, 'store'])->name('store-type');
    Route::get('get-type', [TypeController::class, 'getTypes'])->name('get-type');
    Route::put('update-type', [TypeController::class, 'update'])->name('update-type');
    Route::post('changeStatus-type', [TypeController::class, 'changeStatus'])->name('changeStatus-type');
    Route::post('delete-type', [TypeController::class, 'delete'])->name('delete-type');


    // PlotCharges Controller
    Route::get('show-plotCharges', [PlotChargesController::class, 'show'])->name('show-plotCharges');
    Route::get('add-plotCharges', [PlotChargesController::class, 'index'])->name('add-plotCharges');
    Route::post('store-plotCharges', [PlotChargesController::class, 'store'])->name('store-plotCharges');
    Route::get('plotCharges/{id}/edit', [PlotChargesController::class, 'edit'])->name('edit-plotCharges');
    Route::put('plotCharges{id}', [PlotChargesController::class, 'update'])->name('update-plotCharges');
    Route::post('get-plotCharges', [PlotChargesController::class, 'getPlotCharges'])->name('get-plotCharges');
    Route::post('get-plotCharges-non_period', [PlotChargesController::class, 'getPlotChargesByNonPeriod'])->name('get-plotCharges-non-period');
    Route::post('get-plotCharges-violation', [PlotChargesController::class, 'getPlotChargesByViolation'])->name('get-plotCharges-violation');
    Route::post('getPlotChargesBySize', [PlotChargesController::class, 'getPlotChargesBySize'])->name('getPlotChargesBySize');
    Route::post('changeStatus-plotCharges', [PlotChargesController::class, 'changeStatus'])->name('changeStatus-plotCharges');
    Route::post('delete-plotCharges', [PlotChargesController::class, 'delete'])->name('delete-plotCharges');


    // Allotee Types Controller
    Route::get('show-allotee', [AlloteeController::class, 'show'])->name('show-allotee');
    Route::get('add-allotee', [AlloteeController::class, 'index'])->name('add-allotee');
    Route::post('store-allotee', [AlloteeController::class, 'store'])->name('store-allotee');
    Route::get('allotee/{id}/edit', [AlloteeController::class, 'edit'])->name('edit-allotee');
    Route::get('get-allotee', [AlloteeController::class, 'getAllotees'])->name('get-allotee');
    Route::put('allotee{id}', [AlloteeController::class, 'update'])->name('update-allotee');
    Route::post('changeStatus-allotee', [AlloteeController::class, 'changeStatus'])->name('changeStatus-allotee');
    Route::post('changeVerificationStatus-allotee', [AlloteeController::class, 'changeVerificationStatus'])->name('changeVerificationStatus-allotee');
    Route::post('delete-allotee', [AlloteeController::class, 'delete'])->name('delete-allotee');

    Route::get('add-allotee-transfer', [AlloteeController::class, 'indexTransfer'])->name('add-allotee-transfer');
    Route::post('store-allotee-transfer', [AlloteeController::class, 'storeTransfer'])->name('store-allotee-transfer');

    // Bill Types Controller
    Route::get('show-bill', [BillController::class, 'show'])->name('show-bill');
    Route::get('add-bill', [BillController::class, 'index'])->name('add-bill');
    Route::post('store-bill', [BillController::class, 'store'])->name('store-bill');
    Route::get('view-bill/{id}', [BillController::class, 'view'])->name('view-bill');
    Route::get('bill/{id}/edit', [BillController::class, 'edit'])->name('edit-bill');
    Route::get('get-bill', [BillController::class, 'getBills'])->name('get-bill');
    Route::put('bill{id}', [BillController::class, 'update'])->name('update-bill');
    Route::post('changeStatus-bill', [BillController::class, 'changeStatus'])->name('changeStatus-bill');
    Route::post('delete-bill', [BillController::class, 'delete'])->name('delete-bill');
    Route::post('delete-bill-bulk', [BillController::class, 'deleteBulk'])->name('delete-bill-bulk');
    Route::post('changeVerificationStatus-bill', [BillController::class, 'changeVerificationStatus'])->name('changeVerificationStatus-bill');
    Route::post('check-duplicate-bill', [BillController::class, 'checkDuplicateBill'])->name('check-duplicate-bill');
    Route::post('get-stamp-duty-bill-dropdown', [BillController::class, 'getStampDutyBillsDropdown'])->name('get-stamp-duty-bill-dropdown');
    Route::post('get-non-user-bill-dropdown', [BillController::class, 'getNonUserBillsDropdown'])->name('get-non-user-bill-dropdown');
    Route::get('view-transfer-bill/{id}', [BillController::class, 'viewTransferBill'])->name('view-transfer-bill');
    Route::get('view-possession-bill/{id}', [BillController::class, 'viewPossessionBill'])->name('view-possession-bill');

    Route::get('show-bill-non-period', [BillNonPeriodController::class, 'showNonPeriod'])->name('show-bill-non-period');
    Route::get('add-bill-non-period', [BillNonPeriodController::class, 'indexNonPeriod'])->name('add-bill-non-period');
    Route::post('store-bill-non-period', [BillNonPeriodController::class, 'storeNonPeriod'])->name('store-bill-non-period');
    Route::get('view-bill-non-period/{id}', [BillNonPeriodController::class, 'viewNonPeriod'])->name('view-bill-non-period');
    Route::get('bill-non-period/{id}/edit', [BillNonPeriodController::class, 'editNonPeriod'])->name('edit-bill-non-period');
    Route::post('bill-non-period{id}', [BillNonPeriodController::class, 'updateNonPeriod'])->name('update-bill-non-period');

    Route::get('receipt-bill', [BillController::class, 'receiptBill'])->name('receipt-bill');
    Route::get('all-receipt-bill', [BillController::class, 'allReceiptBill'])->name('all-receipt-bill');

    Route::post('store-receipt-bill', [BillController::class, 'storeReceiptBill'])->name('store-receipt-bill');


    Route::get('show-combine-bill', [CombineBillController::class, 'showCombineBill'])->name('show-combine-bill');
    Route::get('add-combine-bill', [CombineBillController::class, 'addCombineBill'])->name('add-combine-bill');
    Route::post('store-combine-bill', [CombineBillController::class, 'storeCombineBill'])->name('store-combine-bill');
    Route::get('view-combine-bill', [CombineBillController::class, 'viewCombineBill'])->name('view-combine-bill');


    // Role Controller
    Route::get('show-role', [RoleController::class, 'show'])->name('show-role');
    Route::get('add-role', [RoleController::class, 'index'])->name('add-role');
    Route::post('store-role', [RoleController::class, 'store'])->name('store-role');
    Route::get('role/{id}/edit', [RoleController::class, 'edit'])->name('edit-role');
    Route::put('update-role{id}', [RoleController::class, 'update'])->name('update-role');
    Route::post('changeStatus-role', [RoleController::class, 'changeStatus'])->name('changeStatus-role');


    // Bank Controller
    Route::get('show-bank', [BankController::class, 'show'])->name('show-bank');
    Route::get('add-bank', [BankController::class, 'index'])->name('add-bank');
    Route::post('store-bank', [BankController::class, 'store'])->name('store-bank');
    Route::get('bank/{id}/edit', [BankController::class, 'edit'])->name('edit-bank');
    Route::put('bank{id}', [BankController::class, 'update'])->name('update-bank');
    Route::get('get-bank', [BankController::class, 'getBank'])->name('get-bank');
    Route::post('changeStatus-bank', [BankController::class, 'changeStatus'])->name('changeStatus-bank');
    Route::post('delete-bank', [BankController::class, 'delete'])->name('delete-bank');


    Route::get('general-report', [ReportController::class, 'generalReport'])->name('general-report');
    Route::get('get-general-report', [ReportController::class, 'getGeneralReport'])->name('get-general-report');


    Route::get('show-bill-violation', [BillViolationController::class, 'showViolation'])->name('show-bill-violation');
    Route::get('add-bill-violation', [BillViolationController::class, 'indexViolation'])->name('add-bill-violation');
    Route::post('store-bill-violation', [BillViolationController::class, 'storeViolation'])->name('store-bill-violation');
    Route::get('view-bill-violation/{id}', [BillViolationController::class, 'viewViolation'])->name('view-bill-violation');
    Route::get('bill-violation/{id}/edit', [BillViolationController::class, 'editViolation'])->name('edit-bill-violation');
    Route::post('bill-violation{id}', [BillViolationController::class, 'updateViolation'])->name('update-bill-violation');
    Route::post('check-duplicate-bill-violation', [BillViolationController::class, 'checkDuplicateBill'])->name('check-duplicate-bill-violation');
    Route::get('get-bill-violation', [BillViolationController::class, 'getViolation'])->name('get-bill-violation');

    Route::get('receipt-bill-violation', [BillViolationController::class, 'receiptViolation'])->name('receipt-bill-violation');
    Route::post('store-receipt-bill-violation', [BillViolationController::class, 'storeReceiptViolation'])->name('store-receipt-bill-violation');


    Route::get('show-bill-non-user', [BillNonUserController::class, 'showNonUser'])->name('show-bill-non-user');
    Route::get('add-bill-non-user', [BillNonUserController::class, 'indexNonUser'])->name('add-bill-non-user');
    Route::post('store-bill-non-user', [BillNonUserController::class, 'storeNonUser'])->name('store-bill-non-user');
    Route::get('view-bill-non-user/{id}', [BillNonUserController::class, 'viewNonUser'])->name('view-bill-non-user');
    Route::get('bill-non-user/{id}/edit', [BillNonUserController::class, 'editNonUser'])->name('edit-bill-non-user');
    Route::post('bill-non-user{id}', [BillNonUserController::class, 'updateNonUser'])->name('update-bill-non-user');
    Route::post('check-duplicate-bill-non-user', [BillNonUserController::class, 'checkDuplicateBill'])->name('check-duplicate-bill-non-user');
    Route::get('get-bill-non-user', [BillNonUserController::class, 'getNonUser'])->name('get-bill-non-user');
    Route::get('receipt-bill-non-user', [BillNonUserController::class, 'receiptNonUser'])->name('receipt-bill-non-user');
    Route::post('store-receipt-bill-non-user', [BillNonUserController::class, 'storeReceiptNonUser'])->name('store-receipt-bill-non-user');

    Route::get('show-bill-stamp-duty', [BillStampDutyController::class, 'showStampDuty'])->name('show-bill-stamp-duty');
    Route::get('add-bill-stamp-duty', [BillStampDutyController::class, 'indexStampDuty'])->name('add-bill-stamp-duty');
    Route::post('store-bill-stamp-duty', [BillStampDutyController::class, 'storeStampDuty'])->name('store-bill-stamp-duty');
    Route::get('view-bill-stamp-duty/{id}', [BillStampDutyController::class, 'viewStampDuty'])->name('view-bill-stamp-duty');
    Route::get('bill-stamp-duty/{id}/edit', [BillStampDutyController::class, 'editStampDuty'])->name('edit-bill-stamp-duty');
    Route::post('bill-stamp-duty{id}', [BillStampDutyController::class, 'updateStampDuty'])->name('update-bill-stamp-duty');
    Route::post('check-duplicate-bill-stamp-duty', [BillStampDutyController::class, 'checkDuplicateBill'])->name('check-duplicate-bill-stamp-duty');
    Route::get('get-bill-stamp-duty', [BillStampDutyController::class, 'getStampDuty'])->name('get-bill-stamp-duty');
    Route::get('receipt-bill-stamp-duty', [BillStampDutyController::class, 'receiptStampDuty'])->name('receipt-bill-stamp-duty');
    Route::post('store-receipt-bill-stamp-duty', [BillStampDutyController::class, 'storeReceiptStampDuty'])->name('store-receipt-bill-stamp-duty');


    Route::get('show-bill-yearly', [BillYearlyController::class, 'showYearly'])->name('show-bill-yearly');
    Route::get('add-bill-yearly', [BillYearlyController::class, 'indexYearly'])->name('add-bill-yearly');
    Route::post('store-bill-yearly', [BillYearlyController::class, 'storeYearly'])->name('store-bill-yearly');
    Route::get('view-bill-yearly/{id}', [BillYearlyController::class, 'viewYearly'])->name('view-bill-yearly');
    Route::get('bill-yearly/{id}/edit', [BillYearlyController::class, 'editYearly'])->name('edit-bill-yearly');
    Route::post('bill-yearly{id}', [BillYearlyController::class, 'updateYearly'])->name('update-bill-yearly');
    Route::post('check-duplicate-bill-yearly', [BillYearlyController::class, 'checkDuplicateBill'])->name('check-duplicate-bill-yearly');
    Route::get('get-bill-yearly', [BillYearlyController::class, 'getYearly'])->name('get-bill-yearly');
    Route::get('receipt-bill-yearly', [BillYearlyController::class, 'receiptYearly'])->name('receipt-bill-yearly');
    Route::post('store-receipt-bill-yearly', [BillYearlyController::class, 'storeReceiptYearly'])->name('store-receipt-bill-yearly');

});










