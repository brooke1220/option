<?php

namespace Brooke\Traits;

use think\App;
use think\Request;
use think\Model;

class Options extends Model
{
    public $model;

    protected $autoWriteTimestamp = false;

    public static function register(App $app, Request $request)
    {
        $app->bindTo('option', function (Model $model) use ($request) {
            return model(static::class)($model);
        });
    }

    public function pull()
    {
        return $this->where('index', $this->index())->select()->column('value', 'key');
    }

    public function exists($key)
    {
        return ! empty($this->get($key));
    }

    public function get($key, $default = null)
    {
        if ($option = $this->indexWhere('key', $key)->find()) {
            return $option->value;
        }

        return $default;
    }

    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            $this->updateOrCreate(['key' => $key, 'index' => $this->index()], ['value' => $value]);
        }

        return true;
    }

    public function remove($key)
    {
        return (bool) $this->indexWhere('key', $key)->delete();
    }

    public function indexWhere($field, $value)
    {
        return $this->where('index', $this->index())->where($field, $value);
    }

    public function index()
    {
        return strtolower($this->model->getName()) . '_' . $this->model->getAuthIdentifier();
    }

    public function __invoke(Model $model)
    {
        $this->model = $model;

        return $this;
    }
}
