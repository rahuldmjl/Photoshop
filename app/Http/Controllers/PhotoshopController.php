<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\photography;
use App\category;
use App\productListModel;
use App\Helpers\PhotoshopHelper;
use App\photography_product;
use DB;
use Auth;
class PhotoshopController extends Controller
{
    public $product;
    public $photography;
   
    public function __construct()
    {
        $this->product=photography_product::all();
        $this->category=collect(category::all());
        $this->photography=photography::getphotographyProduct();
       
    }
    public function index()
    {

     
        $totoalproduct=count($this->product);
        $photo=collect($this->photography)->where('status',3);
        $totalphotographydone=count($photo);
        $pending=collect($this->product)->where('status',0);
        $totalphotographypending=count($pending);
        return view('Photoshop/Photography/index',compact('totoalproduct','totalphotographydone','totalphotographypending'));
    }

    /*
    Photography pending get data from this function
    */
    public function get_pending_list()
    {
       $pendinglist=array();
        $pendinglist=photography_product::all()->random(10)->take(10)->where('status', 0);
     
        $totalpending=collect($this->product)->where('status','=',0);
        $totaldoneproduct=collect($this->product)->where('status','=',1)->count();;
      $data=collect($this->product);
      $category=$this->category;
      $totalproduct= count($totalpending);  
      $datacount= count($this->product);  
   return view('Photoshop/Photography/photography_pending',compact('pendinglist','totalproduct','totalpending','datacount','data','category'));
 
  
    }

    public function ajax_get_pending_list(Request $request)
    {
        $data=array();
        $params = $request->post();
        $params = $request->post();
		$start = (!empty($params['start']) ? $params['start'] : 0);
		$length = (!empty($params['length']) ? $params['length'] : 10);
		$stalen = $start / $length;
		$curpage = $stalen;
        $maindata = photography_product::query();
     
        $datacount = $maindata->count();
		$datacoll = $maindata->where('status',0);
        $data["recordsTotal"] = $datacoll->count();
		$data["recordsFiltered"] = $datacoll->count();
        $data['deferLoading'] = $datacoll->count();
        
        if(!empty($params['sku']))
        {
            $maindata->where('sku',$params['sku']);
        }
        if(!empty($params['category']))
        {
            $maindata->where('categoryid',$params['category']);
        }
        if(!empty($params['color']))
        {
            $maindata->where('color',$params['color']);
       
        }
       
        $datacollection = $datacoll->take($length)->offset($start)->get();
        
        if(count($datacollection) > 0)
        {
            foreach($datacollection as $key => $product)
            {
                $token=$request->session()->token().
               $id=$product->id;
               $check='<div class="checkbox checkbox-primary" style="width: 100px;">
               <label>
               <input type="checkbox" class="chkProduct" value="'.$id.'" name="chkProduct" id="chkProduct"> <span class="label-text"></span>
               </label>
           </div>';
                $sku=$product->sku;
                $category=$product->category->name;
                $color=$product->color;
                $action1=route('pending.submit1');
                $action='<form action="'.$action1.'" method="GET">
              <input type="hidden" value="'.$id.'" name="product_id" id="product_id"/>
               <input type="hidden" value="'.$product->category->id.'" name="category_id" />
                   <select name="status" id="status" class="form-control" style="height:20px;width:150px;float: left;">
                       <option value="2">Pending</option>
                       <option value="1">In processing</option>
                       <option value="3">Done</option>
                   </select>
                   <input type="submit"  style="height:20px;" onclick="ajaxSave(this.val)" class="btn btn-submit1 btn-primary" value="Submit"/>
           
               </form>';
                $data['data'][] = array( $check,$sku, $color ,$category,$action);
            }
        }else{
            $data['data'][] = array('','', '', '', '', '');
	
        }
        echo json_encode($data);exit;
    }

     /*
    Photography done get data from this function
    */
    public function get_done_list()
    {
     $donelist=collect($this->photography)->where('status','=',3);
     return view('Photoshop/Photography/photography_done',compact('donelist'));
    }
     /*
    Photography Rework get data from this function
    */
    public function get_rework_list()
    {
     
       
         $reworklist=collect($this->photography)->where('status','=',4);
      return view('Photoshop/Photography/photography_rework',compact('reworklist'));
    }

    /*
    photography pending submit button action
    get all detail from photography pending list 

    */
 
