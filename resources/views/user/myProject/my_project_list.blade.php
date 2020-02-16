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
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 class="translate-heading profile-heading">Dashboard</h2>
        </div>
        <div class="col-md-12">
          <div class="credit_history">
            <!-- <h2 class="credit-history-heading">Please check your usage of credit history</h2> -->
            <div class="add-project-btn-container">
            @if (Session::has('message'))
      <div class="alert alert-info">{{ Session::get('message') }}</div>
      @endif
      @if (Session::has('messageError'))
      <div class="alert alert-danger">{{ Session::get('messageError') }}</div>
      @endif
              <form action="{{url('/my-project')}}" method="get">
              @csrf
                <div class="row">
                  <div class="col-sm-6 col-md-7">
                    <div class="text-search user_dash">
                      <div class="form-group">
                        <input class="form-control" type="text" placeholder="Search Project Name" name="ProjectNameSearch" aria-label="Search"> <span class="search_span"> <i class="material-icons">search</i></span>
                       
                      </div>
                      <button type="submit" class="btn btn_project_submit">Search</button> 
                    </div>
                  </div>
                  <div class="col-md-5">
                    <button type="button" class="fly" data-toggle="modal" data-target=".add_new_modal">
                      <i class="material-icons">add</i>Add project</button>
                  </div>
                </div>
              </form>
            </div>
            @if($haveDataCheck > 0)
            <div class="credit_all_histohry">
              <div class="history_table history_table_diff">
                <div class="history_table_part">
                  <!--Table-->
                  <table class="table table-bordered table-responsive">
                    <thead>
                      <tr>
                        <th class="th-lg">Project Name</th>
                        <th class="th-lg">Project Type</th> 
                        <th class="th-lg">Creation Date & Time</th>
                        <th class="th-lg">Project owner</th>
                        <th class="th-lg">Origin language</th>
                        <th class="th-lg">Destination language</th>
                        <th class="th-lg">Status</th>
                        <th class="th-lg big_header">Paragraphs(paragraphs pending approval)</th>
                        <th class="th-lg">Action</th>
                      </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    @foreach($Projects as $pro)
                    <tr>
                        <td rowspan="{{$pro['number']}}" class="firstc">
                          @if($pro['project_type']==1)
                          <!-- {{url('/edit-project-web/'.$pro['id'])}} -->
                            <a href="{{url('/edit-project-web/'.$pro['id'])}}">{{$pro['project_name']}}</a>
                          @else
                          <!-- {{url('/edit-doc-project/'.$pro['id'])}} -->
                          {{$pro['project_name']}}
                          <!-- {{$pro['project_name']}} -->
                          @endif
                          <div class="deletetd">
						<a href="{{url('/edit-project-web/'.$pro['id'])}}"   class="edit_link">Edit Project</a>	
                         <a href="javascript:;"  onclick="DeleteProject('{{ $pro['id'] }}')"  class="delete_link">Delete Project</a>
                           
                          </div> 
                        </td>
                        @if($pro['project_type']==1)
                        <td rowspan="{{$pro['number']}}" class="text-center">Webproject 
                        </td>
                        @else
                        <td rowspan="{{$pro['number']}}" class="text-center">Document
                        </td>
                        @endif
                        <td rowspan="{{$pro['number']}}">{{$pro['created_at']}}</td>
                        <td rowspan="{{$pro['number']}}" class="text-center"> <a href="javascript:;">Me</a>
                        </td>
                        <td rowspan="{{$pro['number']}}">{{$pro['CurrentLanguageName']}}</td>

                        <!-- Destination Language -->
                        
                        <td>{{$pro['DestinationLanguageName'][0]}}</td>


                        @if($pro['DestinationLanguageStatus'][0] == 0)
                        <td class="text-center"> <span class="status public">Public</span>
                        @elseif($pro['DestinationLanguageStatus'][0] == 1)
                        <td class="text-center"> <span class="status private">Private</span>
                        @else
                        <td class="text-center"> <span class="status hidden">Hidden</span>
                        @endif
                        </td> 
                        <td class="text-center big_header">Original ( Character : {{$pro['OriginalTextCount'][0]}} / Word : {{$pro['OriginalTextCountWord'][0]}} ) || Machine Translated : {{$pro['TranslatedTextCount'][0]}} || User Translated : {{$pro['TotalTranslatedTextCount'][0]}} | Total Paragraphs : {{$pro['ProjectTotalParagraphs'][0]}} / Approval : {{$pro['ProjectApprovalParagraphs'][0]}} / Pending : {{$pro['ProjectPendingParagraphs'][0]}}
                        </td>
                        <td class="text-center actiontd"> 
                          <a href="{{url('/proof-reading/'.$pro['id'].'/'.$pro['DestinationLanguagesId'][0])}}" class="edit-btn">Proofreading</a>
                         
                          <a href="{{url('/string-correction/'.$pro['id'].'/'.$pro['DestinationLanguagesId'][0].'/1')}}" class="edit-btn str_correction">String corrections</a>
                        </td>
                      </tr>


                      @for($i=1; $i<$pro['number']; $i++)

                      <tr>
                        <td>{{$pro['DestinationLanguageName'][$i]}}</td>
                        @if($pro['DestinationLanguageStatus'][$i] == 0)
                        <td class="text-center"> <span class="status public">Public</span>
                        @elseif($pro['DestinationLanguageStatus'][$i] == 1)
                        <td class="text-center"> <span class="status private">Private</span>
                        @else
                        <td class="text-center"> <span class="status hidden">Hidden</span>
                        @endif
                        </td>
                        <td class="text-center">Original ( Character : {{$pro['OriginalTextCount'][$i]}} / Word : {{$pro['OriginalTextCountWord'][0]}} ) || Machine Translated : {{$pro['TranslatedTextCount'][$i]}} || User Translated : {{$pro['TotalTranslatedTextCount'][$i]}} | Total Paragraphs: {{$pro['ProjectTotalParagraphs'][$i]}} / Approval : {{$pro['ProjectApprovalParagraphs'][$i]}} / Pending : {{$pro['ProjectPendingParagraphs'][$i]}}
                        </td>
                       <td class="text-center actiontd">
                          <a href="{{url('/proof-reading/'.$pro['id'].'/'.$pro['DestinationLanguagesId'][$i])}}" class="edit-btn">Proofreading</a>
                          <a href="{{url('/string-correction/'.$pro['id'].'/'.$pro['DestinationLanguagesId'][$i].'/1')}}" class="edit-btn str_correction">String corrections</a>
                        </td>
                      </tr>
                      @endfor
						<tr class="blank_tr"><td class="text-center" colspan="9"></td></tr>
                      @endforeach
                    </tbody>
                    <!--Table body-->
                  </table>
                  

                  <!--Table-->
                </div>
                
                <div class="col-md-12">
                  <div class="pagination_right">
                  @include('vendor.pagination.custom', ['paginator' => $results, 'link_limit' => 3])
                   
                  </div>
                </div>
              </div>
            </div>
            @else

			
            <div class="credit_all_histohry">
              <div class="history_table history_table_diff">
                <div class="history_table_part">
					 <div class="no_project_found">
						  <img src="{{ asset('assets/images/icons/no_project.png')}}" class="img-fluid">
						  <h3>No Project Found!</h3>
						  <p>There are no project, in the current status.</br>
						  Why not you create a new Project.
						  </p>  
						  <button type="button" class="fly" data-toggle="modal" data-target=".add_new_modal">
							  <i class="material-icons">add</i>Create A Project</button>
					</div>
          @endif
                </div>
              </div>
            </div>
			


        
          </div>
          </div>
      </div>
    </div>
  </div>
  <!-- end my profile section your document section -->


   <!-- start add project popup -->
   <div class="modal fade add_new_modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body add_mid">
		  <a href="{{url('/edit-project-web')}}" class="add_project_btn"><i class="material-icons">add</i>Add Web Project</a>
          
		  <a href="{{route('add_new_doc_project')}}" class="add_project_btn"><i class="material-icons">add</i>Add API Project</a>
		  <a href="{{route('add_new_doc_project')}}" class="add_project_btn"><i class="material-icons">add</i>Add Documents Project</a>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>
  <!-- end add project popup -->
  <!-- start js section -->
  <script src="{{ asset('assets/js/jquery-1.9.1.min.js')}}"></script>
  <script src="{{ asset('assets/js/bootstrap.js')}}"></script>
  <script src="{{ asset('assets/js/custom.js')}}"></script>
  @endsection
  <script>

function DeleteProject(id){
  
  //var id = document.getElementById("DeleteProjectId");
  var r = confirm("Are you sure to delete!");
  
	        if (r == true) {
            //alert(id);
                  $.ajax({
                type: "GET", 
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                dataType: "json",
                data: {'id' :  id },
                url: "{{route('delete_project')}}", 
                success:function(message){ 
                  
                  if(message.success == true){
                      location.reload();
                  }
                  else{
                    alert("can not delete project within 60days of create date!");
                  }
                }
              });
          }
          else{
            txt = "You pressed Cancel!";
          }
          //alert("OKAY");
}
    </script>