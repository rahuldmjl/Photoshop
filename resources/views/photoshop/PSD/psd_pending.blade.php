
@extends('layout.photo_navi')


@section('title', 'Psd Pending')

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
    {{ Breadcrumbs::render('psd.pending') }}
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
				  <h5 class="box-title">PSD pending Filter</h5>
				  <div class="tabs">
					<ul class="nav nav-tabs">
					 
					  <li class="nav-item active"><a class="nav-link" href="#profile-tab" data-toggle="tab" aria-expanded="true">Filter</a>
					  </li>
					  <li class="nav-item "><a class="nav-link" href="#changestatus" data-toggle="tab" aria-expanded="true">Change Status</a>
					  </li>
					</ul>
					
				  
					<!-- /.nav-tabs -->
					<div class="tab-content">
				
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
										@foreach ($category as $item)
									  <option value="{{$item->entity_id}}">{{$item->name}}</option>
										@endforeach
									  </select>	
									</div>
								  </div>
								  <div class="col-md-3">
									<div class="form-group">
									  <select class="form-control" disabled name="color" id="color">
										<option value="">Select Color</option>
										
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
						<div class="tab-pane" id="changestatus">
						  <div class="widget-list">
							<div class="row">
							 <div class="col-md-12 col-sm-12 widget-holder widget-full-height">
								<form action="javascript:void(0)" style="margin-left: 21%;" method="post">
									<div style="float:left">
										<select style="width: 506px;" id="bulk_status_change_status" class="form-control" name="status">
								<option value="2">Pending</option>
                                <option value="1">In processing</option>
                                <option value="3">Done</option>
								 </select></div>
								<input type="submit" style="margin-left: 20px;" id="bulk_status_change"  value="submit" class="btn btn-primary"/>
							
								</form>
								
							  </div>
							 
							</div>
							<!-- /.row -->
						
						  </div>
						</div>
					  
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
  						
	                    <table class="table table-striped table-center word-break mt-0"   id="psdending" >
  							<thead>
  								<tr class="bg-primary">
									<th class="checkboxth">
										<div class="checkbox checkbox-primary" style="width: 100px;">
										  <label>
											  <input type="checkbox" id="chkAllProduct"> <span class="label-text"></span>
										  </label>
									  </div>
									  </th>
  									<th>Sku</th>
									  <th>Color</th>
									  <th>Category</th>
									  <th>Status</th>
  									<th>Action</th>
  								
  								</tr>
  							</thead>
  							<tbody>
		 @foreach ($psdpending as $item)
		
		 <?php 
									$product=$item->getProduct;
									$category=$item->category;

									?>
	
	<tr>
		<td><div class="checkbox checkbox-primary" style="width: 100px;">
			<label >
			<input type="checkbox" value="{{$item->product_id}}" class="chkProduct" name="chkProduct" id="chkProduct"> <span class="label-text"></span>
			</label>
		</div></td>
		<td><?php echo $product['sku'];?></td>
		<td><?php echo $product['color'];?>
		</td>
    <td>{{$category->name}}</td>
    <td>Pending</td>
		<td style="    float: right;">
			<form action="" method="POST">
				@csrf
				<input type="hidden" value="{{$item->product_id}}" name="product_id"/>
				<input type="hidden" value="{{$item->category_id}}" name="category_id"/>
			
				<select name="status" class="form-control" style="height:20px;width:150px;float: left;">
					<option value="2">Pending</option>
					<option value="1">In processing</option>
					<option value="3">Done</option>
				</select>
				<input type="submit" style="height:20px;" class="btn btn-primary" value="Submit"/>
		
			</form>
			</td>
	
	
	</tr>

									
									@endforeach
							  </tbody>
							  <tfoot>
								<tr class="bg-primary">
									<th></th>
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
<input type="hidden" id="psdpendinglist" value="{{route('psdpendinglist')}}">
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
<script>
	 $("#chkAllProduct").click(function(){
    $('.chkProduct').prop('checked', this.checked);
});
$('#bulk_status_change').click(function(){
  var action = $('#bulk_status_change_status option:selected').val();
  var favorite = [];
  if(action=='3'){
   
            $.each($("input[name='chkProduct']:checked"), function(){
                favorite.push($(this).val());
            });
            $.ajax({
            type: 'POST',
            url: "{{route('psdchangeajaxlist')}}",
            data: {action :action,status: favorite,"_token": "{{ csrf_token() }}"},
            dataType: 'html',
             
            success: function (data) {
              var res = JSON.parse(data);
            
             if(res.action=="0"){
             swal({
									title: 'Error',
									text: res.message,
									type: 'warning',
									buttonClass: 'btn btn-danger'
								  });
								
              
             }
            table.draw();
             
			 console.log(data);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
     }else{
        swal({
									title: 'Error',
									text: 'Select Status Done',
									type: 'warning',
									buttonClass: 'btn btn-danger'
								  });
								
    }

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
	var table = $('#psdending').DataTable({
		"dom": "<'row mb-2 align-items-center'<'col-auto dataTable-length-tb-0'l><'col'B>><'row'<'col-md-12' <'user-roles-main' t>>><'row'<'col-md-3'i><'col-md-6 ml-auto'p>>",
  "lengthMenu": [[10, 50, 100, 200,500], [10, 50, 100, 200,500]],
  "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 0] }, 
       
    ],
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
 
  "deferLoading": <?=$psdpending->count()?>,
  "processing": true,
  "serverSide": true,
  "searching": false,
  "serverMethod": "post",
  "ajax":{
	"url": $("#psdpendinglist").val(),
	"data": function(data, callback){
		data._token = "{{ csrf_token() }}";
		var category = $('#category').children("option:selected").val();

    if(category != ''){
      data.category = category;
    }
	var sku=$('#sku').val();
	if(sku !=''){
		data.sku=sku;
	}
	
     showLoader();
   
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

	table.draw();
});
</script>
@endsection