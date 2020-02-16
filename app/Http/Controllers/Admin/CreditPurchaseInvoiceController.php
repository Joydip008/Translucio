<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\InvoiceDetails;
use App\Models\User;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CreditPurchaseInvoiceController extends Controller
{
     /* List Of ALL the Invoices */
     public function CreditPurchaseInvoiceList(Request $request){

        $input = $request->input();

        $left_menu='credit_purchase_invoice';

        if(!empty($input['search'])){
            $search = $input['search'];
            $InvoiceDetails = InvoiceDetails::where('customer_email',$search)->paginate(config('constants.PER_PAGE'));
        }
        else{
            $search = null;
            $InvoiceDetails = InvoiceDetails::paginate(config('constants.PER_PAGE'));
        }
//dd($search);
        

        foreach($InvoiceDetails as $Invoice){
            $UserDetails = User::where('email',$Invoice['customer_email'])->first();
            $Invoice['customer_name'] = $UserDetails['title'].' '.$UserDetails['name'].' '.$UserDetails['last_name'];
            $Invoice['profile_image'] = $UserDetails['profile_image'];
        }

        /* pagination */
        $results = $InvoiceDetails;
        //This would contain all data to be sent to the view
        $data = array();

        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = new Collection($results);

        //Define how many items we want to be visible in each page
        $per_page = 5;

        //Slice the collection to get the items to display in current page
        $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();

        //Create our paginator and add it to the data array
        $data['results'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

        //Set base url for pagination links to follow e.g custom/url?page=6
        $data['results']->setPath($request->url());

        return view('admin.creditPurchaseInvoice.credit_purchase_invoice',compact('left_menu','InvoiceDetails','results'));
    }

    /* Fetch All data */

    public function CreditPurchaseInvoiceDataList(){ 
        
    }
}
