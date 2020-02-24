<?php

namespace App\Http\Controllers;
use App\photography_product;
use App\category;
use Illuminate\Http\Request;
use App\photoshop_cache;
use App\photoshop_status_type;
use DB;
use Auth;
use Response;
use App\photographyUploadFile;
class PhotoshopProductController extends Controller
{
    public $list_prpduct;
    public $category;
    public function __construct()
    {
        $this->list_prpduct=collect(photography_product::get_product_list());
        $this->category=collect(category::all());
       
    }
    public function list_of_product()
    {
        $data=photography_product::paginate(10);
        $category=$this->category;
        $color=$this->list_prpduct;
        $datacount = $this->list_prpduct->count();
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
        $category=$request->input('category');
        $color=$request->input('color');
        $status=$request->input('status');
        $sku=$request->input('sku');
        $filter=array(
            'categoryid'=>$category,
            'color'=>$color,
            'status'=>$status,
            'sku'=>$sku
        );
        $data1=array_filter($filter);
        $list=photography_product::getFilterData();
        $category=$this->category;
        $color=$this->list_prpduct;
      if($list->count()>0)
      {
        foreach($list as $key=>$value)
        {
            $sku=$value->sku;
            $category=$value->category->name;
            $color=$value->color;
            $status=$value->status;
           if($status=='0')
           {
            $status="Pending";
            $action = '<a class="color-content table-action-style" href="" style="display: none;"><i class="material-icons md-18">show</i></a>&nbsp;';

           }else{
            $status="Done";
            $action = '<a class="color-content table-action-style" href="" style="display: none;"><i class="material-icons md-18">show</i></a>&nbsp;';

           }

            $data['data'][]=array($sku,$category,$color,$status,$action);

        }
      }else{
          $data['data'][]=array('','','','','','','');
      }
       echo json_encode($data);
       exit;
      
        
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
}
