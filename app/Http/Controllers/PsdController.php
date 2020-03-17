<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\PhotoshopHelper;
use App\photography;
use App\psd;
use App\productListModel;
use App\photography_product;
use App\category;
use DB;
use Auth;
class PsdController extends Controller
{
  
 
  public $photography;
  public $psd;
  public $user;
  public function __construct()
  {
      $this->photography=photography::getphotographyProduct();
      $this->psd=psd::getPsdProduct();
      $this->category=collect(category::all());
      
      $user=Auth::user();
        
  }
    public function index()
    {
        return view('Photoshop/PSD/index');
    }
    /*
    Get Pending List 
    this list come from photography done option
    */
    public function get_psd_pending_list()
    {
      $category=$this->category;
      $psdpending=collect($this->photography)->where('status','=','3')->where('next_department_status','=','0');
      return view('Photoshop/PSD/psd_pending',compact('psdpending','category'));
    }
    /*
    Get done List 
    this list come from psd  done option
    */
    public function get_psd_done_list()
    {
      $category=$this->category;
      $psd_done_list=collect($this->psd)->where('status','=','3')->take(10);;
      return view('Photoshop/PSD/psd_done',compact('psd_done_list','category'));
    }
      /*
    Get rework List 
    this list come from psd  rework option
    */
    public function get_psd_rework_list()
    { $category=$this->category;
       $psd_rework=collect($this->psd)->where('status','=','4');
        return view('Photoshop/PSD/psd_rework',compact('psd_rework','category'));
    }
    /* Get All Data from ppending From psd Department
    Submit Pending Data into post method
    */

    public function get_data_from_psd_pending_list(Request $request)
    {
      
      $user=Auth::user();
      $photoshop=new psd();
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
           photography::getUpdatenextdepartmentdone($request->input('product_id'));
         }
        
      }
        return redirect()->back()->with('success', 'Psd status Change Successfull');
     
    }

    public function submit_done_list(Request $request)
    {
      $user=Auth::user();
        $psd=psd::find($request->input('id'));
       if($request->input('status') !='0')
       {
        $cache=array(
          'product_id'=>$request->input('product_id'),
          'url'=>PhotoshopHelper::getDepartment($request->url()),
          'status'=>$request->input('status'),
          'action_by'=>$user->id

      );
      PhotoshopHelper::store_cache_table_data($cache);
      psd::update_psd_status($request->get('product_id'),$request->input('status'));
       }
    if($request->input('status')=='4')
    {
       psd::delete_from_below_department($request->get('product_id'));
       psd::getUpdatestatus_psd($request->input('product_id'));
    }
       return redirect()->back()->with('success', 'Psd status Change Successfull');
    }
