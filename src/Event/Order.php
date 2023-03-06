<?php
namespace Concrete\Package\CommunityStoreSendySubscribing\Src\Event;

use Concrete\Core\Support\Facade\Application;
use SendyPHP;

class Order
{
    private $apiKey;

    public function orderPaymentComplete($event)
    {
        $order = $event->getOrder();
        if ($order) {
            $this->getOrderInfo($order);
        }
    }

    private function getOrderInfo($order)
    {
        $app = Application::getFacadeApplication();
        $config = $app->make('config');
        $defaultListID = $config->get('sendy_subscribing.defaultListID');

        $customerInfo = array();
        $customerInfo['email'] = $order->getAttribute('email');
        $customerInfo['checkbox'] = $order->getAttribute('sendy_checkout_subscribe');
        $customerInfo['name'] = $order->getAttribute('billing_first_name') .' ' . $order->getAttribute('billing_last_name');

        //Check if the checkbox attribute exists. If if exists, only proceed if it is checked
        if(!isset($customerInfo['checkbox']) OR $customerInfo['checkbox'] === true)
        {
            $productListIDs = array();

            if ($defaultListID) {
                $productListIDs[] = $defaultListID;
            }

            $items = $order->getOrderItems();
            if ($items) {
                foreach ($items as $item) {
                    $sendyListID = $item->getProductObject()->getAttribute('sendy_list_id');
                    if ($sendyListID) {
                        $productListIDs[] = trim($sendyListID);
                    }
                }
            }

            if (!empty($productListIDs)) {
                $this->sendRequest($customerInfo, array_unique($productListIDs));
            }
        } else {

            return;
        
        }
    }


    private function sendRequest($customerInfo, $productListIDs)
    {
        $app = Application::getFacadeApplication();
        $config = $app->make('config');
        $apiKey = $config->get('sendy_subscribing.apiKey');
        $url = $config->get('sendy_subscribing.url');

        if (count($productListIDs) > 1) {
            // batch
            foreach ($productListIDs as $productListID) {
                $config = array(
                    'api_key' => $apiKey, //your API key is available in Settings
                    'installation_url' => $url,  //Your Sendy installation
                    'list_id' => $productListID
                );
    
                $sendy = new \SendyPHP\SendyPHP($config);
                $results = $sendy->subscribe(array(
                    'name'=>$customerInfo['name'],
                    'email' => $customerInfo['email'], //this is the only field required by sendy
                ));
            }
        } else {
            // single
            $config = array(
                'api_key' => $apiKey, //your API key is available in Settings
                'installation_url' => $url,  //Your Sendy installation
                'list_id' => $productListIDs[0]
            );
    
            $sendy = new \SendyPHP\SendyPHP($config);
            $results = $sendy->subscribe(array(
                'name'=>$customerInfo['name'],
                'email' => $customerInfo['email'], //this is the only field required by sendy
            ));
        }
    }
}
