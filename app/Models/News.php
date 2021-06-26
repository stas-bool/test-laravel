<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class News
 * @property int $id
 * @property string $title
 * @property string $body
 * @property DateTime $updated_at
 * @property string $status
 *
 * @package App\Models
 */
class News extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function search(string $query, $select = ['*'])
    {
        $query = "%$query%";
        return self::select($select)->where('title', 'like', $query)
            ->orWhere('body', 'like', $query)->get();
    }
}
