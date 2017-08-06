<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/2/2017
 * Time: 2:42 PM
 */
class Custom_email{

    private $Obj;
    public function __construct(){
        $this->Obj =& get_instance();
    }

    public function send_email($from, $to, $subject, $message){
        $this->Obj->email->from($from);
        $this->Obj->email->to($to);
        $this->Obj->email->subject($subject);
        $this->Obj->email->message($message);
        $this->Obj->email->send();
        return true;
    }

}