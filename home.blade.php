<?php //echo "<pre>";print_r($dashboard_access);die;?>

@extends('layouts.app')
@section('content')
<style>
  .custom-modal-lg {
    width: 60% !important;
}
.modal .modal-dialog .modal-content .modal-body {
    padding: 7px 13px;
}
.dsboard-custom-modal-lg{
        width: 100% !important;
}
.enqdetailtab {
    padding-left: 13px !important;
    padding-right: 20px !important;
}
</style>

<!-- Begin page -->
<div class="wrapper">
    <div class="content-dashboard">
        <div class="container homecon">
            <!-- Page-Title -->
            
            <div class="row">
                <div class="col-lg-12">
                    @if(Session::has('myfavourite'))
                    <script>successPopupMsg('{{ Session::get("myfavourite") }}');</script>
                    @endif
                     @if(Session::has('msg'))
                    <script>successPopupMsg('{{ Session::get("msg") }}');</script>
                    @endif
                    <div class="btn-group pull-right m-t-10 hide">
                        <a href="{{ route('configure_module_myfavourite') }}" class="btn-md btn-default waves-effect waves-light" data-animation="fadein" data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a">Configure<span class="m-l-5"><i class="fa fa-cog"></i></span></a>                                      
                    </div>
					
                    <div class="btn-group pull-right m-r-10 m-t-10">
                        <a href="{{ route('myapproval') }}" class="btn-md btn-default waves-effect waves-light">My Approvals <span class="m-l-5"><i class="fa fa-book"></i></span></a>                            
                    </div>
					
                    <div class="btn-group pull-right m-r-10 m-t-10 ">
                        <a href="javascript:void(0)" class="btn-md btn-default dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">My Favourite<span class="m-l-5"><i class="ti-star m-r-5"></i></span></a>
                        <ul class="dropdown-menu" role="menu" style="left: 0;">
                            @foreach($menus as $menu)
                            <li><a href="{{ route('menu_action',['name' => $menu->menu_id]) }}">{{ $menu->menu_name }}</a></li>
                            @endforeach
                             <li class="homepagetop"><a href="{{ route('configure_module_myfavourite') }}"><span ><i class="fa fa-cog"></i></span> <b>Configure</b></a></li>
                        </ul>
                    </div>
					 <div class="btn-group pull-right m-r-10 m-t-10">
                        <a href="{{ url('mymessage') }}" class="btn-md btn-default waves-effect waves-light"> Message Board</a>
                      
                                                   
                    </div>

                    <h4 class="page-title">{{ $companyDetails->display_name }} <!-- Dashboard --></h4>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="row reArrangeDiv" id="reArrangeDiv">    

                @foreach($dashboard_access as $mycomponent) 
                    
                    @if(in_array($mycomponent['subsection_name'], ['quotation']))
                        @if(isset($check_access['quotation']))
                            <div class="col-md-6 col-lg-3 reArrangeSection" priority-attr="{{$check_access['quotation']->priority}}" section_name_attr="quotation">
                                <button type="button" class="close dash-access" aria-hidden="true" autocomplete="off">×</button>
                                <div class="widget-bg-color-icon card-box dashboard-stat red">
                                    <div class="bg-icon bg-icon-white pull-left">
                                        <i class="zmdi zmdi-equalizer text-white"></i>
                                    </div>
                                    <div class="">

                                        <!-- <h3 class="text-white"><b class="counter">{{ getTotalRecordsByTable('customer_quotation_models') }}</b> & <b class="counter">{{ getTotalRecordsByTable('customer_sales_orders') }}</b></h3> -->
                                        <ul class="text-white card-box-list">
                                            <li><span>Yesterday</span><div class="card-counter text-right"><span class="counter">{{ getYesterdayRecordByTable('customer_quotation_models') }}</span> & <sapn class="counter">{{ getYesterdayRecordByTable('customer_sales_orders') }}</span></div></li>
                                            <li><span>Month to Date</span><div class="card-counter text-right"><span class="counter">{{ getCurrentMonthRecordByTable('customer_quotation_models') }}</span> & <span class="counter">{{ getCurrentMonthRecordByTable('customer_sales_orders') }}</span></div></li>
                                            <li><span>Year to Date</span><div class="card-counter text-right"><span class="counter">{{ getCurrentYearRecordByTable('customer_quotation_models') }}</span> & <span class="counter">{{ getCurrentYearRecordByTable('customer_sales_orders') }}</span></div></li>
                                            <!-- <li><label>Total</label><b class="counter">{{ getTotalRecordsByTable('customer_quotation_models') }}</b> & <b class="counter">{{ getTotalRecordsByTable('customer_sales_orders') }}</b></li> -->
                                        </ul>
                                        <p class="text-white text-center card-box-head ">Quotations To Orders</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        @endif                
                    @endif

                    @if(in_array($mycomponent['subsection_name'], ['orders']))
                        @if(isset($check_access['orders']))
                            <div class="col-md-6 col-lg-3 reArrangeSection" priority-attr="{{$check_access['orders']->priority}}" section_name_attr="orders">
                                <button type="button" class="close dash-access" aria-hidden="true" autocomplete="off">×</button>
                                <div class="widget-bg-color-icon card-box dashboard-stat purple">
                                    <div class="bg-icon bg-icon-white pull-left">
                                        <i class="zmdi zmdi-shopping-cart-add text-white"></i>
                                    </div>
                                    <div class="">
                                       <!--  <h3 class="text-white"><b class="counter">{{ getTotalRecordsByTable('customer_sales_orders') }}</b></h3> -->
                                       <ul class="text-white card-box-list">
                                            <li><span>Yesterday</span><div class="card-counter text-right"><span class="counter">{{ getYesterdayRecordByTable('customer_sales_orders') }}</span></div></li>
                                            <li><span>Month to Date</span><div class="card-counter text-right"><span class="counter">{{ getCurrentMonthRecordByTable('customer_sales_orders') }}</span></div></li>
                                            <li><span>Year to Date</span><div class="card-counter text-right"><span class="counter">{{ getCurrentYearRecordByTable('customer_sales_orders') }}</span></div></li>
                                           <!--  <li><label>Total</label><b class="counter">{{ getTotalRecordsByTable('customer_sales_orders') }}</b></li> -->
                                        </ul>
                                        <p class="text-white text-center card-box-head">Orders</p>
                                    </div>
                                    <div class="clearfix"></div>  
                                </div>
                            </div>
                              @endif
                    @endif

                    @if(in_array($mycomponent['subsection_name'], ['customer_inquiries']))
                        @if(isset($check_access['customer_inquiries']))
                            <div class="col-md-6 col-lg-3 reArrangeSection" priority-attr="{{$check_access['customer_inquiries']->priority}}" section_name_attr="customer_inquiries">
                                <button type="button" class="close dash-access" aria-hidden="true" autocomplete="off">×</button>
                                <div class="widget-bg-color-icon card-box dashboard-stat blue">
                                    <div class="bg-icon bg-icon-white pull-left">
                                        <i class="zmdi zmdi-eye text-white"></i>
                                    </div>
                                    <div class="text">
                                        <!-- <h3 class="text-white"><b class="counter">{{ getTotalRecordsByTable('enquiry_models') }}</b></h3> -->
                                        <ul class="text-white card-box-list">
                                            <li><span>Yesterday</span><div class="card-counter text-right"><span class="counter">{{ getYesterdayRecordByTable('enquiry_models') }}</span></div></li>
                                            <li><span>Month to Date</span><div class="card-counter text-right"><span class="counter">{{ getCurrentMonthRecordByTable('enquiry_models') }}</span></div></li>
                                            <li><span>Year to Date</span><div class="card-counter text-right"><span class="counter">{{ getCurrentYearRecordByTable('enquiry_models') }}</span></div></li>
                                            <!-- <li><label>Total</label><b class="counter">{{ getTotalRecordsByTable('enquiry_models') }}</b></li> -->
                                        </ul>
                                        <p class="text-white text-center card-box-head">Customer Inquiries</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            @endif
                    @endif

                    @if(in_array($mycomponent['subsection_name'], ['revenue']))
                        @if(isset($check_access['revenue']))
                <div class="col-md-6 col-lg-3 reArrangeSection" priority-attr="{{$check_access['revenue']->priority}}" section_name_attr="revenue">
                    <button type="button" class="close dash-access" aria-hidden="true" autocomplete="off">×</button>
                    <div class="widget-bg-color-icon card-box dashboard-stat green">
                        <div class="bg-icon bg-icon-white pull-left">
                            <i class="zmdi zmdi-money text-white"></i>
                        </div>
                        <div class="">
                            <!-- <h3 class="text-white"><b class="counter">{{ getAmtForDashboard(getTotalRevenve('customer_invoice_models')) }}</b></h3> -->
                            <ul class="text-white card-box-list">
                                <li><span>Yesterday</span><div class="card-counter text-right"><span class="counter">{{ getAmtForDashboard(getYesterdayRecordByTable('customer_invoice_models')) }}</span></div></li>
                                <li><span>Month to Date</span><div class="card-counter text-right"><span class="counter">{{ getAmtForDashboard(getCurrentMonthRecordByTable('customer_invoice_models')) }}</span></div></li>
                                <li><span>Year to Date</span><div class="card-counter text-right"><span class="counter">{{ getAmtForDashboard(getCurrentYearRecordByTable('customer_invoice_models')) }}</span></div></li>
                                <!-- <li><label>Total</label><b class="counter">{{ getAmtForDashboard(getTotalRevenve('customer_invoice_models')) }}</b></li> -->
                            </ul>
                            <p class="text-white text-center card-box-head">Revenue</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            @endif
        @endif
