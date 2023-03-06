<?php
namespace Concrete\Package\CommunityStoreSendySubscribing;

use Concrete\Core\Support\Facade\Application;
use Events;
use Package;
use SinglePage;
use Whoops\Exception\ErrorException;

class Controller extends Package
{
    protected $pkgHandle = 'community_store_sendy_subscribing';
    protected $appVersionRequired = '5.7.5';
    protected $pkgVersion = '1.0';

    protected $pkgAutoloaderRegistries = [
        'src/Event' => '\Concrete\Package\CommunityStoreSendySubscribing\Src\Event',
    ];

    public function getPackageName()
    {
        return t('Sendy Subscribing');
    }

    public function getPackageDescription()
    {
        return t('Subscribe Community Store customers to Sendy lists based on products purchased.');
    }

    public function install()
    {
        $installed = Package::getInstalledHandles();
        if (!(is_array($installed) && in_array('community_store', $installed)) ) {
            throw new ErrorException(t('This package requires that Community Store be installed'));
        }

        $pkg = parent::install();
        $sp = SinglePage::add('/dashboard/store/sendy_subscribing', $pkg);
        if (is_object($sp)) {
            $sp->update(array('cName' => t('Sendy Subscribing')));
        }
    }

    public function on_start()
    {
        require $this->getPackagePath() . '/vendor/autoload.php';
        $app = Application::getFacadeApplication();
        $config = $config = $app->make('config');
        $enableSubscriptions = $config->get('sendy_subscribing.enableSubscriptions');
        if ($enableSubscriptions) {
            $orderlistener = $app->make('\Concrete\Package\CommunityStoreSendySubscribing\Src\Event\Order');
            Events::addListener('on_community_store_payment_complete', array($orderlistener, 'orderPaymentComplete'));
        }
    }
}
