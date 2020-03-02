
@extends('layout.photo_navi')


@section('title', 'Product List')

@section('distinct_head')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="<?=URL::to('/');?>/cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

@endsection
<style>
    table, td, th {
      
       width: 300px;
    }
 </style>
@section('body_class', 'header-light sidebar-dark sidebar-expandheader-light sidebar-dark sidebar-expand')

@section('content')
<main class="main-wrapper clearfix">
  <!-- Page Title Area -->
  <div class="row page-title clearfix">
    {{ Breadcrumbs::render('product_list') }}
      <!-- /.page-title-right -->
  </div>
  <!-- /.page-title -->
  <!-- =================================== -->
  <!-- Different data widgets ============ -->
  <!-- =================================== -->
  <div class="col-md-12 widget-holder loader-area" style="display: none;">
    <div class="widget-bg text-center">
      <div class="loader"></div>
    </div>
  </div>
  	<div class="widget-list">

		<div class="row">
			<div class="col-md-12 widget-holder">
				<div class="widget-bg">
					<div class="widget-body clearfix">
						<h5 class="box-title">Product List</h5>
						<div class="tabs">
							<ul class="nav nav-tabs">
								<li class="nav-item " ><a class="nav-link" href="#home-tab" data-toggle="tab" aria-expanded="true">Home</a>
								</li>
								<li class="nav-item active"><a class="nav-link" href="#profile-tab" data-toggle="tab" aria-expanded="true">Filter</a>
								</li>
							
							</ul>
							
						
							<!-- /.nav-tabs -->
							<div class="tab-content">
								<div class="tab-pane" id="home-tab">
									<div class="widget-list">
										<div class="row">
											<!-- Counter: Sales -->
											<div class="col-md-4 col-sm-6 widget-holder widget-full-height">
												<div class="widget-bg bg-primary text-inverse">
													<div class="widget-body">
														<div class="widget-counter">
															<h6>Total  <small class="text-inverse">Product</small></h6>
															<h3 class="h1"><span class="counter">{{$color->count()}}</span></h3><i class="material-icons list-icon">event_available</i>
														</div>
														<!-- /.widget-counter -->
													</div>
													<!-- /.widget-body -->
												</div>
												<!-- /.widget-bg -->
											</div>
											<!-- /.widget-holder -->
											<!-- Counter: Subscriptions -->
											<div class="col-md-4 col-sm-6 widget-holder widget-full-height">
												<div class="widget-bg bg-color-scheme text-inverse">
													<div class="widget-body clearfix">
														<div class="widget-counter">
															<h6>Total <small class="text-inverse">Done</small></h6>
															<h3 class="h1"><span class="counter">{{$color->where('status',1)->count()}}</span></h3><i class="material-icons list-icon">event_available</i>
														</div>
														<!-- /.widget-counter -->
													</div>
													<!-- /.widget-body -->
												</div>
												<!-- /.widget-bg -->
											</div>
											<div class="col-md-4 col-sm-6 widget-holder widget-full-height">
												<div class="widget-bg badge-danger text-inverse">
													<div class="widget-body clearfix">
														<div class="widget-counter">
															<h6>Total <small class="text-inverse">Pending</small></h6>
															<h3 class="h1"><span class="counter">{{$color->where('status',0)->count()}}</span></h3><i class="material-icons list-icon">event_available</i>
														</div>
														<!-- /.widget-counter -->
													</div>
													<!-- /.widget-body -->
												</div>
												<!-- /.widget-bg -->
											</div>
										</div>
										<!-- /.row -->
								
									</div>
								</div>
								<div class="tab-pane  active" id="profile-tab">
									<div class="col-md-12 widget-holder content-area">
										<div class="widget-bg">
											<div class="widget-heading clearfix">
												<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">Filter</h5>
											  
											</div>
											<div class="widget-body clearfix dataTable-length-top-0">
												
													<div class="row">
														<div class="col-md-3">
															<div class="form-group">
																<select class="form-control" name="category" id="category">
																	<option value="">Select Category</option>
																	@foreach($category as $cat){
																		<option value={{$cat->entity_id}}>{{$cat->name}}</option>
																	
																	@endforeach
																</select>	
															</div>
														</div>
														<div class="col-md-3">
															<div class="form-group">
																<select class="form-control" name="color" id="color">
																	<option value="">Select Color</option>
																	@foreach($color->unique('color') as $user){
																		<option>{{$user->color}}</option>
																	
																	@endforeach
																</select>	
															</div>
														</div>
														
														<div class="col-md-3">
															<div class="form-group">
																<select class="form-control" name="status" id="status">
																	<option value="">Select Status</option>
																	<option value="0">Pending</option>
																	<option value="1">Done</option>
																</select>	
															</div>
														</div>
														<div class="col-md-3">
															<div class="form-group">
																<input class="form-control" id="sku" name="sku" style="height: 43px;" placeholder="Sku Search" type="text">
															</div>
														</div>
														<div class="col-md-3">
															<div class="form-group">
																<input class="btn btn-primary" style="height: 43px;" id="searchfilter"   type="submit" value="Apply">
																<input class="btn btn-success" style="height: 43px;" id="reset"   type="submit" value="Reset">
															
															</div>
														</div>
													</div>
												
						
						
											</div>
										</div>
									</div>	</div>
								
							</div>
							<!-- /.tab-content -->
						</div>
						<!-- /.tabs -->
					</div>
					<!-- /.widget-body -->
				</div>
				<!-- /.widget-bg -->
			</div>
	
		</div>
		
      	<div class="row">
  			<div class="col-md-12 widget-holder content-area">
  				<div class="widget-bg">
  					<div class="widget-heading clearfix">
  						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">Product List</h5>
						
  					</div>
  					<div class="widget-body clearfix dataTable-length-top-0">
  						
	                    <table class="table table-striped table-center word-break mt-0" id="Productlist" >
  							<thead>
  								<tr class="bg-primary">
  									<th>Sku</th>
									  <th>Color</th>
									  <th>Category</th>
									  <th>Status</th>
  									<th>Action</th>
  								
  								</tr>
  							</thead>
  							<tbody>
					
                     @foreach($data as $key =>$item)
									
	                <tr>
		                <td>{{$item->sku}}</td>
	                    <td>{{$item->color}}</td>
						<td>{{$item->category->name}}</td>
						<td>
							<?php 
							if($item->status=='0')
							{
								echo "Pending";
							}
							else {
								echo "Done";
							}
							?>
							</td>
                      <td>
						<?php 
						if($item->status=='0')
						{
							?>
								<a class="color-content table-action-style btn-delete-customer" data-href="{{ route('photography.product.delete',['id'=>$item->id]) }}" style="cursor:pointer;"><i class="material-icons md-18">delete</i></a>
								<a href="javascript:void(0);"  class="color-content disabled table-action-style"><i class="material-icons md-18">remove_red_eye</i></a>
							
							<?php 
						}
						else {
							?>
							<a href="javascript:void(0)"   class="color-content disabled table-action-style"><i class="material-icons md-18">delete</i></a>
							
							<a href="{{ route('product.view',['id'=>$item->id]) }}" class="color-content  table-action-style"><i class="material-icons md-18">remove_red_eye</i></a>
							<?php 
						}
						?>
						
						</td>

    </tr>
   
	@endforeach

							  </tbody>
							  <tfoot>
								<tr class="bg-primary">
									<th>Sku</th>
									<th>Color</th>
									<th>Category</th>
									<th>Status</th>
                                    <th>Action</th>
								
								</tr>
							</tfoot>
	  					</table>
  					</div>
  				</div>
  			</div>
  		</div>
    </div>
  <!-- /.widget-list -->
</main>
<!-- /.main-wrappper -->
<input type="hidden" id="userAjax" value="<?=URL::to('/Photoshop/Product/ajaxlist');?>">

<style type="text/css">
.form-control[readonly] {background-color: #fff;}
</style>
@endsection

@section('distinct_footer_script')
<script src="<?=URL::to('/');?>/cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js"></script>
<script src="<?=URL::to('/');?>/js/jquery.validate.min.js"></script>
<script src="<?=URL::to('/');?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
	$(document).on('click','.btn-delete-customer',function(){
			var deleteUrl = $(this).data('href');
		    swal({
		        title: 'Are you sure?',
		         type: 'info',
				 text:'Delete This Product',
		        showCancelButton: true,
		        confirmButtonText: 'Confirm',
		        confirmButtonClass: 'btn-confirm-all-productexcel btn btn-info'
		        }).then(function(data) {
		        	if (data.value) {
		        		window.location.href = deleteUrl;
		        	}

		    });
		});
	
		var buttonCommon = {
        exportOptions: {
            format: {
                body: function ( data, row, column, node ) {                    
                    if (column === 3) {
                      data = data.replace(/(&nbsp;|<([^>]+)>)/ig, "");
                    }
                    return data;
                }
            }
        }
	};

	var table = $('#Productlist').DataTable({
		"dom": "<'row mb-2 align-items-center'<'col-auto dataTable-length-tb-0'l><'col'B>><'row'<'col-md-12' <'user-roles-main' t>>><'row'<'col-md-3'i><'col-md-6 ml-auto'p>>",
  "lengthMenu": [[10, 50, 100, 200,500], [10, 50, 100, 200,500]],
  "buttons": [
	$.extend( true, {}, buttonCommon, {
      extend: 'csv',
      footer: false,
      title: 'Photography-product-list',
      className: "btn btn-primary btn-sm px-3",
      exportOptions: {
          columns: [0,1,2,3],
          orthogonal: 'export'
      }
    }),
	$.extend( true, {}, buttonCommon, {
      extend: 'excel',
      footer: false,
      title: 'Photography-product-list',
      className: "btn btn-primary btn-sm px-3",
      exportOptions: {
          columns: [0,1,2,3],
          orthogonal: 'export'
      }
    })
  ],
  "language": {
    "search": "",
    "infoEmpty": "No matched records found",
    "zeroRecords": "No matched records found",
    "emptyTable": "No data available in table",
    /*"sProcessing": "<div class='spinner-border' style='width: 3rem; height: 3rem;'' role='status'><span class='sr-only'>Loading...</span></div>"*/
  },
  "order": [[ 0, "desc" ]],
  "deferLoading": <?=$datacount?>,
  "processing": true,
  "serverSide": true,
  "searching": false,
  "serverMethod": "post",
  "ajax":{
    "url": $("#userAjax").val(),
    "data": function(data, callback){
		data._token = "{{ csrf_token() }}";
		 showLoader();
		 console.log(data);
	  var sku = $('#sku').val();
	  
      if(sku != ''){
        data.sku = sku;
      }
	  var category = $('#category').children("option:selected").val();
      if(category != ''){
        data.category = category;
      }
	  var color = $('#color').children("option:selected").val();
      if(color != ''){
        data.color = color;
      }
	  var status = $('#status').children("option:selected").val();
      if(status != ''){
        data.status = status;
      }
	},
	complete: function(response){
      hideLoader();
    }
  }
	});
	$('#searchfilter').click(function(){
    table.draw();
  });
  $('#reset').click(function(){
	$('#sku').val('');
	$('#category option[value=""]').attr('selected','selected');
	$('#color option[value=""]').attr('selected','selected');
	$('#status option[value=""]').attr('selected','selected');

	$('#category').on('change', function() {
      if(this.value == ''){
        $('#category option[value=""]').attr('selected','selected');
      }else{
        $('#category option[value=""]').removeAttr('selected','selected');
      }
	});
	$('#color').on('change', function() {
      if(this.value == ''){
        $('#color option[value=""]').attr('selected','selected');
      }else{
        $('#color option[value=""]').removeAttr('selected','selected');
      }
	});
	$('#status').on('change', function() {
      if(this.value == ''){
        $('#status option[value=""]').attr('selected','selected');
      }else{
        $('#status option[value=""]').removeAttr('selected','selected');
      }
    });
	table.draw();
  });
</script>

@endsection