    public function pending_list_submit(Request $request)
    {
        
        $user=Auth::user();
        
        $photoshop=new photography();
     
     
        if($request->input('status') !="1")
        {
            

            $photoshop->product_id=$request->input('product_id');

            $photoshop->category_id=$request->input('category_id');
            $photoshop->status=$request->input('status');
            $photoshop->current_status='1';
            $photoshop->next_department_status='0';
         
           //Cache table data Insert
           if($request->input('status')=='3')
           {
           $photoshop->save();
            $cache=array(
                'product_id'=>$request->input('product_id'),
                'url'=>PhotoshopHelper::getDepartment($request->url()),
                'status'=>$request->input('status'),
                'action_by'=>$user->id
    
    
            );
            
             PhotoshopHelper::store_cache_table_data($cache);
              photography_product::getUpdatestatusdone($request->input('product_id'));
             photography::getUpdatestatusdone($request->input('product_id'));
            
          
           }
        }
        return  redirect('Photoshop/Photography/pending')->with('message','Photoshop Status Change Successfull');
      
   }

   public function pending_list_submit1(Request $request)
   {
    $user=Auth::user();
        
    $photoshop=new photography();
 
 
    if($request->get('status') !="1")
    {
        

        $photoshop->product_id=$request->get('product_id');

        $photoshop->category_id=$request->get('category_id');
        $photoshop->status=$request->get('status');
        $photoshop->current_status='1';
        $photoshop->next_department_status='0';
     $cache=array();
       //Cache table data Insert
       if($request->get('status')=='3')
       {
       $photoshop->save();
        $cache[]=array(
            'product_id'=>$request->get('product_id'),
            'url'=>PhotoshopHelper::getDepartment($request->url()),
            'status'=>$request->get('status'),
            'action_by'=>$user->id
         );
        
         PhotoshopHelper::store_cache_table_data($cache);
          photography_product::getUpdatestatusdone($request->get('product_id'));
         photography::getUpdatestatusdone($request->get('product_id'));
        
      
       }
    }
    return  redirect('Photoshop/Photography/pending')->with('message','Photoshop Status Change Successfull');
  

   }
/*
done list submit for particular product change the photography status
done to rework 
*/
    public function submit_done_list(Request $request)
    {
        
        $user=Auth::user();
        $cache=array();
        if($request->input('status') !='0')
        {
            //cache table data insert 
            $cache[]=array(
                'product_id'=>$request->input('product_id'),
                'url'=>PhotoshopHelper::getDepartment($request->url()),
                'status'=>$request->input('status'),
                'action_by'=>$user->id
    
            );
            PhotoshopHelper::store_cache_table_data($cache);
            photography::update_photography_status($request->get('product_id'),$request->input('status'));
         if($request->input('status')=='4')
         {
             photography::delete_from_below_department($request->input('product_id'));
             photography::getUpdatestatusdone($request->input('product_id'));
             photography::updateprodtographystatus($request->input('product_id'));
        
         }
         
        
            return redirect()->back()->with('success', 'Photography status Change Successfull');
        }
        else{
            return redirect()->back()->with('success', 'Select the photography status');
        }
  
        
        
    }

  public function statusajax_List(Request $request)
  {
    $user=Auth::user();
    $response=array();
   $status=$request->get('status');
   $action=$request->get('action');
   $url="";
   $photoshop=array();
  
   $length=count($status);
   $url=PhotoshopHelper::getDepartment($request->url());
  
 for($s=0;$s<$length;$s++){

  $pid=$status[$s];
  $product=photography_product::getProductbyId($pid);
 
  foreach($product as $p)
  {
    $product_id=$p->id;
    $category_id=$p->categoryid;
  }
  $photoshop[]=array(
      'product_id'=>$product_id,
      'category_id'=>$category_id,
      'status'=>$action,
      'current_status'=>'1',
      'next_department_status'=>'0',
      'created_at'=>date("Y-m-d H:i:s"),
      'updated_at'=>date("Y-m-d H:i:s"),
  );
  
  $cache=array(
    'product_id'=>$product_id,
    'url'=>PhotoshopHelper::getDepartment($request->url()),
    'status'=>$request->input('action'),
    'action_by'=>$user->id


);

 PhotoshopHelper::store_cache_table_data($cache);
 photography_product::getUpdatestatusdone($product_id);
    
    
  
 }
 

if(photography::insert($photoshop))
{
    
    //$mm=PhotoshopHelper::store_cache_table_data($cache);
    $response['status']="success";
    $response['message']="Product State Change Successfull";
}else{
    $response['status']="fail";
    $response['message']="Product State Change Successfull";
}


   $response['status']="success";
    $response['message']="Product State Change Successfull";
   

echo json_encode($response);

 
  
  
   
}
}
