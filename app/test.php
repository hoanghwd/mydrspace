<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/27/2019
 * Time: 4:05 PM
 */


//echo Virtual::getConfig()->getOptions()->getDesignDir();
//echo Virtual::getBaseDir();
//$configXml = Virtual::getConfig()->getNode()->global->resources->connection;
//Virtual::dump($configXml);
//Virtual::dump(Virtual::getConfig()->getNode()->default->web);
//Virtual::dump(Virtual::getBaseUrl('skin'));
//Virtual::dump(Virtual::getBaseDir('skin').DS );
//Virtual::dump(Virtual::getStoreConfig('web/default/front'));
//Virtual::dump(Virtual::getConfig()->getNode());

#$configXml = Virtual::getConfig()->getNode()->config->vbox;
#Virtual::dump($configXml);

/**
 * @var Core_Helper_Data
 */
//echo Virtual::helper('core')->jsonEncode(array('param'=>'test'));

//Virtual::dump(Virtual::getDesign()->getDefaultTheme());  //->getSkinUrl('favicon.ico'));
//$test = new Login_Block_Form_Login();
//$test = new Login_Model_Authenticate();
//$test2 = new Login_AuthenticateController();

/**
 * @var Login_Model_User $login
 */
///*
//$encrypt = new Core_Model_Encryption();
//echo $encrypt->generatePassword('Xuongrong1976!');
//*/
#Login Demo
/*
demo/demo
#2a97516c354b68848cdbd8f54a226a0a55b21ed138e207ad6c5cbb9c00aa5aea
*/

//echo $encrypt->encrypt('0:2:49d73518c80e532f:fcYMXYLCyc6qsHfqXZrD/w==');
//echo '<br/>';
//0:2:49d73518c80e532f:fcYMXYLCyc6qsHfqXZrD/w==
//echo $encrypt->hash('0:2:49d73518c80e532f:fcYMXYLCyc6qsHfqXZrD/w==');


//5e828c36f4058a8bc5035d83bf9a347666a4ec0b4315eb6fad251c6f7fbac34d


//$login = Virtual::getModel('login/user');
//
/**
 * @var Login_Model_Resource_User_Collection $users
 */
//$users = $login->getCollection();
//Virtual::dump($users);

//foreach($users AS $user) {
    //Virtual::dump($user->getFirstname());
    //Virtual::dump($user);
//}


/*
$login->login('hdo', 'xuongrong1976');
if($login->isSuccess()) {
    echo "Success";
}
else {
    echo $login->getErrorMessage();
}
*/
//ECHO Virtual::helper('core')->getEncryptor();

//$helper = Virtual::helper('core');
//$helper->test();
//$helper->validateHash('1','1');
//echo Virtual::getImgUrl();
//$this->getConfig(self::XML_PATH_UNSECURE_BASE_URL).DS;
#Virtual::dump(Virtual::getConfig()->getNode()->default->vbox->asArray()['data']);

//$data = Virtual::getConfig()->getNode();
//Virtual::dump($data);

//Virtual::getConfig()->getNode($fullPath);
#$path = (Core_Model_Virtual::XML_ROOT.DS).Core_Model_Virtual::XML_PATH_COOKIE_LIFE_TIME;
#echo $path;
#Virtual::dump(Virtual::getConfig()->getNode($path)->asArray());

//$pass = new Core_Model_Encryption();
//echo $pass->encrypt('xuongrong1976');
//echo $pass->generatePassword('xuongrong1976');


//Virtual::dump(Virtual::getConfig()->getNode()->global->models->asArray());

//session_destroy();
//Virtual::dump($_SESSION);

//$profile = Virtual::getModel('profile/user')->getFullProfile(317);
//Virtual::dump($profile);

//$username = 'hdo';
//$profile = Virtual::getModel('login/user')->loadByUsername($username);
//Virtual::dump($profile->getId());
//echo Virtual::getStoreConfig('web/login/failed_login');

//echo Virtual::app()->getStore()->getConfig('web/captcha/enable');

//$credentials = Virtual::getConfig()->getNode()->default->web->captcha->asArray();
//Virtual::dump($credentials);

//$credentials = Virtual::getConfig()->getNode()->default->mail->asArray();
//Virtual::dump($credentials);

/**
 * @var Core_Mail_Model_Send $test
 */
/*
$test = Virtual::getModel('mail/send');
$test->sendMail('hdohwd@gmail.com', "test", "Hello");
if( $test->isSuccess() ) {
    echo "MAIL SENT";
}
*/

