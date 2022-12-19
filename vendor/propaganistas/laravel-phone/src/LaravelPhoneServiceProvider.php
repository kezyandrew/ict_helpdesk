<?php namespace Propaganistas\LaravelPhone;

use Illuminate\Support\ServiceProvider;
use libphonenumber\PhoneNumberUtil;
use ReflectionClass;

class LaravelPhoneServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $extend = static::canUseDependentValidation() ? 'extendDependent' : 'extend';

        $this->app['validator']->{$extend}('phone', 'Propaganistas\LaravelPhone\PhoneValidator@validatePhone');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('libphonenumber', function ($app) {
            return PhoneNumberUtil::getInstance();
        });

        $this->app->alias('libphonenumber', 'libphonenumber\PhoneNumberUtil');
    }

    /**
     * Determine whether we can register a dependent validator.
     *
     * @return bool
     */
    public static function canUseDependentValidation()
    {
        $validator = new ReflectionClass('\Illuminate\Validation\Factory');

        return $validator->hasMethod('extendDependent');
    }
}
