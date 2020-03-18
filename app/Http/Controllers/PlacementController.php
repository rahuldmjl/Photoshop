<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\psd;
use Auth;
use App\photography_product;
use DB;
use App\category;
use App\Placement;
use App\Helpers\PhotoshopHelper;
class PlacementController extends Controller
{
   
    public $psd;
    public $user;
    public $placement;
    public $category;
    public function __construct()
    {
        $this->category=collect(category::all());
        $this->psd=psd::getPsdProduct();
        $user=Auth::user();
        $this->placement=Placement::all();
          
    }
   //get Placement Pending List
    public function get_placement_pending_list(){
          $category=$this->category;
         $list=collect($this->psd)->where('status','=','3')->where('next_department_status','=','0');
       return View('Photoshop/Placement/placement_pending',compact('list','category'));

    }
    public function get_placement_done_list()
    {  
        $category=$this->category;
        $done_list=collect($this->placement)->where('status',3);
       return View('Photoshop/Placement/placement_done',compact('done_list','category'));
    }

    public function get_placement_rework_list()
    {    $category=$this->category;
        $rework_list=collect($this->placement)->where('status',4);
        return View('Photoshop/Placement/placement_rework',compact('rework_list','category'));
    }

    public function get_pending_list_data_submit(Request $request)
    {
        $user=Auth::user();
        $placement_data=new Placement();
        if($request->input('status') !="1")
        {
            
  
            $placement_data->product_id=$request->input('product_id');
           $placement_data->category_id=$request->input('category_id');
            $placement_data->status=$request->input('status');
            $placement_data->current_status='1';
            $placement_data->next_department_status='0';
         
           //Cache table data Insert
           if($request->input('status')=='3')
           {
            $placement_data->save();
            $cache=array(
                'product_id'=>$request->input('product_id'),
                'url'=>PhotoshopHelper::getDepartment($request->url()),
                'status'=>$request->input('status'),
                'action_by'=>$user->id
    
    
            );
             PhotoshopHelper::store_cache_table_data($cache);
             placement::getUpdatestatusdone($request->input('product_id'));
           }
          
        }
        return redirect()->back()->with('success', 'Psd status Change Successfull');
     
    }