public function Ajax_pending_list(Request $request){

   $data=array();
  
        $params = $request->post();
        $params = $request->post();
        
		$start = (!empty($params['start']) ? $params['start'] : 0);
		$length = (!empty($params['length']) ? $params['length'] : 10);
		$stalen = $start / $length;
		$curpage = $stalen;
    $maindata = photography::query();
    $datacount = $maindata->count();
    $whereData = array(array('status','3') , array('next_department_status' ,'=','0')); 
		$datacoll = $maindata->where($whereData );
    $data["recordsTotal"] = $datacoll->count();
		$data["recordsFiltered"] = $datacoll->count();
    $data['deferLoading'] = $datacoll->count();
    if(!empty($params['category']))
    {
        $maindata->where('category_id',$params['category']);
    }
    if(!empty($params['sku']))
    {
        $maindata->where('product_id',$params['sku']);
    }
    
     $datacollection = $datacoll->take($length)->offset($start)->get();
        
     if(count($datacollection) > 0)
     {
         foreach($datacollection as $key => $product)
         {

             $token=$request->session()->token().
            $id=$product->product_id;
            $check='<div class="checkbox checkbox-primary" style="width: 100px;">
            <label>
            <input type="checkbox" class="chkProduct" value="'.$id.'" name="chkProduct" id="chkProduct"> <span class="label-text"></span>
            </label>
            </div>';
             $sku=$product->getProduct->sku;
             $category=$product->category->name;
             $color=$product->getProduct->color;
             $csrf=csrf_field();
             $statuspst="Pending";
             $action1=route('pending.submit1');
             $action='<form action="" method="post">
             '.$csrf.'
           <input type="hidden" value="'.$id.'" name="product_id" id="product_id"/>
            <input type="hidden" value="'.$product->category->id.'" name="category_id" />
                <select name="status" id="status" class="form-control" style="height:20px;width: 113px;margin-left: -21px;float: left;">
                    <option value="2">Pending</option>
                    <option value="1">In processing</option>
                    <option value="3">Done</option>
                </select>
                <input type="submit"  style="height:20px;margin-right: -14px;" class="btn btn-submit1 btn-primary" value="Submit"/>
        
            </form>';
             $data['data'][] = array( $check,$sku, $color ,$category, $statuspst,$action);
         }
     }
     else{
         $data['data'][] = array('','', '', '', '', '','');

     }
	   echo json_encode($data);exit;
}
public function Ajax_pending_rework_list(Request $request){

  $data=array();
 
       $params = $request->post();
       $params = $request->post();
       
   $start = (!empty($params['start']) ? $params['start'] : 0);
   $length = (!empty($params['length']) ? $params['length'] : 10);
   $stalen = $start / $length;
   $curpage = $stalen;
   $maindata = psd::query();
   $datacount = $maindata->count();
   $whereData = array(array('status','4') , array('next_department_status' ,'=','0')); 
   $datacoll = $maindata->where($whereData );
   $data["recordsTotal"] = $datacoll->count();
   $data["recordsFiltered"] = $datacoll->count();
   $data['deferLoading'] = $datacoll->count();
   if(!empty($params['category']))
   {
       $maindata->where('category_id',$params['category']);
   }
    $datacollection = $datacoll->take($length)->offset($start)->get();
       
    if(count($datacollection) > 0)
    {
        foreach($datacollection as $key => $product)
        {
            $token=$request->session()->token().
           $id=$product->product_id;
           $check='<div class="checkbox checkbox-primary" style="width: 100px;">
           <label>
           <input type="checkbox" class="chkProduct" value="'.$id.'" name="chkProduct" id="chkProduct"> <span class="label-text"></span>
           </label>
           </div>';
            $sku=$product->getProduct->sku;
            $category=$product->category->name;
            $color=$product->getProduct->color;
            $csrf=csrf_field();
            $statuspst="Pending";
            $action1=route('pending.submit1');
            $action='<form action="" method="post">
            '.$csrf.'
          <input type="hidden" value="'.$id.'" name="product_id" id="product_id"/>
           <input type="hidden" value="'.$product->category->id.'" name="category_id" />
               <select name="status" id="status" class="form-control" style="height:20px;width: 113px;margin-left: -21px;float: left;">
               <option value="0">select status</option>
               <option value="3">Done</option>
               </select>
               <input type="submit"  style="height:20px;margin-right: -14px;" class="btn btn-submit1 btn-primary" value="Submit"/>
       
           </form>';
            $data['data'][] = array( $check,$sku, $color ,$category, $statuspst,$action);
        }
    }
    else{
        $data['data'][] = array('','', '', '', '', '','');

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
  $psddata=array();
  if($data !=null){
    $length=count($data);
      for($s=0;$s<$length;$s++){
           $productidarray[]=$data[$s];
            $pid=$data[$s];
            $product=photography_product::getProductbyId($pid);
        
            if(psd::checkpsdproduct($pid)){
              if($action=='4'){
                $response['statusproduct']="Done To Rework ";
                psd::update_psd_status($pid,'4');
                psd::delete_from_below_department($pid);
                 $cache=array(
                  'product_id'=>$pid,
                  'url'=>PhotoshopHelper::getDepartment($request->url()),
                  'status'=>$request->input('action'),
                  'action_by'=>$user->id
              
              
              );
              PhotoshopHelper::store_cache_table_data($cache);
              }else{
                psd::update_psd_status($pid,'3');
                $cache=array(
                  'product_id'=>$pid,
                  'url'=>PhotoshopHelper::getDepartment($request->url()),
                  'status'=>$request->input('action'),
                  'action_by'=>$user->id
              
              
              );
              PhotoshopHelper::store_cache_table_data($cache);
                $response['statusproduct']="Rework to Existed";
              }
           

            }else{
              
              foreach($product as $p)
              {
                $product_id=$p->id;
                $category_id=$p->categoryid;
              }
             
             $psddata=array(
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
              
            
          psd::inserttojson($psddata);
          photography::getUpdatenextdepartmentdone($product_id);
         
            }
           
        }
      
      $response['status']="success";
      $response['message']="Status Change Successfully";
     
  }else{
    $response['action']='0';
      $response['status']="fail";
      $response['message']="Please Select The Product";
  }

  echo json_encode($response);

}

public function ajax_table(Request $request){
  $data=array();
  $params = $request->post();
  $params = $request->post();
$start = (!empty($params['start']) ? $params['start'] : 0);
$length = (!empty($params['length']) ? $params['length'] : 10);
$stalen = $start / $length;
$curpage = $stalen;
  $maindata = psd::query();
  $datacount = $maindata->count();
$datacoll = $maindata->where('status',3);
  $data["recordsTotal"] = $datacoll->count();
$data["recordsFiltered"] = $datacoll->count();
  $data['deferLoading'] = $datacoll->count();
  if(!empty($params['category']))
  {
      $maindata->where('category_id',$params['category']);
  }
  if(!empty($params['sku'])){
      $maindata->where('product_id',$params['sku']);
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
           <select name="status" id="status" class="form-control" style="height:20px;width:120px;float: left;">
               <option value="0">select status</option>
               <option value="4">Rework</option>
          </select>
           <input type="submit"  style="height:20px;margin-left:10px" onclick="ajaxSave(this.val)" class="btn btn-submit1 btn-primary" value="Submit"/>
   
       </form>';
      $data['data'][] = array($check,$p->sku, $p->color, $ca->name, 'Done', $action);
  }

 
}else{
  $data['data'][] = array('','','', '', '', '', '');
}
    

   echo json_encode($data);exit;
}
}
