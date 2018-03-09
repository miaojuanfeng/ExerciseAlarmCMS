<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Quotation management</title>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery-ui.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/chosen.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css"'); ?>" media="all">
		
		<script src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/jquery-ui.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/jquery-ui.multidatespicker.js'); ?>"></script>
		<!-- <script src="<?php echo base_url('assets/js/modernizr-custom.min.js'); ?>"></script> -->
		<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/chosen.jquery.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.maskedinput.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/accounting.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.validate.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/additional-methods.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/echarts.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/function.js'); ?>"></script>

		<script>
		$(function(){
			$('input[name="quotation_id"]').focus();

			/* pagination */
			$('.pagination-area>a, .pagination-area>strong').addClass('btn btn-sm btn-primary');
			$('.pagination-area>strong').addClass('disabled');

			/*--------- date mask ---------*/
			$('.date-mask').mask('9999-99-99');

			/* quotationitem-insert-btn */
			$(document).on('click', '.quotationitem-insert-btn', function(){
				add_quotationitem_row();
			});

			/* quotationitem-delete-btn */
			$(document).on('click', '.quotationitem-delete-btn', function(){
				if(confirm('Confirm delete?')){
					$(this).closest('tr').remove();
					calc();
				}else{
					return false;
				}
			});

			/* client loader */
			<?php if($this->router->fetch_method() == 'insert' && isset($this->uri->uri_to_assoc()['quotation_client_id'])){ ?>
			client_loader();
			<?php } ?>
			$(document).on('change', 'select[name="quotation_client_id"]', function(){
				client_loader();
			});

			/* product loader */
			$(document).on('change', 'select[name="quotationitem_product_id[]"]', function(){
				product_loader($(this));
			});

			/* index_part_number */
			document_display_number();
			$(document).on('change', 'select[name="quotation_display_number"]', function(){
				document_display_number();
			});

			/* terms loader */
			$(document).on('change', 'select[name="remark"]', function(){
				$('.scriptLoader').load('/topexcellent/load', {'thisTableId': 'termsLoader', 'thisTableField': 'quotation_remark', 'thisRecordId': $(this).val(), 't': timestamp()}, function(){
					termsLoader();
				});
			});
			$(document).on('change', 'select[name="warranty"]', function(){
				$('.scriptLoader').load('/topexcellent/load', {'thisTableId': 'termsLoader', 'thisTableField': 'quotation_warranty', 'thisRecordId': $(this).val(), 't': timestamp()}, function(){
					termsLoader();
				});
			});
			$(document).on('change', 'select[name="delivery"]', function(){
				$('.scriptLoader').load('/topexcellent/load', {'thisTableId': 'termsLoader', 'thisTableField': 'quotation_delivery', 'thisRecordId': $(this).val(), 't': timestamp()}, function(){
					termsLoader();
				});
			});
			$(document).on('change', 'select[name="payment"]', function(){
				$('.scriptLoader').load('/topexcellent/load', {'thisTableId': 'termsLoader', 'thisTableField': 'quotation_payment', 'thisRecordId': $(this).val(), 't': timestamp()}, function(){
					termsLoader();
				});
			});

			/* trigger calc */
			$(document).on('blur', 'input[name="quotationitem_product_price[]"]', function(){
				calc();
			});
			$(document).on('blur', 'input[name="quotationitem_quantity[]"]', function(){
				calc();
			});
			$(document).on('blur', 'input[name="quotation_discount"]', function(){
				calc();
			});
			$(document).on('change', 'select[name="quotation_currency"]', function(){
				$.each($('select[name="quotationitem_product_id[]"]'), function(key, val){
					product_loader($(this));
				});
			});

			/* up & down btn */
			$(document).on('click', '.up-btn', function(){
				if($(this).closest('tr').index() > 0){
					$('table.list tbody tr').eq($(this).closest('tr').index()).after($('table.list tbody tr').eq($(this).closest('tr').index() - 1));
				}
			});
			$(document).on('click', '.down-btn', function(){
				if($('table.list tbody tr').length > $(this).closest('tr').index()){
					$('table.list tbody tr').eq($(this).closest('tr').index()).before($('table.list tbody tr').eq($(this).closest('tr').index() + 1));
				}
			});

			/* textarea auto height */
			textarea_auto_height();
			$(document).on('keyup', 'textarea', function(){
				textarea_auto_height();
			});

			/* document auto zoom */
			document_auto_zoom();
			$(window).resize(function(){
				document_auto_zoom();
			});			
		});

		function document_display_number(){
			$('.index_number').css('display', 'none');
			$('.part_number').css('display', 'none');
			$('.' + $('select[name="quotation_display_number"]').val()).fadeIn();
		}

		function document_auto_zoom(){
			$('.document-a4').css('zoom', $('.document-area').width() / 785);
		}

		function client_loader(){
			$('.scriptLoader').load('/topexcellent/load', {'thisTableId': 'clientLoader', 'thisRecordId': $('select[name="quotation_client_id"]').val(), 't': timestamp()}, function(){
				clientLoader();
			});
		}

		function product_loader(thisObject){
			thisRow = $(thisObject).closest('tr').index();
			thisCurrency = $('select[name="quotation_currency"]').val();
			$('.scriptLoader').load('/topexcellent/load', {'thisTableId': 'productLoader', 'thisRecordId': $(thisObject).val(), 'thisCurrency': thisCurrency, 'thisRow': thisRow, 't': timestamp()}, function(){
				productLoader();
				textarea_auto_height();
				calc();
			});
		}

		function textarea_auto_height(){
			$.each($('textarea'), function(key, val){
				$(this).attr('rows', $(this).val().split('\n').length + 1);
			});
		}

		function calc(){
			var total = 0;
			$.each($('table.list tbody tr'), function(key, val){
				$(this).find('input[name="quotationitem_subtotal[]"]').val($(this).find('input[name="quotationitem_product_price[]"]').val() * $(this).find('input[name="quotationitem_quantity[]"]').val()).css('display', 'none').fadeIn();
				total += parseInt($(this).find('input[name="quotationitem_subtotal[]"]').val());
			});
			$('input[name="quotation_total"]').val(total - parseInt($('input[name="quotation_discount"]').val())).css('display', 'none').fadeIn();
		}

		function check_delete(id){
			var answer = prompt("Confirm delete?");
			if(answer){
				$('input[name="quotation_id"]').val(id);
				$('input[name="quotation_delete_reason"]').val(encodeURI(answer));
				$('form[name="list"]').submit();
			}else{
				return false;
			}
		}

		<?php if($this->router->fetch_method() == 'update' || $this->router->fetch_method() == 'insert' || $this->router->fetch_method() == 'duplicate'){ ?>
		function add_quotationitem_row(){
			quotationitem_row = '';
			quotationitem_row += '<tr>';
			quotationitem_row += '<td>';
			quotationitem_row += '<div>';
			quotationitem_row += '<input name="quotationitem_id[]" type="hidden" value="" />';
			quotationitem_row += '<input name="quotationitem_quotation_id[]" type="hidden" value="" />';
			quotationitem_row += '<input name="quotationitem_product_type_name[]" type="hidden" value="" />';
			quotationitem_row += '<input id="quotationitem_product_code" name="quotationitem_product_code[]" type="text" class="form-control input-sm" placeholder="Code" value="" />';
			quotationitem_row += '</div>';
			// quotationitem_row += '<div class="margin-top-10">';
			// quotationitem_row += '<a class="btn btn-sm btn-primary quotationitem-delete-btn" data-toggle="tooltip" title="Delete">';
			// quotationitem_row += '<i class="glyphicon glyphicon-remove"></i>';
			// quotationitem_row += '</a>';
			// quotationitem_row += '</div>';
			quotationitem_row += '<div class="margin-top-10">';
			quotationitem_row += '<div class="btn-group">';
			quotationitem_row += '<button type="button" class="btn btn-sm btn-primary quotationitem-delete-btn"><i class="glyphicon glyphicon-remove"></i></button>';
			quotationitem_row += '<button type="button" class="btn btn-sm btn-primary up-btn"><i class="glyphicon glyphicon-chevron-up"></i></button>';
			quotationitem_row += '<button type="button" class="btn btn-sm btn-primary down-btn"><i class="glyphicon glyphicon-chevron-down"></i></button>';
			quotationitem_row += '</div>';
			quotationitem_row += '</div>';
			quotationitem_row += '</td>';
			quotationitem_row += '<td>';
			quotationitem_row += '<div>';
			quotationitem_row += '<select id="quotationitem_product_id" name="quotationitem_product_id[]" data-placeholder="Product" class="chosen-select">';
			quotationitem_row += '<option value></option>';
			<?php foreach($products as $key1 => $value1){ ?>
			quotationitem_row += '<option value="<?=$value1->product_id?>"><?=$value1->product_code.' - '.$value1->product_name?></option>';
			<?php } ?>
			quotationitem_row += '</select>';
			quotationitem_row += '</div>';
			quotationitem_row += '<div>';
			quotationitem_row += '<input id="quotationitem_product_name" name="quotationitem_product_name[]" type="text" class="form-control input-sm" placeholder="Name" value="" />';
			quotationitem_row += '</div>';
			quotationitem_row += '<div>';
			quotationitem_row += '<textarea id="quotationitem_product_detail" name="quotationitem_product_detail[]" class="form-control input-sm" placeholder="Detail"></textarea>';
			quotationitem_row += '</div>';
			quotationitem_row += '</td>';
			quotationitem_row += '<td>';
			quotationitem_row += '<input id="quotationitem_product_price" name="quotationitem_product_price[]" type="text" class="form-control input-sm" placeholder="Price" value="" />';
			quotationitem_row += '</td>';
			quotationitem_row += '<td>';
			quotationitem_row += '<input id="quotationitem_quantity" name="quotationitem_quantity[]" type="text" class="form-control input-sm" placeholder="Quantity" value="1" />';
			quotationitem_row += '</td>';
			quotationitem_row += '<td>';
			quotationitem_row += '<input readonly="readonly" id="quotationitem_subtotal" name="quotationitem_subtotal[]" type="text" class="form-control input-sm" placeholder="Subtotal" value="" />';
			quotationitem_row += '</td>';
			quotationitem_row += '</tr>';

			$('table.list tbody').append(quotationitem_row);
			$('.chosen-select').chosen();
		}
		<?php } ?>
		</script>
	</head>

	<body>

		<?php $this->load->view('inc/header-area.php'); ?>

		








































		<?php if($this->router->fetch_method() == 'update' || $this->router->fetch_method() == 'insert' || $this->router->fetch_method() == 'duplicate'){ ?>
		<div class="content-area">

			<div class="container-fluid">
				<div class="row">

					<h2 class="col-sm-12"><a href="<?=base_url('quotation')?>">Quotation management</a> > <?=($this->router->fetch_method() == 'update') ? 'Update' : 'Insert'?> quotation</h2>

					<div class="col-sm-12">
						<form method="post" enctype="multipart/form-data">
							<input type="hidden" name="quotation_id" value="<?=$quotation->quotation_id?>" />
							<input type="hidden" name="quotation_version" value="<?=$quotation->quotation_version?>" />
							<input type="hidden" name="quotation_serial" value="<?=$quotation->quotation_serial?>" />
							<input type="hidden" name="referrer" value="<?=$this->agent->referrer()?>" />
							<div class="fieldset">
								<div class="row">
									
									<div class="col-sm-3 col-xs-12 pull-right">
										<blockquote>
											<h4 class="corpcolor-font">Instructions</h4>
											<p><span class="highlight">*</span> is a required field</p>
										</blockquote>
										<h4 class="corpcolor-font">Setting</h4>
										<p class="form-group">
											<label for="quotation_project_name">Project name <span class="highlight">*</span></label>
											<input id="quotation_project_name" name="quotation_project_name" type="text" class="form-control input-sm required" placeholder="Project name" value="<?=$quotation->quotation_project_name?>" />
										</p>
										<!-- <p class="form-group">
											<label for="quotation_language">Language</label>
											<select id="quotation_language" name="quotation_language" data-placeholder="Language" class="chosen-select required">
												<option value></option>
												<?php
												if($quotation->quotation_language == ''){
													$quotation->quotation_language = 'en';
												}
												foreach($languages as $key => $value){
													$selected = ($value->language_name == $quotation->quotation_language) ? ' selected="selected"' : "" ;
													echo '<option value="'.$value->language_name.'"'.$selected.'>'.strtoupper($value->language_name).'</option>';
												}
												?>
											</select>
										</p> -->
										<p class="form-group">
											<label for="quotation_currency">Currency</label>
											<select id="quotation_currency" name="quotation_currency" data-placeholder="Currency" class="chosen-select required">
												<option value></option>
												<?php
												if($quotation->quotation_currency == ''){
													$quotation->quotation_currency = 'hkd';
												}
												foreach($currencys as $key => $value){
													$selected = ($value->currency_name == $quotation->quotation_currency) ? ' selected="selected"' : "" ;
													echo '<option value="'.$value->currency_name.'"'.$selected.'>'.strtoupper($value->currency_name).'</option>';
												}
												?>
											</select>
										</p>
										<p class="form-group">
											<label for="quotation_display_number">Index number / Part number</label>
											<select id="quotation_display_number" name="quotation_display_number" data-placeholder="Index number / Part number" class="chosen-select required">
												<option value></option>
												<?php
												if($quotation->quotation_display_number == ''){
													$quotation->quotation_display_number = 'index_number';
												}
												foreach($display_numbers as $key => $value){
													$selected = ($value->display_number_name == $quotation->quotation_display_number) ? ' selected="selected"' : "" ;
													echo '<option value="'.$value->display_number_name.'"'.$selected.'>'.strtoupper($value->display_number_name).'</option>';
												}
												?>
											</select>

										</p>
										<p class="form-group">
											<label for="attachment">Business registration</label>
											<input id="attachment" name="attachment" type="file" class="form-control input-sm" placeholder="Business registration" accept="image/*" />
										</p>
									</div>
									<div class="col-sm-9 col-xs-12">
										<h4 class="corpcolor-font">Quotation</h4>
										<div class="row">
											<div class="col-sm-6 col-xs-6">
												<table class="table table-condensed table-borderless">
													<tr>
														<td colspan="2">
															<select id="quotation_client_id" name="quotation_client_id" data-placeholder="Client" class="chosen-select">
																<option value></option>
																<?php
																foreach($clients as $key1 => $value1){
																	$selected = ($value1->client_id == $quotation->quotation_client_id) ? ' selected="selected"' : "" ;
																	echo '<option value="'.$value1->client_id.'"'.$selected.'>'.$value1->client_firstname.' '.$value1->client_lastname.'</option>';
																}
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td><label for="quotation_client_company_name">To</label></td>
														<td><input id="quotation_client_company_name" name="quotation_client_company_name" type="text" class="form-control input-sm required" placeholder="Company/Domain/Client" value="<?=$quotation->quotation_client_company_name?>" /></td>
													</tr>
													<tr>
														<td><label for="quotation_client_company_address">Address</label></td>
														<td><textarea id="quotation_client_company_address" name="quotation_client_company_address" class="form-control input-sm" placeholder="Address"><?=$quotation->quotation_client_company_address?></textarea></td>
													</tr>
													<tr>
														<td><label for="quotation_client_company_phone">Phone</label></td>
														<td><input id="quotation_client_company_phone" name="quotation_client_company_phone" type="text" class="form-control input-sm" placeholder="Phone" value="<?=$quotation->quotation_client_company_phone?>" /></td>
													</tr>
													<tr>
														<td><label for="quotation_client_phone">Mobile</label></td>
														<td><input id="quotation_client_phone" name="quotation_client_phone" type="text" class="form-control input-sm" placeholder="Fax" value="<?=$quotation->quotation_client_phone?>" /></td>
													</tr>
													<tr>
														<td><label for="quotation_client_name">Attn</label></td>
														<td><input id="quotation_client_name" name="quotation_client_name" type="text" class="form-control input-sm required" placeholder="Attn." value="<?=$quotation->quotation_client_name?>" /></td>
													</tr>
												</table>
											</div>
											<div class="col-sm-1 col-xs-1">
											</div>
											<div class="col-sm-5 col-xs-5">
												<table class="table table-condensed table-borderless">
													<tr>
														<td><label for="quotation_number">Quotation#</label></td>
														<td>
															<div class="input-group">
																<input readonly="readonly" id="quotation_number" name="quotation_number" type="text" class="form-control input-sm" placeholder="Quotation#" value="<?=$quotation->quotation_number?>" />
																<span class="input-group-addon"><?='v'.$quotation->quotation_version?></span>
															</div>
														</td>
													</tr>
													<tr>
														<td><label for="quotation_issue">Date</label></td>
														<td><input id="quotation_issue" name="quotation_issue" type="text" class="form-control input-sm date-mask required" placeholder="Issue date" value="<?=($quotation->quotation_issue != '') ? $quotation->quotation_issue : date('Y-m-d')?>" /></td>
													</tr>
													<tr>
														<td><label for="quotation_user_name">Sales</label></td>
														<td><input id="quotation_user_name" name="quotation_user_name" type="text" class="form-control input-sm required" placeholder="Saleman" value="<?=$user->user_name?>" /></td>
													</tr>
													<!-- <tr>
														<td><label for="quotation_user_phone">Phone</label></td>
														<td><input id="quotation_user_phone" name="quotation_user_phone" type="text" class="form-control input-sm required" placeholder="Phone" value="<?=$user->user_phone?>" /></td>
													</tr>
													<tr>
														<td><label for="quotation_user_email">Email</label></td>
														<td><input id="quotation_user_email" name="quotation_user_email" type="text" class="form-control input-sm required" placeholder="Email" value="<?=$user->user_email?>" /></td>
													</tr> -->
													<!-- <tr>
														<td><label for="quotation_terms_id">Payment Terms</label></td>
														<td>
															<select id="quotation_terms_id" name="quotation_terms_id" data-placeholder="Terms" class="chosen-select required">
																<option value></option>
																<?php
																foreach($terms as $key1 => $value1){
																	$selected = ($value1->terms_id == $quotation->quotation_terms_id) ? ' selected="selected"' : "" ;
																	echo '<option value="'.$value1->terms_id.'"'.$selected.'>'.$value1->terms_name.'</option>';
																}
																?>
															</select>
														</td>
													</tr> -->
													<tr>
														<td><label for="quotation_terms">Payment terms</label></td>
														<td><input id="quotation_terms" name="quotation_terms" type="text" class="form-control input-sm required" placeholder="Payment terms" value="<?=$quotation->quotation_terms?>" /></td>
													</tr>
													<tr>
														<td><label for="quotation_expire">Expire Date</label></td>
														<td><input id="quotation_expire" name="quotation_expire" type="text" class="form-control input-sm date-mask" placeholder="Expire Date" value="<?=($quotation->quotation_expire != '') ? $quotation->quotation_expire : date('Y-m-d', strtotime('+14 days', time()))?>" /></td>
													</tr>
												</table>
											</div>
										</div>
										<div class="list-area">
											<table class="table list" id="quotation">
												<thead>
													<tr>
														<th width="10%">
															<a class="btn btn-sm btn-primary quotationitem-insert-btn" data-toggle="tooltip" title="Insert">
																<i class="glyphicon glyphicon-plus"></i>
															</a>
														</th>
														<th>Detail</th>
														<th width="12%">Price</th>
														<th width="8%">Quantity</th>
														<th width="12%">Subtotal</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach($quotationitems as $key => $value){ ?>
													<tr>
														<td>
															<div>
																<input name="quotationitem_id[]" type="hidden" value="<?=$value->quotationitem_id?>" />
																<input name="quotationitem_quotation_id[]" type="hidden" value="<?=$value->quotationitem_quotation_id?>" />
																<input name="quotationitem_product_type_name[]" type="hidden" value="<?=$value->quotationitem_product_type_name?>" />
																<input id="quotationitem_product_code" name="quotationitem_product_code[]" type="text" class="form-control input-sm" placeholder="Code" value="<?=$value->quotationitem_product_code?>" />
															</div>
															<div class="margin-top-10">
																<div class="btn-group">
																	<button type="button" class="btn btn-sm btn-primary quotationitem-delete-btn"><i class="glyphicon glyphicon-remove"></i></button>
																	<button type="button" class="btn btn-sm btn-primary up-btn"><i class="glyphicon glyphicon-chevron-up"></i></button>
																	<button type="button" class="btn btn-sm btn-primary down-btn"><i class="glyphicon glyphicon-chevron-down"></i></button>
																</div>
															</div>
														</td>
														<td>
															<div>
																<select id="quotationitem_product_id" name="quotationitem_product_id[]" data-placeholder="Product" class="chosen-select">
																	<option value></option>
																	<?php
																	foreach($products as $key1 => $value1){
																		$selected = ($value1->product_id == $value->quotationitem_product_id) ? ' selected="selected"' : "" ;
																		echo '<option value="'.$value1->product_id.'"'.$selected.'>'.$value1->product_code.' - '.$value1->product_name.'</option>';
																	}
																	?>
																</select>
															</div>
															<div>
																<input id="quotationitem_product_name" name="quotationitem_product_name[]" type="text" class="form-control input-sm" placeholder="Name" value="<?=$value->quotationitem_product_name?>" />
															</div>
															<div>
																<textarea id="quotationitem_product_detail" name="quotationitem_product_detail[]" class="form-control input-sm" placeholder="Detail"><?=$value->quotationitem_product_detail?></textarea>
															</div>
														</td>
														<td>
															<input id="quotationitem_product_price" name="quotationitem_product_price[]" type="text" class="form-control input-sm" placeholder="Price" value="<?=$value->quotationitem_product_price?>" />
														</td>
														<td>
															<input id="quotationitem_quantity" name="quotationitem_quantity[]" type="text" class="form-control input-sm" placeholder="Quantity" value="<?=($value->quotationitem_quantity) ? $value->quotationitem_quantity : '1'?>" />
														</td>
														<td>
															<input readonly="readonly" id="quotationitem_subtotal" name="quotationitem_subtotal[]" type="text" class="form-control input-sm" placeholder="Subtotal" value="<?=$value->quotationitem_subtotal?>" />
														</td>
													</tr>
													<?php } ?>
												</tbody>
												<tfoot>
													<tr>
														<th></th>
														<th></th>
														<th></th>
														<th>Discount</th>
														<th><input id="quotation_discount" name="quotation_discount" type="text" class="form-control input-sm required" placeholder="Discount" value="<?=($quotation->quotation_discount) ? $quotation->quotation_discount : '0'?>" /></th>
													</tr>
													<tr>
														<th width="10%">
															<a class="btn btn-sm btn-primary quotationitem-insert-btn" data-toggle="tooltip" title="Insert">
																<i class="glyphicon glyphicon-plus"></i>
															</a>
														</th>
														<th></th>
														<th></th>
														<th>Grand total</th>
														<th><input readonly="readonly" id="quotation_total" name="quotation_total" type="text" class="form-control input-sm" placeholder="Grand total" value="<?=($quotation->quotation_total) ? $quotation->quotation_total : '0'?>" /></th>
													</tr>
												</tfoot>
											</table>
										</div>
										<div class="hr"></div>
										<p class="form-group">
											<label for="quotation_remark">Remark</label>
											<textarea id="quotation_remark" name="quotation_remark" class="form-control input-sm" placeholder="Remark"><?=$quotation->quotation_remark?></textarea>
										</p>
										<!-- <p class="form-group">
											<label for="quotation_warranty">Warranty</label>
											<textarea id="quotation_warranty" name="quotation_warranty" class="form-control input-sm" placeholder="Warranty"><?=$quotation->quotation_warranty?></textarea>
										</p>
										<p class="form-group">
											<label for="quotation_delivery">Delivery</label>
											<textarea id="quotation_delivery" name="quotation_delivery" class="form-control input-sm" placeholder="Delivery"><?=$quotation->quotation_delivery?></textarea>
										</p> -->
										<p class="form-group">
											<label for="quotation_payment">Payment</label>
											<textarea id="quotation_payment" name="quotation_payment" class="form-control input-sm" placeholder="Payment"><?=$quotation->quotation_payment?></textarea>
										</p>
									</div>
								</div>

								<div class="row">
									<div class="col-xs-12">
										<button type="submit" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
									</div>
								</div>

							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
		<?php } ?>

		











































		<?php if($this->router->fetch_method() == 'select'){ ?>
		<div class="content-area">

			<div class="container-fluid">
				<div class="row">

					<h2 class="col-sm-12">Quotation management</h2>

					<div class="content-column-area col-md-12 col-sm-12">

						<div class="fieldset">
							<div class="search-area">

								<form quotation="form" method="get">
									<input type="hidden" name="quotation_id" />
									<table>
										<tbody>
											<tr>
												<td width="90%">
													<div class="row">
														<div class="col-sm-2"><h6>Quotation</h6></div>
														<div class="col-sm-2">
															<input type="text" name="quotation_number_greateq" class="form-control input-sm" placeholder="QONo From" value="" />
														</div>
														<div class="col-sm-2">
															<input type="text" name="quotation_number_smalleq" class="form-control input-sm" placeholder="QONo To" value="" />
														</div>
														<div class="col-sm-2">
															<input type="text" name="quotation_create_greateq" class="form-control input-sm date-mask" placeholder="Date From (YYYY-MM-DD)" value="" />
														</div>
														<div class="col-sm-2">
															<input type="text" name="quotation_create_smalleq" class="form-control input-sm date-mask" placeholder="Date To (YYYY-MM-DD)" value="" />
														</div>
														<div class="col-sm-2">
															<select id="quotation_status" name="quotation_status" data-placeholder="Status" class="chosen-select">
																<option value></option>
																<?php foreach($statuss as $key => $value){ ?>
																<option value="<?=$value->status_name?>"><?=ucfirst($value->status_name)?></option>
																<?php } ?>
															</select>
														</div>
														<div class="col-sm-2"></div>
													</div>
													<div class="row">
														<div class="col-sm-2"><h6>Customer</h6></div>
														<div class="col-sm-2">
															<input type="text" name="quotation_client_company_name_like" class="form-control input-sm" placeholder="Customer company name" value="" />
														</div>
														<div class="col-sm-2">
															<select id="quotation_user_id" name="quotation_user_id" data-placeholder="Sales" class="chosen-select">
																<option value></option>
																<?php foreach($users as $key => $value){ ?>
																<option value="<?=$value->user_id?>"><?=ucfirst($value->user_name)?></option>
																<?php } ?>
															</select>
														</div>
														<div class="col-sm-2"></div>
														<div class="col-sm-2"></div>
														<div class="col-sm-2"></div>
														<div class="col-sm-2"></div>
													</div>
													<div class="row">
														<div class="col-sm-2"><h6>Project</h6></div>
														<div class="col-sm-2">
															<input type="text" name="quotation_project_name_like" class="form-control input-sm" placeholder="Project Name" value="" />
														</div>
														<div class="col-sm-2"></div>
														<div class="col-sm-2"></div>
														<div class="col-sm-2"></div>
														<div class="col-sm-2"></div>
														<div class="col-sm-2"></div>
													</div>
													<div class="row">
														<div class="col-sm-2"><h6>Product</h6></div>
														<div class="col-sm-2">
															<input type="text" name="quotationitem_product_code_like" class="form-control input-sm" placeholder="Item Code" value="" />
														</div>
														<div class="col-sm-2">
															<input type="text" name="quotationitem_product_name_like" class="form-control input-sm" placeholder="Item Name" value="" />
														</div>
														<div class="col-sm-2">
															<input type="text" name="quotationitem_product_detail_like" class="form-control input-sm" placeholder="Item Description" value="" />
														</div>
														<div class="col-sm-2"></div>
														<div class="col-sm-2"></div>
														<div class="col-sm-2"></div>
														<div class="col-sm-2"></div>
													</div>
												</td>
												<td valign="top" width="10%" class="text-right">
													<button type="submit" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Search">
														<i class="glyphicon glyphicon-search"></i>
													</button>
												</td>
											</tr>
										</tbody>
									</table>
								</form>

							</div> <!-- list-container -->
						</div>
						<div class="fieldset">

							<div class="list-area">
								<form name="list" action="<?=base_url('quotation/delete')?>" method="post">
									<input type="hidden" name="quotation_id" />
									<input type="hidden" name="quotation_delete_reason" />
									<div class="page-area">
										<span class="btn btn-sm btn-default"><?php print_r($num_rows); ?></span>
										<?=$this->pagination->create_links()?>
									</div>
									<table id="quotation" class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>Quotation#</th>
												<th>Create</th>
												<th>Customer</th>
												<th>Attn</th>
												<th>Project</th>
												<th>Sales</th>
												<th>Expiry date</th>
												<th>Status</th>
												<th>Currency</th>
												<th>Total</th>
												<th width="40"></th>
												<th width="40"></th>
												<th width="40"></th>
												<th width="40" class="text-right">
													<a href="<?=base_url('quotation/insert')?>" data-toggle="tooltip" title="Insert">
														<i class="glyphicon glyphicon-plus"></i>
													</a>
												</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($quotations as $key => $value){ ?>
											<tr>
												<td><a href="<?=base_url('quotation/update/quotation_id/'.$value->quotation_id)?>" data-toggle="tooltip" title="Update"><?=$value->quotation_number?>-v<?=$value->quotation_version?></a></td>
												<td><?=convert_datetime_to_date($value->quotation_create)?></td>
												<td><?=$value->quotation_client_company_name?></td>
												<td><?=$value->quotation_client_name?></td>
												<td><?=$value->quotation_project_name?></td>
												<td><?=ucfirst(get_user($value->quotation_user_id)->user_name)?></td>
												<td><?=convert_datetime_to_date($value->quotation_expire)?></td>
												<td><?=ucfirst($value->quotation_status)?></td>
												<td><?=strtoupper($value->quotation_currency)?></td>
												<td><?=money_format('%!n', $value->quotation_total)?></td>
												<td class="text-right">
													<a target="_blank" href="<?=base_url('/assets/images/pdf/quotation/'.$value->quotation_number.'-v'.$value->quotation_version)?>" data-toggle="tooltip" title="Print">
														<i class="glyphicon glyphicon-print"></i>
													</a>
												</td>
												<td class="text-right">
													<a href="<?=base_url('quotation/setting/quotation_id/'.$value->quotation_id)?>" data-toggle="tooltip" title="Setting">
														<i class="glyphicon glyphicon-cog"></i>
													</a>
												</td>
												<td class="text-right">
													<a href="<?=base_url('quotation/update/quotation_id/'.$value->quotation_id)?>" data-toggle="tooltip" title="Update">
														<i class="glyphicon glyphicon-edit"></i>
													</a>
												</td>
												<td class="text-right">
													<a onclick="check_delete(<?=$value->quotation_id?>);" data-toggle="tooltip" title="Remove">
														<i class="glyphicon glyphicon-remove"></i>
													</a>
												</td>
											</tr>
											<?php } ?>

											<?php if(!$quotations){ ?>
											<tr>
												<td colspan="14">No record found</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
									<div class="page-area">
										<span class="btn btn-sm btn-default"><?php print_r($num_rows); ?></span>
										<?=$this->pagination->create_links()?>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="blue">
						<p>DONE: if quotation create date > 2 months && draft, hidden</p>
						<p>DONE: quotation print view item show index number (1, 2, 3, 4....) or part number, can choose by user</p>
						<p>DONE: quotation/insert sub item function, server add spec</p>
						<p>DONE: quotation/update select payment terms</p>
						<p>DONE: quotation/update valid date</p>
						<p>DONE: Remove hourly rate</p>
						<p>DONE: Draft / confirm / cancel</p>
						<p>DONE: When delete the quotation, the change to cancel status</p>
						<p>DONE: Payment Term: Net 7 days, Net 14 days</p>
						<p>DONE: Expire date = Today + 14 days</p>
						<p>DONE: Attachment: BR</p>
						<p>DONE: Keep show remark, remove warranty, Delivery remove, Payment if exist will show on document</p>
						<p>DONE: Payment follow client, if A company "Net 7 days", B company "Net 14 days", C company "Net 30 days"</p>
						<p>DONE: One language only</p>
						<p>DONE: Duplicate button at setting page</p>
						<p>DONE: Preview PDF button</p>
					</div>
				</div>
			</div>

		</div>
		<?php } ?>

		








































		<?php if($this->router->fetch_method() == 'setting'){ ?>
		<div class="content-area">

			<div class="container-fluid">
				<div class="row">

					<h2 class="col-sm-12"><a href="<?=base_url('quotation')?>">Quotation management</a> > Quotation setting</h2>

					<div class="col-sm-12">
						<form method="post" enctype="multipart/form-data">
							<input type="hidden" name="quotation_id" value="<?=$quotation->quotation_id?>" />
							<input type="hidden" name="quotation_number" value="<?=$quotation->quotation_number?>" />
							<input type="hidden" name="quotation_version" value="<?=$quotation->quotation_version?>" />
							<input type="hidden" name="referrer" value="<?=$this->agent->referrer()?>" />
							<div class="fieldset">
								<div class="row">
									
									<div class="col-sm-3 col-xs-12 pull-right">
										<blockquote>
											<h4 class="corpcolor-font">Instructions</h4>
											<p><span class="highlight">*</span> is a required field</p>
										</blockquote>
										<h4 class="corpcolor-font">Setting</h4>
										<p class="form-group">
											<label for="quotation_project_name">Project name <span class="highlight">*</span></label>
											<input id="quotation_project_name" name="quotation_project_name" type="text" class="form-control input-sm required" placeholder="Project name" value="<?=$quotation->quotation_project_name?>" />
										</p>
										<p class="form-group">
											<label for="quotation_display_number">Index number / Part number</label>
											<select id="quotation_display_number" name="quotation_display_number" data-placeholder="Index number / Part number" class="chosen-select required">
												<option value></option>
												<?php
												if($quotation->quotation_display_number == ''){
													$quotation->quotation_display_number = 'index_number';
												}
												foreach($display_numbers as $key => $value){
													$selected = ($value->display_number_name == $quotation->quotation_display_number) ? ' selected="selected"' : "" ;
													echo '<option value="'.$value->display_number_name.'"'.$selected.'>'.strtoupper($value->display_number_name).'</option>';
												}
												?>
											</select>

										</p>
										<p class="form-group">
											<label for="attachment">Business registration</label>
											<input id="attachment" name="attachment" type="file" class="form-control input-sm" placeholder="Business registration" accept="image/*" />
										</p>
										<p class="form-group">
											<a href="<?=base_url('salesorder')?>" class="btn btn-sm btn-primary btn-block"><i class="glyphicon glyphicon-ok"></i> Confirm & convert to Sales Order</a>
										</p>
										<p class="form-group">
											<a class="btn btn-sm btn-primary btn-block" href="<?=base_url('quotation/duplicate/quotation_id/'.$quotation->quotation_id)?>" data-toggle="tooltip" title="Duplicate"><i class="glyphicon glyphicon-duplicate"></i> Duplicate</a>
										</p>
									</div>
									<div class="col-sm-9 col-xs-12">
										<h4 class="corpcolor-font">Quotation</h4>
										<p class="blue">DONE: Preview the document here</p>
										<p class="blue">DONE: confirm button and convert to sales order automatically</p>
										
										<div class="document-area">
											<div class="document-a4">
												<div class="document-header">
													<table>
														<tr>
															<td>
																<h1 class="corpcolor-font">【T】Top Excellent Consultants Limited <small><b>Your Business Partner</b></small></h1>
															</td>
														</tr>
														<tr>
															<td align="right"><h2>Quotation</h2></td>
														</tr>
													</table>
												</div>
												<div class="document-information">
													<table>
														<tr>
															<td width="50%" valign="top">
																<table>
																	<tr>
																		<td valign="top" width="24%"><b>To</b></td>
																		<td width="76%"><?=$quotation->quotation_client_company_name?></td>
																	</tr>
																	<tr>
																		<td valign="top"><b>Address</b></td>
																		<td><?=$quotation->quotation_client_company_address?></td>
																	</tr>
																	<tr>
																		<td valign="top"><b>Tel</b></td>
																		<td><?=$quotation->quotation_client_phone?></td>
																	</tr>
																	<tr>
																		<td valign="top"><b>Mobile</b></td>
																		<td><?=$quotation->quotation_client_phone?></td>
																	</tr>
																	<tr>
																		<td valign="top"><b>Attn</b></td>
																		<td><?=$quotation->quotation_client_name?></td>
																	</tr>
																</table>
															</td>
															<td width="10%"></td>
															<td width="40%" valign="top">
																<table>
																	<tr>
																		<td width="40%"><b>Quotation No.</b></td>
																		<td width="60%"><?=$quotation->quotation_number?>-v<?=$quotation->quotation_version?></td>
																	</tr>
																	<tr>
																		<td><b>Date</b></td>
																		<td><?=$quotation->quotation_issue?></td>
																	</tr>
																	<tr>
																		<td><b>Sales</b></td>
																		<td><?=$quotation->quotation_user_name?></td>
																	</tr>
																	<tr>
																		<td><b>Payment Term</b></td>
																		<td><?=$quotation->quotation_terms?></td>
																	</tr>
																	<tr>
																		<td><b>Expire Date</b></td>
																		<td><?=$quotation->quotation_expire?></td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</div>
												<div class="document-detail document-br">
													<table>
														<tr class="document-separator-bottom">
															<td width="12%"><b>PART NO.</b></td>
															<td width="55%"><b>DESCRIPTION</b></td>
															<td width="15%" align="right"><b>UNIT PRICE</b></td>
															<td width="8%" align="center"><b>QTY</b></td>
															<td width="10%" align="right"><b>AMOUNT</b></td>
														</tr>
														<?php foreach($quotationitems as $key => $value){ ?>
														<tr class="padding-top-5">
															<td>
																<div class="index_number"><?=$key+1?></div>
																<div class="part_number"><?=$value->quotationitem_product_code?></div>
															</td>
															<td><b><?=$value->quotationitem_product_name?></b></td>
															<td align="right"><?=money_format('%!n', $value->quotationitem_product_price)?></td>
															<td align="center"><?=$value->quotationitem_quantity?></td>
															<td align="right"><?=money_format('%!n', $value->quotationitem_product_price * $value->quotationitem_quantity)?></td>
														</tr>
														<tr class="padding-bottom-5">
															<td></td>
															<td valign="top"><?=nl2br($value->quotationitem_product_detail)?></td>
															<td></td>
															<td></td>
															<td></td>
														</tr>
														<?php } ?>
														<tr>
															<td height="100%"></td>
															<td></td>
															<td></td>
															<td></td>
															<td></td>
														</tr>
														<tr class="document-separator-top">
															<td></td>
															<td></td>
															<td align="right"><b>GRAND TOTAL</b></td>
															<td align="center"><?=strtoupper($quotation->quotation_currency)?></td>
															<td align="right"><?=money_format('%!n', 0)?></td>
														</tr>
													</table>
												</div>
												<div class="document-terms document-br page-break-inside-avoid">
													<table>
														<tr>
															<td><b>TERMS AND CONDITIONS</b></td>
														</tr>
														<tr>
															<td>
																All the received payments are non-refundable.
																<br />Cheque(s) should be crossed & made payable to TOP EXCELLENT CONSULTANTS LIMITED.
																<br />This quotation is also an order
																<br />This quotationn will expired on above expired date or unless otherwise stated and subject to change without notice.
															</td>
														</tr>
													</table>
												</div>
												<div class="document-terms document-br page-break-inside-avoid">
													<table>
														<tr>
															<td><b>REMARK</b></td>
														</tr>
														<?php if($quotation->quotation_remark != ''){ ?>
														<tr>
															<td>
																<?=$quotation->quotation_remark?>
															</td>
														</tr>
														<?php } ?>
													</table>
												</div>
												<?php if($quotation->quotation_payment != ''){ ?>
												<div class="document-terms document-br page-break-inside-avoid">
													<table>
														<tr>
															<td><b>PAYMENT</b></td>
														</tr>
														<tr>
															<td>
																<?=$quotation->quotation_payment?>
															</td>
														</tr>
													</table>
												</div>
												<?php } ?>
												<div class="document-sign document-br page-break-inside-avoid">
													<table>
														<tr>
															<td width="40%">
																<div><b>Received By</b></div>
																<div><?=$quotation->quotation_client_company_name?></div>
																<div class="sign-area"></div>
																<div>Authority Signature & Co. Chop</div>
															</td>
															<td width="20%"></td>
															<td width="40%">
																<div><b>For and on behalf of</b></div>
																<div>Top Excellent Consultants Limited</div>
																<div class="sign-area"></div>
																<div>Authority Signature & Co. Chop</div>
															</td>
														</tr>
													</table>
												</div>
												<div class="document-terms document-br">
													<table>
														<tr>
															<td>
																Pleas e return the copy of this quotation with your signature and company chop as confirmation of the above offer.
																<br />Address: Flat D, 3/F, Fu Hop Factory Building, 209-211 Wai Yip Street, Kwun Tong,Kowloon, Hong Kong.Tel: 2709 0666 Fax: 2709 0669
															</td>
														</tr>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-xs-12">
										<button type="submit" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
									</div>
								</div>

							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
		<?php } ?>












































		<?php $this->load->view('inc/footer-area.php'); ?>

	</body>
</html>

<div class="scriptLoader"></div>