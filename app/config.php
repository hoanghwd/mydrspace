<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 12/18/2018
 * Time: 8:49 PM
 */

define('DEFAULT_TIMEZONE', 'America/Los_Angeles');

date_default_timezone_set(DEFAULT_TIMEZONE);

define('APP_NAME', 'MyDrSpace');

define("PHONE_PATTERN","[0-9]{3}-[0-9]{3}-[0-9]{4}");

define("PASSWORD_PATTERN_HTML", '/(?=^.{8,}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&amp;*()_+}{&quot;:;\'?/&gt;.&lt;,])(?!.*\s).*$/');

define("PASSWORD_PATTERN_PHP", "/^(?=.*\d)(?=.*[A-Za-z])(?=.*[A-Z])(?=.*[a-z])(?=.*[ !#$%&'\(\) * +,-.\/[\\] ^ _`{|}~\"])[0-9A-Za-z !#$%&'\(\) * +,-.\/[\\] ^ _`{|}~\"]{8,50}$/");

define("USER_NAME_PATTERN", "/^[a-z0-9]{6,10}$/");

define("ZIP_CODE_PATTERN", "[0-9]{5}");

define("EMAIL_PATTERN_HTML", "/^(?:[\w\d]+\.?)+@(?:(?:[\w\d]\-?)+\.)+\w{2,4}$/i");

const IS_SECURE = true;

const IN_MILI_SEC = 1000;

const STATE_ARRAY = array(    
    "AL"=>'Alabama',
    "AK"=>'Alaska',
    "AS"=>'American Samoa',
    "AZ"=>'Arizona',
    "AR"=>'Arkansas',
    "CA"=>'California',
    "CO"=>'Colorado',
    "CT"=>'Connecticut',
    "DE"=>'Delaware',
    "DC"=>'District of Columbia',
    //"FM"=>'Federated States of Micronesia',
    "FL"=>'Florida',
    "GA"=>'Georgia',
    "GU"=>'Guam',
    "HI"=>'Hawaii',
    "ID"=>'Idaho',
    "IL"=>'Illinois',
    "IN"=>'Indiana',
    "IA"=>'Iowa',
    "KS"=>'Kansas',
    "KY"=>'Kentucky',
    "LA"=>'Louisiana',
    "ME"=>'Maine',
    "MH"=>'Marshall Islands',
    "MD"=>'Maryland',
    "MA"=>'Massachusetts',
    "MI"=>'Michigan',
    "MN"=>'Minnesota',
    "MS"=>'Mississippi',
    "MO"=>'Missouri',
    "MT"=>'Montana',
    "NE"=>'Nebraska',
    "NV"=>'Nevada',
    "NH"=>'New Hampshire',
    "NJ"=>'New Jersey',
    "NM"=>'New Mexico',
    "NY"=>'New York',
    "NC"=>'North Carolina',
    "ND"=>'North Dakota',
    "MP"=>'Northern Mariana Islands',
    "OH"=>'Ohio',
    "OK"=>'Oklahoma',
    "OR"=>'Oregon',
    "PW"=>'Palau',
    "PA"=>'Pennsylvania',
    "PR"=>'Puerto Rico',
    "RI"=>'Rhode Island',
    "SC"=>'South Carolina',
    "SD"=>'South Dakota',
    "TN"=>'Tennessee',
    "TX"=>'Texas',
    "UT"=>'Utah',
    "VT"=>'Vermont',
    "VI"=>'Virgin Islands',
    "VA"=>'Virginia',
    "WA"=>'Washington',
    "WV"=>'West Virginia',
    "WI"=>'Wisconsin',
    "WY"=>'Wyoming',
    //"AA"=>'Armed Forces Americas',
    //"AE"=>'Armed Forces Europe',
    //"AP"=>'Armed Forces Pacific'
);

const MEDICAL_TYPE = array(
    1  => 'Allergy and immunology',
    2  => 'Anesthesiology',
    3  => 'Dermatology',
    4  => 'Diagnostic radiology',
    5  => 'Emergency medicine',
    6  => 'Family medicine',
    7  => 'Internal medicine',
    8  => 'Medical genetics',
    9  => 'Neurology',
    10 => 'Nuclear medicine',
    11 => 'Obstetrics and gynecology',
    12 => 'Ophthalmology',
    13 => 'Pathology',
    14 => 'Pediatrics',
    15 => 'Physical medicine and rehabilitation',
    16 => 'Preventive medicine',
    17 => 'Psychiatry',
    18 => 'Radiation oncology',
    19 => 'Surgery',
    20 => 'Urology',
);