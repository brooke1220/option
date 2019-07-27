<?php

namespace Brooke\Traits;

trait Options
{
    public function option($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('option', [$this]);
        }

        if (is_array($key)) {
            return app('option', [$this])->set($key);
        }

        return app('option', [$this])->get($key, $default);
    }

    public function option_exists($key)
    {
        return app('option', [$this])->exists($key);
    }

    public function option_pull()
    {
        return app('option', [$this])->pull();
    }

    public function option_remove($key)
    {
        return app('option', [$this])->remove($key);
    }

    public function forceLoad()
    {
        $option = app('option', [$this], true);

        return $this;
    }

    public function __get($name)
    {
        if($this->option_exists($name)){
            return $this->option($name);
        }

        return parent::__get($name);
    }
}
