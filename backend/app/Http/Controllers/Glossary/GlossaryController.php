<?php

namespace App\Http\Controllers\Glossary;

use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Glossary\Glossary;
use App\Models\Threads\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GlossaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $data = Glossary::select('art_glossary.*', 'at.title as title_insight', 'at.slug_id', 'at.category', 'at.detail')
            ->leftJoin('art_trx_articles as at', 'at.id', 'art_glossary.article_id')
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('art_glossary.title', 'like', '%' . $request->search . '%')
                    ->orWhere('at.detail', 'like', '%' . $request->search . '%')
                    ->orWhere('at.title', 'like', '%' . $request->search . '%');
            })
            ->when($request->has('filter') && count($request->filter) > 0, function ($query) use ($request) {
                foreach ($request->filter as $idx => $category) {
                    $query->{$idx == 0 ? 'where' : 'orWhere'}("at.category", "LIKE", '%' . $category . '%');
                }
            })
            ->orderBy('art_glossary.title', 'asc')
            ->orderBy('art_glossary.letter', 'asc')
            ->get();
        return response()->json($data->groupBy('letter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'title' => 'required|max:100',
            'detail' => 'required',
            'trx_code' => 'required',
            'banner' => 'nullable|image',
            'status' => 'required',
            'open_discuss' => 'nullable',
            'is_ads' => 'nullable',
            'published_date' => 'required',
            'category' => 'required',
            'slug_id' => 'nullable|max:120',
            'meta_desc' => 'nullable|max:255',
            'meta_title' => 'nullable|max:120',
            'title_gloss' => 'required|max:100',
            'letter' => 'required|max:1'
        ]);

        try {
            $glossary = DB::transaction(function () use ($validated, $request) {
                $title_gloss = $validated['title_gloss'];
                $letter = $validated['letter'];
                unset($validated['title_gloss'], $validated['letter']);
                $validated['banner'] = $request->hasFile('banner') ? $request->file('banner')->storePublicly('public/article', 'oss') : ''; //upload foto
                $validated['trx_date'] = now()->toDateTimeString();
                $validated['created_by'] = auth()->id();
                $validated['user_id'] = auth()->id();
                $article = Article::create($validated);
                $glossary = Glossary::create([
                    'article_id' => $article->id,
                    'title' => $title_gloss,
                    'letter' => $letter,
                ]);
                return $glossary;
            });
            return response()->json($glossary, 201);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getCategoryOfInsight()
    {
        $data = Type::distinct()->get()->unique('name');
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'detail' => 'required',
            'trx_code' => 'required',
            'banner' => 'nullable|image',
            'status' => 'required',
            'open_discuss' => 'nullable',
            'is_ads' => 'nullable',
            'published_date' => 'required',
            'category' => 'required',
            'slug_id' => 'nullable|max:120',
            'meta_desc' => 'nullable|max:255',
            'meta_title' => 'nullable|max:120',
            'title_gloss' => 'required|max:100',
            'letter' => 'required|max:1'
        ]);

        try {
            $glossary = DB::transaction(function () use ($validated, $request, $id) {
                $title_gloss = $validated['title_gloss'];
                $letter = $validated['letter'];
                unset($validated['title_gloss'], $validated['letter']);
                $glossary = Glossary::find($id);
                $article = Article::find($glossary->article_id);
                if ($request->hasFile('banner')) {
                    $validated['banner'] =  $request->file('banner')->storePublicly('public/article', 'oss');
                    Storage::disk('oss')->delete($article->banner);
                }
                $article->update($validated);
                $glossary->update([
                    'article_id' => $article->id,
                    'title' => $title_gloss,
                    'letter' => $letter,
                ]);
                return $glossary;
            });
            return response()->json($glossary, 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
