<?php
class Registration_DeleteController extends Core_Controller_Front_Action
{
    const UPLOAD_DIR = 'upload/profile'.DS;

    /**
     *
     */
    public function indexAction()
    {
        $uploadDir = BP.DS.(self::UPLOAD_DIR);

        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
            $fileName = $_POST['name'];
            $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
            $filePath = $uploadDir. $fileName;
            if (file_exists($filePath))
            {
                unlink($filePath);
            }
            echo "Deleted File ".$fileName."<br>";
        }
    }

}//End of class