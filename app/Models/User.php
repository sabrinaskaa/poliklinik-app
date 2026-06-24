<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'alamat',
        'no_ktp',
        'no_hp',
        'no_rm',
        'role',
        'id_poli',
        'email',
        'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if ($user->role === 'pasien' && empty($user->no_rm)) {
                $currentMonth = date('Ym');
                $lastPasien = self::where('role', 'pasien')
                    ->where('no_rm', 'like', $currentMonth . '-%')
                    ->orderBy('id', 'desc')
                    ->first();

                $sequence = 1;
                if ($lastPasien && preg_match('/-(\d+)$/', $lastPasien->no_rm, $matches)) {
                    $sequence = (int) $matches[1] + 1;
                }

                $user->no_rm = $currentMonth . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    public function jadwalPeriksa()
    {
        return $this->hasMany(JadwalPeriksa::class, 'id_dokter');
    }
}