    public function submit_done_list(Request $request)
    {
        $user=Auth::user();
       
        if($request->input('status') !='0')
        {
            //cache table data insert 
            $cache=array(
                'product_id'=>$request->input('product_id'),
                'url'=>PhotoshopHelper::getDepartment($request->url()),
                'status'=>$request->input('status'),
                'action_by'=>$user->id
    
            );
           
           PhotoshopHelper::store_cache_table_data($cache);
         placement::update_placement_status($request->input('product_id'),$request->input('status'));
    if($request->input('status')=='4')
    {
         placement::delete_from_editing($request->input('product_id'));
   
         placement::delete_from_jpeg($request->input('product_id'));
          placement::getUpdatestatus_JPEG($request->input('product_id'));
    
      
    }
    
        }
return redirect()->back()->with('success', 'Psd status Change Successfull');
    
}
public function get_pending_ajax_list(Request $request){
    $status=PhotoshopHelper::getDepartmentwise($request->url());
    
    if($status=='pending'){
        $id='3';
        $maindata = psd::query();
        $datacount = $maindata->count();
        $whereData = array(array('status','3') , array('next_department_status' ,'=','0')); 
        $action1='<select name="status" class="form-control" style="height:20px;width:150px;float: left;">
        <option value="2">Pending</option>
        <option value="1">In processing</option>
        <option value="3">Done</option>
    </select>';
     
    }
    if($status=='done'){
        $id='3';
        $maindata = placement::query();
        $datacount = $maindata->count();
        $whereData = array(array('status','3') , array('next_department_status' ,'=','0')); 
        $action1='<select name="status" id="status" class="form-control" style="height:20px;width:120px;float: left;">
        <option value="0">select status</option>
        <option value="4">Rework</option>
   </select>';
     }
     if($status=='rework'){
        $id='4';
        $maindata = placement::query();
        $datacount = $maindata->count();
        $whereData = array(array('status','4') , array('next_department_status' ,'=','0')); 
        $action1=' <select name="status" id="status" class="form-control" style="height:20px;width:120px;float: left;">
        <option value="0">select status</option>
        <option value="3">Done</option>
   </select>';
     }
     
    $datacoll = $maindata->where($whereData);
    $data=array();
    $params = $request->post();
    $params = $request->post();
    $start = (!empty($params['start']) ? $params['start'] : 0);
    $length = (!empty($params['length']) ? $params['length'] : 10);
    $stalen = $start / $length;
    $curpage = $stalen;
   
   
    $data["recordsTotal"] = $datacoll->count();
    $data["recordsFiltered"] = $datacoll->count();
    $data['deferLoading'] = $datacoll->count();
    if(!empty($params['category']))
    {
        $maindata->where('category_id',$params['category']);
    }
    $donecollection = $datacoll->take($length)->offset($start)->get();
    if(count($donecollection)>0){
        foreach($donecollection as $key => $product)
        {
            $csrf=csrf_field();
           $p=$product->getProduct;
           $ca=$product->category;
           $check='<div class="checkbox checkbox-primary" style="width: 100px;">
           <label>
           <input type="checkbox" class="chkProduct" value="'.$p->id.'" name="chkProduct" id="chkProduct"> <span class="label-text"></span>
           </label>
       </div>';
            $token=$request->session()->token();
             $action='<form action="" method="post" style="margin-right: 110px;">
             '.$csrf.'
            <input type="hidden" value="'.$product->product_id.'" name="product_id" id="product_id"/>
             <input type="hidden" value="'.$product->category->id.'" name="category_id" />
                '.$action1.'
                 <input type="submit"  style="height:20px;margin-left:10px" onclick="ajaxSave(this.val)" class="btn btn-submit1 btn-primary" value="Submit"/>
         
             </form>';
            $data['data'][] = array($check,$p->sku, $p->color, $ca->name, $action);
        }
      
       
      }else{
        $data['data'][] = array('','','', '', '', '', '');
      }
         
    echo json_encode($data);exit;
}

public function Status_change(Request $request){

    $user=Auth::user();
   
    $response=array();
    $department=PhotoshopHelper::getDepartment($request->url());
    $data=$request->get('status');
    $action=$request->get('action');
    $response['department']=$department;
    $productidarray=array();
    $response['data']=$data;
    $placementddata=array();
    if($data !=null){
       
        $length=count($data);
        for($s=0;$s<$length;$s++){
            $productidarray[]=$data[$s];
             $pid=$data[$s];
             $product=photography_product::getProductbyId($pid);
             if(placement::checkpsdproduct($pid)){
            if($action=='4'){
                placement::update_placement_status($pid,'4');
                placement::delete_from_jpeg($pid);
                psd::getUpdatestatus_psd($pid);
                $response['action']='1';
                $response['message']="Status Update Successfully";
            }
            if($action=='3'){
                psd::getUpdatenextdepartmentdone($pid);
                placement::update_placement_status($pid,'3');
                $response['datastatus']="Rework".$action;
                $response['action']='1';
                $response['status']="success".$length;
                $response['message']="Status Update Successfully";
            }
           
             }
             else{
                foreach($product as $p)
                {
                  $product_id=$p->id;
                  $category_id=$p->categoryid;
                }
               
               $placementddata=array(
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
                  $response['statusproduct']="Not Existed";
                
              
           placement::inserttojson($placementddata);
            psd::getUpdatenextdepartmentdone($product_id);
                $response['datastatus']="Not Existed";
                $response['action']='1';
                $response['status']="success".$length;
                $response['message']="Product done Successfully in Placement Department";
             }
      
        }
            
       
        
      
    }else{
        $response['action']='0';
        $response['status']="fail";
        $response['message']="Please Select The Product";
    }
        
   
  
    echo json_encode($response);
}
}
