@extends('layouts.home')
@section('title')
TRANSLICIO | My Page
@endsection
@section('add-meta')
<style type="text/css">
  .t-red{
     font-size: 80%;
     color: #dc3545;
  }
</style>
@endsection

@section('content')

 <!-- start my profile section css your document section -->
 <div class="translate-document-main personal-main">
    <div class="container custom-con">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 class="translate-heading profile-heading"> String Corrections</h2>
        </div>
            @if(session('error'))
    					<div class="alert alert-danger">
      						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      						{!! session('error') !!}
    					</div>
            @endif
            @if(session('success'))
    					<div class="alert alert-success">
      						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      						{!! session('success') !!}
    					</div>
            @endif
            <div class="credit_history string_correction_top"> 
            <div class="col-12">
              <div class="row">
                <div class="col-md-8">
                    <!-- start tab section -->
                    <ul class="nav nav-tabs custom_string_tab" role="tablist">
                    <!-- onclick="DNTPagination('{{$ProjectDetail['id']}}','{{$ToLanguage['id']}}')" -->
                    @if($LanguagePairDetails['do_not_translate']==1)
                      <li class="nav-item" >
                        <!-- <a class="nav-link toggle_value_0 active" href="#profile" role="tab" data-toggle="tab" >Do not translate</a> -->
                        <!-- <a href="{{url('/string-correction/'.$ProjectDetail['id'].'/'.$ToLanguage['id'].'/1')}}" class="nav-link toggle_value_1 " >Do not Translate as</a> -->
                        <a class="nav-link toggle_value_0 {!! $tabId==1?'active':'' !!}" href="{{url('/string-correction/'.$ProjectDetail['id'].'/'.$ToLanguage['id'].'/1')}}"  >Do not translate</a>
                        
                      
                      </li>
                    @endif
                    @if($LanguagePairDetails['always_translate_as']==1)
                      <li class="nav-item">
                        <!-- <a class="nav-link toggle_value_1" href="#buzz" role="tab" data-toggle="tab">Always translate as</a> -->
                        <a href="{{url('/string-correction/'.$ProjectDetail['id'].'/'.$ToLanguage['id'].'/2')}}" class="nav-link toggle_value_1 {!! $tabId==2?'active':'' !!}" >Always translate as</a>
                      </li>
                    @endif
                      <!-- <li class="nav-item" >
                        <a class="nav-link toggle_value_1"  href="{{url('/updated-project/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])}}">Execute Now </a>
                      </li> -->
                      @if($ProjectDetail['project_type'] == 1)
                      <li class="nav-item">
                      <!-- {{url('/update-web-html/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])}} -->
                     <!-- <a class="btn btn-primary" href="{{url('/website-execute/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])}}">Execute & Show Web Site</a>-->
                      </li>
                      @endif
                      @if($ProjectDetail['project_type'] == 2)
                        @if($ProjectDetail['extension'] === 'pdf')
                        <!-- {{url('/download-updated-project/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])}} -->
                          <li class="nav-item">
                            <a class="nav-link toggle_value_1"  href="{{url('/download-updated-project/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])}}">Execute & Download PDF</a>
                          </li>
                        @elseif($ProjectDetail['extension'] === 'docx') 
                          <li class="nav-item">
                            <a class="nav-link toggle_value_1"  href="{{url('/download-updated-project/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])}}">Execute & Download DOCX</a>
                          </li>
                        @endif
                      @endif



                       <!-- <a href="{{url('/updated-project/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])}}">Execute Now </a>
                <a href="{{url('/download-updated-project/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])}}">Download Now </a> -->
                    </ul>
              </div>
              <div class="col-md-4">
                 <div class="string_tabs_text text_none">
                  
                 </div>

                <div class="string_tabs_text" id="str_1">
                  <span>{{$FromLanguage['name']}} <i class="fa fa-arrow-right" aria-hidden="true"></i>{{$ToLanguage['name']}}</span>
                </div>

                 <div class="string_tabs_text" id="str_2">
                 <span>German <i class="fa fa-arrow-right" aria-hidden="true"></i>German</span>
                </div>

              </div>
            </div>
            </div>
            <div class="col-md-12 p-0">
<!-- Tab panes -->
<div class="tab-content">
@IF($tabId==1)
  <div role="tabpanel" class="tab-pane {!! $tabId==1?'active':'fade' !!}" id="profile">
		<div class="add-project-btn-container">
            <div class="searchbox_aa">
				<div class="searchboxinner_aa">
            @if($LanguagePairDetails['do_not_translate']==1 && $LanguagePairDetails['always_translate_as']==1)
                
					<div class="search_text_box">
					  <form action="{{url('string-correction',[$ProjectDetail['id'], $ToLanguage['id'],$tabId])}}" method="get">
						<div class="text-search user_dash">
						  <div class="form-group">
							<input class="form-control" type="text" name="search" id="DoNotStringSearch" placeholder="Search your translated text" aria-label="Search"> 
							<span class="search_span"> <i class="material-icons">search</i></span>
						 <!-- <input type="submit"> -->
							 
						  </div>
						  <button class="btn btn_project_submit mb0" >Search</button>
						</div>
					  </form>
					</div>

             
				   @endif
					
				</div>	
                <div class="text-right flexdiv">
					<div class="search_ss">
				   
					  <a href="javascript:void(0)" class="btn btn-primary str_from_other" data-toggle="modal" data-target="#string_other">Use string corrections from other projects</a>
					</div>
                  <a class="btn btn-primary btn_add_proof" data-toggle="modal" data-target="#exampleModal3"><i class="material-icons">add</i>Add
                    </a>
                </div>
            </div>
		</div>
              @if($LanguagePairDetails['do_not_translate']==1)
              
 
                 <div class="replace_table">
              <div class="string_correction_1 ">
                <div class="string_correction str_first">
                  <!--Table-->
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="th-lg">String that needs no translation
                          <!-- <span style="color: #716FEA">
          <select class="custom-select cus_1">
        <option value="1">English</option>
        <option value="2">Spanish</option>
        <option value="3">Hindi</option>
        <option value="4">Bengali</option>
      </select></span> -->
                        </th>
                        <th class="th-lg">Action</th>
                      </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                      @foreach($DoNotTranslatedList as $DNTList)
                      <tr>
                        <td id="name_row{{$DNTList['id']}}" class="common-width" id="{{$DNTList['id']}}" value="{{$DNTList['do_not_translate_string']}}" >{{$DNTList->do_not_translate_string}}</td>
                        <input type="hidden" class="mb-0 content-editable" id="ph_{{$DNTList['id']}}" value="{{$DNTList['do_not_translate_string']}}">
                        <td style="color: #716FEA">
                          <input type="button" id="edit_button{{$DNTList['id']}}" value="Edit" class="edit" onclick="edit_row_custom({{$DNTList['id']}})">
                         
                          <input type="button" id="save_button{{$DNTList['id']}}" value="Save" class="save save_dis" onclick="editDoNotTranslate('{{$DNTList['id']}}')">
                          <!-- <input type="button" name="Delete" value="Delete" class="btn-delete" data-toggle="modal" data-target=".delete_modal"> -->
                          <input type="button" name="Delete" value="Delete" class="btn-delete" onclick="deleteDoNotTranslate('{{$ProjectDetail['id']}}','{{$DNTList['id']}}','{{$LanguagePairDetails['from_language']}}','{{$LanguagePairDetails['to_language']}}','1')">
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                    <!--Table body-->
                  </table>
                  <!--Table-->
                </div>
                <div class="col-md-12">
                  <div class="pagination_right">
                 @if(!empty($DoNotTranslatedList))
                	@if (!empty($search))
                  
						{{ $DoNotTranslatedList->appends(['search'=>$search])->links() }}
            
					@else
          
						{{ $DoNotTranslatedList->links() }}
            
					@endif
          
                 
                    <!-- <ul>
                      <li><a href="javascript:void(0)"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                      </li>
                      <li><a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                      </li>
                      <li><span class="activea">1</span>
                      </li>
                      <li><a href="javascript:void(0)" class="bg-none">2</a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                      </li>
                    </ul> -->
                  </div>
                </div>
              </div>
            </div>
            @endif
            @endif
            @endif
  </div>
  
  @IF($tabId==2)
  
  <div role="tabpanel" class="tab-pane{!! $tabId==2?'active':'fade' !!}" >
    <div class="add-project-btn-container">
		<div class="searchbox_aa">
				<div class="searchboxinner_aa">
					<div class="search_text_box">
					  <form action="{{url('string-correction',[$ProjectDetail['id'], $ToLanguage['id'],$tabId])}}" method="get">
						<div class="text-search user_dash">
						  <div class="form-group">
							<input class="form-control" type="text"  name="search" id="AlwaysSearch" placeholder="Search your translated text" aria-label="Search"> 
							<span class="search_span"> <i class="material-icons">search</i></span>
							
						  </div>
						  <button class="btn btn_project_submit mb0" >Search</button>
						</div>
					  </form>
					</div>
					
					
				</div>	

				   
				   
				<div class="text-right"> 
					  <!--     <button class="btn_add" data-toggle="modal" data-target="#add_modal"> <i class="material-icons">add</i> Add</button> -->
					  <a class="btn btn-primary btn btn_add_proof btn_string_proof" data-toggle="modal" data-target="#exampleModal3"><i class="material-icons">add</i>Add
						</a>
				</div>
		</div>
	</div>	
              <div class="replace_table">
              <div class="string_correction_1 ">
                <div class="string_correction">
                  <!--Table-->
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="th-lg">String From
                        
                        </th>
                        <th class="th-lg">String To
                         
                        </th>
                        <th class="th-lg">Action</th>
                      </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                      @foreach($AlwaysTranslatedList as $ATList)
                      <tr>
                        <td>{{$ATList['do_not_translate_string']}}</td>
                        <td id="name_row{{$ATList['id']}}" id="{{$ATList['id']}}" value="{{$ATList['always_translate_as_string']}}" class="common-width">{{$ATList['always_translate_as_string']}}</td>
                        <input type="hidden" class="mb-0 content-editable" id="ph_{{$ATList['id']}}" value="{{$ATList['always_translate_as_string']}}">
                        <td style="color: #716FEA">
                        
                          <input type="button" id="edit_button{{$ATList['id']}}" value="Edit" class="edit" onclick="edit_row_custom({{$ATList['id']}})">
                          <input type="button" id="save_button{{$ATList['id']}}" value="Save" class="save save_dis" onclick="editAlwaysTranslate('{{$ATList['id']}}')">
                          <!-- <input type="button" name="Delete" value="Delete" class="btn-delete" data-toggle="modal" data-target=".delete_modal"> -->
                         
                          <input type="button" name="Delete" value="Delete" class="btn-delete"  onclick="deleteDoNotTranslate('{{$ProjectDetail['id']}}','{{$ATList['id']}}','{{$LanguagePairDetails['from_language']}}','{{$LanguagePairDetails['to_language']}}','2')">
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                    <!--Table body-->
                  </table>
                  <!--Table-->
                </div>
                <div class="col-md-12">
                  <div class="pagination_right">
                @if(!empty($AlwaysTranslatedList))
                	@if (!empty($search))
                  
						{{  $AlwaysTranslatedList->appends(['search'=>$search])->links() }}
            
					@else
          
						{{ $AlwaysTranslatedList->links() }}
            
					@endif
				 @endif
                  </div>
                </div>
              </div>
            
			
			</div>
 




 </div>
  @endif

 
</div>
</div>


<!-- end tab section -->
          </div>














      </div>
    </div>
  </div>
  <!-- end my profile section your document section -->
  <!-- start kstring lcorrection popup -->
  <!-- Modal -->
  <div class="modal fade str_modal" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="add_heading">Add New String Corrections</h2>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body">
          <div class="string-form">
            <form method="" action="">
              <div class="form-group">
                <label for="formGroupExampleInput">String Form</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="">
              </div>
              <div class="form-group">
                <label for="formGroupExampleInput2">String To</label>
                <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="">
              </div>
              <button type="submit" class="btn btn-primary btn_str_save">Save</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end string correction popup -->
  <!-- start delete modal -->
  <div class="modal fade delete_modal" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body">
          <div class="delete-account">
            <h2 class="add_heading">Delete Your Account</h2>
            <p>If you want to delete your account, along with all associated information, your materials and you preferences, please click the button below.</p> <span>This action cannot be reversed.</span>
            <button type="button" class="btn_modal_delete">Delete my account</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end delete modal -->
  <!-- start js section -->
  <script src="{{ asset('assets/js/jquery-1.9.1.min.js')}}"></script>
  <script src="{{ asset('assets/js/bootstrap.js')}}"></script>
  <script src="{{ asset('assets/js/custom.js')}}"></script>


  <div class="modal fade proof-mod" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="add_heading">Add New  String Corrections</h2>
          <button type="button" class="close close_add" data-dismiss="modal" aria-label="Close"> <i class="material-icons">close</i>
          </button>
      </div>
      <div class="modal-body">
        <div class="col-xs-12 ">
          <div class="string-form">
           
            <nav>
          <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
            @if($LanguagePairDetails['do_not_translate']==1)
            <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Do not translate</a>
            @endif
            @if($LanguagePairDetails['always_translate_as']==1)
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Always translate as</a>
            @endif
            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Replace translated string</a>
          </div>
        </nav>
        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
          <div class="tab-pane fade show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
          <form method="post" action="{{route('do_not_translate')}}">
            @csrf
            <input type="hidden" name="id" value="{{$ProjectDetail['id']}}">
            <input type="hidden" value="{{$FromLanguage['id']}}" name="from_language">
            <input type="hidden" value="{{$ToLanguage['id']}}" name="to_language">
              <div class="append_main">
             <div class="str_increment"> 
              <div class="form-group">
                <label for="formGroupExampleInput">Do not Translate</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="string" required>
              </div>
              </div>
              </div>
                <button type="submit" class="btn btn-primary btn_str_save">Add</button>
                </form>
          </div> 

     
          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
          <form method="post" action="{{route('always_translate_as')}}">
            @csrf
            <input type="hidden" name="id" value="{{$ProjectDetail['id']}}">
                   <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">String From ({{$FromLanguage['name']}})</label>
                <input type="hidden" value="{{$FromLanguage['id']}}" name="from_language">
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="from_string" required>
              </div>
                <div class="form-group">
                <label for="formGroupExampleInput">String To ({{$ToLanguage['name']}})</label>
                <input type="hidden" value="{{$ToLanguage['id']}}" name="to_language">
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="to_string" required>
              </div>
              </div>
              </div>
              <!-- <div class="form-group text-right">
                <button type="button" class="btn btn_add_increase"><i class="material-icons">add</i>Add</button>
              </div> -->

              <button type="submit" class="btn btn-primary btn_str_save">Save</button>
              </form>
          </div>

     
          <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
          <form method="post" action="">
            @csrf
            <input type="hidden" name="id" value="{{$ProjectDetail['id']}}" id="id">
           <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">String From ({{$FromLanguage['name']}})</label>
                <input type="text" class="form-control" id="from_string" placeholder="" name="from_string" required>
                <input type="hidden" value="{{$FromLanguage['id']}}" name="from_language" id="from_language">
              </div>
                <div class="form-group">
                <label for="formGroupExampleInput">String To ({{$ToLanguage['name']}})</label>
                <input type="text" class="form-control" id="to_string" placeholder="" name="to_string" required>
                <input type="hidden" value="{{$ToLanguage['id']}}" name="to_language" id="to_language">
                
              </div>
              </div>
              </div>
              <!-- <div class="form-group text-right">
                <button type="button" class="btn btn_add_increase"><i class="material-icons">add</i>Add</button>
              </div> -->

              <button type="button" onclick="ReplaceStringOnFly()" class="btn btn-primary btn_str_save">Save</button>
              </form>
          </div>

        </div>
       
      </div>
      </div>
      <!--  <div class="string-form">
            <form method="" action="">
              <div class="check_left pro_check_left">
             <label class="form_radio">
              <input type="radio" name="status">
               <span class="checkmark">Do not translate</span>
             </label>
             <label class="form_radio">
              <input type="radio" name="status">
               <span class="checkmark">Always translate</span>
             </label>
              <label class="form_radio">
              <input type="radio" name="status">
               <span class="checkmark">Replace translated string</span>
             </label>
             </div>


             <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">String Form</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="">
              </div>
              <div class="form-group">
                <label for="formGroupExampleInput2">String To</label>
                <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="">
              </div>
              </div>
              
              </div>
              <div class="form-group text-right">
                <button type="button" class="btn btn_add_increase" onclick="add_div()"><i class="material-icons">add</i>Add</button>
              </div>

              <button type="submit" class="btn btn-primary btn_str_save">Save</button>
            </form>
          </div> -->
      </div>
    </div>
  </div>
</div>
<script>
  $(".btn_string_proof").click(function(){
    $("#exampleModal3").modal({backdrop: true});
});
    
  // function add_div()
  // {

  //   var html='<div class="str_increment"><div class="form-group"><label for="formGroupExampleInput">String Form</label><input type="text" class="form-control" id="formGroupExampleInput" placeholder=""></div><div class="form-group"><label for="formGroupExampleInput2">String To</label><input type="text" class="form-control" id="formGroupExampleInput2" placeholder=""></div></div>';

  //   $('.append_main').append(html);
  // }
</script>

 <!-- start add project popup -->
  <div class="modal fade add_new_modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body add_mid"> <a href="my-project.html" class="btn_new"><i class="material-icons">add</i>New Webproject</a>
          <a href="new-document.html" class="btn_new btn_diff"><i class="material-icons">add</i>New Document</a>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>
  <!-- end add project popup -->




  <!-- string other modal popup -->
 <!-- Modal -->
<div class="modal fade string_other_popup" id="string_other" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Select Projects for  String Corrections</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <i class="material-icons">
          close
          </i>
        </button>
      </div>
      <div class="modal-body">
          <div class="my-project-main string-correction">
              <div class="my-project-frm">

              <div class="row">
                <div class="col-md-12 col-sm-12"> 
                  <div class="str_project_main">
                <form action="{{route('select_string_correction_project')}}" method="POST">
                
                  @csrf

                  <input type="hidden" value="{{$ProjectDetail['id']}}" name="project_id">
                  <input type="hidden" value="{{$FromLanguage['id']}}" name="from_language">
                  <input type="hidden" value="{{$ToLanguage['id']}}" name="to_language">
                  <div class="form-group">
                    <div class="all-select-all-section">
                          <div class="mutliSelect">
                            <ul>
                              @if(!empty($ProjectLists))
                                @foreach($ProjectLists as $pro)
                                        <li>
                                          <div>
                                              <input id="checkbox-{{$pro['id']}}" class="checkbox-custom" name="project_select[]" type="checkbox" value="{{$pro['id']}}" data-name="{{$pro['project_name']}}">
                                              <label for="checkbox-{{$pro['id']}}" class="checkbox-custom-label">{{$pro['project_name']}}</label>
                                        
                                            </div>
                                        </li>
                                  @endforeach
                              @else
                                  
                                <li>
                                    <div>
                                  
                                    <label for="checkbox-1" class="checkbox-custom-label">No Project</label>
                                  </div>
                                  </li>

                              @endif
                            </ul>
                            <!-- <div class="sve_middle"><a href="javascript:void(0)" class="str_sve">Update</a></div> -->
                            <!-- <div class="sve_middle str_sve" onclick="SelectProject()">Update</a></div> -->
                            <input type="submit"> Update 
                          </div>
                    </div>
                  </div> 

</form>
                </div>
                </div>
              </div>

            </div>
            </div>
      </div>
    </div>
  </div>
</div>
  <!-- End string other modal popup -->

   <script>
    $(".dropdown dt a").on('click', function() {
  $(".dropdown dd ul").slideToggle('fast');
});

$(".dropdown dd ul li a").on('click', function() {
  $(".dropdown dd ul").hide();
});

function getSelectedValue(id) {
  return $("#" + id).find("dt a span.value").html();
}

$(document).bind('click', function(e) {
  var $clicked = $(e.target);
  if (!$clicked.parents().hasClass("dropdown")) $(".dropdown dd ul").hide();
});

$('.mutliSelect input[type="checkbox"]').on('click', function() {

var title = $(this).attr('data-name');
//alert(title)
  title = $(this).attr('data-name') + ",";
  console.log(title);

  if ($(this).is(':checked')) {

  var html = '<span title="' + title + '">' + title + '</span>';
  $('.multiSel').append(html);
  $(".hida").hide();
  } else {
  $('span[title="' + title + '"]').remove();
  var ret = $(".hida");
  $('.dropdown dt a').append(ret);

  }
});
  </script>

  <script>
      $(document).ready(function() {
        $(".toggle_value_0 ").click(function(){
		 $(".string_tabs_text").hide();
        
        });  
});
    $(document).ready(function() {
        $(".toggle_value_1 ").click(function(){
		     $(".string_tabs_text").hide();
           $("#str_1").show();
        });  
});

    $(document).ready(function() {
        $(".toggle_value_2").click(function(){
            $(".string_tabs_text").hide();
             $("#str_2").show();
        });  
});
  </script>

@endsection

<script>


function changeValue(val,id)
    {
      // alert(val);
      // alert('#ph_'+id);
      $('#ph_'+id).val(val);
    }


function editDoNotTranslate(id){
 
   var data = document.getElementById('ph_'+id).value;

  $.ajax({
    type: "POST",
    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
    dataType: "json",
    data: {'existingId' : id , 'string' : data }, 

    url: "{{route('do_not_translate')}}",
    success:function(message){
      //console.log(message.success);
      if(message.success == true){
        alert("Successfully Updated");
      }
      else{
        alert("Something Wrong !");
      }
      
      location.reload(); 
    }
  });
  
}




function deleteDoNotTranslate(project_id, string_id, from_language, to_language, type){
  var r = confirm("Are you sure to delete!");

  if (r == true) {
    //alert(id);
        $.ajax({
        type: "POST", 
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        dataType: "json",
        data: {'project_id' :  project_id, 'string_id' : string_id, 'from_language' : from_language, 'to_language' : to_language, 'type' : type},
        url: "{{route('delete_translate_string')}}", 
        success:function(message){ 
          // alert("successfully deleted");
          location.reload();
        }
      }).done(function( msg ) {
      });
  }
  else{
    txt = "You pressed Cancel!";
  }
}





function editAlwaysTranslate(id){
 
 var data = document.getElementById('ph_'+id).value;
//  alert(data);

 $.ajax({
    type: "POST",
    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
    dataType: "json",
    data: {'existingId' : id , 'to_string' : data },

    url: "{{route('always_translate_as')}}",
    success:function(message){
      console.log(message);
      if(message.success == true){
        alert("Successfully Updated");
      }
      else{
        alert("Something Wrong !");
      }
      
      location.reload(); 
    }
  });

}


</script>


<script>

  function DoNotSearch(project_id,language_id){
    // alert(project_id);
    // alert(language_id);
    var type=1;
    var search = document.getElementById("DoNotStringSearch").value;
    // var length = search.length;
    // alert(length);
    // alert(search);

    if(search.length === 0){
      alert("Please Enter e text!");
      return false;
    } 
    else{
      window.location.href = "{{url('/string-correction')}}"+'/'+project_id+'/'+language_id+'/'+search+'/'+type;

    }

    
  //   $.ajax({
  //   type: "GET",
  //   // headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
  //   dataType: "json",
  //   // data: {'id' : id , 'to_string' : data },

  //   url: "{{url('/string-correction')}}"+'/'+project_id+'/'+language_id+'/'+search,
   
  //   success:function(message){
  //      console.log(message);
  //      window.location.href = "{{url('/string-correction')}}"+'/'+project_id+'/'+language_id+'/'+search;
  //     // location.reload(); 
  //   }
  // });
  }

  function AlwaysSearch(project_id,language_id){ 

    var search = document.getElementById("AlwaysSearch").value;
    var type=2;
    if(search.length === 0){
      alert("Please Enter e text!");
      return false;
    }
    else{
      window.location.href = "{{url('/string-correction')}}"+'/'+project_id+'/'+language_id+'/'+search+'/'+type;
    }
    

  }

</script>


<script>

function SelectProject(){
  var data = document.getElementById("").val();
  alert("OKAY");
}

  </script>



<script>

function ReplaceStringOnFly(){

var from_language = document.getElementById("from_language").value;
var to_language = document.getElementById("to_language").value;
var id = document.getElementById("id").value;
var from_string = document.getElementById("from_string").value;
var to_string = document.getElementById("to_string").value;
// alert(from_language);
// alert(to_language);
// alert(id);
// alert(from_string);
// alert(to_string);
// alert("under processing");



$(document).ajaxStart(function(){
// Show image container
$("#main").hide();
$("#exampleModal2").hide();
 $("#loadingmessage").show();
//alert("OKAY");
});
$(document).ajaxComplete(function(){
// Hide image container
$("#loadingmessage").hide();
// $("#main").show();
// $("#exampleModal2").show();

});


$.ajax({
  type: "POST",
  headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
  dataType: "json",
  data: {'project_id' : id , 'from_language' : from_language , 'to_language' : to_language , 'from_string' : from_string , 'to_string' : to_string},
  // url: "{{url('/user/replace-translated-string')}}",
  url: "{{route('replace_translated_string')}}",
  success:function(message){
    console.log(message);
    location.reload(); 
  }
});

}

  </script>