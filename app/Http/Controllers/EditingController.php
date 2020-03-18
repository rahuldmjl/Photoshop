<?php

namespace App\Http\Controllers;
use App\Helpers\PhotoshopHelper;
use Illuminate\Http\Request;
use App\EditingModel;
use App\psd;
use Auth;

use App\category;
use App\placement;
class EditingController extends Controller
{
    //get all Pending list of Editing Department
    public $product;
    public $psd;
   public $category;
    public function __construct()
    {
        $this->psd=EditingModel::getEditingProduct();
        $this->editing=EditingModel::all();
        $this->category=category::all();
        $user=Auth::user();
    }
    
    public function get_pending_list_editing()
    {
        $category=$this->category;
        $psd_done=collect($this->psd)->where('status','=','3')->where('next_department_status','=','0');        
     //  $editing_pending_list=PhotoshopHelper::get_editing_pending_list();
      return view('Photoshop/Editing/editing_pending',compact('psd_done','category'));
    }
   //get all done list of Editing Department

    public function get_done_list_editng()
    {
       
        $category=$this->category;
      
         $done_list=collect($this->editing)->where('status',3);
       return view('Photoshop/Editing/editing_done',compact('done_list','category'));

    }
    public function get_rework_list_editing()
    {
        $category=$this->category;
        $editing_rework_list=collect($this->editing)->where('status',4);
       return view('Photoshop/Editing/editing_rework',compact('editing_rework_list','category'));
    }

    public function get_pending_submit_editing(Request $request)
    {
        $user=Auth::user();
      $editing=new EditingModel();
      if($request->input('status') !="1")
      {
          

          $editing->product_id=$request->input('product_id');

          $editing->category_id=$request->input('category_id');
          $editing->status=$request->input('status');
          $editing->current_status='1';
          $editing->next_department_status='0';
       
         //Cache table data Insert
         if($request->input('status')=='3')
         {
          $editing->save();
          $cache=array(
              'product_id'=>$request->input('product_id'),
              'url'=>PhotoshopHelper::getDepartment($request->url()),
              'status'=>$request->input('status'),
              'action_by'=>$user->id
  
  
          );
           PhotoshopHelper::store_cache_table_data($cache);
           EditingModel::getUpdatestatusdone($request->input('product_id'));
         }
        
      }
        return redirect()->back()->with('success', 'Editing status Change Successfull');
     
   
     
      //  return redirect()->back()->with($message);
    }

    public function submit_done_list_editng(Request $request)
    {
        $user=Auth::user();
     if($request->input('status')=='0')
     {
      
        $message=array(
            'success'=>'Editing Select Status',
            'class'=>'alert alert-danger'
        );
        
     }
     else{


        $cache=array(
            'product_id'=>$request->input('product_id'),
            'url'=>PhotoshopHelper::getDepartment($request->url()),
            'status'=>$request->input('status'),
            'action_by'=>$user->id
  
        );
       PhotoshopHelper::store_cache_table_data($cache);
       EditingModel::update_editing_status($request->get('product_id'),$request->input('status'));
     
      
       
        $message=array(
            'success'=>'Editing Rework Successfull',
            'class'=>'alert alert-success'
        );
       if($request->input('status')=='4')
       {
        EditingModel::getUpdatestatusrework($request->input('product_id'));
        EditingModel::delete_from_jpeg_List($request->input('product_id'));
       }
       
      
     }
    return redirect()->back()->with($message);   
    }
    public function get_pending_ajax_list(Request $request){
        $status=PhotoshopHelper::getDepartmentwise($request->url());
        $data=array();
        if($status=='pending'){
            $id='3';
            $maindata = placement::query();
            $datacount = $maindata->count();
            $whereData = array(array('status','3') , array('next_department_status' ,'=','0')); 
            $action1='<select name="status" class="form-control" style="height:20px;width:150px;float: left;">
            <option value="2">Pending</option>
            <option value="1">In processing</option>
            <option value="3">Done</option>
        </select>';
         
        }
        if($status=='done'){
            
            $maindata = EditingModel::query();
            $datacount = $maindata->count();
            $whereData = array(array('status','3') , array('next_department_status' ,'=','0')); 
            $action1='<select name="status" id="status" class="form-control" style="height:20px;width:120px;float: left;">
            <option value="0">select status</option>
            <option value="4">Rework</option>
       </select>';
         }
         if($status=='rework'){
            $id='4';
            $maindata = EditingModel::query();
            $datacount = $maindata->count();
            $whereData = array(array('status','4') , array('next_department_status' ,'=','0')); 
            $action1=' <select name="status" id="status" class="form-control" style="height:20px;width:120px;float: left;">
            <option value="0">select status</option>
            <option value="3">Done</option>
       </select>';
         }
         
        $datacoll = $maindata->where($whereData);
       
        $params = $request->post();
        $params = $request->post();
        $start = (!empty($params['start']) ? $params['start'] : 0);
        $length = (!empty($params['length']) ? $params['length'] : 10);
        $stalen = $start / $length;
        $curpage = $stalen;
        $data["recordsTotal"] = $datacoll->count();
        $data["recordsFiltered"] = $datacoll->count();
        $data['deferLoading'] = $datacoll->count();
        
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
}
