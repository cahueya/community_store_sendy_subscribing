<?php defined('C5_EXECUTE') or die('Access Denied.');
$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
?>

<form method="post" action="<?php echo $this->action('save_settings'); ?>">
    <?php $app->make('token')->output('save_settings'); ?>

    <div class="form-group">
        <div class="checkbox">
            <label>
            <?php
            echo $form->checkbox('enableSubscriptions', 1, $enableSubscriptions);
            echo t('Enable Product List Subscriptions');
            ?>
            </label>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->label('apiKey', t('API Key')); ?>
        <?php echo $form->text('apiKey', $apiKey); ?>
    </div>
    <div class="form-group">
        <?php echo $form->label('url', t('Sendy URL')); ?>
        <?php echo $form->text('url', $url); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label('defaultListID', t('Default List ID')); ?>
        <?php echo $form->text('defaultListID', $defaultListID, array('placeholder'=>t('Optional'))); ?>
        <span class="help-text"><?php t('Customers will be added to this list on transaction completion, regardless of product purchases'); ?></span>
    </div>

    <p class="alert alert-info">
        <?php echo t('To add customers to specific Sendy lists for specific products, <a href="%s">create a text product attribute</a> with the handle \'sendy_list_id\', then enter a list ID when editing a product.', \URL::to('/dashboard/store/products/attributes')); ?>
        <br><?php echo t('A list ID is found under the Sendy settings, list name and defaults section of a list.'); ?>
    </p>

    <p class="alert alert-info">
        <?php echo t('If you require your customers consent to add them to a mailing list, as it is required in GDPR, <a href="%s">create a checkbox attribute</a> with the handle \'sendy_checkout_subscribe\', then add it to the Other Customer Choices Group.', \URL::to('/dashboard/store/orders/attributes')); ?>
    </p>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <button class="pull-right btn btn-primary" type="submit"><?php echo t('Save'); ?></button>
        </div>
    </div>
</form>
