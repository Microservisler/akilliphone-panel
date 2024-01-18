<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use App\Models;
class BrandManagement extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function index(Request $request ){
        $data['dataTable'] = $this->dataTableParams();


        return view('Product.brand-management', $data);
    }

    public function delete(Request $request, $brandId ){
        $response = \WebService::brand_delete($brandId);

        if(isset($response['errors']) && $response['errors']){
            $html = implode(', ', $response['errors']);
            $request->session()->flash('flash-error', [$html, 'Silinemedi' ]);
        } else{

            $request->session()->flash('flash-success', [ '' , 'Silindi']);
        }
        return redirect( route('product.brand-management'));
    }

    public function save(Request $request ){
        $html = '';

        if($brand = $request->input('brand')){
            $brandId = isset($brand['brandId'])?$brand['brandId']:false;
            if($brandId=='new'){
                $brand['images'] = $request->input('images', []);
                dd($brand["desktopImageFile"]);
                $response = \WebService::brand_add($brand);
                $update=\CdnService::saveToCdn($brand['images'], '');


            } else {

                $response = \WebService::brand_edit($brand['brandId'],$brand);
            }
            if(isset($response['errors']) && $response['errors']){
                $html = implode(', ', $response['errors']);
                $request->session()->flash('flash-error', [$html, 'Kaydedilemedi' ]);
            } else{

                $html = '"'.$response['data']['name'].'"';
                $request->session()->flash('flash-success', [ $html , 'Kaydedildi']);
            }
        }
        return redirect( route('product.brand-management'));
    }

    public function dataTable(Request $request){
        $dataTable = $this->dataTableParams();
        $offset = $request->input('length', 10);
        $start = $request->input('start', 0);
        $page = ($start/$offset)+1;
        $response = \WebService::brands($page);
        $dataTable->setRecordsTotal(count($response));
        $dataTable->setRecordsFiltered(count($response));
        $items = [];
        foreach($response as $row){
            $item = [];
            foreach($dataTable->cols() as $key=>$col){
                $method = '_format_'.$key;
                if(method_exists($this, $method)){
                    $value = $this->$method($row);
                } else {
                    $value = isset($row[$key])?$row[$key]:'';
                }
                $item[$key] = $value;
            }
            if(isset($item['orderNumber'])){
                $item['orderNumber'] = count($items) + $start + 1;
            }
            $items[] = $item;
        }
        $dataTable->setItems($items);
        return $dataTable->toJson();
    }
    private function _format_image($item){


        return '<img src="'._CdnImageUrl($item["image"]).'" >';

    }
    private function _format_action($item){
        $delete = route('product.brand-delete', $item['brandId']);
        $edit = route('popup', 'BrandSave').'?brandId='.$item['brandId'];
        $html = '';
        $html .= '<a class="btn confirm-popup" href="'.$delete.'" title="\''.$item['name'].'\' silinsin mi?"><i class="fa fa-trash"></i></a> ';
        $html .= '<a class="btn btn-popup-form" data-url="'.$edit.'" title="\''.$item['name'].'\' düzenle"><i class="fa fa-edit"></i></a>';
        return '<div class="text-end">'.$html.'</div>';
    }
    private function dataTableParams(){
        $dataTable = new \AjaxDataTable();
        $dataTable->setTableId('brand-management');
        $dataTable->setUrl(route('product.brand-management-table'));
        $dataTable->setRecordsTotal(100);
        $dataTable->setRecordsFiltered(90);
        $dataTable->setCols([
            'orderNumber'=>['title'=>'Sıra No', 'className'=>'', 'orderable'=>''],
            'brandId'=>['title'=>'Id', 'className'=>'', 'orderable'=>''],
            'image'=>  ['title'=>'Logo','className'=>'','orderable'=>''],
            'name'=>['title'=>'Marka Adı', 'className'=>'', 'orderable'=>''],
            'action'=>['title'=>'','className'=>'','orderable'=>'']
        ]);
        return $dataTable;
    }

}