@endforeach

           </div>
            <div class="row reArrangeDivGraph" id="reArrangeDivGraph">
        
              @foreach($dashboard_access as $mycomponent) 
                    
                    @if(in_array($mycomponent['subsection_name'], ['customer_inquiries_graph']))
                        @if(isset($check_access['customer_inquiries_graph']))
                <div class="col-lg-4 reArrangeSection" section_name_attr="customer_inquiries_graph">
                    <div class="portlet">
                        <div class="portlet-heading portlet-default">
                            <h3 class="portlet-title text-dark">
                                Customer Inquires 
                            </h3>
                            <div class="portlet-widgets">
                                <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                                <span class="divider"></span>
                                <a data-toggle="collapse" data-parent="#accordion1" href="#bg-default-1"><i class="ion-minus-round"></i></a>                               <span class="divider"></span>
                                 <button type="button" class="close-graph dash-access" aria-hidden="true" autocomplete="off">×</button>
                               <!--  <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a> -->
                                
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div id="bg-default1" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div class="text-center">
                                        <ul class="list-inline chart-detail-list">
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #5fbeaa;"></i>Actual</h5>
                                            </li>
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #5d9cec;"></i>Budgeted</h5>
                                            </li>
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #dcdcdc;"></i>Last Year</h5>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="cust-inquires" style="height: 285px;"></div>
                                    <script type="text/javascript">
                                        Morris.Bar({
                                            element: 'cust-inquires',
                                            data: [
                                                    { y: 'Yesterday',     a: {{ getYesterdayRecordByTable('enquiry_models') }}, b: 0 , c: {{ getLastYearRecordByTable('enquiry_models') }} },
                                                    { y: 'Month', a: {{ getCurrentMonthRecordByTable('enquiry_models') }},  b: 0 , c: {{ getLastYearRecordByTable('enquiry_models') }} },
                                                    { y: 'Year to Date',  a: {{ getCurrentYearRecordByTable('enquiry_models') }},  b: 0 , c: {{ getLastYearRecordByTable('enquiry_models') }} }
                                                ],
                                                xkey: 'y',
                                                ykeys: ['a', 'b', 'c'],
                                                labels: ['Actual', 'Budgeted', 'Last year'],
                                                hideHover: 'auto',
                                                resize: true, //defaulted to true
                                                gridLineColor: '#eeeeee',
                                                barColors: ['#5fbeaa', '#5d9cec', '#ebeff2']
                                        });
                                    </script>
                                </div>
                            </div>
                    </div>
                </div>
                @endif
                @endif
         
              @if(in_array($mycomponent['subsection_name'], ['order_graph']))
                        @if(isset($check_access['order_graph']))
                <div class="col-lg-4 reArrangeSection" section_name_attr="order_graph">
                    <div class="portlet">
                        <div class="portlet-heading portlet-default">
                            <h3 class="portlet-title text-dark">
                                Sales Orders 
                            </h3>
                            <div class="portlet-widgets">
                                <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                                <span class="divider"></span>
                                <a data-toggle="collapse" data-parent="#accordion1" href="#bg-default-1"><i class="ion-minus-round"></i></a>                                <span class="divider"></span>
                              <button type="button" class="close-graph dash-access" aria-hidden="true" autocomplete="off">×</button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div id="bg-default2" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div class="text-center">
                                        <ul class="list-inline chart-detail-list">
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #5fbeaa;"></i>Actual</h5>
                                            </li>
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #5d9cec;"></i>Budgeted</h5>
                                            </li>
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #dcdcdc;"></i>Last Year</h5>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="morris-bar-example" style="height: 285px;"></div>
                                    <script type="text/javascript">
                                        Morris.Bar({
                                            element: 'morris-bar-example',
                                            data: [
                                                    { y: 'Yesterday',     a: {{ getYesterdayRecordByTable('customer_sales_orders') }}, b: 0 , c: {{ getLastYearRecordByTable('customer_sales_orders') }} },
                                                    { y: 'Month', a: {{ getCurrentMonthRecordByTable('customer_sales_orders') }},  b: 0 , c: {{ getLastYearRecordByTable('customer_sales_orders') }} },
                                                    { y: 'Year to Date',  a: {{ getCurrentYearRecordByTable('customer_sales_orders') }},  b: 0 , c: {{ getLastYearRecordByTable('customer_sales_orders') }} }
                                                ],
                                                xkey: 'y',
                                                ykeys: ['a', 'b', 'c'],
                                                labels: ['Actual', 'Budgeted', 'Last year'],
                                                hideHover: 'auto',
                                                resize: true, //defaulted to true
                                                gridLineColor: '#eeeeee',
                                                barColors: ['#5fbeaa', '#5d9cec', '#ebeff2']
                                        });
                                    </script>
                                </div>
                            </div>
                    </div>
                </div>
                  @endif
                @endif
        @if(in_array($mycomponent['subsection_name'], ['invoice_graph']))
                        @if(isset($check_access['invoice_graph']))
                <div class="col-lg-4 reArrangeSection" section_name_attr="invoice_graph"">
                    <div class="portlet">
                        <div class="portlet-heading portlet-default">
                            <h3 class="portlet-title text-dark">
                                Invoices
                            </h3>
                            <div class="portlet-widgets">
                                <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                                <span class="divider"></span>
                                <a data-toggle="collapse" data-parent="#accordion1" href="#bg-default-1"><i class="ion-minus-round"></i></a>                                <span class="divider"></span>
                              <button type="button" class="close-graph dash-access" aria-hidden="true" autocomplete="off">×</button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div id="bg-default3" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div class="text-center">
                                        <ul class="list-inline chart-detail-list">
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #5fbeaa;"></i>Actual</h5>
                                            </li>
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #5d9cec;"></i>Budgeted</h5>
                                            </li>
                                            <li>
                                                <h5><i class="fa fa-circle m-r-5" style="color: #dcdcdc;"></i>Last Year</h5>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="cust-invoice" style="height: 285px;"></div>
                                    <script type="text/javascript">
                                        Morris.Bar({
                                            element: 'cust-invoice',
                                            data: [
                                                    { y: 'Yesterday',     a: {{ getAmtForDashboard(getYesterdayRecordByTable('customer_invoice_models')) }}, b: 0 , c: {{ getAmtForDashboard(getLastYearRecordByTable('customer_invoice_models')) }} },
                                                    { y: 'Month', a: {{ getAmtForDashboard(getCurrentMonthRecordByTable('customer_invoice_models')) }},  b: 0 , c: {{ getAmtForDashboard(getLastYearRecordByTable('customer_invoice_models')) }} },
                                                    { y: 'Year to Date',  a: {{ getAmtForDashboard(getCurrentYearRecordByTable('customer_invoice_models')) }},  b: 0 , c: {{ getAmtForDashboard(getLastYearRecordByTable('customer_invoice_models')) }} }
                                                ],
                                                xkey: 'y',
                                                ykeys: ['a', 'b', 'c'],
                                                labels: ['Actual', 'Budgeted', 'Last year'],
                                                hideHover: 'auto',
                                                resize: true, //defaulted to true
                                                gridLineColor: '#eeeeee',
                                                barColors: ['#5fbeaa', '#5d9cec', '#ebeff2']
                                        });
                                    </script>
                                </div>
                            </div>
                    </div>
                </div>
           @endif
        @endif
  @endforeach
            </div>
  @foreach($dashboard_access as $mycomponent) 
                    
                    @if(in_array($mycomponent['subsection_name'], ['invoice_list']))
                        @if(isset($check_access['invoice_list']))
            <div class="row">
                <div class="col-lg-12" section_name_attr="invoice_list">
                    <div class="portlet">
                        <div class="portlet-heading portlet-default">
                            <h3 class="portlet-title text-dark">
                                Recent Invoices
                            </h3>
                            <div class="portlet-widgets">
                                <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                                <span class="divider"></span>
                                <a data-toggle="collapse" data-parent="#accordion1" href="#bg-default-2"><i class="ion-minus-round"></i></a>
                                <span class="divider"></span>
                               <button type="button" class="close-graph dash-access" aria-hidden="true" autocomplete="off">×</button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div id="bg-default-2" class="panel-collapse collapse in">
                            <div class="portlet-body">
                                <table data-toggle="table" data-sort-name="id" data-page-list="[5, 10, 20]" data-page-size="5" data-pagination="true" class="table-bordered table-striped table-custom ">
                                    <thead>
                                        <tr>                                                 
                                            <th class="min-width-80 text-center"><label class="custom-label">Sales Order Number</label></th>
                                            <th class="min-width-80 text-center"><label class="custom-label">Invoice Number</label></th>
                                            <th class="min-width-200"><label class="custom-label">Customer Name</label></th>
                                            <th class="min-width-80 text-center"><label class="custom-label">Invoice Date</label></th>
                                            <th class="min-width-80"><label class="custom-label">Invoice Amount</label></th>
                                            <th class="min-width-80 text-center"><label class="custom-label">Payment Status</label></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customer_inv as $orderinvoice)
                                        @php($pendingaount = getBalanceByCustInvId($orderinvoice->id))
                                        <tr>
                                            <td class="text-center"><a href="javascript:void(0)" onclick="getprevorderpopup('{{ $orderinvoice->so_id }}')">{{ getSalesOrderNoById($orderinvoice->so_id) }}</a></td>
                                            <td class="text-center">{{ $orderinvoice->invoice_no }}</td>
                                            <td>{{ getCustomerName($orderinvoice->customer_id) }}</td>
                                            <td class="text-center">{{ getDateView($orderinvoice->invoice_date) }}</td>
                                            <td class="text-right">{{ getCurrencyNameById(getCurrencyIdBySoId($orderinvoice->so_id)) }} / {{ $orderinvoice->grandtotal }}</td>
                                            <td class="text-center">                                                        
                                                @if($pendingaount == 0)
                                                    <button type="button" class="label label-table label-success btn">Full Paid</button>
                                                @elseif($pendingaount == 1)
                                                    <button type="button" class="label label-table label-warning btn">Pending</button>
                                                @elseif($pendingaount == 2)
                                                    <button type="button" class="label label-table label-pink btn">Not Paid</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table> 
                            </div>
                            
                        </div>
                        <!-- Alert Message Modal-->
                    </div>
                </div>
            </div>
         @endif
        @endif
  @endforeach    
                
                    </div>
                </div>
            </div>
            
        </div>
    </div>        
