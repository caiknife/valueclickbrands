<?php
/**
 * smcninternal.Sys_User table
 * @author Yaron Jiang
 * @version $id
 */
namespace BackEnd\Model\Users;

class User
{
    public $UserID;
    public $Name;
    public $Mail;
    public $Remark;
    public $LastChangeDate;
    public $AddDate;
    public $Password;
    public $DahongbaoRole;
    
    
    function exchangeArray(Array $data){
        $this->UserID = isset($data['UserID']) ? $data['UserID'] : '';
        $this->Name = isset($data['Name']) ? $data['Name'] : '';
        $this->Mail = isset($data['Mail']) ? $data['Mail'] : '';
        $this->Remark = isset($data['Remark']) ? $data['Remark'] : '';
        $this->LastChangeDate = isset($data['LastChangeDate']) ? $data['LastChangeDate'] : '';
        $this->AddDate = isset($data['AddDate']) ? $data['AddDate'] : '';
        $this->Password = isset($data['Password']) ? $data['Password'] : '';
        $this->DahongbaoRole = isset($data['DahongbaoRole']) ? $data['DahongbaoRole'] : '';
    }
    
    function getArrayCopy(){
        return get_object_vars($this);
    }
}