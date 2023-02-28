<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Article\ArticleView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $data = Article::query()
            ->selectRaw('id, slug_id, title,trx_code, is_ads, banner, published_date, category, user_id, status, (CASE WHEN meta_desc IS NULL THEN SUBSTRING(detail, 1, 300) ELSE meta_desc END) detail')
            ->with('author:id,name')
            ->orderBy('published_date', 'DESC')  
            ->when($request->headers->get('Origin') != config('app.admin_url'), function ($query) use ($request) {
                $query->where('is_ads', '0')->published();
            })
            ->when($request->headers->get('Origin') == config('app.admin_url'), function ($query) use ($request) {
                $query->orderable($request->orders);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('detail', 'like', '%' . $request->search . '%');
            })
            ->paging($request->perpage ?? 10);

        $data['data']->transform(function ($item) {
            $item->detail = Str::of(strip_tags($item->detail))->limit(200);
            return $item;
        });

        return response()->json($data);
    }

    public function show(Request $request, $slug)
    {
        if ($request->preview) {
            $article = session()->get('article_' . $slug);

            return response()->json([
                'data' => $article,
                'next' => null,
                'prev' => null,
            ]);
        }

        $article = Article::selectRaw('id, slug_id,title,trx_code,detail, is_ads, banner,status, published_date,open_discuss,category,meta_title,meta_desc')
            ->with('comments')
            ->where('slug_id', $slug)->orWhere('id', $slug)->firstOrFail();
        if ($article->trx_code == 'ART-PRE' && !auth()->check()) {
            $article->detail = Str::of(strip_tags($article->detail))->limit(300);
        }
        $next = Article::selectRaw('id, slug_id, title')->published()->where('id', '<>', $article->id)->where('published_date', '>=', $article->published_date)->first();

        $prev = Article::selectRaw('id, slug_id, title')->published()->where('id', '<>', $article->id)->where('published_date', '<=', $article->published_date)->first();

        ArticleView::UpdateOrCreate(
            ['article_id' => $article->id, 'session_id' => session()->getId()],
            [
                'article_id' => $article->id,
                'url'     => $request->fullUrl(),
                'user_id' => @$request->user()->id,
                'session_id' => session()->getId(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]
        );

        return response()->json([
            'data' => $article,
            'next' => $next,
            'prev' => $prev,
        ]);
    }

    public function premium()
    {
        $data = Article::query()
            ->selectRaw('id, slug_id, title, banner, is_ads, published_date')
            ->published()
            ->where('is_ads', '1')
            // ->where('ads_start_date', '<=', date('Y-m-d'))
            // ->where('ads_end_date', '>=', date('Y-m-d'))
            ->orderBy('published_date', 'DESC')
            ->limit(4)
            ->get();

        return response()->json($data);
    }

    public function popular()
    {
        $data = Article::query()
            ->selectRaw('id, slug_id, title, banner, is_ads, published_date, user_id')
            ->published()
            ->viewCount()
            ->with('author:id,name')
            ->orderBy('num_view')
            ->limit(4)
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'detail' => 'required',
            'trx_code' => 'required',
            'banner' => 'required|image',
            'status' => 'required',
            'open_discuss' => 'nullable',
            'is_ads' => 'nullable',
            'published_date' => 'required',
            'category' => 'required',
            'slug_id' => 'nullable|max:120',
            'meta_desc' => 'nullable|max:255',
            'meta_title' => 'nullable|max:120'
        ]);

        $validated['banner'] = $request->hasFile('banner') ? $request->file('banner')->storePublicly('public/article', 'oss') : ''; //upload foto
        $validated['trx_date'] = now()->toDateTimeString();
        $validated['created_by'] = auth()->id();
        $validated['user_id'] = auth()->id();

        return response()->json(Article::create($validated), 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'trx_code' => 'required',
            'detail' => 'required',
            'banner' => 'nullable|image',
            'status' => 'required',
            'open_discuss' => 'nullable',
            'is_ads' => 'nullable',
            'published_date' => 'required',
            'category' => 'required',
            'slug_id' => 'nullable|max:120',
            'meta_desc' => 'nullable|max:255',
            'meta_title' => 'nullable|max:120'
        ]);

        $data = Article::find($id);

        if ($request->hasFile('banner')) {
            $validated['banner'] =  $request->file('banner')->storePublicly('public/article', 'oss');
            Storage::disk('oss')->delete($data->banner);
        }

        $data->update($validated);
        return response()->json($data, 200);
    }

    public function store_prev(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'detail' => 'required',
            'trx_code' => 'required',
            'banner' => 'nullable|image',
            'status' => 'required',
            'published_date' => 'required',
            'category' => 'required',
            'slug_id' => 'nullable|max:120',
            'meta_desc' => 'nullable|max:255',
            'meta_title' => 'nullable|max:120'
        ]);

        $validated['banner'] = $request->hasFile('banner') ? base64_encode(file_get_contents($request->file('banner'))) : base64_encode(file_get_contents('https://propertywiselaunceston.com.au/wp-content/themes/property-wise/images/no-image.png'));

        $validated['trx_date'] = now()->toDateTimeString();
        $article = (new Article())->fill($validated)->toArray();
        $article['banner_url'] = 'data:' . ($request->hasFile('banner') ? $request->file('banner')->getMimeType() : 'image/png') . ';base64,' . $article['banner'];

        $previewid = md5($article['title']);
        session()->forget('article_' . $previewid);
        session()->put('article_' . $previewid, $article);

        return response()->json([
            'status' => 'OK',
            'previewid' => $previewid,
            'url' => config('app.frontend_url') . "/insight/$previewid?preview=true"
        ]);
    }

    public function destroy(Request $request)
    {
        try {
            Article::whereIn('id', $request->all())->delete();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }

        return response()->noContent();
    }
}
