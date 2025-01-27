<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FAQsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PDFPrintController;
use App\Http\Controllers\ChangeLogController;
use App\Http\Controllers\HomeOwnersCntroller;
use App\Http\Controllers\LuckyDrawController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\PrizeCheckController;
use App\Http\Controllers\CustomerIPsController;
use App\Http\Controllers\CustomerViewController;
use App\Http\Controllers\InvoiceCheckController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\NewPromotionController;
use App\Http\Controllers\PreusedSlipsController;
use App\Http\Controllers\PrizeCCCheckController;
use App\Http\Controllers\ProductCheckController;
use App\Http\Controllers\ReturnChecksController;
use App\Http\Controllers\SubPromotionController;
use App\Http\Controllers\TicketReportController;
use App\Http\Controllers\AllCategoriesController;
use App\Http\Controllers\LuckyDrawTypeController;
use App\Http\Controllers\ReturnBannersController;
use App\Http\Controllers\InstallerCardsController;
use App\Http\Controllers\CCWinningChanceController;
use App\Http\Controllers\PointPromotionsController;
use App\Http\Controllers\SaleAmountChecksController;
use App\Http\Controllers\CreateTicket\ClaimController;
use App\Http\Controllers\CreditPointAdjustsController;
use App\Http\Controllers\HomeownerInstallersController;
use App\Http\Controllers\InstallerCardPointsController;
use App\Http\Controllers\InstallerHomeownersController;
use App\Http\Controllers\CardNumberGeneratorsController;
use App\Http\Controllers\CreateTicket\SummaryController;
use App\Http\Controllers\FixedPrizeAmountCheckController;
use App\Http\Controllers\CollectionTransactionsController;
use App\Http\Controllers\RedemptionTransactionsController;
use App\Http\Controllers\ReturnProductDocumentsController;
use App\Http\Controllers\NewCreateTicket\InvoicesController;
use App\Http\Controllers\CreateTicket\CustomerInfoController;
use App\Http\Controllers\NewCreateTicket\NewDesignController;
use App\Http\Controllers\NewCreateTicket\NewSummaryController;
use App\Http\Controllers\CreateTicket\CollectInvoiceController;
use App\Http\Controllers\NewCreateTicket\InformationController;
use App\Http\Controllers\NewCreateTicket\MyPromotionController;
use App\Http\Controllers\CreateTicket\ChoosePromotionController;
use App\Http\Controllers\NewCreateTicket\CreateInvoiceController;
use App\Http\Controllers\CollectionTransactionDeleteLogsController;

Route::get('/', function () {
    return view('auth.login');
});
Route::get("/user_login/{employee_id}/{password}", function ($employee_id, $password) {
    return view('auth.user_login', compact('employee_id', 'password'));
});
/////New Design/////
Route::get('all_layout', [NewDesignController::class, 'all_layout'])->name('all_layout');

Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::get('/customauth/currentbranch', [CustomAuthController::class, 'currentbranch'])->name('customauth.currentbranch');
    Route::post('/customauth/currentbranch', [CustomAuthController::class, 'store'])->name('customauth.currentbranch.store');
});