</div><!-- END wrapper -->


<div id="drupalchat-wrapper">
    <div id="drupalchat" style="">
        <div class="item-list" id="chatbox_chatlist">
            <ul id="mainpanel">
                <li id="chatpanel" class="first last">
                    <div class="subpanel" style="display: block;">
                        <div class="subpanel_title" onclick="javascript:toggleChatBoxGrowth('chatlist')" >Chat<span class="options"></span>
                            <span class="min localhost-icon-minus-1"><i class="fa fa-minus-circle minusicon text-20" aria-hidden="true"></i></span>
                        </div>
                        <div>
                            <div class="drupalchat_search_main chatboxinput" style="background:#f9f9f9">
                                <div class="drupalchat_search" style="height:30px;">
                                    <input class="drupalchat_searchinput live-search-box" placeholder="Type here to search" value="" size="24" type="text">
                                    <input class="searchbutton" value="" style="height:30px;border:none;margin:0px; padding-right:13px; vertical-align: middle;" type="submit"></div>
                            </div>
                            <div class="contact-list chatboxcontent">
                                <ul class="live-search-list">
                                    <?php $users = []; ?>
                                    @foreach($userList as $username)
                                    <?php/* if($username['online'] == 1){
                                        $onofst = 1;
                                    }else{
                                        $onofst = 0;
                                    }*/
                                    ?>
                                    <li id="chatuser_<?php echo $username['id'];?>" class="iflychat-olist-item iflychat-ol-ul-user-img iflychat-userlist-room-item chat_options">
                                        <div class="drupalchat-self-profile">
                                            <span title="Offilne" class="Offline statuso" style="text-align: right"><span class="statusIN"><i class="fa fa-circle" aria-hidden="true"></i></span></span>
                                            <div class="drupalchat-self-profile-div">
                                                <?php $split = str_split($username['first_name']);
                                                        $unamefirstchar = $split[0];
                                                        ?>
                                                <div class="drupalchat-self-profile-img + localhost-avatar-sprite-28 <?php echo strtoupper($unamefirstchar); ?>_3">
                                                    <?php if (!empty($row['picname'])) { ?>
                                                        <img src="storage/user_image/small<?php echo $row['picname']; ?>"/>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="drupalchat-self-profile-namdiv">
                                                <a class="drupalchat-profile-un drupalchat_cng" href="javascript:void(0)" onclick="javascript:chatWith('<?php echo $username['first_name'] ?>', '<?php echo $username['id'] ?>','', this)"> <?php echo $username['first_name'] ?></a>
                                            </div>
                                        </div>
                                    </li>
                                    <?php $users[$username['id']] = $username['first_name']; ?>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('assets/js/chatjs/chat.js') }}"></script>
