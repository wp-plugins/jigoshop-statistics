<?php
$args = array(
    'posts_per_page'     => -1,
    'orderby'         => 'post_date',
    'order'           => 'DESC',
    'post_type'       => 'shop_order',
    'post_status'     => 'publish' ,
    'suppress_filters' => false,
    'no_found_rows' => 1
);
$allorders = new WP_Query( $args );

$args = array(
    'posts_per_page'     => -1,
    'orderby'         => 'post_date',
    'order'           => 'DESC',
    'post_type'       => 'shop_order',
    'post_status'     => 'publish' ,
    'suppress_filters' => false,
    'no_found_rows' => 1,
    'monthnum' => date( 'n', current_time( 'timestamp' ) )
);
$monthorders = new WP_Query( $args );


?>

<div id="jigoshop_stats" class="wrap jigoshop">
    <div class="icon32 icon32-attributes jigoshop_icon"><br/></div>
	<h2><?php _e('Jigoshop Statistics','jigoshop'); ?></h2>
	<div id="jigoshop_dashboard">
		
		<div id="dashboard-widgets" class="metabox-holder">
		
			<div class="postbox-container" style="width:49%;">
			
				<div id="jigoshop_right_now" class="jigoshop_right_now postbox">
					<h3><?php _e('Sales Statistics', 'jigoshop') ?></h3>
					<div class="inside">

						<div class="table table_content table_third">
							<table cellpadding="0" cellspacing="0">
								<tbody>
									<tr class="first">
										<td class="row_title"><?php _e('Lifetime Sales', 'jigoshop'); ?></td>
										<td class="b">
											<?php
											
											$all_order_totals = 0;
											$order_sales = array();
											
											while ( $allorders->have_posts() ) : $allorders->the_post();
											
													$order_data = get_post_meta(get_the_ID(),'order_data');
													$order_data = $order_data[0];
													
													$status = wp_get_post_terms( get_the_ID(), 'shop_order_status', array("fields" => "names") );													$status = $status[0];
													
													if ($status != 'cancelled' && $status != 'refunded') {									
														
														$current_order_total = (float) $order_data['order_subtotal'];
														$current_order_shipping = (float) $order_data['order_shipping'];				
														$current_order_subtotal = $current_order_total - $current_order_shipping;
														
														$all_order_totals = $all_order_totals + $current_order_subtotal;
														$order_sales[] = $current_order_subtotal;
														
													}
													
											endwhile; wp_reset_postdata();
											
											echo '<a href="edit.php?post_type=shop_order"><span class="total-count">'.get_jigoshop_currency_symbol().number_format($all_order_totals, 2, '.', ',').'</a>';
											?>
										</td>
										
									</tr>
									
								
									<tr>
										<td class="row_title"><?php _e('Sales this Month', 'jigoshop'); ?></td>
										<td class="b">
											<?php
											
											$order_totals = 0;
											
											while ( $monthorders->have_posts() ) : $monthorders->the_post();
													
													$order_data = get_post_meta(get_the_ID(),'order_data');
													$order_data = $order_data[0];
													
													$status = wp_get_post_terms( get_the_ID(), 'shop_order_status', array("fields" => "names") );													$status = $status[0];
													
													if ($status != 'cancelled' && $status != 'refunded') {
													
														// echo $order_data->order_total.'<br>';
														
														$current_order_total = (float) $order_data['order_subtotal'];
														$current_order_shipping = (float) $order_data['order_shipping'];				
														$current_order_subtotal = $current_order_total - $current_order_shipping;
														
														$order_totals = $order_totals + $current_order_subtotal;
														
													}
													
											endwhile; wp_reset_postdata();
											
											//echo get_jigoshop_currency_symbol().number_format($order_totals);
											echo '<a href="edit.php?post_type=shop_order"><span class="total-count">'.get_jigoshop_currency_symbol().number_format($order_totals, 2, '.', ',').'</a>';
											?>												
										</td>
									</tr>
								
									<tr>
										<td class="row_title"><?php _e('Average Order Value', 'jigoshop'); ?></td>
										<td class="b">
											
											<?php 
												$countorders = count($order_sales);
												$average = ($all_order_totals) ? $all_order_totals/$countorders : 0;
												
												echo '<a href="edit.php?post_type=shop_order"><span class="total-count">'.get_jigoshop_currency_symbol().number_format($average, 2, '.', ',').'</a>';
											?>
																						
										</td>
									</tr>
								
									<tr>
										<td class="row_title"><?php _e('Total Number of Sales', 'jigoshop'); ?></td>
										<td class="b">
											
											<?php 												
												echo '<a href="edit.php?post_type=shop_order"><span class="total-count">'.$countorders.'</a>';
											?>
																						
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="clear"></div>
					</div>
				
				</div><!-- postbox end -->	
				
				
						
				
				
			
			</div>
			<div class="postbox-container" style="width:49%; float:right;">
				
				
				<div class="postbox">
					<h3 class="hndle" id="poststuff"><span><?php _e('Best Sellers', 'jigoshop') ?></span></h3>
					<div class="inside">							
						
						
						<?php
						
						$product_sales = array();
						
						while ( $allorders->have_posts() ) : $allorders->the_post();
			
								$order_data = get_post_meta(get_the_ID(),'order_data');
								$order_data = $order_data[0];
								
								$order_items = get_post_meta(get_the_ID(),'order_items');
								$order_items = $order_items[0];
								
								$status = wp_get_post_terms( get_the_ID(), 'shop_order_status', array("fields" => "names") );													$status = $status[0];
								
								if ($status != 'cancelled' && $status != 'refunded') {
									
									foreach($order_items as $order_item) {
										$curr_product_sales = ($product_sales[$order_item['id']]) ? $product_sales[$order_item['id']] : 0;
										$product_sales[$order_item['id']] = $curr_product_sales + (1 * $order_item['qty']);
									}
									
								}
								
						endwhile; wp_reset_postdata();
						
						arsort($product_sales);
						?>
						
						
						
						
						<?php
							if ($product_sales) :
								echo '<ul class="recent-orders">';
								$i = 0; foreach ($product_sales as $key => $value) :
									
									echo '
									<li>
										<span class="order-status">Sales: '.$value.'</span> <a href="post.php?post='.$key.'&action=edit">'.get_the_title($key).'</a><br />
										
									</li>';

								if($i == 4) { break; } else { $i++; } endforeach;
								echo '</ul>';
							endif;
						?>
					</div>
				</div><!-- postbox end -->	
				
				
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	#jigoshop_stats .inside { padding-top: 0; }
	.row_title {
		color: #777;
		font-style: italic;
		font-family: Georgia,"Times New Roman","Bitstream Charter",Times,serif;
	}
	#jigoshop_stats .table_third {
		width: 100%;
		float: left;
		clear: both;
		display: inline;
	}
	#jigoshop_stats p.sub {
		white-space: nowrap;
	}
	#jigoshop_stats .jigoshop_right_now td.b {
		text-align: left;
	}
	#jigoshop_dashboard div#totalsales div.inside {
		margin-top: 5px;
		padding-top: 0;
	}
	#jigoshop_dashboard ul.recent-orders li .order-status {
		margin-left: 15px;
	}
	
	#jigoshop_dashboard .jigoshop_right_now table td {
		border-bottom: #ECECEC 1px solid;
		padding: 8px 0;
		margin: 0 0 8px;
	}
	#jigoshop_dashboard .jigoshop_right_now table tr.first td {
		padding-top: 0;
	}
</style>