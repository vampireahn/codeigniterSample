<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_RouterBack2 extends CI_Router {

    protected function _parse_routes()
    {
        if (file_exists(APPPATH.'config/routes.php'))
        {
            include(APPPATH.'config/routes.php');
        }
        $http_method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';

        if (isset($route[$http_method]) && is_array($route[$http_method])) {
            $uri = implode('/', $this->uri->segments);
            foreach ($route[$http_method] as $key => $val) {
                if ($this->_route_match($key, $uri, $matches)) {
                    return $this->_set_request(explode('/', preg_replace('#^/#', '', $this->_replace_route($val, $matches))));
                }
            }
        }
        return parent::_parse_routes();
    }

    protected function _route_match($route, $uri, &$matches)
    {
        $matches = array();
        $route = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $route);
        $route = '#^' . $route . '$#';
        return (bool) preg_match($route, $uri, $matches);
    }

    protected function _replace_route($val, $matches)
    {
        if (strpos($val, '$') !== FALSE && !empty($matches)) {
            for ($i = 1; $i < count($matches); $i++) {
                $val = str_replace('$' . $i, $matches[$i], $val);
            }
        }
        return $val;
    }
}
