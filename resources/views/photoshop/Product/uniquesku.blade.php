
@extends('layout.photo_navi')
<?php
use App\Uniquedefine;
?>

@section('title', 'Unique Sku')

@section('distinct_head')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="<?=URL::to('/');?>/cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<style>
    table, td, th {
      
       width: 300px;
    }
 </style>
@endsection

@section('body_class', 'header-light sidebar-dark sidebar-expandheader-light sidebar-dark sidebar-expand')

@section('content')
<main class="main-wrapper clearfix">
  <!-- Page Title Area -->
  <div class="row page-title clearfix">
    {{ Breadcrumbs::render('uniquesku') }}
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
  			<div class="col-md-12 widget-holder content-area">
  				<div class="widget-bg">
  					<div class="widget-heading clearfix">
  						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">Unique Product List</h5>
						
					  </div>
					  @if(Session::has('success'))
					  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success') }}</p>
					  @endif
  					<div class="widget-body clearfix dataTable-length-top-0">
  						
	                    <table class="table table-striped table-center word-break mt-0"   data-toggle="datatables" >
  							<thead>
  								<tr class="bg-primary">
  									<th>Sr no</th>
									  <th>Sku</th>
                                      <th style="display: none">Action</th>
								
  									<th style="display: none">Action</th>
  								
  								</tr>
  							</thead>
  							<tbody>
                                  <?php 
                                  $i=1;
                                  ?>
		 @foreach ($datacollection->unique('sku') as $item)
	<?php 
								if(!Uniquedefine::checkproduct($item->sku))
								{
								?>
	     <tr>
		<td wdth="30px"><?php echo $i++;?></td>
		<td>{{$item->sku}}</td>
			<td style="display: none">{{$item->product_id}}</td>
			<td style="display: none">{{$item->category_id}}</td>
		
		
	
	</tr>
	<?php 
	}
	?>

									
									@endforeach
							  </tbody>
							  <tfoot>
								<tr class="bg-primary">
									<th>Sku</th>
									<th>Color</th>
                                   <th style="display: none">Action</th>
								   <th style="display: none">Action</th>
								
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
<div  class="modal fade modal-info fade bs-modal-md-primary" id="myModal" style="display: none">
	<div class="modal-dialog  modal-md">
		<div class="modal-content">
			<div class="modal-header text-inverse">
				<h5 class="modal-title" id="myMediumModalLabel">Medium </h5>
			</div>
			<div class="modal-body" >
			
				<form action="" method="post">
					@csrf
					<div class="form-group">
						<label for="username">Sku</label>
						<input class="form-control" type="text" name="sku" id="sku">
					</div>
					<div class="form-group">
						<input class="form-control" name="product_id" type="hidden" id="productid">
						<input class="form-control" name="category_id" type="hidden" id="category_id">
				
					</div>
					<div class="form-group mr-b-30">
						<label for="password">Sub Category</label>
						<select class="form-control" id="password" name="subcatid">
							<option>Select Sub Category</option>
							@foreach ($subcategory as $items)
						<option value="{{$items->id}}">{{$items->subcatname}}</option>
							
							@endforeach
						</select>
					</div>
					<div class="form-group mr-b-30">
						<label for="username">Sku Name</label>
						<input class="form-control" type="text" id="sku" name="skuname"  placeholder="Sku Name">
					
					</div>
					<div class="mr-b-30">
						<button class="btn btn-rounded btn-lg btn-success ripple" type="submit">Submit</button>
						
			
					</div>
				</form>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

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
$('table tbody tr  td').on('click',function(){
    $("#myModal").modal("show");
    $("#productid").val($(this).closest('tr').children()[2].textContent);
	$("#sku").val($(this).closest('tr').children()[1].textContent);
	$("#category_id").val($(this).closest('tr').children()[3].textContent);

	
	
});
</script>
@endsection