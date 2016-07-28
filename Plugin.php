<?php namespace Crm\Userbackend;

use System\Classes\PluginBase;
use RainLab\User\Models\User;
use Backend\Models\User as BackendUser;
use Hash;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        User::extend(function($model){
            $model->bindEvent('model.beforeSave', function() use ($model) {
                $backendUser = BackendUser::where('email', $model->email)->first();
                if (!$backendUser) {
                    $backendUser = new BackendUser();
                    if ($model->name) {
                        $backendUser->first_name = $model->name;
                    }

                    if ($model->surname) {
                        $backendUser->last_name = $model->surname;
                    }

                    $backendUser->email = $model->email;
                    $backendUser->login = $model->email;
                    $backendUser->password = post('User[password]');
                    $backendUser->password_confirmation = post('User[password]');
                    $backendUser->save();
                }
            });
        });
    }
}
