<?php

namespace SmsService\Models;

use Exception;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsProvider extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'method',
        'type',
        'params',
        'usable',
        'count',
        'fail_count',
        'total_fail_count',
        'short_deactivation',
        'inactive_level',
        'inactive_until',
        'last_use_at',
        'last_fail_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'params' => AsArrayObject::class,
        'usable' => 'boolean',
        'inactive_until' => 'datetime',
        'last_use_at' => 'datetime',
        'last_fail_at' => 'datetime',
    ];

    /**
     * @return void
     */
    public function deactivate(): void
    {
        $this->fail_count = $this->fail_count + 1;
        $this->total_fail_count = $this->total_fail_count + 1;

        $anotherSmsPlan = SmsProvider::query()->where('usable', true)
            ->where('id', '!=', $this->id)
            ->whereNull('inactive_until')
            ->exists();

        if ($anotherSmsPlan) {
            if ($this->fail_count >= config('sms.deactivation.fifth_count') && $this->inactive_level === 4 && now()->diffInMinutes($this->last_fail_at) < config('sms.deactivation.fifthTime')) {
                $inactiveStep = 'Fifth';
                $deactivationTime = $this->short_deactivation ? config('sms.deactivation.third_time') : config('sms.deactivation.fifthTime');
                $this->inactive_until = now()->addMinutes($deactivationTime);
            } elseif ($this->fail_count >= config('sms.deactivation.fourth_count') && $this->inactive_level === 3 && now()->diffInMinutes($this->last_fail_at) < config('sms.deactivation.fourthTime')) {
                $inactiveStep = 'Fourth';
                $deactivationTime = $this->short_deactivation ? config('sms.deactivation.third_time') : config('sms.deactivation.fourthTime');
                $this->inactive_until = now()->addMinutes($deactivationTime);
                $this->inactive_level = 4;
            } elseif ($this->fail_count >= config('sms.deactivation.third_count') && $this->inactive_level === 2 && now()->diffInMinutes($this->last_fail_at) < config('sms.deactivation.third_time')) {
                $inactiveStep = 'Third';
                $this->inactive_until = now()->addMinutes(config('sms.deactivation.third_time'));
                $this->inactive_level = 3;
            } elseif ($this->fail_count >= config('sms.deactivation.second_count') && $this->inactive_level === 1 && now()->diffInMinutes($this->last_fail_at) < config('sms.deactivation.second_time')) {
                $inactiveStep = 'Second';
                $this->inactive_until = now()->addMinutes(config('sms.deactivation.second_time'));
                $this->inactive_level = 2;
            } elseif ($this->fail_count >= config('sms.deactivation.first_count') && $this->inactive_level === 0 && now()->diffInMinutes($this->last_fail_at) < config('sms.deactivation.firstTime')) {
                $inactiveStep = 'First';
                $this->inactive_until = now()->addMinutes(config('sms.deactivation.first_time'));
                $this->inactive_level = 1;
            }
        }
        $this->last_fail_at = now();
        $this->save();
    }

    /**
     * Select Active Provider.
     *
     * @param $planId
     * @throws Exception
     */
    public static function getActiveProvider($providerId = null,$type = '*')
    {
        if ($providerId) {
            $provider = self::query()->find($providerId);

            if (!$provider) {
                throw new Exception(__('sms.plan_selection_fail'));
            }
        } else{
            $provider = self::query()->where('usable', true)
                ->where('type', $type)
                ->where(function ($query) {
                    $query->whereNull('inactive_until');
                    $query->orWhere('inactive_until', '<', now());
                })
                ->orderBy('last_use_at')
                ->first();
        }

        $provider->count = $provider->count + 1;
        $provider->last_use_at = now();
        $provider->save();
        return $provider;
    }

}
