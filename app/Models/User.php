<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Interact with the user's first name.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function type(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  ["client", "manager"][$value],
        );
    }

    // Дата создания в текстовом формате
    public function getCreatedStrAttribute() {
        return date('d.m.Y H:i',strtotime($this->created_at));
    }

    // Телефон в текстовом формате
    public function getPhoneStrAttribute() {
        return phoneMask($this->phone);
    }

    // Дата обновления чата в формате int
    public function getChatUpdatedAtIntAttribute() {
        return strtotime($this->chat_updated_at);
    }

    // Статистика заказчика
    public function stat()
    {
        return $this->hasMany(Stat::class, 'user_id')->orderBy('id','DESC');
    }

    // Технические документы
    public function tech_docs()
    {
        return $this->hasMany(TechDoc::class, 'client_id')->orderBy('id','DESC');
    }

    // Сметы
    public function estimate()
    {
        return $this->hasMany(Estimate::class, 'client_id')->orderBy('id','DESC');
    }

    // Комментарий к смете
    public function estimate_comment()
    {
        return $this->hasOne(EstimateComment::class, 'client_id');
    }

    // Сметы для мастера
    public function master_estimate()
    {
        return $this->hasMany(MasterEstimate::class, 'client_id')->orderBy('id','DESC');
    }

    // Комментарий к смете для мастера
    public function master_estimate_comment()
    {
        return $this->hasOne(MasterEstimateComment::class, 'client_id');
    }

    // Активные задачи
    public function active_tasks()
    {
        return $this->hasMany(Task::class, 'client_id')->whereNull('closed_at');
    }

    // Завершённые задачи
    public function closed_tasks()
    {
        return $this->hasMany(Task::class, 'client_id')->whereNotNull('closed_at')->orderBy('closed_at','DESC');
    }

    // Напоминать о задачах
    protected function taskRemember(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (empty($value)) ? [] :  json_decode($value,true),
            set: fn ($value) => (empty($value)) ? NULL : json_encode($value),
        );
    }

    // Напоминать о чате
    protected function chatRemember(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (empty($value)) ? [] :  json_decode($value,true),
            set: fn ($value) => (empty($value)) ? NULL : json_encode($value),
        );
    }

    // Это демо-объект или нет
    public function getDemoAttribute() {
        return $this->id === config('global.intDemoObject');
    }

    // Дата окончания гарантии в текстовом формате
    public function getWarrantyEndStrAttribute() {
        return empty($this->warranty_end) ? '' : date('d.m.Y',strtotime($this->warranty_end));
    }

}
