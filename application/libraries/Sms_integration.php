<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/14/2017
 * Time: 1:20 AM
 */

require_once realpath('.').'/system/CLX_sms_integration/vendor/autoload.php';

class Sms_integration{

    private $Obj;
    public $servicePlanId                   = '';
    public $token                           = '';
    public $senderNum                       = '';
    public $deliverySmsCode                 = 'DS';
    public $typeSms                         = 'sms';

    public function __construct(){
        $this->Obj              =& get_instance();
        $this->servicePlanId    = 'mycon22';
        $this->token            = 'e308e13792394aee9521ee9867b8350c';
        $this->senderNum        = '923074203020';
    }

    public function sendSms($RecipientsArray, $msgBody){
        $client = new Clx\Xms\Client($this->servicePlanId, $this->token);
        try {
            $batchParams    = new Clx\Xms\Api\MtBatchTextSmsCreate();
            $batchParams->setSender($this->senderNum);
            //$batchParams->setRecipients(['+923224209199', '+923224783530', '+923074203020']);
            //$batchParams->setBody('Asalam-u-alikum, qasim');
            $batchParams->setRecipients($RecipientsArray);
            $batchParams->setBody($msgBody);

            $batch          = $client->createTextBatch($batchParams);
            return $batch;
            echo('The batch was given ID ' . $batch->getBatchId() . "\n");
        } catch (Exception $ex) {
            echo('Error creating batch: ' . $ex->getMessage() . "\n"); exit;
        }
        return true;
    }

    public function getSmsTemplate($code, $type ){
        $this->Obj->db->select('*');
        $this->Obj->db->from($this->notificationTable.' as NT');
        $this->Obj->db->join($this->notificationCategoryTable.' as NCT', 'NT.notify_cat_id = NCT.	id');
        $this->Obj->db->where('NCT.code', $code);
        $this->Obj->db->where('NT.type', $type);
        $this->Obj->db->where('NT.status', '1');
        $response       = $this->Obj->db->get();
        if($response->num_rows() > 0){
            $returnData             = $response->result();
        }else{
            $returnData             = false;
        }
        return $returnData;

    }

    public function deliverySms($emailTo, $shortCodeArray){
        $code       = $this->deliverySmsCode;
        $type       = $this->typeSms;
        $template   = $this->getEmailTemplate($code, $type);

        if($template){
            $template       = $template[0]->template;
            $subject        = $template[0]->name;
            $template       = $this->shortCodeReplace($template, $shortCodeArray);
        }else{
            $template   = 'Order delivered';
            $subject    = '';
        }
        $this->sendSms($emailTo, $template);
    }

    public function shortCodeReplace($template, $data){

        if(isset($data['firstName'])){
            $template = str_replace('[user_first_name]',$data['firstName'],$template);

        }
        if(isset($data['lastName'])){
            $template = str_replace('[user_last_name]',$data['lastName'],$template);
        }
        if(isset($data['userEmail'])){
            $template = str_replace('[user_email]',$data['userEmail'],$template);

        }
        if(isset($data['password'])){
            $template = str_replace('[user_password]',$data['password'],$template);
        }

        return $template;

    }

}
?>