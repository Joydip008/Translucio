<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ProjectCatagories; 
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use App\Models\Projects; 


use Validator;

class ProjectCategoryController extends Controller 
{
    /* List of All Project Category */ 
    public function ProjectCategoryList(Request $request,$id=null){

        /* All the Project Category List */

        $projectCategory = ProjectCatagories::orderBy('created_at','DESC')->get();
        $ProjectCatagoriesDetails = '';




        // /* Pagination Section */

        // $results = $projectCategory;
        // //This would contain all data to be sent to the view
        // $data = array();

        // //Get current page form url e.g. &page=6
        // $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // //Create a new Laravel collection from the array data
        // $collection = new Collection($results);

        // //Define how many items we want to be visible in each page
        // $per_page = 5;

        // //Slice the collection to get the items to display in current page
        // $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();

        // //Create our paginator and add it to the data array
        // $data['results'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

        // //Set base url for pagination links to follow e.g custom/url?page=6
        // $data['results']->setPath($request->url());




        $left_menu='project_category';


       
        return view('admin.projectCategory.project_category',compact('projectCategory','ProjectCatagoriesDetails','left_menu'));
    }

    public function UpdateProjectCategory(Request $request,$id=null){

        $projectCategory = ProjectCatagories::orderBy('created_at','DESC')->get();

        $ProjectCatagoriesDetails = ProjectCatagories::where('id',$id)->first();


        // /* pagination */
        // $results = $projectCategory;
        // //This would contain all data to be sent to the view
        // $data = array();

        // //Get current page form url e.g. &page=6
        // $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // //Create a new Laravel collection from the array data
        // $collection = new Collection($results);

        // //Define how many items we want to be visible in each page
        // $per_page = 5;

        // //Slice the collection to get the items to display in current page
        // $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();

        // //Create our paginator and add it to the data array
        // $data['results'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

        // //Set base url for pagination links to follow e.g custom/url?page=6
        // $data['results']->setPath($request->url());
        $left_menu='project_category';

        return view('admin.projectCategory.project_category',compact('projectCategory','ProjectCatagoriesDetails','left_menu'));

    }



    /* Add New Project category OR UPDATE Project category*/
    public function AddProjectCategory(Request $request){
        $input = $request->input();
        //   dd($input);
        if(empty($input['id'])){

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
            ]); 
    
            if($validator->fails()){
                return back()->withInput($input)->withErrors($validator);
            }
            else{
                /* insert data */
                if(!empty($input['status'])){
                    $status=$input['status'];
                }
                else{
                    $status=1;
                }
                $ProjectCatagories = new ProjectCatagories();
                $ProjectCatagories->catagories = $input['name'];
                $ProjectCatagories->description = $input['description'];
                $ProjectCatagories->status = $status;
                $ProjectCatagories->save();
                return \redirect()->route('project_category_list');
            }
        }
        else{

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
            ]); 
    
            if($validator->fails()){
                return back()->withInput($input)->withErrors($validator);
            }
            else{
                if(!empty($input['status'])){
                    $status=$input['status'];
                }
                else{
                    $status=1;
                }

                $ProjectCatagories = ProjectCatagories::where('id',$input['id'])->first();
                $ProjectCatagories->update([
                    'catagories' => $input['name'],
                    'description' => $input['description'],
                    'status' => $status,
                ]);
            }
            return \redirect()->route('project_category_list');
        }
    }

    /* Delete Project Category */
    public function DeleteProjectCategory(Request $request ){

        $input = $request->input();

        /* Check First This Project category have any link to any project or not */

        $ProjectDetails = Projects::where('project_category',$input['id'])->get();

        if(sizeof($ProjectDetails)==0){
            $data = ProjectCatagories::where('id',$request['id'])->delete();
            return response()->json(array('success'=>true));
        }
        if(sizeof($ProjectDetails)>0){
            return response()->json(array('success'=>false));
        }
    }
}
