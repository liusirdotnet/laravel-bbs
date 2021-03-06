<?php

if (! function_exists('route_class')) {
    /**
     * 将路由名称转换为页面 div class 名称。
     *
     * @return string
     */
    function route_class()
    {
        return str_replace('.', '-', Route::currentRouteName());
    }
}

if (! function_exists('make_excerpt')) {
    /**
     * 根据给定的内容生成摘要。
     *
     * @param string $text
     * @param int    $length
     *
     * @return string
     */
    function make_excerpt($text, $length = 200)
    {
        $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($text)));

        return str_limit($excerpt, $length);
    }
}

if (! function_exists('admin_menu')) {
    /**
     * 获取后台菜单。
     *
     * @param string      $name
     * @param string|null $type
     * @param array       $options
     *
     * @return mixed
     */
    function admin_menu($name, $type = null, array $options = [])
    {
        return \App\Support\Facades\Admin::getModel('Menu')->display(
            $name,
            $type,
            $options
        );
    }
}

if (! function_exists('get_reflection_method')) {
    function get_reflection_method($object, $method)
    {
        $reflectionMethod = new \ReflectionMethod($object, $method);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod;
    }
}

if (! function_exists('call_protected_method')) {
    function call_protected_method($object, $method, ...$args)
    {
        return get_reflection_method($object, $method)->invoke($object, ...$args);
    }
}

if (! function_exists('get_reflection_property')) {
    function get_reflection_property($object, $property)
    {
        $reflectionProperty = new \ReflectionProperty($object, $property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty;
    }
}

if (! function_exists('get_protected_property')) {
    function get_protected_property($object, $property)
    {
        return get_reflection_property($object, $property)->getValue($object);
    }
}