Route::group(['middleware' => ['auth','customauth.currentbranch']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');


    Route::get('lang/{locale}', [LocalizationController::class, 'index'])->name('lang');
    Route::get('/users/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::resource('users', UserController::class);
    Route::post('/users/update_profile', [UserController::class, 'update_profile'])->name('user.update_profile');
    Route::resource('roles', RoleController::class);
    // Route::resource('permissions', PermissionController::class);


    Route::get('/installercards',[InstallerCardsController::class,"index"])->name("installercards.index");
    Route::get('/installercards/create',[InstallerCardsController::class,"create"])->name("installercards.create");
    Route::post('/installercards',[InstallerCardsController::class,"store"])->name("installercards.store");
    Route::get('/installercards/{cardnumber}/edit',[InstallerCardsController::class,"edit"])->name("installercards.edit");
    Route::patch('/installercards/refresh/{cardnumber}',[InstallerCardsController::class,"refresh"])->name("installercards.refresh");
    Route::delete('/installercards/{cardnumber}',[InstallerCardsController::class,"destroy"])->name("installercards.destroy");
    Route::get('/installercards/verifycustomer',[InstallerCardsController::class,"verifycustomer"])->name("installercards.verifycustomer");
    Route::get('/installercards/checking',[InstallerCardsController::class,"checking"])->name("installercards.checking");
    Route::get('/installercards/check',[InstallerCardsController::class,"check"])->name("installercards.check");
    Route::get('/installercards/{cardnumber}/track',[InstallerCardsController::class,"track"])->name("installercards.track");
    Route::post('/installercards/storecardlock',[InstallerCardsController::class,"storecardlock"])->name("installercards.storecardlock");
    Route::post("/installercardsstatus",[InstallerCardsController::class,"changestatus"]);
    Route::get('/searchinstallercards',[InstallerCardsController::class,'search'])->name('installercards.search');
    Route::post('/installercards/transfer/{cardnumber}',[InstallerCardsController::class,"transfer"])->name("installercards.transfer");
    Route::get('/installercards/match',[InstallerCardsController::class,"match"])->name("installercards.match");
    Route::post('/installercards/matchbysaleamount',[InstallerCardsController::class,"matchbysaleamount"])->name("installercards.matchbysaleamount");
    Route::get('/installercards/register',[InstallerCardsController::class,"register"])->name("installercards.register");
    Route::post("/installercards/{cardnumber}/approvecardrequest",[InstallerCardsController::class,'approveCardRequest'])->name('installercards.approveCardRequest');
    Route::post("/installercards/{cardnumber}/rejectcardrequest",[InstallerCardsController::class,'rejectCardRequest'])->name('installercards.rejectCardRequest');



    Route::post('/homeownerinstallers',[HomeownerInstallersController::class,"store"])->name("homeownerinstallers.store");
    Route::delete('/homeownerinstallers/{id}',[HomeownerInstallersController::class,"destroy"])->name("homeownerinstallers.destroy");
    Route::delete("/homeownerinstallersbulkdeletes",[HomeownerInstallersController::class,"bulkdeletes"])->name("homeownerinstallers.bulkdeletes");



    Route::get('/homeowners',[HomeOwnersCntroller::class,"index"])->name("homeowners.index");
    Route::get('/homeowners/create',[HomeOwnersCntroller::class,"create"])->name("homeowners.create");
    Route::post('/homeowners',[HomeOwnersCntroller::class,"store"])->name("homeowners.store");
    Route::get('/homeowners/{homeowner}/edit',[HomeOwnersCntroller::class,"edit"])->name("homeowners.edit");
    Route::patch('/homeowners/refresh/{homeowner}',[HomeOwnersCntroller::class,"refresh"])->name("homeowners.refresh");
    Route::get('/homeowners/verifycustomer',[HomeOwnersCntroller::class,"verifycustomer"])->name("homeowners.verifycustomer");
    Route::delete('/homeowners/{homeowner}',[HomeOwnersCntroller::class,"destroy"])->name("homeowners.destroy");
    Route::get('/searchhomeowners',[InstallerCardsController::class,'search'])->name('homeowners.search');



    Route::get('/installercardpoints/detail/{cardnumber}',[InstallerCardPointsController::class,'detail'])->name('installercardpoints.detail')
    // ->middleware(['installercards.cardlocked'])
    ;
    Route::post('/installercardpoints/requestredeem/{cardnumber}',[InstallerCardPointsController::class,'requestredeem'])->name('installercardpoints.requestredeem');
    Route::post('/installercardpoints/collectpoints/{cardnumber}',[InstallerCardPointsController::class,'collectpoints'])->name('installercardpoints.collectpoints');
    Route::get('/installercardpoints',[InstallerCardPointsController::class,'index'])->name('installercardpoints.index');
    Route::get('/installercardpoints/find',[InstallerCardPointsController::class,'find'])->name('installercardpoints.find');
    Route::get('/installercardpoints/calequivalentamount/{cardnumber}',[InstallerCardPointsController::class,'calculateEquivalentAmount'])->name('installercardpoints.calculateEquivalentAmount');
    Route::get('/installercardpoints/{cardnumber}/check',[InstallerCardPointsController::class,'check'])->name('installercardpoints.check');
    Route::get('/installercardpoints/search/{cardnumber}',[InstallerCardPointsController::class,'search'])->name('installercardpoints.search');


    Route::resource('/pointpromos',PointPromotionsController::class);
    Route::get('/searchpointpromos',[PointPromotionsController::class,'search'])->name('pointpromos.search');


    Route::get("/redemptiontransactions",[RedemptionTransactionsController::class,'index'])->name('redemptiontransactions.index');
    Route::get("/redemptiontransactions/{redemptiontransaction}",[RedemptionTransactionsController::class,'show'])->name('redemptiontransactions.show');
    Route::get("/redemptiontransaction/approvalnotifications",[RedemptionTransactionsController::class,'approvalnotifications'])->name('redemptiontransactions.approvalnotifications');
    Route::post("/redemptiontransactions/{redemptiontransaction}/approveredemptionrequest",[RedemptionTransactionsController::class,'approveRedemptionRequest'])->name('redemptiontransactions.approveRedemptionRequest');
    Route::post("/redemptiontransactions/{redemptiontransaction}/rejectredemptionrequest/{step}",[RedemptionTransactionsController::class,'rejectRedemptionRequest'])->name('redemptiontransactions.rejectRedemptionRequest');
    Route::post("/redemptiontransactions/{redemptiontransaction}/paidredemptionrequest",[RedemptionTransactionsController::class,'paidRedemptionRequest'])->name('redemptiontransactions.paidRedemptionRequest');
    Route::post("/redemptiontransactions/{redemptiontransaction}/finishedredemptionrequest",[RedemptionTransactionsController::class,'finishRedemptionRequest'])->name('redemptiontransactions.finishRedemptionRequest');
    Route::get('/searchredemptiontransactions',[RedemptionTransactionsController::class,'search'])->name('redemptiontransactions.search');


    Route::get("/collectiontransactions",[CollectionTransactionsController::class,'index'])->name('collectiontransactions.index');
    Route::get("/collectiontransactions/{collectiontransaction}",[CollectionTransactionsController::class,'show'])->name('collectiontransactions.show');
    Route::delete("/collectiontransactions/{collectiontransaction}",[CollectionTransactionsController::class,'destroy'])->name('collectiontransactions.destroy');
    Route::get('/searchcollectiontransactions',[CollectionTransactionsController::class,'search'])->name('collectiontransactions.search');
    Route::post("/collectiontransactions/{collectiontransaction}/returnproduct",[CollectionTransactionsController::class,'returnproduct'])->name('collectiontransactions.returnproduct');

    Route::get("/collectiontransactiondeletelogs",[CollectionTransactionDeleteLogsController::class,'index'])->name('collectiontransactiondeletelogs.index');


    Route::get('/filter/groups/{maincatid}',[AllCategoriesController::class,'groupfilterbyMaincatid']);


    Route::get("/returnbanners/{returnbanner}",[ReturnBannersController::class,'show'])->name('returnbanners.show');


    Route::get('/returnproductdocuments/checking',[ReturnProductDocumentsController::class,"checking"])->name("returnproductdocuments.checking");

    Route::get("/saleamountchecks",[SaleAmountChecksController::class,'index'])->name('saleamountchecks.index');
    Route::get("/saleamountchecks/{saleamountcheck}",[SaleAmountChecksController::class,'show'])->name('saleamountchecks.show');
    Route::get("/searchsaleamountchecks",[SaleAmountChecksController::class,'search'])->name('saleamountchecks.search');

    Route::get("/returnchecks",[ReturnChecksController::class,'index'])->name('returnchecks.index');

    Route::get("/preusedslips/{preusedslip}",[PreusedSlipsController::class,'show'])->name('preusedslips.show');


    Route::get("/faqs",[FAQsController::class,'index'])->name('faqs.index');

    Route::get("/cardnumbergenerators",[CardNumberGeneratorsController::class,'index'])->name('cardnumbergenerators.index');
    Route::get('/cardnumbergenerators/create',[CardNumberGeneratorsController::class,"create"])->name("cardnumbergenerators.create");
    Route::post('/cardnumbergenerators',[CardNumberGeneratorsController::class,"store"])->name("cardnumbergenerators.store");
    Route::get('/cardnumbergenerators/{uuid}/edit',[CardNumberGeneratorsController::class,"edit"])->name("cardnumbergenerators.edit");
    Route::get("/cardnumbergenerators/{cardnumbergenerator}",[CardNumberGeneratorsController::class,'show'])->name('cardnumbergenerators.show');
    // Route::delete("/cardnumbergenerators/{cardnumbergenerator}",[CardNumberGeneratorsController::class,'destroy'])->name('cardnumbergenerators.destroy');
    Route::get('/cardnumbergenerators/{uuid}/export',[CardNumberGeneratorsController::class,"export"])->name("cardnumbergenerators.export");
    Route::post("/cardnumbergenerators/{cardnumbergenerator}/approveCardNumberGenerator",[CardNumberGeneratorsController::class,'approveCardNumberGenerator'])->name('cardnumbergenerators.approveCardNumberGenerator');
    Route::post("/cardnumbergenerators/{cardnumbergenerator}/rejectCardNumberGenerator/{step}",[CardNumberGeneratorsController::class,'rejectCardNumberGenerator'])->name('cardnumbergenerators.rejectCardNumberGenerator');


    Route::get("/creditpointadjusts",[CreditPointAdjustsController::class,'index'])->name('creditpointadjusts.index');
    Route::post('/creditpointadjusts',[CreditPointAdjustsController::class,"store"])->name("creditpointadjusts.store");
    Route::get("/creditpointadjusts/{creditpointadjust}",[CreditPointAdjustsController::class,'show'])->name('creditpointadjusts.show');
    Route::get('/creditpointadjusts/{uuid}/edit',[CreditPointAdjustsController::class,"edit"])->name("creditpointadjusts.edit");
    Route::post("/creditpointadjusts/{creditpointadjusts}/approvecreditpointadjustreq",[CreditPointAdjustsController::class,'approveCreditPointAdjustReq'])->name('creditpointadjusts.approveCreditPointAdjustReq');
    Route::post("/creditpointadjusts/{creditpointadjusts}/rejectcreditpointadjustreq",[CreditPointAdjustsController::class,'rejectCreditPointAdjustReq'])->name('creditpointadjusts.rejectCreditPointAdjustReq');

});
