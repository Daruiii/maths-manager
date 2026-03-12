<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use HasFactory;

    protected $table = 'problems';

    protected $fillable = [
        'header', 'multiple_chapter_id', 'difficulty', 'time', 'name', 'statement', 'latex_statement', 'image_paths', 'correction_pdf',
         'type', 'year', 'academy', 'date_data'
    ];

    protected $casts = [
        'difficulty' => 'integer',
        'image_paths' => 'array',
    ];

    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'chapter_problem', 'problem_id', 'chapter_id');
    }

    public function ds()
    {
        return $this->belongsToMany(DS::class, 'ds_problem', 'problem_id', 'ds_id');
    }

    public function multipleChapter()
    {
        return $this->belongsTo(MultipleChapter::class, 'multiple_chapter_id');
    }
}