<style type="text/css">
    .card-box-list {
     	list-style: none;
     	width: 165px;
	   margin: 0 auto;
	 }
	 .card-box-list li{
	     font-size: 11px;
	     width: 165px;
	   	float: left;
	 }
	 .card-box-list li label {
	     float: left;
	    width: 85px;
	    line-height: 1;
	    text-align: left;
	 }
	.card-box-list .card-counter {
	    width: 70px;
	    float: right;
	}
	 .card-box-list li .counter {
	     text-align: right;
	     display: inline-block;
	     line-height: 1;
	 }
	 .content-dashboard .card-box.dashboard-stat {
	    padding: 10px 0 10px 10px;
	 }
	 .content-dashboard .card-box-head {
	    font-weight: 700;
	    display: block;
	     width: 73%;
	    float: left;
	    margin: 10px 0 0;
	}
</style>
<input type="hidden" id="loggedinuserid" value="<?php echo getUserId();?>">
<script type="text/javascript">
    
function getprevorderpopup(id){
    var so_no   =   $('#sales_orderno').text();
    $.get('{{ route("cust_sales_order")}}' + '/detail/' + id, function(data){
        $('#sales_order_no').html(so_no);
        $('#viewsalesorderdetail').html(data);
        $('#sales-order-modal').modal('show');
    });
}

var windowFocus = true;
var username;
var chatHeartbeatCount = 0;
var minChatHeartbeat = 50000;
var maxChatHeartbeat = 50000;
var chatHeartbeatTime = minChatHeartbeat;
var originalTitle;
var blinkOrder = 0;
var audioogg = new Audio('audio/chat.ogg');
var audiomp3 = new Audio('audio/chat.mp3');
var my_id = '{{ Auth::id() }}';
var my_company = 'warehouse';
var chatboxFocus = new Array();
var newMessages = new Array();
var newMessagesWin = new Array();
var chatBoxes = new Array();
var userNames = {!! json_encode($users) !!}
userNames[my_id] = "You";


$(document).ready(function(){
    originalTitle = document.title;
    startChatSession();

    $([window, document]).blur(function(){
        windowFocus = false;
    }).focus(function(){
        windowFocus = true;
        document.title = originalTitle;
    });
});


function disableChat(){
    $('#chatbox_chatlist').find('.subpanel_title').attr('onclick', 'javascript:void()');
}

var emoji = " :a :b :c :( :d :O :e :f :D :g :L :l :m :^ :* :v :) :# :p := :o :; :r ";

var emoticons = {
    ':a'  :  '<img src="emotions-fb/angel.png" title="angel" alt=":a" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':b'  :  '<img src="emotions-fb/apple.png" title="apple" alt=":b" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':c'  :  '<img src="emotions-fb/confused.png" title="confused" alt=":c" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':('  :  '<img src="emotions-fb/cry.png" title="cry" alt=":(" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':d'  :  '<img src="emotions-fb/devil.png" title="devil" alt=":d" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)" />',
    ':O'  :  '<img src="emotions-fb/gasp.png" title="gasp" alt=":O" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':e'  :  '<img src="emotions-fb/frown.png" title="frown" alt=":e" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':f'  :  '<img src="emotions-fb/glasses.png" title="glasses" alt=":f" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':D'  :  '<img src="emotions-fb/grin.png" title="grin" alt=":D" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':g'  :  '<img src="emotions-fb/grumpy.png" title="grumpy" alt=":g" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':L'  :  '<img src="emotions-fb/heart-beat.gif" title="heart-beat" alt=":L" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':l'  :  '<img src="emotions-fb/heart.png" title="heart-beat" alt=":l" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':m'  :  '<img src="emotions-fb/broken-heart.png" title="heart-beat" alt=":m" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':^'  :  '<img src="emotions-fb/kiki.png" title="kiki" alt=":^" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':*'  :  '<img src="emotions-fb/kiss.png" title="kiss" alt=":*" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':v'  :  '<img src="emotions-fb/pacman.png" title="pacman" alt=":v" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':)'  :  '<img src="emotions-fb/smile.png" title="smile" alt=":)" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':#'  :  '<img src="emotions-fb/squint.png" title="squint" alt=":#" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':p'  :  '<img src="emotions-fb/tongue.png" title="tongue" alt=":p" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':='  :  '<img src="emotions-fb/unsure.png" title="unsure" alt=":/" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':o'  :  '<img src="emotions-fb/upset.png" title="upset" alt=":o" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':;'  :  '<img src="emotions-fb/wink.png" title="Wink" alt=":;" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>',
    ':r'  :  '<img src="emotions-fb/rose.png" title="Rose" alt=":r" class="embtn" onclick="enableTxt(this)" href="javascript:void(0)"/>'
}

