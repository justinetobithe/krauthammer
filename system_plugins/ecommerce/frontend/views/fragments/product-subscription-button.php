<?php function layout_subscribe($billing_info=array()){ // FUNTIONS 1
  $subscription_slug = 'global';
  if ($billing_info['billing_info']['type'] == "Global Subscription") {
    if (count($billing_info['global_subscriptions']) == 1) {
      $id = isset($billing_info['global_subscriptions'][0]['plan']['id']) ? $billing_info['global_subscriptions'][0]['plan']['id'] : "";
      $subscription_slug = "api/cms-paypal/paypal_create_subscription/{$id}/";
    }else{
      $subscription_slug = "membership-subscription/?item=" . ecatalog_get_product_detail('id');
    }
  }elseif($billing_info['billing_info']['type'] == "Subscription"){
    $subscription_type = 'subscription';
    $subscription_slug = "api/cms-paypal/paypal_create_product_subscription/". ecatalog_get_product_detail('id') ."/";
  } 
  $subscription_ling = rtrim(get_bloginfo("baseurl"),'/') . "/{$subscription_slug}";
?>
<form action="<?php echo $subscription_ling ?>">
  <?php if ($billing_info['billing_info']['type'] == "Subscription"): ?>
  <h4>Select Billing Period</h4>
  <select name="subscription-period" class="input input-small form-control">
  <?php foreach ($billing_info['subscriptions'] as $key => $value): ?>
    <option value="<?php echo $value['plan_name'] ?>"><?php echo $value['plan_name'] ?></option>
  <?php endforeach ?>
  </select>
  <br>
  <?php endif ?>
  <button class="btn btn-primary"><i class="fa fa-group"></i> Subscribe</button>
</form>
<?php } ?>

<?php function layout_download(){ // FUNTIONS 2 ?>
<a href="<?php bloginfo('baseurl') ?>api/cms-paypal/download-files/<?php echo ecatalog_get_product_detail('id') ?>" target="_blank" class="btn btn-primary"><i class="fa fa-download"></i> Download File</a >
<?php } ?>


<?php function layout_login(){ // FUNTIONS 3 ?>
<a href="<?php bloginfo("baseurl") ?>customer/login/">Go to Login Page</a>
<?php } ?>

<?php function layout_checkout($data = array()){ // FUNTION 4 ?>
<?php if ($data['product']['quantity']): ?>
  <span><small>Stock: <b><?php echo $data['product']['quantity']; ?></b></small></span>

  <form action="<?php echo get_current_url(); ?>" method="post" enctype="multipart/form-data">
    <div class="hide">
      <p>
        <input type="text" name="product-id" value="<?php echo $data['product']['id']; ?>">
      </p>
      <?php echo cms_contact_form_honey_pot(); ?>
    </div>
    <div>
      Quantity: 
      <input type="number" name="product-quantity" id="product-option-selected-quantity" class="text-center" min="1" max="<?php echo $data['product']['quantity'] ?>" value="1">
      <!-- <select name="product-quantity" id="product-option-selected-quantity">
        <?php for ($i=1; $i <= intval($data['product']['quantity']); $i++): ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        <?php endfor; ?>
      </select> -->
    </div>
    <p>
      <button name="product-submit" class="btn btn-primary">Add to Cart</button> &nbsp;
      <a href="<?php echo cms_get_cart_url() ?>" name="product-submit" class="">View Cart</a>
    </p>
  </form>
<?php else: ?>
  <p><b><?php echo isset($data['product']['out_of_stock_status']) ? $data['product']['out_of_stock_status'] : "Out of Stocks" ; ?></b></p>
<?php endif ?>
<?php } ?>

<!-- START -->
<?php if ($billing_info['billing_info']['product_type'] == 'Downloadable'): ?>
  <?php if (customer_is_login()): ?>
    <?php if (customer_validate_payment()): ?>
      <?php layout_download() ?>
    <?php else: ?>
      <?php if ($billing_info['billing_info']['type'] == "One-time"): ?>
        <?php layout_checkout($data) ?>
      <?php else: ?>
        <?php layout_subscribe($billing_info) ?>
      <?php endif ?>
    <?php endif ?>
  <?php else: ?>
    <?php layout_login() ?>
  <?php endif ?>
<?php else: ?>
  <?php if ($billing_info['billing_info']['type'] == "One-time"): ?>
    <?php layout_checkout($data) ?>
  <?php elseif ($billing_info['billing_info']['type'] == "Subscription"): ?>
    <?php layout_subscribe($billing_info) ?>
  <?php elseif ($billing_info['billing_info']['type'] == "Global Subscription"): ?>
    <?php layout_subscribe($billing_info) ?>
  <?php endif ?>
<?php endif ?>
<!-- END -->
