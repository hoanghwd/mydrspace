<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Notices
 *
 * @author THAO
 */
class Core_Page_Block_Html_Notices extends Core_Block_Template {
    
    /**
     * Check if noscript notice should be displayed
     *
     * @return boolean
     */
    public function displayNoscriptNotice()
    {
        return Virtual::getStoreConfig('web/browser_capabilities/javascript');
    }

    /**
     * Check if demo store notice should be displayed
     *
     * @return boolean
     */
    public function displayDemoNotice()
    {
        return Virtual::getStoreConfig('design/head/demonotice');        
    }

    /**
     * Get Link to cookie restriction privacy policy page
     *
     * @return string
     */
    public function getPrivacyPolicyLink()
    {
        return Virtual::getUrl('privacy-policy-cookie-restriction-mode');        
    }
    
}//End of class