<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">
<?php
$itemsMapper = $this->get('itemsMapper');
$order = $this->get('order');
$purchaseUnits = [];
?>

<h1>
    <?=$this->getTrans('menuPayment') ?>
</h1>

<div class="panel panel-default">
    <div class="panel-heading" id="orderHeading" data-toggle="collapse" data-target="#orderDetails"><?=$this->getTrans('paymentPanelHeading', substr($order->getInvoiceFilename(),0,strrpos($order->getInvoiceFilename(), '_')), $order->getDatetimeInvoiceSent(), $order->getDatetime()) ?><span class="pull-right clickable"><i class="fa-solid fa-chevron-down"></i></span></div>
    <div class="panel-body collapse" id="orderDetails">
        <div class="table-responsive order">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th><?=$this->getTrans('productImage') ?><br />&nbsp;</th>
                    <th><?=$this->getTrans('productName') ?><br /><small><?=$this->getTrans('itemNumber') ?></small></th>
                    <th><?=$this->getTrans('shippingTime') ?><br />&nbsp;</th>
                    <th><?=$this->getTrans('singlePrice') ?><br /><small><?=$this->getTrans('withoutTax') ?></small></th>
                    <th><?=$this->getTrans('taxShort') ?><br />&nbsp;</th>
                    <th><?=$this->getTrans('singlePrice') ?><br /><small><?=$this->getTrans('withTax') ?></small></th>
                    <th class="text-center"><?=$this->getTrans('entries') ?><br />&nbsp;</th>
                    <th class="text-right"><?=$this->getTrans('total') ?><br /><small>incl. <?=$this->getTrans('taxShort') ?></small></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $orderItems = json_decode(str_replace("'", '"', $order->getOrder()), true);
                $subtotal_price = 0;
                $pdfOrderNr = 1;
                foreach ($orderItems as $orderItem):
                    $itemId = $orderItem['id'];
                    $itemImg = $itemsMapper->getShopItemById($itemId)->getImage();
                    $itemName = $itemsMapper->getShopItemById($itemId)->getName();
                    $itemNumber = $itemsMapper->getShopItemById($itemId)->getItemnumber();
                    $itemPrice = $itemsMapper->getShopItemById($itemId)->getPrice();
                    $itemTax = $itemsMapper->getShopItemById($itemId)->getTax();
                    $itemPriceWithoutTax = round(($itemPrice / (100 + $itemTax)) * 100, 2);
                    $arrayShippingCosts[] = $itemsMapper->getShopItemById($itemId)->getShippingCosts();
                    $itemShippingTime = $itemsMapper->getShopItemById($itemId)->getShippingTime();
                    $arrayShippingTime[] = $itemShippingTime;
                    $arrayTaxes[] = $itemTax;
                    $arrayPrices[] = $itemPrice * $orderItem['quantity'];
                    $arrayPricesWithoutTax[] = $itemPriceWithoutTax * $orderItem['quantity'];
                    $shopImgPath = '/application/modules/shop/static/img/';
                    if ($itemImg AND file_exists(ROOT_PATH.'/'.$itemImg)) {
                        $img = BASE_URL.'/'.$itemImg;
                    } else {
                        $img = BASE_URL.$shopImgPath.'noimg.jpg';
                    }
                    $currency = iconv('UTF-8', 'windows-1252', $this->escape($this->get('currency')->getName()));
                    $pdfOrderData[] = array(
                        $pdfOrderNr++,
                        utf8_decode($itemName),
                        number_format($itemPriceWithoutTax, 2, '.', '').' '.$currency,
                        $itemTax.' %',
                        number_format($itemPrice, 2, '.', '').' '.$currency,
                        $orderItem['quantity'],
                        number_format($itemPrice * $orderItem['quantity'], 2, '.', '').' '.$currency,
                        utf8_decode($this->getTrans('itemNumberShort')).' '.$itemNumber);
                    ?>
                    <tr>
                        <td><img src="<?=$img ?>" class="item_image" alt="<?=$this->escape($itemName) ?>"> </td>
                        <td>
                            <b><?=$this->escape($itemName); ?></b><br /><small><?=$this->escape($itemNumber); ?></small>
                        </td>
                        <td><?=$itemShippingTime ?> <?=$this->getTrans('days') ?></td>
                        <td>
                            <?=number_format($itemPriceWithoutTax, 2, '.', '') ?> <?=$this->escape($this->get('currency')->getName()) ?>
                        </td>
                        <td><?=$itemTax ?> %</td>
                        <td>
                            <b><?=number_format($itemPrice, 2, '.', '') ?> <?=$this->escape($this->get('currency')->getName()) ?></b>
                        </td>
                        <td class="text-center">
                            <b><?=$orderItem['quantity'] ?></b>
                        </td>
                        <td class="text-right">
                            <b><?=number_format($itemPrice * $orderItem['quantity'], 2, '.', '') ?> <?=$this->escape($this->get('currency')->getName()) ?></b>
                        </td>
                    </tr>
                    <?php
                    $subtotal_price += round($itemPrice * $orderItem['quantity'], 2);

                    // Fill purchase_units array for PayPal.
                    $purchaseUnits['items'][] = ['name' => $this->escape($itemName), 'unit_amount' => ['value' => $itemPrice, 'currency_code' => $this->escape($this->get('currency')->getCode())], 'quantity' => $orderItem['quantity'], 'sku' => $this->escape($itemNumber)];
                    ?>
                <?php endforeach; ?>
                <tr>
                    <td colspan="7" class="text-right finished">
                        <b><?=$this->getTrans('deliveryCosts') ?>:</b>
                    </td>
                    <td colspan="1" class="text-right finished">
                        <?php $shipping_costs = max($arrayShippingCosts); ?>
                        <b><?=number_format($shipping_costs, 2, '.', '') ?> <?=$this->escape($this->get('currency')->getName()) ?></b>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="text-right finish">
                        <?=$this->getTrans('subtotal') ?> <?=$this->getTrans('withTax') ?>:
                    </td>
                    <td colspan="1" class="text-right finish">
                        <?php $total_price = array_sum($arrayPrices) + $shipping_costs; ?>
                        <?=number_format($total_price, 2, '.', '') ?> <?=$this->escape($this->get('currency')->getName()) ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="text-right finish">
                        <?=$this->getTrans('subtotal') ?> <?=$this->getTrans('withoutTax') ?>:
                    </td>
                    <td colspan="1" class="text-right finish">
                        <?php $sumPricewithoutTax = array_sum($arrayPricesWithoutTax) + round(($shipping_costs / (100 + max($arrayTaxes))) * 100, 2); ?>
                        <?=number_format($sumPricewithoutTax, 2, '.', '') ?> <?=$this->escape($this->get('currency')->getName()) ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="text-right finish">
                        <?=$this->getTrans('tax') ?>:
                    </td>
                    <td colspan="1" class="text-right finish">
                        <?php $differenzTax = round($total_price - $sumPricewithoutTax, 2); ?>
                        <?=number_format($differenzTax, 2, '.', '') ?> <?=$this->escape($this->get('currency')->getName()) ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="text-right finished">
                        <b><?=$this->getTrans('totalPrice') ?>:</b>
                    </td>
                    <td colspan="1" class="text-right finished">
                        <b><?=number_format($total_price, 2, '.', '') ?> <?=$this->escape($this->get('currency')->getName()) ?></b>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Create "purchase_units" for PayPal.
