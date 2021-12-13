<?php 
$g_subs       = get_global_subscriptions();
// echo json_encode($g_subs); 
$api_location = get_bloginfo("baseurl") . "api/cms-paypal/paypal_create_subscription/";
?>

<div class="row-fluid">
  <?php if (customer_is_login()): ?>
  <?php foreach ($g_subs as $key => $value): ?>
  <div class="col-sm-4">
    <div class="well well-sm" style="font-family: 'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif; padding: 20px 15px; ">
      <h4 class="text-center" style="margin: 0px; height: 30px"><small><strong><?php echo $value['plan']['name']; ?></strong></small></h4>
      <hr>
      <?php foreach ($value['plan']['payment_definitions'] as $k => $v): ?>
        <p class="text-center" style="margin: 0px; font-size: 13px; "><strong><?php echo $v['type'] ?>: </strong><?php echo $v['name'] ?></p>
        <p class="text-center" style="margin: 0px; font-size: 13px; "><strong><?php echo $v['amount']['value'] . " " . $v['amount']['currency'] ?></strong> per <small><strong><?php echo $v['frequency_interval'] ?> <?php echo $v['frequency'] ?></strong></small></p>
      <?php endforeach ?>
      <br>
      <form action="<?php echo "{$api_location}{$value['plan']['id']}" ?>" class="text-center">
        <button class="btn btn-primary" <?php echo count($value['subscriptions']) > 0 ? "disabled" : "" ?>><i class="fa fa-group"></i> <?php echo count($value['subscriptions']) > 0 ? "Subscribed" : "Subscribe" ?></button>
      </form>
    </div>
  </div>
  <?php endforeach ?>
  <?php else: ?>
  <div class="col-sm-12 text-center">
    <a href="<?php bloginfo("baseurl") ?>customer/login/">Go to Login Page</a>
  </div>
  <?php endif ?>
</div>