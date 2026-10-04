<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'nik', 'participant_number', 'phone', 'gender', 'birth_place', 'birth_date', 'address', 'desa', 'kecamatan', 'no_meja', 'institution', 'latest_education', 'profile_photo_path', 'wave_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'birth_date' => 'date',
        ];
    }

    /**
     * Backward compatibility for institution attribute
     */
    public function getInstitutionAttribute()
    {
        return $this->attributes['desa'] ?? null;
    }

    public function setInstitutionAttribute($value)
    {
        $this->attributes['desa'] = $value;
    }

    /**
     * Get the user's profile photo URL.
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? route('storage.file', $this->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=1d4ed8&background=eff6ff';
    }

    public function assignedExams()
    {
        return $this->belongsToMany(Exam::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class);
    }

    public function examSessions()
    {
        return $this->hasMany(ExamSession::class);
    }

    public function wave()
    {
        return $this->belongsTo(Wave::class);
    }
}
