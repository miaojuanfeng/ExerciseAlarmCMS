<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Stock In Management</title>

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
      $('input[name="invoice_id"]').focus();

      /* pagination */
      $('.pagination-area>a, .pagination-area>strong').addClass('btn btn-sm btn-primary');
      $('.pagination-area>strong').addClass('disabled');

      /*--------- date mask ---------*/
      $('.date-mask').mask('9999-99-99');

      /* invoice-completed-btn */
      $(document).on('click', '.completed-btn', function(){
        if($(this).is(':checked')){
          $(this).parent().parent().find('input[name="invoice_account_remark[]"]').prop('disabled', false).focus();
        }else{
          $(this).parent().parent().find('input[name="invoice_account_remark[]"]').prop('disabled', true);
        }
      });
    });
    </script>
  </head>

  <body>

    <?php $this->load->view('inc/header-area.php'); ?>

    








































    <?php if($this->router->fetch_method() == 'update' || $this->router->fetch_method() == 'insert'){ ?>
    <div class="content-area">

      <div class="container-fluid">
        <div class="row">

          <h2 class="col-sm-12"><a href="<?=base_url('stock_out')?>">Stock Out Management</a> > <?=($this->router->fetch_method() == 'update') ? 'Upate' : 'Insert'?> Stock Out</h2>

          <div class="col-sm-12">
            <form method="post" enctype="multipart/form-data">
              <input type="hidden" name="stock_out_id" value="<?=$stock_out->stock_out_id?>" />
              <!-- <input type="hidden" name="z_stock_out_product_stock_out_id[]" value="<?=$stock_out->stock_out_id?>" /> -->
              <input type="hidden" name="referrer" value="<?=$this->agent->referrer()?>" />
              <div class="fieldset">
                <?=$this->session->tempdata('alert');?>
                <div class="row">
                  
                  <div class="col-sm-3 col-xs-12 pull-right">
                    <blockquote>
                      <h4 class="corpcolor-font">Instructions</h4>
                      <p><span class="highlight">*</span> is a required field</p>
                      <p>
                        <b>BOLD</b>: &lt;b&gt;content&lt;/b&gt;
                        <br /><b>ITALIC</b>: &lt;i&gt;content&lt;/i&gt;
                        <br /><b>UNDERLINE</b>: &lt;u&gt;content&lt;/u&gt;
                      </p>
                    </blockquote>
                    <!-- <h4 class="corpcolor-font">Setting</h4> -->
                    <!-- <p class="form-group">
                      <label for="stock_out_project_name">Project name <span class="highlight">*</span></label>
                      <input id="stock_out_project_name" name="stock_out_project_name" type="text" class="form-control input-sm required" placeholder="Project name" value="<?=$stock_out->stock_out_id?>" />
                    </p> -->
                    <!-- <p class="form-group">
                      <label for="stock_out_currency">Currency</label>
                      <select id="stock_out_currency" name="stock_out_currency" data-placeholder="Currency" class="chosen-select required">
                        <option value></option>
                      </select>
                    </p> -->
                    <!-- <p class="form-group">
                      <label for="stock_out_display_number">Index number / Part number</label>
                      <select id="stock_out_display_number" name="stock_out_display_number" data-placeholder="Index number / Part number" class="chosen-select required">
                        <option value></option>
                      </select>
                    </p>
                    <p class="form-group">
                      <label for="attachment">Business registration</label>
                      <input id="attachment" name="attachment" type="file" class="form-control input-sm" placeholder="Business registration" accept="image/*" />
                    </p> -->
                    <!-- <p class="form-group">
                      <label for="stock_out_status">Status</label>
                      <select id="stock_out_status" name="stock_out_status" data-placeholder="Status" class="chosen-select required">
                        <option value></option>
                      </select>
                    </p> -->
                  </div>
                  <div class="col-sm-9 col-xs-12">
                    <div class="row">
                      <div class="col-sm-6 col-xs-6">
                        <table class="table table-condensed table-borderless">
                          <tr>
                            <td colspan="2">
                              <select id="stock_out_booking_id" name="stock_out_booking_id" data-placeholder="Booking Number" class="chosen-select">
                                <option value></option>
                                <?php
                                foreach($bookings as $key1 => $value1){
                                  $selected = ($value1->booking_id == $stock_out->stock_out_booking_id || $value1->booking_id == $this->uri->uri_to_assoc()['stock_out_booking_id']) ? ' selected="selected"' : "" ;
                                  echo '<option value="'.$value1->booking_id.'"'.$selected.'>'.$value1->booking_number.'</option>';
                                }
                                ?>
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td><label for="stock_out_vendor_name">Person in Charge (if not from booking)</label></td>
                            <td><input id="stock_out_person" name="stock_out_person" type="text" class="form-control input-sm" placeholder="Name" value="<?=$stock_out->stock_out_person?>" /></td>
                          </tr>
                          <!-- <tr>
                            <td><label for="stock_out_vendor_company_address">Address</label></td>
                            <td><textarea id="stock_out_vendor_company_address" name="stock_out_vendor_company_address" class="form-control input-sm" placeholder="Address"><?=$stock_out->stock_out_id?></textarea></td>
                          </tr>
                          <tr>
                            <td><label for="stock_out_vendor_company_phone">Phone</label></td>
                            <td><input id="stock_out_vendor_company_phone" name="stock_out_vendor_company_phone" type="text" class="form-control input-sm" placeholder="Phone" value="<?=$stock_out->stock_out_id?>" /></td>
                          </tr>
                          <tr>
                            <td><label for="stock_out_vendor_phone">Mobile</label></td>
                            <td><input id="stock_out_vendor_phone" name="stock_out_vendor_phone" type="text" class="form-control input-sm" placeholder="Fax" value="<?=$stock_out->stock_out_id?>" /></td>
                          </tr>
                          <tr>
                            <td><label for="stock_out_vendor_email">Email</label></td>
                            <td><input id="stock_out_vendor_email" name="stock_out_vendor_email" type="text" class="form-control input-sm" placeholder="Email" value="<?=$stock_out->stock_out_id?>" /></td>
                          </tr>
                          <tr>
                            <td><label for="stock_out_vendor_name">Attn</label></td>
                            <td><input id="stock_out_vendor_name" name="stock_out_vendor_name" type="text" class="form-control input-sm required" placeholder="Attn." value="<?=$stock_out->stock_out_id?>" /></td>
                          </tr> -->
                        </table>
                      </div>
                      <div class="col-sm-1 col-xs-1">
                      </div>
                      <div class="col-sm-5 col-xs-5">
                        <table class="table table-condensed table-borderless">
                          <!-- <tr>
                            <td><label for="stock_out_number">Quotation#</label></td>
                            <td>
                              <div class="input-group">
                                <input readonly="readonly" id="stock_out_number" name="stock_out_number" type="text" class="form-control input-sm" placeholder="Quotation#" value="<?=$stock_out->stock_out_id?>" />
                                <span class="input-group-addon"><?='v'.$stock_out->stock_out_id?></span>
                              </div>
                            </td>
                          </tr> -->
                          <tr>
                            <td><label for="stock_out_issue">Date (Y-m-d)</label></td>
                            <td><input id="stock_out_issue" name="stock_out_issue" type="text" class="form-control input-sm date-mask required" placeholder="Issue date" value="<?php echo $stock_out->stock_out_issue ? $stock_out->stock_out_issue : date('Y-m-d'); ?>" /></td>
                          </tr>
                          <!-- <tr>
                            <td><label for="stock_out_user_name">Sales</label></td>
                            <td><input readonly="readonly" id="stock_out_user_name" name="stock_out_user_name" type="text" class="form-control input-sm required" placeholder="Sales" value="<?=$stock_out->stock_out_id?>" /></td>
                          </tr> -->
                          <!-- <tr>
                            <td><label for="stock_out_terms">Payment terms</label></td>
                            <td><input id="stock_out_terms" name="stock_out_terms" type="text" class="form-control input-sm required" placeholder="Payment terms" value="<?=$stock_out->stock_out_id?>" /></td>
                          </tr>
                          <tr>
                            <td><label for="stock_out_expire">Expire Date</label></td>
                            <td><input id="stock_out_expire" name="stock_out_expire" type="text" class="form-control input-sm date-mask" placeholder="Expire Date" value="<?=$stock_out->stock_out_id?>" /></td>
                          </tr> -->
                        </table>
                      </div>
                    </div>
                    <div class="list-area">
                      <table class="table list" id="stock_out">
                        <thead>
                          <tr>
                            <th width="10%">
                              <a class="btn btn-sm btn-primary stock_out_product-insert-btn" data-toggle="tooltip" title="Insert">
                                <i class="glyphicon glyphicon-plus"></i>
                              </a>
                            </th>
                            <th>Detail</th>
                            <th width="16%">Warehouse</th>
                            <th width="16%">Quantity</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach($products as $key => $product){ ?>
                          <tr>
                            <td>
                              <div>
                                <input name="z_stock_out_product_id[]" type="hidden" value="" />
                                <!-- <input name="stock_outitem_product_type_name[]" type="hidden" value="<?=$stock_out->stock_out_id?>" /> -->
                                <!-- <input id="stock_outitem_product_code" name="stock_outitem_product_code[]" type="text" class="form-control input-sm" placeholder="Code" value="<?=$stock_out->stock_out_id?>" /> -->
                              </div>
                              <div class="margin-top-10">
                                <div class="btn-group">
                                  <button type="button" class="btn btn-sm btn-primary delete-btn"><i class="glyphicon glyphicon-remove"></i></button>
                                  <button type="button" class="btn btn-sm btn-primary up-btn"><i class="glyphicon glyphicon-chevron-up"></i></button>
                                  <button type="button" class="btn btn-sm btn-primary down-btn"><i class="glyphicon glyphicon-chevron-down"></i></button>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div>
                                <select id="z_stock_out_product_product_id" name="z_stock_out_product_product_id[]" data-placeholder="Product" class="chosen-select">
                                  <option value="0"></option>
                                  <?php foreach($a_products as $key2 => $a_product){
                                  $selected = ($a_product->product_id == $product->z_stock_out_product_product_id) ? ' selected="selected"' : "" ; ?>
                                  <option value="<?php echo $a_product->product_id ?>" <?php echo $selected ?>><?php echo $a_product->product_name ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div>
                                <textarea id="z_stock_out_product_remark" name="z_stock_out_product_remark[]" class="form-control input-sm" placeholder="Remark"></textarea>
                              </div>
                            </td>
                            <td>
                              <div>
                                <select id="z_stock_out_product_warehouse_id" name="z_stock_out_product_warehouse_id[]" data-placeholder="Warehouse" class="chosen-select stock_product_quantity">
                                  <?php foreach($warehouses as $key2 => $warehouse){ 
                                  $selected = ($warehouse->warehouse_id == $product->z_stock_out_product_warehouse_id) ? ' selected="selected"' : "" ; ?>
                                  <option value="<?php echo $warehouse->warehouse_id ?>" <?php echo $selected ?>><?php echo $warehouse->warehouse_name ?>: <?php echo $product->stock_product_quantity[$key2] ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </td>
                            <td>
                              <input id="z_stock_out_product_quantity" name="z_stock_out_product_quantity[]" type="text" class="required form-control input-sm" placeholder="Quantity" value="<?php echo $product->z_stock_out_product_quantity ?>" />
                            </td>
                          </tr>
                          <?php } ?>
                        </tbody>
                        <!-- <tfoot>
                          <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Discount</th>
                            <th><input id="stock_out_discount" name="stock_out_discount" type="text" class="form-control input-sm required" placeholder="Discount" value="<?=$stock_out->stock_out_id?>" /></th>
                          </tr>
                          <tr>
                            <th width="10%">
                              <a class="btn btn-sm btn-primary stock_outitem-insert-btn" data-toggle="tooltip" title="Insert">
                                <i class="glyphicon glyphicon-plus"></i>
                              </a>
                            </th>
                            <th></th>
                            <th></th>
                            <th>Grand total</th>
                            <th><input readonly="readonly" id="stock_out_total" name="stock_out_total" type="text" class="form-control input-sm" placeholder="Grand total" value="<?=$stock_out->stock_out_id?>" /></th>
                          </tr>
                        </tfoot> -->
                      </table>
                    </div>
                    <div class="hr"></div>
                    <p class="form-group">
                      <label for="stock_out_remark">Remark</label>
                      <textarea id="stock_out_remark" name="stock_out_remark" class="form-control input-sm" placeholder="Remark"><?=$stock_out->stock_out_id?></textarea>
                    </p>
                    <!-- <p class="form-group">
                      <label for="stock_out_payment">Payment</label>
                      <textarea id="stock_out_payment" name="stock_out_payment" class="form-control input-sm" placeholder="Payment"><?=$stock_out->stock_out_id?></textarea>
                    </p> -->
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

          <h2 class="col-sm-12">Warehouse Management - Stock Out List</h2>

            <div class="content-column-area col-md-12 col-sm-12">

              <div class="fieldset min-height-500">
                <!-- <div class="row">

                  <div class="col-md-3 col-sm-12">
                    <blockquote>
                      <i class="glyphicon glyphicon-user"></i>
                      <a href="<?=base_url('stock_out')?>">Stock In</a>
                    </blockquote>
                  </div>
                  <div class="col-md-3 col-sm-12">
                    <blockquote>
                      <i class="glyphicon glyphicon-user"></i>
                      <a href="<?=base_url('stock_out')?>">Stock Out</a>
                    </blockquote>
                  </div>
                  <div class="col-md-3 col-sm-12">
                    <blockquote>
                      <i class="glyphicon glyphicon-user"></i>
                      <a href="<?=base_url('stock_switch')?>">Stock Transfer</a>
                    </blockquote>
                  </div>
                  <div class="col-md-3 col-sm-12">
                    <blockquote>
                      <i class="glyphicon glyphicon-user"></i>
                      <a href="<?=base_url('consignment')?>">Consignment</a>
                    </blockquote>
                  </div>

                </div>

                <div class="hr"></div> -->

                

                <div class="fieldset">
                  <div class="search-area">

                    <form stock_out="form" method="get">
                      <input type="hidden" name="stock_out_id" />
                      <input type="hidden" name="z_stock_out_product_stock_out_id" />
                      <table>
                        <tbody>
                          <tr>
                            <td width="90%">
                              <div class="row">
                                <div class="col-sm-2"><h6>Filters</h6></div>
                                <div class="col-sm-2">
                                  <input type="text" name="stock_out_id" class="form-control input-sm" placeholder="#" value="" />
                                </div>
                                <div class="col-sm-2">
                                  <input type="text" name="stock_out_name_like" class="form-control input-sm" placeholder="Name" value="" />
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
                    <form name="list" action="<?=base_url('stock_out/delete')?>" method="post">
                      <input type="hidden" name="stock_out_id" />
                      <input type="hidden" name="z_stock_out_product_stock_out_id" />
                      <input type="hidden" name="stock_out_delete_reason" />
                      <div class="page-area">
                        <span class="btn btn-sm btn-default"><?php print_r($num_rows); ?></span>
                        <?=$this->pagination->create_links()?>
                      </div>
                      <table id="stock_out" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th width="40">#</th>
                            <!-- <th>
                              <a href="<?=get_order_link('stock_out_name')?>">
                                Name <i class="glyphicon glyphicon-sort corpcolor-font"></i>
                              </a>
                            </th> -->
                            <th>
                              <a>
                                Booking Number / Person In Charge</i>
                              </a>
                            </th>
                            <th width="160">
                              <a href="<?=get_order_link('stock_out_issue')?>">
                                Issue <i class="glyphicon glyphicon-sort corpcolor-font"></i>
                              </a>
                            </th>
                            <th width="160">
                              <a href="<?=get_order_link('stock_out_modify')?>">
                                Modify <i class="glyphicon glyphicon-sort corpcolor-font"></i>
                              </a>
                            </th>
                            <!-- <th width="40"></th> -->
                            <th width="40"></th>
                            <th width="40" class="text-right">
                              <a href="<?=base_url('stock_out/insert')?>" data-toggle="tooltip" title="Insert">
                                <i class="glyphicon glyphicon-plus"></i>
                              </a>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach($stock_outs as $key => $value){ ?>
                          <tr>
                            <td title="<?=$value->stock_out_id?>"><?=$key+1?></td>
                            <!-- <td><?=ucfirst($value->stock_out_name)?></td> -->
                            <td><?= ($value->stock_out_booking_id) ? $value->booking_number : ucfirst($value->stock_out_person)?></td>
                            <td><?=convert_datetime_to_date($value->stock_out_issue)?></td>
                            <td><?=convert_datetime_to_date($value->stock_out_modify)?></td>
                            <!-- <td class="text-right">
                              <span data-toggle="modal" data-target="#myModal" class="modal-btn" rel="<?=$value->stock_out_id?>">
                                <a data-toggle="tooltip" title="More">
                                  <i class="glyphicon glyphicon-chevron-right"></i>
                                </a>
                              </span>
                            </td> -->
                            <td class="text-right">
                              <a href="<?=base_url('stock_out/update/stock_out_id/'.$value->stock_out_id)?>" data-toggle="tooltip" title="Update">
                                <i class="glyphicon glyphicon-edit"></i>
                              </a>
                            </td>
                            <td class="text-right">
                              <a onclick="check_delete('<?=$value->stock_out_id?>');" class="<?=check_permission('stock_out_delete', 'disable')?>" data-toggle="tooltip" title="Delete">
                                <i class="glyphicon glyphicon-remove"></i>
                              </a>
                            </td>

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
          </div>


        </div>
      </div>

    </div>

    <?php } ?>












































    <?php $this->load->view('inc/footer-area.php'); ?>

  </body>
</html>

<div class="scriptLoader"></div>

<script type="text/javascript">

  $(function(){
    $('input[name="stock_out_id"]').focus();

    /* pagination */
    $('.pagination-area>a, .pagination-area>strong').addClass('btn btn-sm btn-primary');
    $('.pagination-area>strong').addClass('disabled');

    /*--------- date mask ---------*/
    $('.date-mask').mask('9999-99-99');

    /* stock_out_product-insert-btn */
    $(document).on('click', '.stock_out_product-insert-btn', function(){
      add_stock_out_product_row();
    });

    /* stock_out_product-delete-btn */
    $(document).on('click', '.delete-btn', function(){
      if(confirm('Confirm delete?')){
        $(this).closest('tr').remove();
        calc();
      }else{
        return false;
      }
    });

    /* vendor loader */
    $(document).on('change', 'select[name="stock_out_vendor_id"]', function(){
      vendor_loader();
    });

    /* product loader */
    $(document).on('change', 'select[name="z_stock_out_product_product_id[]"]', function(){
      product_stock_loader($(this));
    });

    /* trigger calc */
    $(document).on('blur', 'input[name="stock_out_product_quantity[]"]', function(){
      calc();
    });
    $(document).on('change', 'select[name="stock_out_currency"]', function(){
      $.each($('select[name="stock_out_product_product_id[]"]'), function(key, val){
        product_loader($(this));
      });
    });

    /* up & down btn */
    $(document).on('click', '.up-btn', function(){
      if($(this).closest('tr').index() > 0){
        $('table#stock_out tbody tr').eq($(this).closest('tr').index()).after($('table#stock_out tbody tr').eq($(this).closest('tr').index() - 1));
      }
    });
    $(document).on('click', '.down-btn', function(){
      if($('table#stock_out tbody tr').length > $(this).closest('tr').index()){
        $('table#stock_out tbody tr').eq($(this).closest('tr').index()).before($('table#stock_out tbody tr').eq($(this).closest('tr').index() + 1));
      }
    });

    /* textarea auto height */
    textarea_auto_height();
    $(document).on('keyup', 'textarea', function(){
      textarea_auto_height();
    });
  });

  function vendor_loader(){
    $('.scriptLoader').load("<?php echo base_url('load'); ?>", {'thisTableId': 'vendorLoader', 'thisRecordId': $('select[name="stock_out_vendor_id"]').val(), 't': timestamp()}, function(){
      vendorLoader();
    });
  }

  function product_stock_loader(thisObject){
    thisRow = $(thisObject).closest('tr').index();
    $('.scriptLoader').load("<?php echo base_url('load'); ?>", {'thisTableId': 'productStockLoader', 'thisRecordId': $(thisObject).val(), 'thisRow': thisRow, 't': timestamp()}, function(){
      productStockLoader();
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
      if($(this).find('input[name="stock_out_product_product_type_name[]"]').val() == 'customise'){
        $(this).find('input[name="stock_out_product_product_price[]"]').val($(this).find('input[name="stock_out_product_product_hour[]"]').val() * $('input[name="stock_out_hourlyrate"]').val());
      }
      $(this).find('input[name="stock_out_product_subtotal[]"]').val($(this).find('input[name="stock_out_product_product_price[]"]').val() * $(this).find('input[name="stock_out_product_quantity[]"]').val()).css('display', 'none').fadeIn();
      total += parseInt($(this).find('input[name="stock_out_product_subtotal[]"]').val());
      $('input[name="stock_out_total"]').val(total).css('display', 'none').fadeIn();
    });
  }

  function check_delete(id){
    var answer = prompt("Confirm delete?");
    if(answer){
      $('input[name="stock_out_id"]').val(id);
      $('input[name="z_stock_out_product_stock_out_id"]').val(id);
      $('input[name="stock_out_delete_reason"]').val(encodeURI(answer));
      $('form[name="list"]').attr('action', "<?php echo base_url('stock_out/delete'); ?>");
      $('form[name="list"]').submit();
    }else{
      return false;
    }
  }

  function check_confirm(id){
    var answer = confirm("Quotation confirm by vendor?");
    if(answer){
      $('input[name="stock_out_id"]').val(id);
      $('form[name="list"]').attr('action', "<?php echo base_url('stock_out/delete'); ?>");
      $('form[name="list"]').submit();
    }else{
      return false;
    }
  }

  function add_stock_out_product_row(){
    stock_out_product_row = '';
    stock_out_product_row += '<tr>';

    stock_out_product_row += '<td>';
    stock_out_product_row += '<div>';
    stock_out_product_row += '<input name="z_stock_out_product_id[]" type="hidden" value="" />';
    stock_out_product_row += '</div>';
    stock_out_product_row += '<div class="margin-top-10">';
    stock_out_product_row += '<div class="btn-group">';
    stock_out_product_row += '<button type="button" class="btn btn-sm btn-primary delete-btn"><i class="glyphicon glyphicon-remove"></i></button>';
    stock_out_product_row += '<button type="button" class="btn btn-sm btn-primary up-btn"><i class="glyphicon glyphicon-chevron-up"></i></button>';
    stock_out_product_row += '<button type="button" class="btn btn-sm btn-primary down-btn"><i class="glyphicon glyphicon-chevron-down"></i></button>';
    stock_out_product_row += '</div>';
    stock_out_product_row += '</div>';
    stock_out_product_row += '</td>';

    stock_out_product_row += '<td>';
    stock_out_product_row += '<div>';
    stock_out_product_row += '<select id="z_stock_out_product_product_id" name="z_stock_out_product_product_id[]" data-placeholder="Product" class="chosen-select">';
    stock_out_product_row += '<option value="0"></option>';
    <?php foreach($a_products as $key => $product){ ?> 
      stock_out_product_row += '<option value="<?php echo $product->product_id ?>"><?php echo $product->product_name ?></option>';
    <?php } ?>
    stock_out_product_row += '</select>';
    stock_out_product_row += '</div>';
    stock_out_product_row += '<div>';
    stock_out_product_row += '<textarea id="z_stock_out_product_remark" name="z_stock_out_product_remark[]" class="form-control input-sm" placeholder="Remark"></textarea>';
    stock_out_product_row += '</div>';
    stock_out_product_row += '</td>';

    stock_out_product_row += '<td>';
    stock_out_product_row += '<div>';
    stock_out_product_row += '<select id="z_stock_out_product_warehouse_id" name="z_stock_out_product_warehouse_id[]" data-placeholder="Warehouse" class="chosen-select stock_product_quantity">';
    <?php foreach($warehouses as $key => $warehouse){ ?> 
      stock_out_product_row += '<option value="<?php echo $warehouse->warehouse_id ?>"><?php echo $warehouse->warehouse_name ?></option>';
    <?php } ?>
    stock_out_product_row += '</select>';
    stock_out_product_row += '</div>';
    stock_out_product_row += '</td>';

    stock_out_product_row += '<td>';
    stock_out_product_row += '<input id="z_stock_out_product_quantity" name="z_stock_out_product_quantity[]" type="text" class="required form-control input-sm" placeholder="Quantity" value="" />';
    stock_out_product_row += '</td>';

    stock_out_product_row += '</tr>';

    // productCount++;

    $('#stock_out tbody').append(stock_out_product_row);
    $('.chosen-select').chosen();
  }
</script>