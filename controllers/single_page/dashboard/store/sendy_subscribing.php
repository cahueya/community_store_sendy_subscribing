<?php
namespace Concrete\Package\CommunityStoreSendySubscribing\Controller\SinglePage\Dashboard\Store;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Support\Facade\Application;

class SendySubscribing extends DashboardPageController
{
    public function view()
    {
        $app = Application::getFacadeApplication();
        $config = $app->make('config');
        $this->set('enableSubscriptions', $config->get('sendy_subscribing.enableSubscriptions'));
        $this->set('apiKey', $config->get('sendy_subscribing.apiKey'));
        $this->set('defaultListID', $config->get('sendy_subscribing.defaultListID'));
        $this->set('url', $config->get('sendy_subscribing.url'));
    }

    public function settings_saved()
    {
        $this->set('message', t('Settings Saved'));
        $this->view();
    }

    public function save_settings()
    {
        $app = Application::getFacadeApplication();
        $config = $app->make('config');

        if ($this->post()) {
            if ($this->token->validate('save_settings')) {
                $enableSubscriptions = $this->request->post('enableSubscriptions');
                $apiKey = $this->request->post('apiKey');
                $defaultListID = $this->request->post('defaultListID');
                $url = $this->request->post('url');

                if ($enableSubscriptions) {
                    if (!$apiKey) {
                        $this->error->add(t('An API Key is required'));
                    }
                    if (!$url) {
                        $this->error->add(t('An URL is required'));
                    }
                }

                $config->save('sendy_subscribing.enableSubscriptions', $enableSubscriptions);
                $config->save('sendy_subscribing.apiKey', $apiKey);
                $config->save('sendy_subscribing.defaultListID', $defaultListID);
                $config->save('sendy_subscribing.url', $url);

                if (!$this->error->has()) {
                    $this->redirect('/dashboard/store/sendy_subscribing', 'settings_saved');
                }
            } else {
                $this->error->add(t('Invalid CSRF token. Please refresh and try again.'));
                $this->view();
            }
        }
    }
}
