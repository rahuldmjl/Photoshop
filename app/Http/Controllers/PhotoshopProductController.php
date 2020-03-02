<?php

namespace App\Http\Controllers;
use App\photography_product;
use App\category;
use Illuminate\Http\Request;
use App\photoshop_cache;
use App\photoshop_status_type;
use App\jpegModel;
use DB;
use Auth;
use Response;
use App\photographyUploadFile;
use App\Subcategory;
class PhotoshopProductController extends Controller
{
    public $list_prpduct;
    public $category;
    public $subcategory;
    public function __construct()
    {
        $this->list_prpduct=collect(photography_product::get_product_list());
        $this->category=collect(category::all());
        $this->subcategory=collect(Subcategory::all());
       
    }
    public function list_of_product()
    {
        $data=photography_product::paginate(10);
        $category=$this->category;
        $color=$this->list_prpduct;
        $datacount =$data->count();
       return view('Photoshop/Product/list',compact('data','category','color','datacount'));
    }

    public function upload_csv_list()
    {
        $list=photographyUploadFile::getFile();
        return view('Photoshop/Product/upload',compact('list'));
    }
    public function add_of_product()
    {
      
        return view('Photoshop/Product/add');
    }

    public function list_of_product_filter(Request $request)
    {
        $data=array();
        $params = $request->post();
        $params = $request->post();
		$start = (!empty($params['start']) ? $params['start'] : 0);
		$length = (!empty($params['length']) ? $params['length'] : 10);
		$stalen = $start / $length;
		$curpage = $stalen;
        $maindata = photography_product::query();
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
        if(!empty($params['status']))
        {
            if($params['status']=='1')
            {
                $status1="1";
            }else{
                $status1="0";
            }
            $maindata->where('status',$status1);
       
        }
        $datacount = $maindata->count();
		$datacoll = $maindata;
        $data["recordsTotal"] = $datacount;
		$data["recordsFiltered"] = $datacount;
		$data['deferLoading'] = $datacount;
        
        $datacollection = $datacoll->take($length)->offset($start)->get();
        
        if(count($datacollection) > 0)
        {
            foreach($datacollection as $key => $product)
            {
               $id=$product->id;
                $sku=$product->sku;
                $category=$product->category->name;
                $color=$product->color;
                if($product->status=='0')
                {
                    $status="pending";
                
                }
                else{
                    $status="Done";
               
                }
                if($product->status=='0')
                {
                    
                    $action='<a class="color-content table-action-style btn-delete-customer" data-href="'.route('photography.product.delete',['id'=>$product->id]) .'" style="cursor:pointer;"><i class="material-icons md-18">delete</i></a>&nbsp;&nbsp;';
                    $action .='<a href="'.route('product.view',['id'=>$product->id]) .'" class="color-content disabled table-action-style"><i class="material-icons md-18">remove_red_eye</i></a>';
                 
               
				
                
                }
                else{
                    $action='<a class="color-content table-action-style btn-delete-customer disabled" data-href="'.route('photography.product.delete',['id'=>$product->id]) .'" style="cursor:pointer;"><i class="material-icons md-18">delete</i></a>&nbsp;&nbsp;';
                  
                    $action .='<a href="'.route('product.view',['id'=>$product->id]) .'" class="color-content  table-action-style"><i class="material-icons md-18">remove_red_eye</i></a>';
                   
                
               
                }
                $data['data'][] = array( $sku, $color ,$category, $status,$action);
            }
        }else{
            $data['data'][] = array('', '', '', '', '', '');
	
        }
        echo json_encode($data);exit;
       //return view('Photoshop/Product/list',compact('list','category','color','filter','done'));
    }

