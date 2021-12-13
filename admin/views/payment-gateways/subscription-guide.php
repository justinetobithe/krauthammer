<?php  
$guide = array(
  array(
    'function_name' => "ecatalog_get_product_billing_period()",
    'description' => array(
      'Gets Product payment info including subscription detail.',
    ),
  ),
  array(
    'function_name' => "customer_get_info()",
    'description' => array(
      'Get the current login from session with index "customer".',
      'If no customer found, the return value will be "null".',
      'Store the value to a global variable "$_global_customer_var" intended for customer related functions',
    ),
  ),
  array(
    'function_name' => "customer_the_info()",
    'description' => array(
      'Get the current login from "$_global_customer_var"',
      'This function will use the stored value of customer info from',
    ),
  ),
  array(
    'function_name' => "customer_is_login()",
    'description' => array(
      'Check if there is a current logged in user',
    ),
  ),
  array(
    'function_name' => "customer_is_login_enable()",
    'description' => array(
      'Check if customer login function is enable',
    ),
  ),
  array(
    'function_name' => "customer_validate_payment()",
    'description' => array(
      'Check of the current logged in use has a subscription of the current selected product.',
    ),
  ),
  array(
    'function_name' => "get_global_subscriptions()",
    'description' => array(
      'Returns array of defined Globla Subscription Plans.',
      'These items are located under Gateway Payment - Paypal Subscription.',
    ),
  ),
  array(
    'function_name' => "cms_payment_paypal_subscription()",
    'description' => array(
      'Usage:',
      '- add the tag "[cms_payment_paypal_subscription]" inside Page/Product content.',
      'Customize:',
      '- add a file called "product-global-subscription-item.php" inside the current activated theme.',
    ),
  ),
  array(
    'function_name' => "cms_subscription_confirmed()",
    'description' => array(
      'Tag : [cms_subscription_confirmed]',
      'Usage: Add the tag inside the content section of a Page.',
      'Description: This function will handle the incoming Paypal Confirmation',
    ),
  ),
);
$guide2 = array(
  array(
    'function_name' => "update_invoice_number()",
    'description' => array(
      'Updates system_option "invoice_next_number" by incrementing by 1',
    ),
  ),
  array(
    'function_name' => "get_active_theme_location()",
    'description' => array(
      'Returns the path of active theme directory',
    ),
  ),
);
?>

<h5><strong>Setup Guide</strong></h5>
<hr>
<div class="row-fluid">
  <div class="span12">
    <ul>
      <li>
        <p><strong>General Settings</strong></p>
        <p>Get your Client ID and Secret from <a href="https://www.paypal.com/us/home">Paypal.</a></p>
        <p></p>
      </li>
      <li>
        <p><strong>Subscription Plan</strong></p>
        <em>(Save first the General Setting information before adding Subscription Plans)</em>
        <ol>
          <li>
            <p>Click the "Add Subscription Plan" button, then a form will pop-up. It consists of 3 tabs with different set of fields: </p>
            <p>Subscription Detail: <br>- Contains the subscription plan detail</p>
            <p>Agreement: <br>- Contains the Agreement Name and Description that will be used upon subscription.</p>
            <p>Subscriber: <br>- Will show the list of subscriber of that Subscriptio Plan.</p>
          </li>
          <li>
            Fill-up the information needed. See <a href="https://developer.paypal.com/docs/api/payments.billing-plans/v1/">Paypal Docs</a> for reference.<br>
            <small><em>Note: Make sure not to leave an empty field specially on the name and description area.</em></small>
          </li>
          <li>
            <p>Upon saving, the system will send a request to paypal to create a Subscription Plan. On this state, the detail of the subscription plan is editable.</p>
          </li>
          <li>
            <p>If the subscription detail is all set, you can now activate by clicking the "Activate" button at the lower left side of the form. In this state, the Subscription Plan (Subscription Detail) can no longer be modified.</p>
          </li>
        </ol>
        <p></p>
      </li>
      <li>
        <p><strong>Products Default Subscription Settings</strong></p>
        <p><strong>Default Subscription Settings</strong></p>
        <p>This section contains set of fields that is used in Product Billing Period. </p>
        <p><strong>Default Agreement Settings</strong></p>
        <p>This panel contains <strong>default</strong> Agreement Name and Agreement Description. These set of fields is similar to Subscription Plan that is also used upon subscription.  </p>
        <p><strong>Products Default Billing Period</strong></p>
        <p>This section allows the user to add a default billing period options. You can specify different billing period per item. </p>
        <br>

        <h5><strong>Integrating Product module</strong></h5>
        <p>The <strong>Billing Period</strong> tab in <strong>Products</strong> module will be available if the <strong>Paypal Subscription</strong> is <strong>Enabled</strong>. There are 2 section where you can specify what kind of subscription a product has</p>
        <p><strong>Payment Type</strong></p>
        <ul>
          <li>
            <p><strong>One-time</strong></p>
            <p>This option indicates that the product is using a checkout payment.</p>
          </li>
          <li>
            <p><strong>Subscription</strong></p>
            <p>Selecting this option will enable selecting a Billing Period of that specific Product. By default, it will have the item from Product Default Billing Period under Paypal Subscription module. The subcription detail of each item can also be modified. The modified information is only applied in to the selected product. Only the enabled Billing Period will be available in frontend implementation. <em>(Be sure to fill in all the fields espcially in name and description)</em></p>
          </li>
          <li>
            <p><strong>Global Subscription</strong></p>
            <p>Global Subscription will have a table of Subscription Plans (Defined in Paypal Subscription Module). By selecting a Subscription Plan, the product will be available if user availing product has a current membership subscription. </p>
          </li>
        </ul>
        <p><strong>Product Type</strong></p>
        <ul>
          <li>
            <p><strong>Physical Goods</strong></p>
          </li>
          <li>
            <p><strong>Downloadable</strong></p>
            <p>This option is for those digital products (Image, Video, Audio, PDF and etc)</p>
          </li>
        </ul>
      </li>
    </ul>
    <hr>
  </div>
</div>
<br><br><br>

<h5><strong>Front-end Functions</strong></h5>
<hr>
<?php foreach ($guide as $key => $value): ?>
<div class="row-fluid">
  <div class="span5">
    <p><strong><?php echo $value['function_name'] ?></strong></p>
  </div>
  <div class="span7">
    <?php foreach ($value['description'] as $k => $v): ?>
      <p><?php echo $v ?></p>
    <?php endforeach ?>
    <hr>
  </div>
</div>
<?php endforeach ?>
<br><br><br>

<h5><strong>Backend Related Functions</strong></h5>
<hr>
<?php foreach ($guide2 as $key => $value): ?>
<div class="row-fluid">
  <div class="span5">
    <p><strong><?php echo $value['function_name'] ?></strong></p>
  </div>
  <div class="span7">
    <?php foreach ($value['description'] as $k => $v): ?>
      <p><?php echo $v ?></p>
    <?php endforeach ?>
    <hr>
  </div>
</div>
<?php endforeach ?>

<h5><strong>Backend Related Functions</strong></h5>
<hr>
<?php foreach ($guide2 as $key => $value): ?>
<div class="row-fluid">
  <div class="span5">
    <p><strong><?php echo $value['function_name'] ?></strong></p>
  </div>
  <div class="span7">
    <?php foreach ($value['description'] as $k => $v): ?>
      <p><?php echo $v ?></p>
    <?php endforeach ?>
    <hr>
  </div>
</div>
<?php endforeach ?>