function Emotions (text,chatuser) {
    if (text == null || text == undefined || text == "") return;

    var pattern = /:-?[()*^#=;abcdDefghilLmoOprvz]/gi;
    return text.replace(pattern, function (match) {
        var rtr = typeof emoticons[match] != 'undefined' ? emoticons[match] : match;
        return emoticons[match].replace("embtn", 'embtn" cuser = "'+chatuser);
    });
}
function chatemoji(chatuser) {
    $(".target-emoji-"+chatuser).toggle( 'fast', function(){
    });
}
function enableTxt(event) {
    var client = $(event).attr("cuser");
    var prevMsg = $("#chatbox_"+client+" .chatboxtextarea").val();
    var emotiText = $(event).attr("alt");

    $("#chatbox_"+client+" .chatboxtextarea").val(prevMsg+' '+emotiText);
    $("#chatbox_"+client+" .chatboxtextarea").focus();
}

function restructureChatBoxes() {
    align = 0;
    for (x in chatBoxes) {
        chatboxtitle = chatBoxes[x];

        if ($("#chatbox_"+chatboxtitle).css('display') != 'none') {
            if (align == 0) {
                $("#chatbox_"+chatboxtitle).css('right', '230px');
            } else {
                width = (align)*(273+7)+230;
                $("#chatbox_"+chatboxtitle).css('right', width+'px');
            }
            align++;
        }
    }
}

function chatWith(chatuser,toid,img,status) {
    createChatBox(chatuser,toid,img,status);	
    $("#chatbox_"+toid+" .chatboxtextarea").focus();
}
var hasInitialHistoryOf = [];
function createChatBox(chatboxtitle,toid,img,status,minimizeChatBox) {
    chatboxtitle = toid;
    if(!hasInitialHistoryOf["user_" + toid])
        connection.send(JSON.stringify({type: "messageHistory", recipient: toid}));
    if ($("#chatbox_"+toid).length > 0) {
        if ($("#chatbox_"+toid).css('display') == 'none') {
            $("#chatbox_"+toid).css('display','block');
            restructureChatBoxes();
        }
    $("#chatbox_"+toid+" .chatboxtextarea").focus();
    return;
    }

    status  = $('#chatuser_'+toid).find('.statuso').hasClass('Offline')? "Offline" : "Online";

    var emoji2 = Emotions (emoji,chatboxtitle);
    /*<div class="emoji-panel-body target-emoji-'+chatboxtitle+'" style="display: none;"><div class="emoji-Recent">'+emoji2+'</div></div>*/

    $(" <div />" ).attr("id","chatbox_"+toid)
    .addClass("chatbox active-chat")
    .attr("client",toid)
    .html('<div class="box box-success direct-chat direct-chat-success chatboxhead" style="padding: 0px;"><div class="box-header with-border"><h3 class="box-title"><a href="profile.php?uname='+chatboxtitle+'"> '+userNames[toid]+'</a></h3>&nbsp;<span title="'+status+'" class="'+status+'"><i class="fa fa-circle" aria-hidden="true"></i></span><div class="box-tools pull-right"><a onclick="javascript:toggleChatBoxGrowth(\''+chatboxtitle+'\')" href="javascript:void(0)" data-widget="collapse" class="btn-box-tool"><i class="fa fa-minus-circle chtfw text-20" aria-hidden="true"></i></a><a onclick="javascript:closeChatBox(\''+chatboxtitle+'\')" href="javascript:void(0)" data-widget="remove" class="btn-box-tool"><i class="fa fa-times-circle chtfw text-20" aria-hidden="true"></i></a></div></div></div><div class="chatboxcontent direct-chat-success" id="resultchat_'+toid+'"></div><div class="uiContextualLayerPositioner _53ii uiLayer"><div class="uiContextualLayer uiContextualLayerAboveLeft target-emoji-'+chatboxtitle+'" style="display: none; bottom: 0px;"><div class="_5v-0 _53ik"><div class="_53ij"><div class="_4pi8"><div class="_4pi9"><div class="dze"><div class="uiScrollableAreaWrap scrollable"><div class="uiScrollableAreaBody"><div class="uiScrollableAreaContent"><!--773 --><div class="emoji-panel-body"><div class="emoji-Recent">'+emoji2+'</div></div></div></div></div></div></div></div></div><i class="_53io"></i></div></div></div><div class="chatboxinput"><a onclick="javascript:chatemoji(\''+chatboxtitle+'\')" href="javascript:void(0)" class="write-smiley"></a><div class="write-link attach"><img class="loadmsg" id="loadmsg_'+chatboxtitle+'" src="img/chatloading.gif"><input id="imageInput" type="file" name="file" onChange="uploadimage(\''+chatboxtitle+'\');"/></div><textarea class="chatboxtextarea" id="textarea_'+ toid +'" onkeypress="javascript:return updateLastTypedTime();" onkeyup="javascript:return refreshTypingStatus(\''+chatboxtitle+'\',\''+toid+'\');" onkeydown="javascript:return checkChatBoxInputKey(event,this,\''+chatboxtitle+'\',\''+toid+'\',\''+img+'\');"></textarea></div>')
    .appendTo($( "body" ));

    //$("#resultchat_"+chatboxtitle).scrollTop($("#resultchat_"+chatboxtitle)[0].scrollHeight);
	
    var scrollcode = $("#resultchat_"+chatboxtitle).scroll(function(){
        if ($("#resultchat_"+chatboxtitle).scrollTop() == 0){

            var client = $("#chatbox_"+chatboxtitle).attr("client");

            if($("#chatbox_"+client+" .pagenum:first").val() != $("#chatbox_"+client+" .total-page").val()) {

                $("#loader").show();
                var pagenum = parseInt($("#chatbox_"+client+" .pagenum:first").val()) + 1;

                //var URL = "chat.php?page="+pagenum+"&action=get_all_msg&client="+client;
                var URL = "{{ route('get_all_msg') }}" + '/' +client;

                get_all_msg(URL);

                $("#loader").hide();									// Hide loader on success

                if(pagenum != $("#chatbox_"+client+" .total-page").val()) {
                    setTimeout(function () {				//Simulate server delay;
                        $("#resultchat_"+chatboxtitle).scrollTop(100);  // Reset scroll
                    }, 458);
                }
            }
        }
    });
	

    $("scrollcode").appendTo(document.body);

    //get_all_msg("chat.php?page=1&action=get_all_msg&client="+chatboxtitle);
    get_all_msg("{{ route('get_all_msg') }}" + '/' +chatboxtitle);
    $("#chatbox_"+chatboxtitle).css('bottom', '0px');

    chatBoxeslength = 0;
    for (x in chatBoxes) {
            if ($("#chatbox_"+chatBoxes[x]).css('display') != 'none') {
                    chatBoxeslength++;
            }
    }

    if (chatBoxeslength == 0) {
            $("#chatbox_"+chatboxtitle).css('right', '230px');
    } else {
            width = (chatBoxeslength)*(273+7)+230;
            $("#chatbox_"+chatboxtitle).css('right', width+'px');
    }

    chatBoxes.push(toid);

    if (minimizeChatBox == 1) {
            minimizedChatBoxes = new Array();

            if ($.cookie('chatbox_minimized')) {
                    minimizedChatBoxes = $.cookie('chatbox_minimized').split(/\|/);
            }
            minimize = 0;
            for (j=0;j<minimizedChatBoxes.length;j++) {
                    if (minimizedChatBoxes[j] == chatboxtitle) {
                            minimize = 1;
                    }
            }

            if (minimize == 1) {
                    $('#chatbox_'+toid+' .chatboxcontent').css('display','none');
                    $('#chatbox_'+toid+' .chatboxinput').css('display','none');
            }
    }

	chatboxFocus[toid] = false;

	$("#chatbox_"+toid+" .chatboxtextarea").blur(function(){
		chatboxFocus[toid] = false;
		$("#chatbox_"+toid+" .chatboxtextarea").removeClass('chatboxtextareaselected');
	}).focus(function(){
		chatboxFocus[toid] = true;
		newMessages[toid] = false;
		$('#chatbox_'+toid+' .chatboxhead').removeClass('chatboxblink');
		$("#chatbox_"+toid+" .chatboxtextarea").addClass('chatboxtextareaselected');
	});

	/*$("#chatbox_"+chatboxtitle).click(function() {
		if ($('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display') != 'none') {
			$("#chatbox_"+chatboxtitle+" .chatboxtextarea").focus();
		}
	});*/

	$("#chatbox_"+toid).show();
	
}

function refreshOnlineUsers(onlineUserIds){
    $('.statuso').removeClass('Online'); 
    $('.statuso').addClass('Offline'); 
    $.each(onlineUserIds, function(i, user) {                
        if($('#chatuser_'+user).find('.statuso').hasClass('Offline')){
            $('#chatuser_'+user).find('.statuso').removeClass('Offline');
            $('#chatuser_'+user).find('.statuso').addClass('Online');
        }
        var name = $('#chatuser_'+user).find('.drupalchat_cng').text();
        var str = "javascript:chatWith('"+name.trim()+"','"+user+"','','Online')";
        $('#chatuser_'+user).find('.drupalchat_cng').attr('onclick',str);
    });
}

function getonlineUsers(){ 
    /*$.ajax({        
        url: 'onlineuser',
        cache: false,
        dataType: "json",
        success: function(data) {            
            $.each(data.offline, function(i, user) {
                if($('#chatuser_'+user).find('.statuso').hasClass('Online')){
					$('#chatuser_'+user).find('.statuso').removeClass('Online');
					$('#chatuser_'+user).find('.statuso').addClass('Offline');
				}
				var name = $('#chatuser_'+user).find('.drupalchat_cng').text();
				var str = "javascript:chatWith('"+name.trim()+"','"+user+"','','Offline')";
                $('#chatuser_'+user).find('.drupalchat_cng').attr('onclick',str);	
            });
            $.each(data.online, function(i, user) {                
                if($('#chatuser_'+user).find('.statuso').hasClass('Offline')){
					$('#chatuser_'+user).find('.statuso').removeClass('Offline');
					$('#chatuser_'+user).find('.statuso').addClass('Online');
				}
				var name = $('#chatuser_'+user).find('.drupalchat_cng').text();
				var str = "javascript:chatWith('"+name.trim()+"','"+user+"','','Online')";
                $('#chatuser_'+user).find('.drupalchat_cng').attr('onclick',str);
            });
               
        }
    });*/
}

function chatHeartbeat(){
    return false;
    var itemsfound = 0;
    getonlineUsers();
    if (windowFocus == false) {
        var blinkNumber = 0;
        var titleChanged = 0;
        for (x in newMessagesWin) {
            if (newMessagesWin[x] == true) {
                ++blinkNumber;
                if (blinkNumber >= blinkOrder) {
                        document.title = x+' says...';
                        titleChanged = 1;
                        break;	
                }
            }
        }
        if (titleChanged == 0) {
            document.title = originalTitle;
            blinkOrder = 0;
        } else {
            ++blinkOrder;
        }
    } else {
        for (x in newMessagesWin) {
            newMessagesWin[x] = false;
        }
    }

    for (x in newMessages) {
        if (newMessages[x] == true) {
            if (chatboxFocus[x] == false) {
                //FIXME: add toggle all or none policy, otherwise it looks funny
                $('#chatbox_'+x+' .chatboxhead').toggleClass('chatboxblink');
            }
        }
    }
	
    $.ajax({
        //url: "chat.php?action=chatheartbeat",
        url: '{{ route("chatsession") }}' + '/' + 'action' + '/'  + 'chatheartbeat',
        cache: false,
        dataType: "json",
        success: function(data) {
            img = 'avatar_default.png';
            $.each(data.items, function(i,item){
                if (item)	{ // fix strange ie bug
                            
                            chatboxtitle = item.f;
                            toid = item.x;
                            img = item.p2;
                            status = item.st;
                            msgtype = item.mtype;
                            img = 'avatar_default.png';
                            if ($("#chatbox_"+chatboxtitle).length <= 0) {
                                    createChatBox(chatboxtitle,toid,img,status);
                                    //get_all_msg(chatboxtitle);
                                    audiomp3.play();
                                    audioogg.play();
                            }
                            if ($("#chatbox_"+chatboxtitle).css('display') == 'none') {
                                    $("#chatbox_"+chatboxtitle).css('display','block');
                                    restructureChatBoxes();
                            }

                            if (item.s == 1) {
                                    item.f = username;
                            }

            var message_content = item.m;
            if (msgtype=="text")
                message_content = item.m;
            else if (msgtype=="file") {

                var str = item.m;
                str = str.replace(/&quot;/g, '"');
                var file_content = JSON.parse(str);
                var message_content="";

                if (file_content.file_type == "image")
                    message_content = "<a url='"+file_content.file_path+"' onclick='trigq(this)'><img src='http://www.byweb.online/zechat/storage/user_files/small"+file_content.file_name+"' style='max-width:156px;padding: 4px 0 4px 0; border-radius: 7px;cursor: pointer;'/></a>";
                else
                    message_content = "<a href='"+file_content.file_path+"'>Download : "+file_content.file_name+"</a>";

            }

                            if (item.s == 2) {
                                    $("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage direct-chat-msg"><div class="_5w-5"><div class="_5w-6"><abbr class="livetimestamp">'+item.m+'</abbr></div></div></div>');
                            } else {
                                item.p = 'avatar_default.png';
                message_content = Emotions (message_content,chatboxtitle);   // Set imotions
                                    newMessages[chatboxtitle] = true;
                                    newMessagesWin[chatboxtitle] = true;
                                    $("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage direct-chat-msg"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left">'+item.f+'</span></div><img class="direct-chat-img" src="storage/user_image/'+item.p+'" alt="message user image"><span class="direct-chat-text">'+message_content+'</span></div>');
                                    audiomp3.play();
                                    audioogg.play();
                            }


                            itemsfound += 1;
                    }
            });
            chatHeartbeatCount++;
            if (itemsfound > 0) {
                chatHeartbeatTime = minChatHeartbeat;
                chatHeartbeatCount = 1;
            } else if (chatHeartbeatCount >= 10) {
                chatHeartbeatTime *= 2;
                chatHeartbeatCount = 1;
                if (chatHeartbeatTime > maxChatHeartbeat) {
                    chatHeartbeatTime = maxChatHeartbeat;
                }
            }
            // if (itemsfound > 0) {
                $("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
             // }
        }
    });
    // setTimeout('chatHeartbeat();',chatHeartbeatTime);
}


function handleIncomingMsg(item){
    img = 'avatar_default.png';
        if (item)   { // fix strange ie bug
                    
                    chatboxtitle = item.f;
                    toid = item.x;
                    img = item.p2;
                    status = item.st;
                    msgtype = item.mtype;
                    img = 'avatar_default.png';
                    if ($("#chatbox_"+chatboxtitle).length <= 0) {
                            createChatBox(chatboxtitle,toid,img,status);
                            //get_all_msg(chatboxtitle);
                            audiomp3.play();
                            audioogg.play();
                    }
                    if ($("#chatbox_"+chatboxtitle).css('display') == 'none') {
                            $("#chatbox_"+chatboxtitle).css('display','block');
                            restructureChatBoxes();
                    }

                    if (item.s == 1) {
                            item.f = username;
                    }

    var message_content = item.m;
    if (msgtype=="text")
        message_content = item.m;
    else if (msgtype=="file") {

        var str = item.m;
        str = str.replace(/&quot;/g, '"');
        var file_content = JSON.parse(str);
        var message_content="";

        if (file_content.file_type == "image")
            message_content = "<a url='"+file_content.file_path+"' onclick='trigq(this)'><img src='http://www.byweb.online/zechat/storage/user_files/small"+file_content.file_name+"' style='max-width:156px;padding: 4px 0 4px 0; border-radius: 7px;cursor: pointer;'/></a>";
        else
            message_content = "<a href='"+file_content.file_path+"'>Download : "+file_content.file_name+"</a>";

    }

                    if (item.s == 2) {
                            $("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage direct-chat-msg"><div class="_5w-5"><div class="_5w-6"><abbr class="livetimestamp">'+item.m+'</abbr></div></div></div>');
                    } else {
                        item.p = 'avatar_default.png';
        message_content = Emotions (message_content,chatboxtitle);   // Set imotions
                            newMessages[chatboxtitle] = true;
                            newMessagesWin[chatboxtitle] = true;
                            $("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage direct-chat-msg"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left">'+item.f+'</span></div><img class="direct-chat-img" src="storage/user_image/'+item.p+'" alt="message user image"><span class="direct-chat-text">'+message_content+'</span></div>');
                            audiomp3.play();
                            audioogg.play();
                    }


                    itemsfound += 1;
            }
    if (itemsfound > 0) {
        $("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
     }
}

function get_all_msg(url){
    return false;
	$.ajax({
	  //url: "chat.php?action=get_all_msg&client="+client,
	  url: url,
	  cache: false,
	  dataType: "json",
	  success: function(data) {
		$.each(data.items, function(i,item){                   
			if (item){ // fix strange ie bug
                            
				chatboxtitle = item.f;
				toid = item.x;
				img = item.p;
				status = item.st;
                                
				page = item.page;
                                pages = item.pages;
                                msgtype = item.mtype;
                                img = 'avatar_default.png';
				/*if ($("#chatbox_"+chatboxtitle).length <= 0) {
					createChatBox(chatboxtitle,toid,img,status,1);
				}*/

                                if (item.page != "" && i == 0) {
                                        $("#chatbox_"+chatboxtitle+" .chatboxcontent").html(' ');
					$("#chatbox_"+chatboxtitle+" .chatboxcontent").prepend('<input type="hidden" class="pagenum" value="'+item.page+'" /><input type="hidden" class="total-page" value="'+pages+'" />');
				}

				if (item.s == 1) {
					//item.f = username;
				}

                var message_content = item.m;
                if (msgtype=="text")
                    message_content = item.m;
                else if (msgtype=="file") {

                    var str = item.m;
                    str = str.replace(/&quot;/g, '"');
                    var file_content = JSON.parse(str);
                    var message_content="";
                    /*onclick='lightpopup(event,this);'*/
                    if (file_content.file_type == "image")
                        message_content = "<a url='"+file_content.file_path+"' onclick='trigq(this)'><img src='http://www.byweb.online/zechat/storage/user_files/small"+file_content.file_name+"' style='max-width:156px;padding: 4px 0 4px 0; border-radius: 7px;cursor: pointer;'/></a>";
                    else
                        message_content = "<a href='"+file_content.file_path+"'>Download : "+file_content.file_name+"</a>";

                }

				if (item.s == 2) {
					$("#chatbox_"+chatboxtitle+" .chatboxcontent").prepend('<div class="chatboxmessage"><div class="_5w-5"><div class="_5w-6"><abbr class="livetimestamp">'+item.m+'</abbr></div></div></div>');
				} else {

                    message_content = Emotions (message_content,chatboxtitle);   // Set imotions
					if (item.u == 2) {
					$("#chatbox_"+chatboxtitle+" .chatboxcontent").prepend('<div class="chatboxmessage direct-chat-msg"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left">'+item.sender+'</span></div><img class="direct-chat-img" src="storage/user_image/'+img+'" alt="message user image"><span class="direct-chat-text">'+message_content+'</span></div>');
					} else {
						$("#chatbox_"+chatboxtitle+" .chatboxcontent").prepend('<div class="chatboxmessage direct-chat-msg right"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right">'+item.sender+'</span></div><img class="direct-chat-img" src="storage/user_image/'+img+'" alt="message user image"><span class="direct-chat-text">'+message_content+'</span></div>');
					}
				}
			}
		});

          if (page == 1) {
              //$("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
          }

          /*for (i=0;i<chatBoxes.length;i++) {
			chatboxtitle = chatBoxes[i];
			$("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
			setTimeout('$("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);', 100); // yet another strange ie bug
          }*/

	}});
	
	
}

function closeChatBox(chatboxtitle) {
    $('#chatbox_'+chatboxtitle).css('display','none');
    restructureChatBoxes();

    $.post("{{ route('closechat') }}", { chatbox: chatboxtitle} , function(data){ console.log(data);
    });

}

function toggleChatBoxGrowth(chatboxtitle) {
	
    if ($('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display') == 'none') {
		var minimizedChatBoxes = new Array();

        if ($.cookie('chatbox_minimized')) {
            minimizedChatBoxes = $.cookie('chatbox_minimized').split(/\|/);
        }

        var newCookie = '';

        for (i=0;i<minimizedChatBoxes.length;i++) {
            if (minimizedChatBoxes[i] != chatboxtitle) {
                newCookie += chatboxtitle+'|';
            }
        }

        newCookie = newCookie.slice(0, -1)


        $.cookie('chatbox_minimized', newCookie);
        $('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display','block');
        $('#chatbox_'+chatboxtitle+' .chatboxinput').css('display','block');
        $("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
    } else {
		var newCookie = chatboxtitle;

		if ($.cookie('chatbox_minimized')) {
			newCookie += '|'+$.cookie('chatbox_minimized');
		}


		$.cookie('chatbox_minimized',newCookie);
		$('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display','none');
		$('#chatbox_'+chatboxtitle+' .chatboxinput').css('display','none');
    }

}

function checkChatBoxInputKey(event,chatboxtextarea,chatboxtitle,toid,img) {
    
    if(event.keyCode == 13 && event.shiftKey == 0)  {
        message = $(chatboxtextarea).val();
        message = message.replace(/^\s+|\s+$/g,"");

        $(chatboxtextarea).val('');
        $(chatboxtextarea).focus();
        $(chatboxtextarea).css('height','24px');
        connection.send(JSON.stringify({msg: message, recipient: toid}));
        // connection.send(message);
        $("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
        return;//Start here
        var token   =   '<?php echo Session::token(); ?>'; console.log(token);
        if (message != '') {
            img = 'avatar_default.png';
            $.post('{{ route("sendchat") }}', {to: chatboxtitle, toid: toid, message: message, _token: token} , function(data){
                message = message.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\"/g,"&quot;");

                var $con = message;
                var $words = $con.split(' ');
                for (i in $words) {
                    if ($words[i].indexOf('http://') == 0 || $words[i].indexOf('https://') == 0) {
                        $words[i] = '<a href="' + $words[i] + '">' + $words[i] + '</a>';
                    }
                    else if ($words[i].indexOf('www') == 0 ) {
                        $words[i] = '<a href="' + $words[i] + '">' + $words[i] + '</a>';
                    }
                }
                message = $words.join(' ');
                message = Emotions (message);   // Set imotions
                $("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage direct-chat-msg right"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right">'+username+'</span></div><img class="direct-chat-img" src="storage/user_image/'+img+'" alt="message user image"><span class="direct-chat-text">'+message+'</span></div>');
                $("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
            });
        }
        chatHeartbeatTime = minChatHeartbeat;
        chatHeartbeatCount = 1;

        return false;
    }

    var adjustedHeight = chatboxtextarea.clientHeight;
    var maxHeight = 94;

    if (maxHeight > adjustedHeight) {
        adjustedHeight = Math.max(chatboxtextarea.scrollHeight, adjustedHeight);
        if (maxHeight)
            adjustedHeight = Math.min(maxHeight, adjustedHeight);
        if (adjustedHeight > chatboxtextarea.clientHeight)
            $(chatboxtextarea).css('height',adjustedHeight+8 +'px');
    } else {
        $(chatboxtextarea).css('overflow','auto');
    }

}

function startChatSession(){
    $.ajax({
        url: '{{ route("startchatsession") }}' + '/' + 'action' + '/'  + 'startchatsession',
        //url: "chat.php?action=startchatsession",
        cache: false,
        dataType: "json",
        success: function(data) {console.log(data);
            console.log(data);
            username = data.username;

            $.each(data.items, function(i,item){
                if (item)	{ // fix strange ie bug

                    chatboxtitle = item.f;
                    toid = item.x;
                    //img = item.spic;
                    img = item.p2;
                    status = item.st;

                    if ($("#chatbox_"+chatboxtitle).length <= 0) {
                        createChatBox(chatboxtitle,toid,img,status,1);
                    }

                    if (item.s == 1) {
                        item.f = username;
                    }

                    if (item.s == 2) {
                        //$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><div class="_5w-5"><div class="_5w-6"><abbr class="livetimestamp">'+item.m+'</abbr></div></div></div>');
                    } else {

                        if (item.u == 2) {
                            //$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage direct-chat-msg"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left">'+item.f+'</span></div><img class="direct-chat-img" src="storage/user_image/'+item.p+'" alt="message user image"><span class="direct-chat-text">'+item.m+'</span></div>');
                        } else {
                            //$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage direct-chat-msg right"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right">'+item.f+'</span></div><img class="direct-chat-img" src="storage/user_image/'+img+'" alt="message user image"><span class="direct-chat-text">'+item.m+'</span></div>');
                        }
                    }
                }
            });

            for (i=0;i<chatBoxes.length;i++) {
                chatboxtitle = chatBoxes[i];
                $("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
                setTimeout('$("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);', 100); // yet another strange ie bug

            }

            // setTimeout('chatHeartbeat();',chatHeartbeatTime);
    }});
}

/**
 * Cookie plugin
 *
 * Copyright (c) 2015 Dev Katariya (Bylancer.com)

 *
 */

jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

var lastTypedTime = new Date(0); // it's 01/01/1970, actually some time in the past
var typingDelayMillis = 5000; // how long user can "think about his spelling" before we show "No one is typing -blank space." message

function refreshTypingStatus(chatboxtitle,toid){
	/*if (!$('#textarea').is(':focus') || $('#textarea').val() == '' || new Date().getTime() - lastTypedTime.getTime() > typingDelayMillis) {
        $("#typing_on").html('');
    } else {
        //$("#typing_on").html('User is typing...');
		$.post("{{ route("chatsession") }}?action=typingstatus", {to: chatboxtitle, toid: toid, typing: 1} , function(data){
			
		});	
    }*/
}
function updateLastTypedTime() {
    lastTypedTime = new Date();
}
// setInterval(refreshTypingStatus, 100);


</script>

<!--View Sales Order Detail Modal-->
<div id="sales-order-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog dsboard-custom-modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Sales Order Detail </h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal" id="viewsalesorderdetail"></div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>



<script>
 /* alert message script*/

  $('document').ready(function(){
    var user=0;

//Sortable section js for Boxes
       var section_name=[];
        $( ".reArrangeDiv" ).sortable({
            items: '.reArrangeSection',
            appendTo: "parent",
            helper: "clone",
            axis: "x",
            update:function(event, ui){
               section_name=$('.reArrangeDiv').sortable("toArray", {attribute: 'section_name_attr'});

              $.ajax({
                        type: "POST",
                        url: base_url+'/updatepriority/'+user,
                        data: {
                            'section_name'  :section_name,
                            '_token'        : $('input[name=_token]').val()
                        },
                        success: function(result)
                        {
                        }
                    });
            }
        }).disableSelection();
//Close Sortable section js for Boxes

//sortable for graph

     $( ".reArrangeDivGraph" ).sortable({
        items: '.reArrangeSection',
        appendTo: "parent",
        helper: "clone",
        axis: "x",
        update:function(event, ui){
           section_name=$('#reArrangeDivGraph').sortable("toArray", {attribute: 'section_name_attr'});

          $.ajax({
                    type: "POST",
                    url: base_url+'/updatepriority/'+user,
                    data: {
                        'section_name'  :section_name,

                        '_token'        : $('input[name=_token]').val()
                    },
                    success: function(result)
                    {
                    }
                });
        }
    }).disableSelection();

//End section of sortable graph

});//Closing of document.ready

    function messageDetail(msgId){
     $.ajax({
      type: "POST",
      url: base_url+'/single-message',
      data: {
        'msgId'   :msgId,
        '_token'  : $('input[name=_token]').val()
      },
      success: function(result)
      {
        var data= JSON.parse(result);
        $('.home-message-box span.mSub').text(data.subject);
        $('.home-message-box span.mBy').text(data.by);
        $('.home-message-box span.mDate').text(data.date);
        $('.home-message-box .message-content').html(data.body);
      $(this).css('cursor','pointer');
      }
    });
}


    $(document).ready(function(){
        var msgcount= parseInt('{{count($messages)}}');
        if (msgcount>0) {
            $('#messagealert2').modal('show');
            $('#messagealert2').attr("id","messagealert2show");
        }
        $(".message_detail").click(function(){
            $('#messageSection table tr').removeClass('currentMsg');
            var msgId= $(this).closest('tr').attr('msgid');
            $(this).closest('tr').addClass('currentMsg');
            messageDetail(msgId);
        });
        var delMsgCount=0;
         $('.hidemsgsection').each(function(){
                    delMsgCount++;
            });
        $(".confirm-alert-btn").click(function(){
            delMsgCount--;
            var row=$(this);
            var msgid= parseInt(row.attr('rowid'));
            $.ajax({
                    type: "GET",
                    url: base_url+'/markreadmessage/'+msgid,
                    data: {},
                    success: function(result)
                    {
                        if (result==1) {
                            if(delMsgCount ==0)
                                $('#messagealert2').modal("hide");
                            else
                                row.closest('.alertmessgbody').hide("slide",{ direction: "right" }, 500);
                        }
                        else{
                            warningPopupMsg('Unable To Update Status');
                        }
                    }
                });
        });

    //Hide Sections from dashboard JS

        $(document).on('click','.dash-access', function(){
            divToHide = $(this).closest('div');
            var section_name=$(this).closest("div").attr("section_name_attr");
            if(typeof section_name === "undefined"){
            var section_name=$(this).parent('div').parent('div').parent('div').parent('div').attr('section_name_attr');
            graphToHide=$(this).parent('div').parent('div').parent('div').parent('div');
            }
                  $.ajax({
                    type: "POST",
                    url: base_url+'/hide_section_from_dash',
                    data: {
                        'section_name'   :section_name,
                        '_token'  : $('input[name=_token]').val()
                    },
                    success: function(result)
                    {
                        if (result==1) {
                            divToHide.hide();
                            graphToHide.hide();
                        }
                        else{
                            alert('Unable to hide this section');
                        }
                    }
                });
        });

    //Closing of hide section JS

    });//Closing Of document.ready

</script>
<style>

</style>
@endsection

    

