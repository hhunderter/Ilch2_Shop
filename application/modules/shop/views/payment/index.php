<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">
<?php
    $itemsMapper = $this->get('itemsMapper');
    $order = $this->get('order');
    $body = json_encode([
        'selector' => $this->get('order')->getSelector(),
        'order' => $this->get('order')->getOrder(),
    ]);
?>

<h1>
    <?=$this->getTrans('menuPayment') ?>
</h1>

<div class="panel panel-default">
    <div class="panel-heading" id="orderHeading" data-toggle="collapse" data-target="#orderDetails"><?=$this->getTrans('paymentPanelHeading', substr($order->getInvoiceFilename(),0,strrpos($order->getInvoiceFilename(), '_')), $order->getDatetimeInvoiceSent(), $order->getDatetime()) ?><span class="pull-right clickable"><i class="fa fa-chevron-down"></i></span></div>
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
                    $itemImg = $itemsMapper->getShopById($itemId)->getImage();
                    $itemName = $itemsMapper->getShopById($itemId)->getName();
                    $itemNumber = $itemsMapper->getShopById($itemId)->getItemnumber();
                    $itemPrice = $itemsMapper->getShopById($itemId)->getPrice();
                    $itemTax = $itemsMapper->getShopById($itemId)->getTax();
                    $itemPriceWithoutTax = round(($itemPrice / (100 + $itemTax)) * 100, 2);
                    $arrayShippingCosts[] = $itemsMapper->getShopById($itemId)->getShippingCosts();
                    $itemShippingTime = $itemsMapper->getShopById($itemId)->getShippingTime();
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
                    <?php $subtotal_price += round($itemPrice * $orderItem['quantity'], 2); ?>
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

<script src="https://www.paypal.com/sdk/js?client-id=<?=$this->get('settings')->getClientID() ?>&currency=<?=$this->get('currency')->getCode() ?>"></script>

<div id="paypal-button-container"></div>
<script>
    paypal.Buttons({
        // Order is created on the server and the order id is returned
        createOrder: (data, actions) => {
            return fetch("/api/orders", {
                method: "post",
                body: <?=$body ?>
            })
                .then((response) => response.json())
                .then((order) => order.id);
        },
        // Finalize the transaction on the server after payer approval
        onApprove: (data, actions) => {
            return fetch(`/api/orders/${data.orderID}/capture`, {
                method: "post",
            })
                .then((response) => response.json())
                .then((orderData) => {
                    // Successful capture! For dev/demo purposes:
                    console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                    const transaction = orderData.purchase_units[0].payments.captures[0];
                    alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
                    // When ready to go live, remove the alert and show a success message within this page. For example:
                    // const element = document.getElementById('paypal-button-container');
                    // element.innerHTML = '<h3>Thank you for your payment!</h3>';
                    // Or go to another URL:  actions.redirect('thank_you.html');
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
