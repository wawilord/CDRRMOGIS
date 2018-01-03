<?php
/* Convert hexdec color string to rgb(a) string */

    function hex2rgba($color, $opacity = false) {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if(empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
            if(abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
    }

    function getUserIP()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }


    //2016-05-01 14:38:00
    function converttoformaldatetimestring($txt)
    {
        $x = explode(' ', $txt);
        $ind = 'am';
        $date = explode('-', $x[0]);
        $time = explode(':', $x[1]);
        $year = $date[0];
        $month = $date[1];
        $day = $date[2];
        $hour = $time[0];
        $min = $time[1];

        switch ($month)
        {
            case '01':
                $month = 'Jan';
                break;
            case '02':
                $month = 'Feb';
                break;
            case '03':
                $month = 'Mar';
                break;
            case '04':
                $month = 'Apr';
                break;
            case '05':
                $month = 'May';
                break;
            case '06':
                $month = 'Jun';
                break;
            case '07':
                $month = 'Jul';
                break;
            case '08':
                $month = 'Aug';
                break;
            case '09':
                $month = 'Sep';
                break;
            case '10':
                $month = 'Oct';
                break;
            case '11':
                $month = 'Nov';
                break;
            case '12':
                $month = 'Dec';
                break;
        }

        if($hour > 12)
        {
            $ind = 'pm';
            $hour = $hour - 12;
        }
        else if($hour == 12)
        {
            $ind = 'pm';
        }
        if($hour == 0){
            $hour = 12;
        }
        return $month . ' ' . $day . ', ' . $year . ', ' . $hour . ':' . $min . $ind;
    }

    function gettimestring($txt)
{
    $x = explode(' ', $txt);
    $ind = 'am';
    $time = explode(':', $x[1]);
    $hour = $time[0];
    $min = $time[1];

    if($hour > 12)
    {
        $ind = 'pm';
        $hour = $hour - 12;
    }
    else if($hour == 12)
    {
        $ind = 'pm';
    }
    if($hour == 0){
        $hour = 12;
    }
    return $hour . ':' . $min . $ind;
}

    function getdatestring($txt)
{
    $x = explode(' ', $txt);
    $date = explode('-', $x[0]);
    $year = $date[0];
    $month = $date[1];
    $day = $date[2];

    switch ($month)
    {
        case '01':
            $month = 'Jan';
            break;
        case '02':
            $month = 'Feb';
            break;
        case '03':
            $month = 'Mar';
            break;
        case '04':
            $month = 'Apr';
            break;
        case '05':
            $month = 'May';
            break;
        case '06':
            $month = 'Jun';
            break;
        case '07':
            $month = 'Jul';
            break;
        case '08':
            $month = 'Aug';
            break;
        case '09':
            $month = 'Sep';
            break;
        case '10':
            $month = 'Oct';
            break;
        case '11':
            $month = 'Nov';
            break;
        case '12':
            $month = 'Dec';
            break;
    }

    return $month . ' ' . $day . ', ' . $year;
}

    function console_log($txt, $dir)
    {
        date_default_timezone_set('Asia/Hong_Kong');
        $time = time();
        $logfile = fopen($dir, 'a');
        fwrite($logfile, date("Y-m-d H:i:s", $time) . ": (" . getUserIP() . ")" . PHP_EOL . " \t\t" . $txt . PHP_EOL . PHP_EOL);
        fclose($logfile);
    }

    function PageNotAvailable(){
        echo '<html style="awidth: 100%; height: 100%; margin: 0px; padding: 0px;"> <head> <title>Page not Availble</title><link rel="icon" href="img/favicon.ico">
 </head> <body style="width: 100%; height: 100%; margin: 0px; padding: 0px; text-align: center; background-color: #454551; color: lightgray;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table-cell;"> 

            <h1><a href = "https://www.pornhub.com/" style = "font-size: 50px; color: lightgray; text-decoration:none;"> 403 Forbidden</a></h1> <h4>  u got access denied boi</h4> </div> </div> </body> </html>';
        exit;
    }

    function TwoDigitOf($digit){
        $digit = 0 + $digit;
        if($digit < 10)
        {
            return '0' . $digit;
        }
        else
        {
            return $digit;
        }
    }

?>
