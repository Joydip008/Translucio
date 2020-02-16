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
<center>
<div id='loadingmessage' style='display:none'>
  <img src="{{ asset('assets/upload/loading.gif')}}">
</div>
<center>


<div class="translate-document-main personal-main" id="main">

    <div class="container custom-con">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 class="translate-heading profile-heading">Proofreading</h2>
         
          @if(session()->has('message'))
          <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
        {{ session()->get('message') }}
    </div>
@endif
@if(session()->has('messageError'))
          <div class="alert alert-danger">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
        {{ session()->get('messageError') }}
    </div>
@endif
 
@if($errors->any())
  						<div class="alert alert-danger">
    							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							@foreach($errors->all() as $error)
    								<p>{!! $error !!}</p>
    							@endforeach
  						</div>
  					@endif 
          <div class="credit_history">
            <div class="col-sm-12 col-md-12">
              <form method="" action="" class="frm_proof_sub">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="selectbox proof">
                        <label for="content">Content Type</label>
                        <select class="form-control" id="">
                          <option >Visible text</option>
                          <option>Metadata</option>
                          <option>Media</option>
                        </select> <i class="material-icons">keyboard_arrow_down</i>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="selectbox proof">
                        <label for="status">Status</label>
                        <select class="form-control" id="FilterData" onchange="FilterDataList(this.value,'{{$ProjectDetails['id']}}','{{$DestinationLanguage['id']}}')">
                          <option value="1" @if($check==1) selected @endif>All</option>
                          <option value="2" @if($check==2) selected @endif>Approved</option>
                          <option value="3" @if($check==3) selected @endif>Not approved</option>
                        </select> <i class="material-icons">keyboard_arrow_down</i>
                      </div>
                    </div>
                  </div>
                   
                  <div class="col-md-6 text-right"> 
                  <!-- <a href="{{url('/updated-project/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])}}">Execute Now </a> -->
                   <a class="btn btn-primary btn btn_add_proof" data-toggle="modal" data-target="#exampleModal2"><i class="material-icons">add</i>Add String Correction
                    </a>
                    <input type="hidden" name="select_project" value="{{$ProjectDetails['id']}}" id="select_project">
                    <input type="hidden" name="select_language" value="{{$DestinationLanguage['id']}}" id="select_language">
                    @if($ProjectDetails['project_type'] == 1)
                    <a class="btn btn-primary" data-toggle="modal" data-target="#myModal">Show Script</a>
                    <!-- {{url('/website-execute/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])}} -->
                    <a class="btn btn-primary" href="{{url('/website-execute/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])}}">Execute & Show Web Site</a>
                    @endif
                    @if($ProjectDetails['project_type'] == 2)
                   
                      @if($ProjectDetails['extension'] === 'pdf')
                      <!-- {{url('/download-updated-project/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])}} --> 
                        <a class="btn btn-primary" href="{{url('/download-updated-project/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])}}">Execute & Download PDF</a>
                      @elseif($ProjectDetails['extension'] === 'docx') 
                        <a class="btn btn-primary" href="{{url('/download-updated-project/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])}}">Execute & Download DOCX</a>
                      @endif
                    @endif 
                  </div> 
                </div> 
              </form>
            </div>
            <div class="replace_table">
              <div class="string_correction_1 ">
                <div class="string_correction">
                  <!--Table-->
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="th-lg">Translated from <span style="color: #716FEA">{{$OriginLanguage['name']}}</span>
                        </th>
                        <th class="th-lg">Translated into <span style="color: #716FEA">{{$DestinationLanguage['name']}}</span>
                        </th>
                        <th class="th-lg">Action</th>
                      </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                      @if(!empty($ParagraphDetails))
                        @foreach($ParagraphDetails as $Data)
                        @if(strip_tags($Data['original_data'])!='')
                        @php $y=preg_replace('/<\s*w:t[^>]*>/', "|**|",strip_tags($Data['original_data'],'<w:t>')) ;
                              $y=str_replace('</w:t>','',str_replace("|**|","",$y),$y);
                              @endphp
                          <tr>
                            @if(!empty($Data['original_data']) || $Data['original_data']!=null)
                              <td>{{$y}}</td> 
                            @endif
                              <td id=""  class="common-width">

                              @if(!empty($Data['translated_data']) || $Data['translated_data']!=null)
                              @php $x=preg_replace('/<\s*w:t[^>]*>/', "|**|",strip_tags($Data['translated_data'],'<w:t>')) ;
                              $x=html_entity_decode(str_replace('</w:t>','|##|',str_replace("|**|","",$x),$x));
                              $x_show=$x;
                              $x_show=str_replace('|##|','',$x_show);
                              $show=explode('|##|',$x);
                              $show_count=count($show)-1;
                              unset($show[$show_count]);
                              @endphp
                              <!-- content-editable -->
                                <p class="mb-0" id="{{$Data['id']}}" value="{{$x}}" onkeyup="changeValuePro(this,'<?php echo $Data['id'];?>')">{{$x_show}}</p>
                                <!-- <input type="text" class="mb-0 content-editable" id="ph_{{$Data['id']}}" value="{{$Data['translated_data']}}"> -->
                                <!-- <input type="text" class="mb-0 content-editable" id="ph_{{$Data['id']}}" value="{{$x}}"> -->
                             <div id="div_edit_{{$Data['id']}}" style="display:none">
                              @foreach($show as $key =>$each)
                               <input type="text" class="mb-0 content-editable" id="ph_{{$key}}_{{$Data['id']}}" value="{{$each}}">
                              @endforeach
                              </div>
                                @endif
                               
                               
                                  <p class="approved_head" id="approve_{{$Data['id']}}"> @if($Data['status'] == 1)Approved by<span>:  {{Auth::user()->name}} at {{$Data['updated_at']}} </span> @elseif($Data['status'] == 0)Pending @endif
                                      <!-- <button class="Version-btn" data-toggle="modal" href="#ignismyModal">Version</button> -->
                                  </p>
                               
                                
                        </td>
                        <td style="color: #716FEA">
                      
                          <input type="button" id="edit_button1" value="Edit" class="edit string-edit-btn" onclick="edit_data('{{$Data['id']}}')">
                       
                          <input type="button" id="save_button1" value="Save" class="save save_dis" onclick="save_data('{{$Data['id']}}','{{count($show)}}')">
                          <!-- onclick="save_data('{{$Data['id']}}')" -->

                          @if($Data['status'] != 1)
                            <button class="approve-btn" id="approve_button_{{$Data['id']}}" onclick="Approved_Paragraph('{{$Data['id']}}')">Approve</button>
                          @endif
                        </td>
                      </tr>
                     @endif
                      @endforeach
                      @endif
                    </tbody>
                    <!--Table body-->
                  </table>
                  <!--Table-->
                </div>
                <div class="col-md-12">
                  <div class="pagination_right">
                  @include('vendor.pagination.custom', ['paginator' => $results, 'link_limit' => 3])
                    <!-- <ul>
                      <li><a href="javascript:void(0)"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                      </li>
                      <li><a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                      </li>
                      <li><span class="activea">1</span>
                      </li>
                      <li><a href="javascript:void(0)" class="bg-none">2</a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-right"
                            aria-hidden="true"></input></a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-double-right"
                            aria-hidden="true"></input></a>
                      </li>
                    </ul> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end my profile section your document section -->


   <!-- start version modal section -->
   <div class="modal fade" id="ignismyModal" role="dialog"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog profedding-bx">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="version_title">Version</h3>
          <!--  <div class="frm_headin">
                    <form method="" action="" class="frm_proof">
                        <div class="container">
                          <div class="row">
                            <div class="col-md-3">
                            <div class="profeding_search">
                           <div class="form-group">
                            <label>Search Your Text</label>
                            <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                            <span class="search_proff"><img src="assets/images/icons/search.png" class="img-responsive"></span>
                          </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="filter">Filter Language</label>
                            <select class="form-control" id="">
                              <option>English</option>
                              <option>French</option>
                              <option>Bengali</option>
                              <option>Hindi</option>
                            </select>
                          </div>
                         </div>
                           <div class="col-md-3">
                          <div class="form-group">
                            <label for="content">Content Type</label>
                            <select class="form-control" id="">
                              <option>Visible text</option>
                              <option>Metadata</option>
                              <option>Media</option>
                            </select>
                          </div>
                         </div>
                           <div class="col-md-3">
                          <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="">
                              <option>Approved</option>
                              <option>Not approved</option>
                            </select>
                          </div>
                         </div>
                        </div>
                        </div>
                      </form>
                      </div> -->
          <button type="button" class="close close_version" data-dismiss="modal" aria-label=""><i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body">
          <ul class="timeline">
            <li>
              <div class="timeline-badge warning"></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Origin language</h4>
                </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                </div>
              </div>
            </li>
            <li class="timeline-inverted">
              <div class="timeline-badge warning"></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Destination language (machine translated)</h4>
                </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                </div>
              </div>
            </li>
            <li>
              <div class="timeline-badge warning"></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Edited version <span class="update_tags">current</span></h4>
                      </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                </div>
              </div>
            </li>

              <li>
              <div class="timeline-badge warning"></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Currnet version <span class="update_tags tag_different">Last updated 21th July</span></h4>
                      <div class="back_drop dropdown">
                      <button onclick="show_hide()" type="button" class="btn_previous btn_next">make it Actual</button>
                      <div style="display: none;" id="drop-content"> <a href="javascript:void(0)">Last Updated 26th September</a>
                        <a href="javascript:void(0)">Last Updated 21th July</a>
                        <a href="javascript:void(0)">Last Updated 10th Otober</a>
                      </div>
                    </div>
                      </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                </div>
              </div>
            </li>

              <li>
              <div class="timeline-badge warning"></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Currnet version <span class="update_tags tag_different">Last updated 21th July</span></h4>
                      <div class="back_drop dropdown">
                      <button onclick="show_hide()" type="button" class="btn_previous btn_next">make it Actual</button>
                      <div style="display: none;" id="drop-content"> <a href="javascript:void(0)">Last Updated 26th September</a>
                        <a href="javascript:void(0)">Last Updated 21th July</a>
                        <a href="javascript:void(0)">Last Updated 10th Otober</a>
                      </div>
                    </div>
                      </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                </div>
              </div>
            </li>

              <li>
              <div class="timeline-badge warning"></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Currnet version <span class="update_tags tag_different">Last updated 21th July</span></h4>
                      <div class="back_drop dropdown">
                      <button onclick="show_hide()" type="button" class="btn_previous btn_next">make it Actual</button>
                      <div style="display: none;" id="drop-content"> <a href="javascript:void(0)">Last Updated 26th September</a>
                        <a href="javascript:void(0)">Last Updated 21th July</a>
                        <a href="javascript:void(0)">Last Updated 10th Otober</a>
                      </div>
                    </div>
                      </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- end version modal section -->

  <script>
    function show_hide(){
        var click = document.getElementById("drop-content");
        if(click.style.display == "none"){
          click.style.display = "block";
        }
        else{
          click.style.display = "none";
        }
      } 
  </script>
  <!-- start js section -->
  <script src="{{ asset('assets/js/jquery-1.9.1.min.js')}}"></script>
  <script src="{{ asset('assets/js/bootstrap.js')}}"></script>
  <script src="{{ asset('assets/js/custom.js')}}"></script>
  <script>


// $('body').on('click', '.string-edit-btn', function () {
//           var editelement = $(this).parents('tr').find('.content-editable');;
//           editelement.attr('contenteditable', 'true').focus();
//           editelement.parent("td").css('background', '#f2f2f2');
//           $(this).css('display', 'none');
//           $(this).next('.save').css('display', 'inline-block');
//         });
    
    $('body').on('click', '.string-edit-btn', function () {
          var editelement = $(this).parents('tr').find('.content-editable');;
          editelement.attr('contenteditable', 'true').focus();
          editelement.parent("td").css('background', '#f2f2f2');
          $(this).css('display', 'none');
          $(this).next('.save').css('display', 'inline-block');
        });
        $('body').on('click', '.save_dis', function () {
          var editelement = $(this).parents('tr').find('.content-editable');;
          editelement.attr('contenteditable', 'false').blur();
          editelement.parent("td").css('background', '#ffff');
          $(this).css('display', 'none');
          $(this).prev('.string-edit-btn').css('display', 'inline-block');
        });
  </script>



<div class="modal fade proof-mod" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="add_heading">Add New String Corrections</h2>
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
            <input type="hidden" name="id" value="{{$ProjectDetails['id']}}">
            <input type="hidden" value="{{$OriginLanguage['id']}}" name="from_language">
            <input type="hidden" value="{{$DestinationLanguage['id']}}" name="to_language">
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
            <input type="hidden" name="id" value="{{$ProjectDetails['id']}}">
                   <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">String From ({{$OriginLanguage['name']}})</label>
                <input type="hidden" value="{{$OriginLanguage['id']}}" name="from_language">
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="from_string" required>
              </div>
                <div class="form-group">
                <label for="formGroupExampleInput">String To ({{$DestinationLanguage['name']}})</label>
                <input type="hidden" value="{{$DestinationLanguage['id']}}" name="to_language">
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
            <input type="hidden" name="id" value="{{$ProjectDetails['id']}}" id="id">
           <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">String From ({{$OriginLanguage['name']}})</label>
                <input type="text" class="form-control" id="from_string" placeholder="" name="from_string" required>
                <input type="hidden" value="{{$OriginLanguage['id']}}" name="from_language" id="from_language">
              </div>
                <div class="form-group">
                <label for="formGroupExampleInput">String To ({{$DestinationLanguage['name']}})</label>
                <input type="text" class="form-control" id="to_string" placeholder="" name="to_string" required>
                <input type="hidden" value="{{$DestinationLanguage['id']}}" name="to_language" id="to_language">
                
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
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        <div>
        <code>
        <span><</span>script type="text/javascript">
          var chidbx ="MzU=";
          window.onload=(function(d,s,u){
          var e=d.getElementsByTagName(s)[0],$=d.createElement(s);
          $.src=atob(u);
          e.parentNode.insertBefore($,e) 
          })(document,"script","aHR0cHM6Ly9kZXYudHJhbnNsdWMuaW8vcHVibGljL3RyYW5zbGF0b3IvcmVtb3RlLmpz");
          <span><</span>/script>
            </code>
        </div>
        </div>
        </div>
        </div>

<script>
$(document).ready(function () {

// Attach Button click event listener 
$("#myBtn").click(function(){

    // show Modal
    $('#myModal').modal('show');
});
});
</script>
<script>
  $(".btn_add_proof").click(function(){
    $("#exampleModal2").modal({backdrop: true}); 
});

  //   function add_div()
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


@endsection

<script>

function edit_data(id)
{
  $('#'+id).css('background', '#f2f2f2');
  document.getElementById('div_edit_'+id).style.display='block';

}
  function save_data(id,arr_count){ 
     //alert('div_edit_'+id);
    // alert(language_id);
  
    data='';
    for(var i=0;i<=arr_count-1;i++)
    {
      if(i==arr_count-1)
      var data = data + document.getElementById('ph_'+i+'_'+id).value;
      else
    var data = data + document.getElementById('ph_'+i+'_'+id).value+'|##|';
    }
    //alert(data);
    // var project_id = document.getElementById('select_project').value;
    // alert(project_id);
    //alert(data);
    // var r = confirm("Are you sure to Update");
    
    // if(r == true){ 
        $.ajax({
          type:"GET",
          headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
          dataType: "json",
          data: {'id' :  id, 'data' : data },
          url: "{{route('update_paragraph')}}", 
          success:function(message){
            console.log(message);
            document.getElementById('approve_button_'+id).style.display='none';
            document.getElementById('div_edit_'+id).style.display='none';
           
            $('#'+id).css('background', '#ffff');
            document.getElementById(id).innerHTML=message.success.data;
           
           // document.getElementById('pending_'+id).style.display='none';
            document.getElementById('approve_'+id).innerHTML=message.success.msg;
            

            //location.reload();
          }
        });
    // }
    // else{
    //   txt = "You pressed Cancel!";
    // }
  }


function Approved_Paragraph(id){
  //alert(id);
  // var r = confirm("Are you sure to Approved!");
  
  
  // if (r == true) {
   
          $.ajax({
        type: "GET", 
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        dataType: "json",
        data: {'id' :  id },
        url: "{{route('approved_paragraph')}}", 
        success:function(message){ 
          document.getElementById('approve_button_'+id).style.display='none';
          document.getElementById('approve_'+id).innerHTML=message.success.msg;
          // console.log(message);
          // console.log("H4ello");
          // location.reload();
        }
      }).done(function( msg ) {
      });
  // }
  // else{
  //   txt = "You pressed Cancel!";
  // }
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
      // console.log(message);
       location.reload(); 
    }
  });
  
}
  </script>

<script>

function FilterDataList(val,id,destination_language){
  // alert("Prpocessing");
  // alert(val);
  // alert(id);
  // alert(destination_language);
  window.location.href = "{{url('/proof-reading')}}"+'/'+id+'/'+destination_language+'/'+val;

  // $.ajax({
  //     type: "GET", 
  //     //headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
  //     dataType: "json",
  //     //data: {'val' :  val , 'id' : id , 'language_id' : destination_language},
  //     url: "{{url('/proof-reading')}}"+'/'+id+'/'+destination_language+'/'+val, 
  //     success:function(message){ 
  //       console.log(message);
  //       // location.reload();
  //     }
  //   });
}
</script>