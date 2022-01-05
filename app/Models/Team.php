<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'teams';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'provinsi_id',
        'kota_id',
        'file_id',
        'nama',
        'tahun',
        'alamat',
    ];

    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function provinsi()
    {
        return $this->hasOne(Province::class,'id' , 'provinsi_id');
    }

    public function kota()
    {
        return $this->hasOne(Regency::class, 'id' , 'kota_id');
    }
}
