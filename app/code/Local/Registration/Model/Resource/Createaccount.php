<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Registration_Model_Resource_Createaccount extends Core_Model_Resource_Db_Abstract
{
    /**
     * Define main table
     *
     */
    protected function _construct()
    {
        $this->_init('login/user', 'user_id');
    }
    
    /**
     * Load data by specified username
     * @param $username
     * @return array
     * @throws Zend_Db_Adapter_Exception
     */
    public function loadByUsername($username)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                          ->from($this->getMainTable())
                          ->where('username=:username');

        $binds = array('username' => $username);

        return $adapter->fetchRow($select, $binds);
    }

    /**
     * @param $params
     * @return string
     * @throws Zend_Db_Adapter_Exception
     */
    public function recordLoginUser($params)
    {
        $adapter = $this->_getReadAdapter();
        $data = array(
            'username'             => $params['tuserName'],
            'password'             => $params['passwordHash'],
            'role_id'              => $params['roleId'],
            'user_activation_hash' => $params['activationHash'],
            'user_registration_ip' => $params['userIp'],
            'security_question_1'  => $params['ssec1'],
            'security_answer_1'    => trim($params['tsecAnswer1']),
            'security_question_2'  => $params['ssec2'],
            'security_answer_2'    => trim($params['tsecAnswer2'])
        );

        $adapter->insert($this->getMainTable(), $data);

        return $adapter->lastInsertId();
    }

    /**
     * @param $params
     * @return string
     * @throws Zend_Db_Adapter_Exception
     */
    public function recordLoginPassword($params)
    {
        $adapter = $this->_getReadAdapter();
        $data = array(
            'user_name'     => $params['tuserName'],
            'password_hash' => $params['passwordHash']
        );

        $adapter->insert($this->getTable('login/password'), $data);

        return $adapter->lastInsertId();
    }

    /**
     * @param $params
     * @return string
     * @throws Zend_Db_Adapter_Exception
     */
    public function recordUserProfile($params)
    {
        $adapter = $this->_getReadAdapter();
        $data = array(
            'user_id'         => $params['userId'],
            'title'           => trim($params['stitle']),
            'first_name'      => trim($params['tfName']),
            'middle_name'     => trim($params['tmI']),
            'last_name'       => trim($params['tlName']),
            'email'           => trim(strtolower($params['temail'])),
            'language'        => trim($params['tLanguageSpoken']),
            'contact_from_us' => trim($params['cfrm']),
            'contact_from_partner' => trim($params['cfrmPartners']),
            'ext'             => trim($params['text']),
            'address_1'       => trim($params['taddress']),
            'address_2'       => trim($params['tapt']),
            'city'            => trim($params['tcity']),
            'state'           => trim($params['sstate']),
            'zip'             => trim($params['tzip'])
        );

        //Business
        if( $params['raccount'] === 'business') {
            $data['office_no'] = trim($params['tphone']);
            $data['medical_type'] = trim($params['tMedicalType']);
            $data['specialties'] = trim($params['tSpecialties']);
            $data['practice_name'] = trim($params['tPracticeName']);

            $data['education'] = trim($params['tEducation']);

            $data['school_of_graduate'] = trim($params['tSchoolOfGraduate']);
            $data['insurance'] = trim($params['tInNetworkInsurances']);
            $data['hospital_affiliations'] = trim($params['tHospitalAffiliations']);
            $data['residency_program_1'] = trim($params['tResidency_1']);
            $data['residency_program_2'] = trim($params['tResidency_2']);
            $data['professional_statement'] = trim($params['tProfessionalStatement']);
        }
        //Patient
        else {
            $data['phone_no'] = trim($params['tphone']);
        }

        $adapter->insert($this->getTable('user/profile'), $data);

        return $adapter->lastInsertId();
    }

    /**
     * @return array
     * @throws Zend_Db_Adapter_Exception
     */
    public function getSecurityQuestions()
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                          ->from( 'security_question' )
                          ->where('is_active = 1')
                          ->order('sequence_id', 'ASC');

        return $adapter->fetchAll($select);
    }
    
}//End of class