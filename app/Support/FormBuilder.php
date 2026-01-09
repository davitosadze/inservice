<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class FormBuilder
{
    protected static $model = null;

    public static function model($model, $options = [])
    {
        static::$model = $model;
        return static::open($options);
    }

    public static function open($options = [])
    {
        $method = strtoupper($options['method'] ?? 'POST');
        $action = $options['route'] ?? ($options['url'] ?? '');

        if (is_array($action)) {
            $action = route($action[0], array_slice($action, 1));
        }

        $id = $options['id'] ?? '';
        $enctype = isset($options['files']) && $options['files'] ? ' enctype="multipart/form-data"' : '';

        $html = '<form method="' . ($method === 'GET' ? 'GET' : 'POST') . '" action="' . e($action) . '"' . $enctype;
        if ($id) {
            $html .= ' id="' . e($id) . '"';
        }
        $html .= '>';
        $html .= csrf_field();

        if (!in_array($method, ['GET', 'POST'])) {
            $html .= method_field($method);
        }

        return new HtmlString($html);
    }

    public static function close()
    {
        static::$model = null;
        return new HtmlString('</form>');
    }

    public static function text($name, $value = null, $options = [])
    {
        $value = $value ?? static::getModelValue($name);
        return static::input('text', $name, $value, $options);
    }

    public static function textarea($name, $value = null, $options = [])
    {
        $value = $value ?? static::getModelValue($name);
        $options = static::buildAttributes($options);
        return new HtmlString('<textarea name="' . e($name) . '"' . $options . '>' . e($value ?? '') . '</textarea>');
    }

    public static function select($name, $list = [], $selected = null, $options = [])
    {
        $selected = $selected ?? static::getModelValue($name);
        $options = static::buildAttributes($options);

        $html = '<select name="' . e($name) . '"' . $options . '>';
        foreach ($list as $key => $val) {
            $isSelected = (string)$key === (string)$selected ? ' selected' : '';
            $html .= '<option value="' . e($key) . '"' . $isSelected . '>' . e($val) . '</option>';
        }
        $html .= '</select>';

        return new HtmlString($html);
    }

    public static function checkbox($name, $value = 1, $checked = null, $options = [])
    {
        $options = static::buildAttributes($options);
        $checkedAttr = $checked ? ' checked' : '';
        return new HtmlString('<input type="checkbox" name="' . e($name) . '" value="' . e($value) . '"' . $checkedAttr . $options . '>');
    }

    public static function submit($value = null, $options = [])
    {
        $options = static::buildAttributes($options);
        return new HtmlString('<input type="submit" value="' . e($value) . '"' . $options . '>');
    }

    public static function date($name, $value = null, $options = [])
    {
        $value = $value ?? static::getModelValue($name);
        if ($value instanceof \DateTime || $value instanceof \Carbon\Carbon) {
            $value = $value->format('Y-m-d');
        }
        return static::input('date', $name, $value, $options);
    }

    public static function datetimeLocal($name, $value = null, $options = [])
    {
        $value = $value ?? static::getModelValue($name);
        if ($value instanceof \DateTime || $value instanceof \Carbon\Carbon) {
            $value = $value->format('Y-m-d\TH:i');
        }
        return static::input('datetime-local', $name, $value, $options);
    }

    public static function input($type, $name, $value = null, $options = [])
    {
        $options = static::buildAttributes($options);
        return new HtmlString('<input type="' . e($type) . '" name="' . e($name) . '" value="' . e($value ?? '') . '"' . $options . '>');
    }

    protected static function getModelValue($name)
    {
        if (static::$model && isset(static::$model->{$name})) {
            return static::$model->{$name};
        }
        return null;
    }

    protected static function buildAttributes($options)
    {
        $html = '';
        foreach ($options as $key => $value) {
            if (is_numeric($key)) {
                // Handle cases like ['readonly' => true] vs ['readonly']
                $html .= ' ' . e($value);
            } elseif ($value === true) {
                $html .= ' ' . e($key);
            } elseif ($value !== false && $value !== null) {
                $html .= ' ' . e($key) . '="' . e($value) . '"';
            }
        }
        return $html;
    }
}