$purchaseUnits['amount']['value'] = number_format($total_price, 2, '.', '');
$purchaseUnits['amount']['currency_code'] = $this->escape($this->get('currency')->getCode());
$purchaseUnits['amount']['breakdown']['item_total']['value'] = $purchaseUnits['amount']['value'];
$purchaseUnits['amount']['breakdown']['item_total']['currency_code'] = $purchaseUnits['amount']['currency_code'];

$purchaseUnits['invoice_id'] = substr($order->getInvoiceFilename(),0, strrpos($order->getInvoiceFilename(), '_'));
?>

<script src="https://www.paypal.com/sdk/js?client-id=<?=urlencode($this->get('settings')->getClientID()) ?>&currency=<?=urlencode($this->get('currency')->getCode()) ?>"></script>

<div id="paypal-button-container"></div>

<script>
    paypal.Buttons({
        // Sets up the transaction when a payment button is clicked
        createOrder: (data, actions) => {
            return actions.order.create({
                purchase_units: [<?=json_encode($purchaseUnits) ?>]
            });
        },
        // Finalize the transaction after payer approval
        onApprove: (data, actions) => {
            return actions.order.capture().then(function(orderData) {
                // Successful capture! For dev/demo purposes:
                console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                const transaction = orderData.purchase_units[0].payments.captures[0];
                const element = document.getElementById('paypal-button-container');
                element.innerHTML = '<h3>Thank you for your payment!</h3>';
            });
        }
    }).render('#paypal-button-container');

    const $this = $('#orderDetails');

    $this.on('show.bs.collapse', function () {
        $('#orderHeading').find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
    })

    $this.on('hide.bs.collapse', function () {
        $('#orderHeading').find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
    })
</script>