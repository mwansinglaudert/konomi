<?php

namespace KonomiBundle\Utility;

class CookieUtility {

    /**
     * Cookie name prefix
     *
     * @var string
     */
    public $prefix = 'ko_cookie_saved-';





    /**
     * Set cookie
     *
     * @param string $name
     * @param string $cookie_value
     * @return void
     */
    public function set($name = '',$cookie_value = ''){
        setcookie($this->prefix.$name, $cookie_value, time() + (86400 * 30));
    }


    /**
     * Get cookie
     *
     * @param string $name
     * @return bool
     */
    public function get($name = ''){
        $getCookie = false;
        $cookie_name = $this->prefix.$name;
        if(isset($_COOKIE[$cookie_name])) {
            $getCookie = $_COOKIE[$cookie_name];
        }
        return $getCookie;
    }


    /**
     * Delete cookie
     *
     * @param string $name
     */
    public function delete($name = ''){
        setcookie($this->prefix.$name, "",  time() - 3600);
    }
}