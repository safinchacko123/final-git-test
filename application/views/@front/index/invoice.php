

<?php echo isset($pdfStyleSheetLinks)?$pdfStyleSheetLinks:''; ?>


<div class="container">
	

    <div class="row">
		<div class="col-xs-12 text-right invoice-control"> 
			<a href="<?php echo site_url('invoice/'.$this->uri->segment(2).'/1'); ?>">Download Invoice</a> 
			<!-- <a onclick="window.print();" href="javascript:void(0)">Print Invoice </a> </div>-->
		</div>   		
        <div class="col-xs-12">
			<div class="row">
				<div class="invoice-title col-xs-6">
					<h2>Invoice</h2>
				</div>
				<div class="invoice-title col-xs-6">
					<h3 class="pull-right">Invoice no. <?php echo isset($orderResult['order_number'])?$orderResult['order_number']:''; ?></h3>
				</div>
			</div>	
    		<hr>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    				<strong>Billed To:</strong><br>
    					<?php echo isset($orderResult['bill_firstname'])?$orderResult['bill_firstname']:''; ?> <?php echo isset($orderResult['bill_lastname'])?$orderResult['bill_lastname']:''; ?><br>
    					<?php echo isset($orderResult['bill_address1'])?$orderResult['bill_address1']:''; ?><br>
    					<?php echo isset($orderResult['bill_address2'])?$orderResult['bill_address2'].'<br>':''; ?>
    					<?php echo isset($orderResult['bill_city'])?$orderResult['bill_city']:''; ?>, <?php echo isset($orderResult['bill_zip'])?$orderResult['bill_zip']:''; ?> 
    				</address>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
        			<strong>Shipped To:</strong><br>
    					<?php echo isset($orderResult['ship_firstname'])?$orderResult['ship_firstname']:''; ?> <?php echo isset($orderResult['bill_lastname'])?$orderResult['bill_lastname'].'<br>':''; ?>
    					<?php echo isset($orderResult['ship_address1'])?$orderResult['ship_address1'].'<br>':''; ?>
    					<?php echo isset($orderResult['ship__address2'])?$orderResult['bill_address2'].'<br>':''; ?>
    					<?php echo isset($orderResult['ship_city'])?$orderResult['ship_city']:''; ?>, <?php echo isset($orderResult['bill_zip'])?$orderResult['bill_zip']:''; ?> 

    				</address>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-xs-6">

    				<address>
    					<strong>Order Date:</strong><br>
    					<?php echo date('M d, Y',strtotime($orderResult['ordered_on'])); ?>  <br><br>
    				</address>

    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
<!--
    					<strong>Order Date:</strong><br>
    					<?php echo date('M d, Y',strtotime($orderResult['ordered_on'])); ?>  <br><br>
-->
    				</address>
    			</div>
    		</div>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong>Order summary</strong></h3>
    			</div>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed">
    						<thead>
                                <tr>
        							<td><strong>Item</strong></td>
        							<td class="text-center"><strong>Price</strong></td>
        							<td class="text-center"><strong>Quantity</strong></td>
        							<td class="text-right"><strong>Totals</strong></td>
                                </tr>
    						</thead>
    						<tbody>
    							<!-- foreach ($order->lineItems as $line) or some such thing here -->
    							<?php $subTotal = array();
									foreach($orderItemResult as $orderItem) {
									
									$productDet = unserialize($orderItem['contents']);
									//echo '<pre>'; print_r($orderItem); echo '</pre>';
    							 ?>
    							<tr>
    								<td><?php echo $productDet['name']; ?></td>
    								<td class="text-center"><?php echo config_item('myCurrency'); ?><?php echo number_format($productDet['price'],2,".",","); ?></td>
    								<td class="text-center"><?php echo $orderItem['quantity']; ?></td>
    								<?php 	$total = $productDet['price']*$orderItem['quantity'];
											$subTotal[] = $total;
    								 ?>
    								<td class="text-right"><?php echo config_item('myCurrency'); ?><?php  echo number_format($total,2,".",","); ?></td>
    							</tr>
    							<?php } ?>
                                
    							<tr>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line text-center"><strong>Subtotal</strong></td>
    								<td class="thick-line text-right"><?php echo config_item('myCurrency'); ?><?php echo number_format(array_sum($subTotal),2,".",","); ?></td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>Shipping</strong></td>
    								<td class="no-line text-right"><?php echo config_item('myCurrency'); ?><?php echo  $orderResult['shipping']; ?></td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>Grant Total</strong></td>
    								<td class="no-line text-right"><?php echo config_item('myCurrency'); ?><?php echo number_format(array_sum($subTotal)+$orderResult['shipping'],2,".",","); ?></td>
    							</tr>
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>
