<?php
    
  class XapoMicroPaymentSDK
  {
    
    static $serviceUrl;
    static $appID;
    static $appSecret;
   
    static public function setEnvironmentUrl($url)
    {
      XapoMicroPaymentSDK::$serviceUrl = $url;
    }
  
    static public function setApplication($appID, $secret)
    {
      XapoMicroPaymentSDK::$appID = $appID;
      XapoMicroPaymentSDK::$appSecret = $secret;
    }
    
    static private function encrypt($data)
    {
      //pkcs7 padding
      $block = 16;
      $pad = $block - (strlen($data) % $block);
      $data .= str_repeat(chr($pad), $pad);
      
      $key = XapoMicroPaymentSDK::$appSecret;
      
      $enc = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, 'ecb'));
      
      return $enc;
    }
    
    static private function buildWidgetUrl($request)
    {
      $buttonRequestObj = new stdClass;
      $buttonRequestObj->sender_user_id = $request->sender_user_id;
      $buttonRequestObj->sender_user_email = $request->sender_user_email;
      $buttonRequestObj->sender_user_cellphone = $request->sender_user_cellphone;
      $buttonRequestObj->receiver_user_id = $request->receiver_user_id;
      $buttonRequestObj->receiver_user_email = $request->receiver_user_email;
      $buttonRequestObj->pay_object_id = $request->pay_object_id;
      $buttonRequestObj->amount_BIT = $request->amount_BIT;
      $buttonRequestObj->timestamp = time() * 1000;
      $buttonRequestJson = json_encode($buttonRequestObj);

      $customization = new stdClass;
      $customization->button_text = $request->pay_type;
      $queryStrObj = new stdClass;
      $queryStrObj->customization = json_encode($customization);
      
      if(isset(XapoMicroPaymentSDK::$appID) && isset(XapoMicroPaymentSDK::$appSecret)){
        $buttonRequestEnc = XapoMicroPaymentSDK::encrypt($buttonRequestJson);
        $queryStrObj->app_id = XapoMicroPaymentSDK::$appID;
        $queryStrObj->button_request = $buttonRequestEnc;
      }else{
        $queryStrObj->payload = $buttonRequestJson;
      }
      
      $queryStr = http_build_query($queryStrObj);
      
      $widgetUrl = XapoMicroPaymentSDK::$serviceUrl.'?'.$queryStr;
      return $widgetUrl;      
    }
    
    static public function buildDivWidget($request)
    {
      $widgetUrl = XapoMicroPaymentSDK::buildWidgetUrl($request);
      
      $res = '<div id="tipButtonDiv" class="tipButtonDiv"></div>';
      $res .= '<div id="tipButtonPopup" class="tipButtonPopup"></div>';
      $res .= '<script>';
      $res .= '$(document).ready(function() {';
      $res .= '$("#tipButtonDiv").load("'.$widgetUrl.'");';
      $res .= '});';      
      $res .= '</script>';            
      
      return $res;
    }

    static public function buildIframeWidget($request)
    {
      $widgetUrl = XapoMicroPaymentSDK::buildWidgetUrl($request);
      $res = '<iframe id="tipButtonFrame" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:22px;" allowTransparency="true" src="'.$widgetUrl.'"></iframe>';
      return $res;
    }
    
    static public function iframeWidget($sender_user_id, $sender_user_email, $sender_user_cellphone, $receiver_user_id, $receiver_user_email, $pay_object_id, $amount_BIT, $pay_type)
    {
      $buttonRequestObj = new stdClass();
      $buttonRequestObj->sender_user_id = $sender_user_id;
      $buttonRequestObj->sender_user_email = $sender_user_email;
      $buttonRequestObj->sender_user_cellphone = $sender_user_cellphone;
      $buttonRequestObj->receiver_user_id = $receiver_user_id;
      $buttonRequestObj->receiver_user_email = $receiver_user_email;
      $buttonRequestObj->pay_object_id = $pay_object_id;
      $buttonRequestObj->amount_BIT = $amount_BIT;
      $buttonRequestObj->pay_type = $pay_type;        
      
      $widgetUrl = XapoMicroPaymentSDK::buildWidgetUrl($buttonRequestObj);
      $res = '<iframe id="tipButtonFrame" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:22px;" allowTransparency="true" src="'.$widgetUrl.'"></iframe>';
      return $res;
    }    
    
  }

?>