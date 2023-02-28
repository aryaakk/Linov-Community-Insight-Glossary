<?php

namespace App\Models\Threads;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TypeQuestion extends Model
{
    use HasFactory, Uuids;
    protected $table = 'hom_thread_type_questions';
    protected $guarded = [];

    public function scopeWithQuestion($query)
    {
        $query->leftJoin(DB::raw('(select id type_question_id, code, name, color, color_bg from hom_type_questions) questions'), 'hom_thread_type_questions.type_question_id', '=', 'questions.type_question_id');
    }
}
