<?
class Emails {

    private $_data=array();

    function Emails($transaction=false, $dump=false) {
        if ($transaction) {

            global $_CONF;

            loadclass('Transactions');
            loadclass('Sites');
            loadclass('Merchants');
            loadclass('MerchantAccounts');
            loadclass('Customers');
            loadclass('EmailSettings');


            $customer=new Customers($transaction->transactionCustomerID);
            $merchant=new Merchants($transaction->transactionMerchantID);

	    
            //if(!($_CONF[mail_enabled]!='FALSE' && $merchant->merchantEmailSending==1)) return false;

            $merchantaccount=new MerchantAccounts($merchant->merchantMerchantAccountID);
            $site=new Sites($transaction->transactionSiteID);

            $order=unserialize($customer->customerOrderObject);


            $emailSettings=new EmailSettings($merchant->merchantEmailSettingID);


            $smarty=new Smarty();
            $smarty->assign($transaction->getValues());
            $smarty->assign($customer->getValues());
            $smarty->assign($merchant->getValues());
            $smarty->assign($merchantaccount->getValues());
            $smarty->assign($emailSettings->getValues());
            $smarty->assign($site->getValues());
            $smarty->assign($order);
            $smarty->assign('Order',$order);

            if(!empty($emailSettings->emailFromEmail)) $from="$emailSettings->emailCompanyName <$emailSettings->emailFromEmail>";



            $recipients[]=$customer->customerEmail;
            if ($site->siteEmailSendCustomerService) $recipients[]=$site->siteCustomerSupportEmail;
            if ($site->siteEmailSendAdditionalRecipients) $recipients[]=$site->siteEmailAdditionalRecipients;
            $smarty->assign('mheaders',$emailSettings->emailHeaders);


	    $emails_dir=$_CONF[root_dir].$_CONF[emails_dir];
	    if (is_dir($emails_dir."/merchant_emails/".$merchant->getID())) {
		    $emails_dir=$emails_dir."/merchant_emails/".$merchant->getID()."/";
	    }


            switch($transaction->transactionType) {

            case 'rebill':
                    if ($transaction->transactionStatus=='approved' || ($transaction->transactionStatus=='test' && $transaction->transactionReturnNumber=='AUTH_TESTCARD_APP' )) {
                        $subject=$emailSettings->emailPaymentSubject;
                        $smarty->assign('msubject',$subject);

                        if (!$site->siteRebillEmailTemplate) $site->siteRebillEmailTemplate=$_CONF[emails_rebill];
                        $out=$smarty->fetch($emails_dir.$site->siteRebillEmailTemplate);
                    } else {
			return false;
                       // $out=$smarty->fetch($_CONF[root_dir].$_CONF[emails_dir].$_CONF[emails_rebill]);
                    }
                break;
            case 'sale':
                if ($transaction->transactionStatus=='approved' || ($transaction->transactionStatus=='test' && $transaction->transactionReturnNumber=='AUTH_TESTCARD_APP' )) {
                    $subject=$emailSettings->emailPaymentSubject;
                    $smarty->assign('msubject',$subject);

                    if (!$site->sitePaymentEmailTemplate) $site->sitePaymentEmailTemplate=$_CONF[emails_payment];
                    $out=$smarty->fetch($emails_dir.$site->sitePaymentEmailTemplate);
                } else {
			return false;
                    //$out=$smarty->fetch($_CONF[root_dir].$_CONF[emails_dir].$_CONF[emails_payment]);
                }
                break;

            case 'chargeback':
                $out=$smarty->fetch($emails_dir.$_CONF[emails_chargeback]);
                break;
            case 'refund':
                $out=$smarty->fetch($emails_dir.$_CONF[emails_refund]);
                break;
            case 'reversal':
                $out=$smarty->fetch($emails_dir.$_CONF[emails_reversal]);
                break;

            }
            $body=$smarty->getSmartyVar('capture.body');
            $subject=$smarty->getSmartyVar('capture.subject');

            $headers=array();
            if ($add_h=explode("\n",$smarty->getSmartyVar('capture.headers'))) $headers=array_merge($headers,$add_h);

		if ($dump) {
	    mydump($body);
	    mydump($subject);
	    mydump($headers);
	    mydump($recipients);

	    exit();
		}

            if($_CONF[mail_enabled]!='FALSE' && $merchant->merchantEmailSending==1) return pmail($recipients,$body,$subject,$headers,$from);
        }
    }

    function Send($object,$template_name,$recipients,$dump=false) {
	    global $_CONF;
            $smarty=new Smarty();
            $smarty->assign($object->getValues());
	    $subject=" ";
	    $headers=" ";


	    $smarty->assign('msubject',$subject);
	    $smarty->assign('mheaders',$headers);


	    $out=$smarty->fetch($_CONF[root_dir].$_CONF[emails_dir].$_CONF[$template_name]);

            $body=$smarty->getSmartyVar('capture.body');
            $subject=$smarty->getSmartyVar('capture.subject');
	    $from=$smarty->getSmartyVar('capture.from');
	    if (empty($from)) $from=$_CONF[mail_from];
            $headers=array();
            if ($add_h=explode("\n",$smarty->getSmartyVar('capture.headers'))) $headers=array_merge($headers,$add_h);

	    if (!is_array($recipients) && is_string($recipients)) $recipients=array($recipients);

		if ($dump) {
	    mydump($body);
	    mydump($subject);
	    mydump($headers);
	    mydump($recipients);

	    exit();
		}



            if($_CONF[mail_enabled]!='FALSE') return pmail($recipients,$body,$subject,$headers,$from);

	    //exit();

    }

}

?>
