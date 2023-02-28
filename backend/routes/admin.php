<?php

use App\Http\Controllers\Article\ArtCommentController;
use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Consult\CatConsultantController;
use App\Http\Controllers\Consult\ConsultantController;
use App\Http\Controllers\Glossary\GlossaryController;
use App\Http\Controllers\Master\SocialMediaController;
use App\Http\Controllers\Profile\CityController;
use App\Http\Controllers\Profile\DegreeController;
use App\Http\Controllers\Training\ClassesController;
use App\Http\Controllers\Training\CompanyController;
use App\Http\Controllers\Training\CompanyTypeController;
use App\Http\Controllers\Training\OrderController;
use App\Http\Controllers\Training\ProviderController;
use App\Http\Controllers\Training\ScheduleController;
use App\Http\Controllers\Training\SyllabusController;
use App\Http\Controllers\Training\TrainerController;
use App\Http\Controllers\Training\TrainingController;
use App\Http\Controllers\Utility\BankController;
use App\Http\Controllers\Profile\IndustriController;
use App\Http\Controllers\Profile\MajorController;
use App\Http\Controllers\Profile\PositionController;
use App\Http\Controllers\Profile\SkillController;
use App\Http\Controllers\Profile\SocialController;
use App\Http\Controllers\Profile\StateController;
use App\Http\Controllers\Profile\UniversityController;
use App\Http\Controllers\Threads\ThreadsController;
use App\Http\Controllers\Utility\SitemapController;
use App\Http\Controllers\Utility\UploadController;
use Illuminate\Support\Facades\Route;

/*
--------------------------------------------------------------------------
| route for api admin
--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
    Route::apiResource('/company', CompanyController::class);
    Route::apiResource('/company-type', CompanyTypeController::class);
    Route::apiResource('/training', TrainingController::class)->only(['store', 'update']);
    Route::post('/training/deletes', [TrainingController::class, 'destroy'])->name('training.destroy');
    Route::apiResource('/syllabus', SyllabusController::class)->except(['show', 'index']);
    Route::apiResource('/class', ClassesController::class)->except(['show']);
    Route::apiResource('/schedule', ScheduleController::class)->except(['show', 'index']);
    Route::apiResource('/order', OrderController::class)->except(['show', 'store', 'update']);
    Route::apiResource('/industries', IndustriController::class)->except(['index', 'destroy']);
    Route::post('/industries/deletes', [IndustriController::class, 'destroy'])->name('industries.destroy');
    Route::apiResource('/positions', PositionController::class)->except(['index', 'destroy']);
    Route::post('/positions/deletes', [PositionController::class, 'destroy'])->name('positions.destroy');
    Route::apiResource('/university', UniversityController::class)->except(['index', 'destroy']);
    Route::post('/university/deletes', [UniversityController::class, 'destroy'])->name('university.destroy');
    Route::apiResource('/degrees', DegreeController::class)->except(['index', 'destroy']);
    Route::post('/degrees/deletes', [DegreeController::class, 'destroy'])->name('degrees.destroy');
    Route::apiResource('/majors', MajorController::class)->except(['index', 'destroy']);
    Route::post('/majors/deletes', [MajorController::class, 'destroy'])->name('majors.destroy');
    Route::apiResource('/state', StateController::class)->except(['destroy']);
    Route::post('/state/deletes', [StateController::class, 'destroy'])->name('state.destroy');
    Route::apiResource('/city', CityController::class)->except(['destroy']);
    Route::post('/city/deletes', [CityController::class, 'destroy'])->name('city.destroy');

    Route::apiResource('/category/consultant', CatConsultantController::class)->except(['destroy']);
    Route::post('/category/consultant/deletes', [CatConsultantController::class, 'destroy'])->name('category-consultant.destroy');

    Route::apiResource('/social-media', SocialController::class)->except(['destroy']);
    Route::post('/social-media/deletes', [SocialController::class, 'destroy'])->name('social-media.destroy');

    Route::apiResource('/bank', BankController::class)->except(['destroy', 'index']);
    Route::post('/bank/deletes', [BankController::class, 'destroy'])->name('bank.destroy');

    Route::apiResource('/trainer', TrainerController::class)->only(['store', 'update']);
    Route::get('/trainer', [TrainerController::class, 'admin'])->name('trainer.admin');
    Route::post('/trainer/deletes', [TrainerController::class, 'destroy'])->name('trainer.destroy');

    Route::apiResource('/provider', ProviderController::class)->only(['store', 'update']);
    Route::post('/provider/deletes', [ProviderController::class, 'destroy'])->name('provider.destroy');

    Route::apiResource('/article', ArticleController::class)->only(['store', 'update']);
    Route::post('/article/preview', [ArticleController::class, 'store_prev'])->name('article.store_prev');
    Route::post('/article/deletes', [ArticleController::class, 'destroy'])->name('article.destroy');
    Route::get('/article/comment', [ArtCommentController::class, 'index']);
    Route::post('/article/comment/{status}', [ArtCommentController::class, 'action']);

    // glossary routes
    Route::apiResource('/glossary', GlossaryController::class)->only(['store', 'update']);


    Route::post('/upload/{content}', [UploadController::class, 'upload'])->name('upload.data');

    Route::apiResource('/skills', SkillController::class)->only(['store', 'update', 'show']);
    Route::post('/skills/deletes', [SkillController::class, 'destroy'])->name('skills.destroy');

    Route::get('/consultant', [ConsultantController::class, 'index']);
    Route::post('/consultant', [ConsultantController::class, 'store']);
    Route::put('/consultant/{id}', [ConsultantController::class, 'update']);

    Route::get('/consultant/submissions', [ConsultantController::class, 'submissions']);
    Route::get('/consultant/submission/{id}', [ConsultantController::class, 'submited']);
    Route::post('/consultant/submission/{id}', [ConsultantController::class, 'verify']);
    Route::get('/threads/reported', [ThreadsController::class, 'reported']);
    Route::get('/threads/{thread}/chart', [ThreadsController::class, 'chart']);
    Route::get('/threads/{thread}/reporters', [ThreadsController::class, 'reporters']);
    Route::get('/threads/{thread}/visibility', [ThreadsController::class, 'visibility']);
});

Route::get('/sitemap/article', [SitemapController::class, 'article']);
Route::get('/sitemap/event', [SitemapController::class, 'event']);