$userName = 'hdo';
/**
 * @var Login_Model_Password $passwordModel
 */
/*
$passwordModel = Virtual::getModel('login/password');
$passwordModel->init($userName)
              ->resetPassword();
*/


//$test = Virtual::getModel('g2f/authentication');
//$secret = $test->generateRandomSecret();
//echo $test->getQR("www.huynhdo.us", $secret);

//$test = Virtual::getModel('login/user');
//$test->recordFailedG2f('hdo');

//ECHO Virtual::app()->getStore()->getConfig('web/login/failed_g2f_max');

//$passwordModel = Virtual::getModel('login/password');
//echo $passwordModel->_getAbsEmailTemplate();

/*
$phrase  = "You should eat fruits, vegetables, and fiber every day.";
$healthy = array("fruits", "vegetables", "fiber");
$yummy   = array("pizza", "beer", "ice cream");

$newphrase = str_replace($healthy, $yummy, $phrase);
echo $newphrase;
*/

/*
echo now();
$date1 = strtotime("2020-12-25 22:44:53");
$date2 = strtotime(now());
$diff = abs($date2 - $date1);
echo "Difference between two dates: " . floor($diff/60);
*/

//echo Virtual::getStoreConfig('web/password/min_lenght');
/*
$password = 'Xuongrong1976%';
//$pattern="/^(?=.*\d)(?=.*[A-Za-z])(?=.*[A-Z])(?=.*[a-z])(?=.*[ !#$%&'\(\) * +,-.\/[\\] ^ _`{|}~\"])[0-9A-Za-z !#$%&'\(\) * +,-.\/[\\] ^ _`{|}~\"]{8,50}$/";
if(preg_match(PASSWORD_PATTERN_PHP, $password)){
    echo "Password strength is OK";
} else {
    echo "Password is not strong enough";
}
*/

//$help = Virtual::helper('core');
//echo $help->jsonEncode(array('test'=> 'hello'));

/*
$password = 'Xuongrong1234!';
function check_password_sequence($password, $max) {
    $j = 0;

    for($i = 0; $i < strlen($password); $i++) {
        if(isset($password[$i+1]) && ord($password[$i]) + 1 === ord($password[$i+1])) {
            $j++;
        } else {
            $j = 0;
        }

        if($j === $max) {
            return true;
        }
    }

    return false;
}
echo "found=".(check_password_sequence($password, 2));
*/
//$password = 'Xuongrong1234!';
//$test = Virtual::getModel('login/password')->_checkPasswordSequence($password, 3);
//var_dump($test);

//$hash = '054408efb9d6a9a4a2405cb5c8ba1e67cbf087b5c3e55d39b600689e23e72c00';
//$test = Virtual::getModel('login/password')->getPasswordHashByHash($hash);
//Virtual::dump($test);

/*
$email = 'hdohwd@hotmail.com';
$test = Virtual::helper('core/string')->maskEmail($email);
echo $test;
*/

//$test = Virtual::getModel('password/evaluate');
//echo $test->test();

//$test = Virtual::getModel('user/profile');
//echo "Id=".$test->isEmailExist('hdohwd@gmail.com');

/**
 * @var Login_Model_Email $test
 */
//$test = Virtual::getModel('login/email');
//$test->init('hdohwd@gmail.com')->sendEmailReminder();
/*
Virtual::dump($_SERVER);
function getUserIP() {
    return gethostbyname($_SERVER['SERVER_NAME']);
}

echo getUserIP();
*/
//echo Virtual::helper('core/http')->getRemoteAddr();

//ECHO (Virtual::getBaseUrl()).DS.'registration'.DS.'confirm';

/*
$username = 'hdo';
$test = Virtual::getModel('registration/createaccount');
if($test->isUserNameExist($username)) {
    echo "exist";
}
*/

//$test = Virtual::helper('core');
//echo $test->getRandomString(3,'1234567890');

/**
 * @var Registration_Model_Createaccount $test
 */
//$test = Virtual::getModel('registration/createaccount');
//echo $test->getUsernameSuggestList('hdo');
//Virtual::dump($test->getSecurityQuestions());

/**
 * @var Registration_Model_Emailverify $test
 */
//$test = Virtual::getModel('registration/emailverify');
//$id = $test->createNewReservation('hdohwd@hotmail.com');
//echo "ID=".$id;
//echo $test->getMyTable();