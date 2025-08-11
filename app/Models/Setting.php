<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'value' => 'string'
    ];

    // Constants for setting types
    const TYPE_STRING = 'string';
    const TYPE_NUMBER = 'number';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_JSON = 'json';
    const TYPE_FILE = 'file';

    // Constants for setting groups
    const GROUP_GENERAL = 'general';
    const GROUP_EMAIL = 'email';
    const GROUP_PAYMENT = 'payment';
    const GROUP_SECURITY = 'security';
    const GROUP_BACKUP = 'backup';

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $cacheKey = 'setting_' . $key;

        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $type = self::TYPE_STRING, $group = self::GROUP_GENERAL)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => self::prepareValue($value, $type),
                'type' => $type,
                'group' => $group
            ]
        );

        // Clear cache
        Cache::forget('setting_' . $key);
        Cache::forget('settings_' . $group);

        return $setting;
    }

    /**
     * Get all settings for a group
     */
    public static function getGroup($group)
    {
        $cacheKey = 'settings_' . $group;

        return Cache::remember($cacheKey, 3600, function () use ($group) {
            $settings = self::where('group', $group)->get();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = self::castValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    /**
     * Get all settings
     */
    public static function getAll()
    {
        return Cache::remember('all_settings', 3600, function () {
            $settings = self::all();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = self::castValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::flush();
    }

    /**
     * Cast value to appropriate type
     */
    protected static function castValue($value, $type)
    {
        switch ($type) {
            case self::TYPE_BOOLEAN:
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case self::TYPE_NUMBER:
                return is_numeric($value) ? (float) $value : $value;
            case self::TYPE_JSON:
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Prepare value for storage
     */
    protected static function prepareValue($value, $type)
    {
        switch ($type) {
            case self::TYPE_BOOLEAN:
                return $value ? '1' : '0';
            case self::TYPE_JSON:
                return json_encode($value);
            default:
                return (string) $value;
        }
    }

    /**
     * Boot method to handle cache clearing
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            Cache::forget('setting_' . $setting->key);
            Cache::forget('settings_' . $setting->group);
            Cache::forget('all_settings');
        });

        static::deleted(function ($setting) {
            Cache::forget('setting_' . $setting->key);
            Cache::forget('settings_' . $setting->group);
            Cache::forget('all_settings');
        });
    }
}
