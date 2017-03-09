<?php
    function getTemplates($type = 0, $userid = 'public'){
        global $db;
        $result = mysqli_query($db, "SELECT * FROM ko_templates WHERE type='$type' AND user='$userid'");
        $rows = array();
        while($row = mysqli_fetch_assoc($result)){
            array_push($rows, $row);
        }
        return $rows;
    }
    function logFixes($dateStamp, $userid = ''){
        global $db;
        $aFixes = getTemplates(-1, $userid);
        $sql = '';
        $values = '';
        foreach ($aFixes as $template) {
            $query = 'time, ';
            $vals = "'".$dateStamp."', ";
            foreach ($template as $key => $value) {
                if($key != 'id'){
                    $query .= $key;
                    $vals .= "'".$value."'";

                    if ($value !== end($template)){
                        $query .= ', ';
                        $vals .= ', ';
                    }
                }
            }
            $values .= '('.$vals.'),';
        }
        $values = rtrim($values, ',');
        $sql .= " INSERT INTO ko_logs ($query) VALUES $values";
        if (mysqli_query($db, $sql)) {
            throwLog('Added Fix costs');
            return getLogs($dateStamp);
        } else {
            throwLog('ERROR while adding Fix costs');
        }
    }
    function getLogs($dateStamp){
        global $db;
        $_cook = new cookie;
        $userid = $_cook->get('konomi_user_password');
        $result = mysqli_query($db, "SELECT * FROM ko_logs WHERE time='$dateStamp' AND user='$userid' ORDER BY timestamp DESC ");
        $rows = array();
        while($row = mysqli_fetch_assoc($result)){
            array_push($rows, $row);
        }
        if(count($rows) == 0){
            return logFixes($dateStamp, $userid);
        }
        else {
            $logs = array(
                "logs" => $rows,
                "balance" => getBalance($rows)
            );
            return $logs;
        }
    }
    function loggedUser(){
        $_cook = new cookie;
        return $_cook->get('konomi_user_password');
    }
    function throwLog($message = ''){
        echo '<script>console.log("'.$message.'")</script>';
    }
    function getBalance($aRows){
        $balance = 0;
        foreach ($aRows as $row) {
            if(intval($row['type']) < 1){
                $balance = $balance - $row['sum'];
            }else {
                $balance = $balance + $row['sum'];
            }
        }
        return $balance;
    }
    function price($price = 0.00){
      $price = str_replace(',', '.', $price);
      $price = number_format($price,2);
      $price = str_replace(',', '', $price);
      return $price;
    };
    function lang($sKey = 'none'){
        global $lang;
        echo $lang[$sKey];
    }
    function getPassword($user = ''){
        global $db;
        $result = mysqli_query($db, "SELECT pass FROM ko_access WHERE id='$user'");
        $rows = array();
        while($row = mysqli_fetch_assoc($result)){
            array_push($rows, $row);
        }
        if(count($rows) > 0){
            return $rows[0]['pass'];
        }
        else {
            return false;
        }
    };
    function isPassword($user = '', $pass = ''){
        $getPass = getPassword($user);
        if(gettype($getPass) == 'string'){
            return $getPass == $pass;
        }
        else {
            return $getPass;
        }
    }
    function userId($username = ''){
        global $db;
        $result = mysqli_query($db, "SELECT id FROM ko_access WHERE user='$username'");
        $rows = array();
        while($row = mysqli_fetch_assoc($result)){
            array_push($rows, $row);
        }
        if(count($rows) > 0){
            return $rows[0]['id'];
        }
        else {
            return false;
        }
    }
    class cookie {
        public $prefix = 'ko_cookie_saved-';
        public function set($name = '',$cookie_value = ''){
            setcookie($this->prefix.$name, $cookie_value, time() + (86400 * 30));
        }
        public function get($name = ''){
            $getCookie = false;
            $cookie_name = $this->prefix.$name;
            if(isset($_COOKIE[$cookie_name])) {
                $getCookie = $_COOKIE[$cookie_name];
            }
            return $getCookie;
        }
        public function delete($name = ''){
            setcookie($this->prefix.$name, "",  time() - 3600);
        }
    }
    $cookie = new cookie;

    class pass {
        private $_cook;
        public $oCookie = array(
            'user' => 'konomi_user_password',
            'pass' => 'konomi_pass_password'
        );
        public function set($cookie, $value){
            $this->_cook = new cookie;
            $this->_cook->set($this->oCookie[$cookie], $value);
        }
        public function loggedIn(){
            $this->_cook = new cookie;
            return isPassword($this->_cook->get($this->oCookie['user']),$this->_cook->get($this->oCookie['pass']));
        }
        public function delete(){
            $this->_cook = new cookie;
            $this->_cook->delete($this->oCookie['user']);
            $this->_cook->delete($this->oCookie['pass']);
        }
    };
    $pass = new pass();

    function getMonths(){
        global $db;
        $result = mysqli_query($db, "SELECT time FROM ko_logs ORDER BY timestamp DESC ");
        $years = array();
        while($row = mysqli_fetch_assoc($result)){
            $aMonth = explode("-",$row['time']);
            $year = $aMonth[0];
            $month = $aMonth[1];
            if (!array_key_exists($year, $years)) {
                $years[$year]=array();
            }
            if (!in_array($month, $years[$year])) {
                array_push($years[$year], $month);
            }
        }
        return $years;
    }
?>