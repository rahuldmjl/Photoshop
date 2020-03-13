
@extends('layout.photo_navi')


@section('title', 'Photography Pending')

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
    {{ Breadcrumbs::render('photography.pending') }}
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
              <h5 class="box-title">Photography pending Filter</h5>
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
                                <h3 class="h1"><span class="counter">{{$datacount}}</span></h3><i class="material-icons list-icon">event_available</i>
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
                                <h3 class="h1"><span class="counter">{{$datacount-$totalpending->count()}}</span></h3><i class="material-icons list-icon">event_available</i>
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
                                <h3 class="h1"><span class="counter">{{$totalpending->count()}}</span></h3><i class="material-icons list-icon">event_available</i>
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
                                    @foreach($data->unique('color') as $user){
                                      <option>{{$user->color}}</option>
                                    
                                    @endforeach
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
  					<div style="float:left" class=" col-sm-4 widget-heading clearfix">
  						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">Photography Pending List</h5>
						
            </div>
          	<form action="javascript:void(0)" method="post">
              <div style="float:left" class=" col-sm-8 widget-heading clearfix">
  				
              <select style="float:left" id="bulk_status_change_status" class="form-control col-sm-6" name="status">
                <option value="2">Pending</option>
                <option value="1">In processing</option>
                <option value="3">Done</option>
              </select>
             
              <input type="submit" id="bulk_status_change" style="float:left;margin-left:30px" value="submit" class="col-sm-4 btn btn-primary"/>
  					</form>
						
  					</div>
  					<div class="widget-body clearfix dataTable-length-top-0">
  						<table class="table table-striped table-center word-break mt-0" id="pendinglist" >
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
  									<th>Action</th>
  								
  								</tr>
  							</thead>
  							<tbody>
				 @foreach ($pendinglist as $item)
<tr>
  <td><div class="checkbox checkbox-primary" style="width: 100px;">
    <label >
    <input type="checkbox" value="{{$item->id}}" class="chkProduct" name="chkProduct" id="chkProduct"> <span class="label-text"></span>
    </label>
</div></td>
		<td>{{$item->sku}}</td>
	
	<td>{{$item->color}} Gold</td>
	<td>
		{{$item->category->name}}
			
	</td>
		<td>
			<form  method="POST" action="{{route('pending.submit')}}">
			<input type="hidden" value="{{$item->id}}" name="product_id" id="product_id"/>
			<input type="hidden" value="{{$item->categoryid}}" name="category_id" id="category_id"/>
				@csrf
				<select name="status" id="status" class="form-control" style="height:20px;width:150px;float: left;">
					<option value="2">Pending</option>
					<option value="1">In processing</option>
					<option value="3">Done</option>
				</select>
				<input type="submit" style="height:20px;"  class="btn btn-submit btn-primary" value="Submit"/>
		
			</form>
			</td>

	</tr>
	
@endforeach
							  </tbody>
							  <tfoot>
								<tr class="bg-primary">
                  <th>Check All</th>
									<th>Sku</th>
									<th>Color</th>
									<th>Category</th>
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
<input type="hidden" id="pendinglistproductajax" value="<?=URL::to('/Photoshop/Photography/ajaxlist1');?>">

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
            url: "{{route('statusajaxlist')}}",
            data: {action :action,status: favorite,"_token": "{{ csrf_token() }}"},
            dataType: 'html',
             
            success: function (data) {
              var res = JSON.parse(data);
             
             if(res.status=="success"){
             swal({
									title: 'Success',
									text: res.message,
									type: 'success',
									buttonClass: 'btn btn-primary'
								  });
								
              
             }
             table.draw();
             
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
     }else{
     alert("Select Done Proper Data")
    }

});
	var table = $('#pendinglist').DataTable({
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
 
  "deferLoading": <?=$totalpending->count()?>,
  "processing": true,
  "serverSide": true,
  "searching": false,
  "serverMethod": "post",
  "ajax":{
	"url": $("#pendinglistproductajax").val(),
	"data": function(data, callback){
		data._token = "{{ csrf_token() }}";
     showLoader();
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