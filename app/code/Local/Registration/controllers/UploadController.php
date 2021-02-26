<?php
class Registration_UploadController extends Core_Controller_Front_Action
{
    const UPLOAD_DIR = 'var/tmp'.DS;

    const ALLOW_EXT_ARRAY = array('gif', 'png', 'jpg');

    /**
     *
     */
    public function indexAction()
    {
        $uploadDir = BP.DS.(self::UPLOAD_DIR);
        $error = $_FILES["myfile"]["error"];

        //single file
        if(!is_array($_FILES["myfile"]["name"]))
        {
            $fileName = strtolower($_FILES["myfile"]["name"]);
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            if( in_array($ext, self::ALLOW_EXT_ARRAY) ) {
                move_uploaded_file($_FILES["myfile"]["tmp_name"],$uploadDir.$fileName);
                $ret[]= $fileName;
            }
        }
        //Multiple files, file[]
        else
        {
            $fileCount = count($_FILES["myfile"]["name"]);
            for($i=0; $i < $fileCount; $i++)
            {
                $fileName = strtolower($_FILES["myfile"]["name"][$i]);
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                if( in_array($ext, self::ALLOW_EXT_ARRAY) ) {
                    move_uploaded_file($_FILES["myfile"]["tmp_name"],$uploadDir.$fileName);
                    $ret[]= $fileName;
                }
                $ret[]= $fileName;
            }

        }

        echo json_encode($ret);
    }


}//End of class