    public function upload_csv_product(Request $request)
    {
        $user=Auth::user();
        $upload=new photographyUploadFile();
         $filename=$request->file('name');
        $name=$filename->getClientOriginalName();
         $filepath=$filename->getRealPath();
         
        $upload->filename=$name;
        $upload->userid=$user->id;
        $upload->Save();
        
          $file=fopen($filepath,'r');
          $header=fgetcsv($file);

           // dd($header);
            $escapedHeader=[];
        
            foreach ($header as $key => $value) {
                $lheader=strtolower($value);
                $escapedItem=preg_replace('/[^a-z]/', '', $lheader);
                array_push($escapedHeader, $escapedItem);
            }
            
            while($columns=fgetcsv($file))
        {
            if($columns[0]=="")
            {
                continue;
            }
          
          $sku=$columns[0];
          $color=$columns[1];
          $categoryid=$columns[2];
       $data=array
       (
           'sku'=>$sku,
           'color'=>$color
       );
       if(!photography_product::validationuploadedProduct($data))
       {
           $product=new photography_product();
           $product->sku=$sku;
           $product->color=$color;
           $product->categoryid=$categoryid;
           $product->status='0';
           $product->save();
           
          
       }
        
        }
   
        return redirect('Photoshop/Product/list')->with('message',"Product  Successfully Uploaded");
       
    }
   public function delete_product(Request $request)
   {
       $request->get('id');
       photography_product::deletye_photography_product($request->get('id'));
       return redirect()->back()->with('success', 'Product Delete  Successfull');
   }

   public function get_product_detail($id)
   {
     $listproduct1=photoshop_cache::getproduct($id);
      $listproduct=collect($listproduct1);
     $totalwork=$listproduct->count();
     $sku=$listproduct->where('id',$id);
     $rework=array('2','4','6','8','10');
     $done=array('1','3','5','7','9','11');
    
     $totalrework=0;
     $totaldone=0;
     foreach($rework as $re)
     {
        $listproductcount=$listproduct->where('action_name',$re)->count();
        $totalrework+=$listproductcount;
     }
     foreach($done as $re1)
     {
        $listproductcount1=$listproduct->where('action_name',$re1)->count();
        $totaldone+=$listproductcount1;
     }
    $product=photoshop_cache::productdetail($id);
    return view('Photoshop/Product/view',compact('listproduct','listproduct1','totalwork','product','totalrework','totaldone'));
   }


   public function subcategory_list()
   {
        $list=$this->subcategory;
       return view('Photoshop/Product/subcategory',compact('list'));
      
   }
   public function add_subcategory()
   {
      $list=$this->category;
    return view('Photoshop/Product/addsubcategory',compact('list'));
   }
   public function edit_subcategory($id)
   {    
       $data=Subcategory::find($id);
      $list=$this->category;
       return view('Photoshop/Product/editsubcategory',compact('data','list'));
   }

   public function delete_subcategory($id)
   {
       $data=Subcategory::find($id);
       $data->delete();
       return  redirect('Photoshop/Product/subcategory')->with('msg',"subCategory delete Successfulll");
   }
   public function submit_subcategory(Request $request)
   {
    $dara=array();
        $main=new Subcategory();
       $main->subcatname=$request->input('subcategory');
       $main->maincategoryname=$request->input('maincategory');
       if($id=$request->input('id'))
       {
       $sub=Subcategory::find($id);
       $sub->subcatname=$request->input('subcategory');
       $sub->maincategoryname=$request->input('maincategory');
       $sub->save();
       }
       else{
          
         $check=Subcategory::validation($request->input('subcategory'),$request->input('maincategory')); 
         if($check=='1')
         {
             return  redirect('Photoshop/Product/subcategory/add')->with('msg',"Sub Category Already Existed Successfulll");
         }
         else{
             $main->save();
             return  redirect('Photoshop/Product/subcategory')->with('msg',"Sub Category add Successfulll");
     
         }
         return  redirect('Photoshop/Product/subcategory')->with('msg',"subCategory add Successfulll");
     
        
       }
      
      
   }


   public function unique_deifne_sku()
   {
    DB::setTablePrefix('');
    $uniqueskudata=array();
    $uniquesku = DB::table('dml_photography_products')->select("*")
    ->join("dml_jpeg_models","dml_jpeg_models.product_id","=","dml_photography_products.id")
    ->where("dml_jpeg_models.next_department_status",'=',0)
    ->get();
     DB::setTablePrefix('dml_');
     $datacollection=collect($uniquesku);
     $subcategory=Subcategory::all();

     return view('Photoshop/Product/uniquesku',compact('datacollection','subcategory'));
   }
}
