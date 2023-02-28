<?php

use App\Http\Controllers\Article\ArtCommentController;
use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Consult\CatConsultantController;
use App\Http\Controllers\Consult\ConCommentController;
use App\Http\Controllers\Consult\ConsultantController;
use App\Http\Controllers\Consult\ConThreadController;
use App\Http\Controllers\Glossary\GlossaryController;
use App\Http\Controllers\Profile\CategoryController;
use App\Http\Controllers\Profile\DegreeController;
use App\Http\Controllers\Profile\IndustriController;
use App\Http\Controllers\Profile\MajorController;
use App\Http\Controllers\Profile\PositionController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\RegionController;
use App\Http\Controllers\Profile\SkillController;
use App\Http\Controllers\Profile\SocialController;
use App\Http\Controllers\Profile\UniversityController;
use App\Http\Controllers\Threads\CommentController;
use App\Http\Controllers\Threads\ThreadsController;
use App\Http\Controllers\Training\OrderController;
use App\Http\Controllers\Training\ProviderController;
use App\Http\Controllers\Training\ScheduleController;
use App\Http\Controllers\Training\SyllabusController;
use App\Http\Controllers\Training\TrainerController;
use App\Http\Controllers\Training\TrainingController;
use App\Http\Controllers\Utility\BankController;
use App\Http\Controllers\Utility\FilesController;
use App\Http\Controllers\Utility\NotifController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
require __DIR__.'/admin.php';
/*
--------------------------------------------------------------------------
| route for authenticated api
--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth:sanctum'], function(){
    //general route
    Route::get('/user', [UserController::class, 'getUser'])->middleware('admin');
    Route::get('/industries', [IndustriController::class, 'index']);
    Route::get('/positions', [PositionController::class, 'index']);
    Route::get('/socials', [SocialController::class, 'socials']);
    Route::get('/university', [UniversityController::class, 'index']);
    Route::get('/degrees', [DegreeController::class, 'index']);
    Route::get('/majors', [MajorController::class, 'index']);
    Route::get('/skills', [SkillController::class, 'index']);
    
    //thread route
    Route::get('/poll-duration', [ThreadsController::class, 'pollduration']);
    Route::post('/threads', [ThreadsController::class, 'store']);
    Route::post('/threads/{thread}/edit', [ThreadsController::class, 'update']);
    Route::post('/threads/{thread}/love', [ThreadsController::class, 'love']);
    Route::post('/threads/{thread}/bookmark', [ThreadsController::class, 'bookmark']);
    Route::post('/threads/{thread}/dismiss', [ThreadsController::class, 'dismiss']);
    Route::post('/threads/{thread}/polling', [ThreadsController::class, 'vote']);
    Route::post('/threads/{thread}/report', [ThreadsController::class, 'report']);
    Route::delete('/threads/{thread}', [ThreadsController::class, 'delete']);
    //comment thread route
    Route::post('/comments/{comment}/love', [CommentController::class, 'love']);
    Route::post('/comments/{thread}', [CommentController::class, 'save']);
    Route::delete('/comments/{comment}', [CommentController::class, 'delete']);

    //consulting thread route
    Route::post('/consulting', [ConThreadController::class, 'save']);
    Route::post('/consulting/{thread}/love', [ConThreadController::class, 'love']);
    Route::delete('/consulting/{thread}', [ConThreadController::class, 'delete']);
    //consulting comment route
    Route::post('/consulting/comments/{thread}', [ConCommentController::class, 'save']);
    Route::delete('/consulting/comments/{thread}', [ConCommentController::class, 'delete']);

    Route::post('/consultant/verify', [ConsultantController::class, 'submit']);
    Route::get('/consultant/submission', [ConsultantController::class, 'submission']);

    //profile route
    Route::get('/profile/user', [ProfileController::class, 'profile']);
    Route::get('/profile/like', [ProfileController::class, 'liked']);
    Route::get('/profile/bookmark', [ProfileController::class, 'bookmarked']);
    Route::get('/profile/notification', [ProfileController::class, 'notification']);
    Route::post('/profile', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/password', [ProfileController::class, 'updatePassword']);
    Route::post('/profile/notification', [ProfileController::class, 'updateNotification']);

    //order route
    Route::apiResource('/order', OrderController::class)->except(['index','update', 'destroy']);
    Route::post('/order/{id}', [OrderController::class, 'update'])->name('order.update');
    Route::post('/order/event/free', [OrderController::class, 'free'])->name('order.free');
    Route::get('/bank', [BankController::class, 'index']);

    Route::get('/notifications', [NotifController::class, 'index'])->name('notif.index');
    Route::post('/notifications', [NotifController::class, 'read'])->name('notif.reads');

    Route::post('/article/comment', [ArtCommentController::class, 'store']);
});
/*
--------------------------------------------------------------------------
| route for public api
--------------------------------------------------------------------------
*/

//general route
Route::get('/regions/{country?}/{state?}', [RegionController::class, 'getRegion']);
//profile route
Route::get('/profile/user/{id?}', [ProfileController::class, 'profile']);
Route::get('/profile/threads', [ProfileController::class, 'threads']);
Route::get('/profile/consultation', [ProfileController::class, 'consultation']);
Route::get('/profile/comment', [ProfileController::class, 'comment']);

//threads route
Route::get('/types', [ThreadsController::class, 'getTypes']);
Route::get('/threads/most-discuses', [ThreadsController::class, 'getDiscuses']);
Route::get('/threads/{category?}', [ThreadsController::class, 'index']);
Route::get('/threads/{thread}/show', [ThreadsController::class, 'show']);
Route::get('/threads/{thread}/related', [ThreadsController::class, 'related']);
//comment thread route
Route::get('/comments/{thread}', [CommentController::class, 'index']);

Route::get('/consulting/{category?}', [ConThreadController::class, 'index']);
Route::get('/consulting/{thread}/show', [ConThreadController::class, 'show']);
Route::get('/consulting/comments/{thread}', [ConCommentController::class, 'index']);

//consulting route
Route::get('/consultant/category', [CatConsultantController::class, 'category']);
Route::get('/consultant/list', [ConsultantController::class, 'list']);

//articles route
Route::get('/article', [ArticleController::class, 'index']);
Route::get('/article/{article}/show', [ArticleController::class, 'show']);

Route::get('/article/premium', [ArticleController::class, 'premium']);
Route::get('/article/popular', [ArticleController::class, 'popular']);

// glossary routes
Route::get('/glossary', [GlossaryController::class, 'index']);
Route::get('/glossary/types-insight', [GlossaryController::class, 'getCategoryOfInsight']);

//event training route
Route::get('/training/premium', [TrainingController::class, 'getPremium']);
Route::get('/training/calendar', [TrainingController::class, 'calendar']);
Route::get('/training/{type}', [TrainingController::class, 'index']);
Route::get('/training/{type}/{id}', [TrainingController::class, 'show']);
//trainer route
Route::get('/trainer', [TrainerController::class, 'index']);
Route::get('/trainer/{id}', [TrainerController::class, 'show']);
Route::get('/trainer/{id}/course', [TrainerController::class, 'course']);
//provider route
Route::get('/provider', [ProviderController::class, 'index']);
Route::get('/provider/{id}', [ProviderController::class, 'show']);
//schedule route
Route::get('/schedule/{id}', [ScheduleController::class, 'index'])->name('schedule.index');
Route::get('/schedule/{id}/show', [ScheduleController::class, 'show'])->name('schedule.show');
//syllabus route
Route::get('/syllabus/{id}', [SyllabusController::class, 'index'])->name('syllabus.index');





