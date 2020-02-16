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
  <img src="assets/upload/loading.gif">
</div>
<center>
 <!-- start buy credit secltion -->
 <div class="translate-document-main personal-main" id="main" style='display:block'>
    <div class="container custom-con">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 class="translate-heading profile-heading">New Document</h2>
          <form action="{{url('/submit-doc-project')}}" method="POST" id="add_language_form" enctype="multipart/form-data">
          @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
          @endif
            @if (Session::has('message_exception'))
              <div class="alert-danger">{{ Session::get('message_exception') }}</div>
            @endif
          @csrf
            <div class="my-project-main">
              <div class="my-project-frm">
                <div class="row">
                  
                  <!-- <div class="col-md-12">
                    <div class="form-group">
                   
                      <label for="name">Project Name</label>
                      @if(!empty($Project))
                      <input type="hidden" name="id" value="{{$Project['id']}}">
                      <input type="text" class="form-control" id="" name="project_name" value="{{$Project['project_name']}}" required>
                      @else
                      <input type="text" class="form-control" id="" name="project_name"  value="{{ old('project_name') }}" autocomplete="project_name" autofocus required>
                      @endif
                    </div>
                    @if ($errors->has('project_name'))
                        <span>
                            <strong>{{ $errors->first('project_name') }}</strong>
                        </span>
                    @endif
                  </div> -->
                  <div class="col-md-12 col-sm-12 text-left">
                    <div class="form-group">
                      <label for="name">Original Document &nbsp;<i class="fa fa-star star"></i></label>
                      <!-- <button type="submit" class="btn btn_upload_document">Upload</button> -->
                            <div class="upload_cover">
                             
                            <input type="file" class="btn btn_upload_document" name="upload_document_project" required
                            accept="application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"/>
                            Upload</button>
                            <span class="hint">(only allow word, text & pdf file)</span>
                            @if ($errors->has('upload_document_project'))
                        <span class="help-block">
                          <strong class="error-msg mb-2">{{ $errors->first('upload_document_project') }}</strong>
                        </span>
                    @endif
                       </div>
                    </div>

                  </div>
                  <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                      <label for="exampleFormControlSelect1">Origin Language &nbsp;<i class="fa fa-star star"></i></label>
                        <select class="form-control" id="" name="current_website_language" required onchange="CurrentLanguageId(this.value)">
                        <option value=""> -- Select One --</option>

                        @foreach ($LanguagesList as $Language)
                            @if(!empty($Project))
                                <option value="{{ $Language->from_language }}" {{ ($Language->id == $Project['current_language_id']) ? 'selected' : '' }}>{{ $Language->name }}</option>
                            @else
                            <!-- {{ ($Language->id == old('current_website_language')) ? 'selected' : '' }} -->
                            <option value="{{ $Language->from_language }}" >{{ $Language->name }}</option>
                            @endif
                        @endforeach   

                    </select>
                    </div>
                  </div>
                  <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                      <label for="exampleFormControlSelect1">Project Category &nbsp;<i class="fa fa-star star"></i></label>
                      <select class="form-control" id="" name="project_category" required>
                      <option value=""> -- Select One --</option>
                      @foreach ($ProjectCatagories as $ProjectCategory)
                            @if(!empty($Project))
                                <option value="{{ $ProjectCategory->id }}" {{ ($ProjectCategory->id == $Project['project_category']) ? 'selected' : '' }}>{{ $ProjectCategory->catagories }}</option>
                            @else
                            <option value="{{ $ProjectCategory->id }}" {{ ($ProjectCategory->id == old('project_category')) ? 'selected' : '' }}>{{ $ProjectCategory->catagories }}</option>
                            @endif
                        @endforeach  
                    </select>
                    @if ($errors->has('project_category'))
                        <span class="help-block">
                          <strong class="error-msg mb-2">{{ $errors->first('project_category') }}</strong>
                        </span>
                    @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="destination_head_main">
              <h3 class="destination-heading">Destination language &nbsp;<i class="fa fa-star star"></i></h3>
            </div>
            <div class="my-project-main add-language-part">
              <div class="my-project-frm">
                <div class="row">
                <div class="col-md-6 col-sm-12">
                  <div class="form-group">
                    <label for="exampleFormControlSelect1">Add Language &nbsp;<i class="fa fa-star star"></i></label>
                    <input type="hidden" id="lang_sel_id">
                    <input type="hidden" id="dest_lang_id">
                    <select class="form-control lang_diff" id="Language_Pair_Id" onchange="langChange(this.value,this)" required>
                    <option value=""> -- Select One --</option>

                      @if(!empty($Project))
                        @if(!empty($DestinationProjectLanguage))
                      
                          @foreach($LanguagePairList as $LanList)

                            <option value="{{ $LanList['to_language'] }}">{{ $LanList['name'] }}</option>

                          @endforeach

                        @endif
                      @endif


                    </select>
                    <button type="button" class="add_lang" onclick="return AddLanguage();">Add Language</button>
                    @if ($errors->has('Visibility'))
                      <span class="help-block">
                        <strong class="error-msg mb-2">The destination language field is required.</strong>
                      </span>
                    @endif
                  </div>
                </div>
                <div class="col-md-12" id="add_language" required >

                <div id="IfChangeHide">
                    @if(!empty($DestinationProjectLanguage))
                      @foreach($DestinationProjectLanguage as $LanList)

                    <div class="visibility_section" id="{{$LanList['language_id']}}" id="IfChangeHide1">
                      <div class="spanish-line" id="LanguageName">
                        <img src="{{ asset('assets/images/icons/spain.png')}}" class="img-fluid">{{$LanList['name']}}</div>
                      <div class="lang_vis">
                        <p>Language Visibility :</p>
                        <div class="form-group mb-0 c-cus">
                          <select class="form-control lang_type" id="visibility_status2" name="Visibility[]">
                          <option value="0" {{ ($LanList->visibility_status == "0") ? 'selected' : '' }}>Public</option>
                            <option value="1" {{ ($LanList->visibility_status == "1") ? 'selected' : '' }}>Private</option>
                            <option value="2" {{ ($LanList->visibility_status == "2") ? 'selected' : '' }}>Hidden</option>
                          
                          </select>
                        </div>
                      </div>
                      <div class="cross-section"  onclick="RemoveDiv('{{ $LanList['language_id'] }}')">
                        
                          <img src="{{ asset('assets/images/icons/cross.png')}}" class="img-fluid">
                        
                      </div>
                    </div>

                    @endforeach

@endif
</div>

                </div>
                </div>
              </div>
            </div>
            <div class="my-project-main string-correction" style="display:none">
              <div class="my-project-frm">

<div class="row">
                 <div class="col-md-12 col-sm-12">
                  <div class="str_project_main">
                  <div class="form-group">
                    <label for="exampleFormControlSelect1">If string corrections must be used from other projects ? </label>
                    <div class="all-select-all-section">
                      <dl class="dropdown"> <dt id="check_service_div">
                          <a>
                            <span class="hida">Select Project</span> 
                            <i class="material-icons">keyboard_arrow_down</i> 
                            <p class="multiSel"></p>  
                          </a>
                      </dt>
                        <dd>
                          <div class="mutliSelect">
                            <ul>
                            @if(!empty($ProjectLists))
                                @foreach($ProjectLists as $pro)
                                        <li>
                                          <div>
                                          @if(!empty($Project))
                                                <input id="checkbox-{{$pro['id']}}" {{ (in_array($pro->id,$arrayPSC) ? 'checked' : '')}} class="checkbox-custom" name="project_select[]" type="checkbox" value="{{$pro['id']}}" data-name="{{$pro['project_name']}}">
                                                <label for="checkbox-{{$pro['id']}}" class="checkbox-custom-label">{{$pro['project_name']}}</label>
                                            @else
                                              <input id="checkbox-{{$pro['id']}}" class="checkbox-custom" name="project_select[]" type="checkbox" value="{{$pro['id']}}" data-name="{{$pro['project_name']}}">
                                              <label for="checkbox-{{$pro['id']}}" class="checkbox-custom-label">{{$pro['project_name']}}</label>
                                          @endif
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
                          </div> 
                        </dd>
                        <!-- // <button>Filter</button>  -->
                      </dl>
                    </div>
                  </div>
                </div>
                </div>
              </div>

            </div>
            </div>
            @if(empty($Project))
            <button type="submit" class="btn btn_project_submit" id="submit" onclick="SubmitForm()">Render Translation</button>
            @endif
            </form>
          
         
          @if(!empty($Project) || Session::has('message'))

          <div class="my-project-main string-correction">
              <div class="my-project-frm"><div class="row">
                 <div class="col-md-12 col-sm-12">
                  <div class="str_project_main"> 
                  @if(!empty($DestinationProjectLanguage))
                  @foreach($DestinationProjectLanguage as $DesLanguage)
                  <div class="form-group">

                  
                   <label for="exampleFormControlSelect1">Download Render Document<span class="tran">Language <i class="material-icons">arrow_forward</i> {{$DesLanguage['name']}}</span></label>
               
                
                  <?php $name = $DesLanguage['sortname'].'_'.$Project['documentation_name']; ?>
                     <div class="render_button">
                     <!-- {{url('/download-updated-project/'.$Project['id'].'/'.$DesLanguage['language_id'])}} -->

                     <!-- {{route('download_project',$name)}} -->
                       <a href="{{url('/download-updated-project/'.$Project['id'].'/'.$DesLanguage['language_id'])}}" target=”_blank” class="btn_render">Download Now <i class="material-icons">cloud_download</i></a>
                     </div>
                  
                    @if(Session::has('message'))
                      <div class="render_button" onclick="DownloadProject('{{ Session()->get('documentation_name') }}')">
                        <a href="javascript:void(0)" class="btn_render">Download Now <i class="material-icons">cloud_download</i></a>
                      </div>
                    @endif
                  </div>
                  @endforeach
                  @endif
                </div>
                </div>
              </div>
            </div>
            </div>
            
          <!-- If translated time null or up 60 days then delete option display -->
         
          @if($DeleteOption == 1)
            <div class="my-project-main delete-part" id="delete_section">
            <div class="my-project-frm">
              <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="delete-heading">
                  <p>Delete This Project <span onclick="DeleteProject('{{ Session()->get('id') }}')"><a href="">Delete Project</a></span>
                  </p>
                </div>
                <div class="note_area">
                  <div class="note_img">
                    <img src="{{ asset('assets/images/icons/note.png')}}">
                  </div>
                  <div class="note_content">
                    <p><span>Note :</span> before you delete this project!</p> <small>Once you delete this project there is no way to recovering data and all translations associated will be deleted too/</small>
                  </div>
                </div>
              </div>
              </div>
            </div>
          </div>
        
          @endif

@endif

        </div>
      </div>
    </div>
  </div>
  <!-- end buy credit section -->

  <!-- start js section -->
  <script src="{{ asset('assets/js/jquery-1.9.1.min.js')}}"></script>
  <script src="{{ asset('assets/js/bootstrap.js')}}"></script>
  <script src="{{ asset('assets/js/custom.js')}}"></script>
  <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js')}}"></script>
  <script>
    function readURL(input) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
              $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
              $('#imagePreview').hide();
              $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
          }
        }
        $("#imageUpload").change(function () {
          readURL(this);
        });
    
        $('body').on('click', '.select-plan', function(){
          $('#payment-form').removeClass('collapse');
    
        });
  </script>
  <script>
    $('.date-own').datepicker({
          minViewMode: 2,
          format: 'yyyy'
        });
  </script>
  <script>
    $('#data').datepicker({
          format: "dd-MM",
          todayHighlight: true,
          autoclose: true,
          clearBtn: true
        });
  </script>
  <script>
    $('body').on('click', '.select-project', function(){
          $('#select-check').removeClass('collapse');
    
        });
  </script>
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
    function GeeksForGeeks() { 
  /* Get the text field */
  var copyGfGText = document.getElementById("GfGInput"); 
  
  /* Select the text field */
  copyGfGText.select(); 
  
  /* Copy the text inside the text field */
  document.execCommand("copy"); 
}  
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
var AddLanguages=[];
var existsDestArr=[];
var DestinationProjectLanguage=[];
window.onload = function() {
  var DestinationProjectLanguage = {!! json_encode($DestinationProjectLanguage, JSON_HEX_TAG) !!};
  for(var i=0; i<DestinationProjectLanguage.length; i++){
  //  alert(DestinationProjectLanguage[i]['language_id']);
    var lang_id = DestinationProjectLanguage[i]['language_id'];
    existsDestArr.includes(lang_id);
   // alert(existsDestArr);
  }
}

function CurrentLanguageId(val){

//alert(val);
    var divsToHide = document.getElementsByClassName("Visibility_section"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++){
        divsToHide[i].style.visibility = "hidden"; // or
        divsToHide[i].style.display = "none"; // depending on what you're doing
    }
    existsDestArr.length= 0;
    $("#visibility_status1").val([]);
    $("#visibility_status2").val([]);
    // /alert(existsDestArr)

    $.ajax({
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
      url:"{{url('/get-language-pair')}}",
		  type: "POST",
		  data: {'id':val},
        dataType : 'json',
		  success: function(response){
		   //console.log(response);
       $('#lang_sel_id').val(response[0].id);
    $('#dest_lang_id').val(response[0].name);
       var obj = response;
         $("#Language_Pair_Id").empty();
         $.each(obj, function(index, element) {
             //alert(element.name);
           $("#Language_Pair_Id").append("<option value='"+ element.id +"' >" + element.name + "</option>");
         });
      
		    }
		});


}

function langChange(val,all)
{
  
 
  var dest_lang = document.getElementById("Language_Pair_Id");
    //console.log(dest_lang.options[dest_lang.selectedIndex].text);
    $('#lang_sel_id').val(val);
    $('#dest_lang_id').val(dest_lang.options[dest_lang.selectedIndex].text);
}



function AddLanguage(language_id){
    //$("#add_language").show();
    
    var $form = $('#add_language_form');
     
     var lang_id=$('#lang_sel_id').val();
     var lang_text=$('#dest_lang_id').val();
    //alert(lang_id);
    
    console.log(existsDestArr.length);
    var n = existsDestArr.includes(lang_id);
    //alert(n);
    if(n==false){

    
     if(lang_id){
        //$form.append($('<input type="hidden" name="AddLanguages[]">').val(existsDestArr));

        var html='<div class="visibility_section" id='+lang_id+'><div class="spanish-line" id="LanguageName"><img src="{{ asset('assets/images/icons/spain.png')}}" class="img-fluid">'+lang_text+'</div><div class="lang_vis" hidden><p>Language Visibility :</p><div class="form-group mb-0 c-cus"><select class="form-control lang_type" id="visibility_status1" name="Visibility[]"><option value="0">Private</option><option value="1">Public</option><option value="2">Hidden</option></select></div></div><div class="cross-section"  onclick="RemoveDiv('+lang_id+')"><img src="{{ asset('assets/images/icons/cross.png')}}" class="img-fluid"></div></div>';

        $('#add_language').append(html);
        //langChange(null);
        existsDestArr.push(lang_id);

     }
     else{
        alert("Please Select A Language!");
     }
    }
    else{
      alert("Already added");
    }
}

function SubmitForm(){
  
var $form = $('#add_language_form');


  


  

$form.submit(function(event) {

  
  $form.append($('<input type="hidden" name="AddLanguages[]" required>').val(existsDestArr));

  // var div= document.createElement("div");
  //   div.className += "overlay";
  //   document.body.appendChild(div);
  

  alert("When File translated You have got a email on upr email id. Thank You");
  document.getElementById("add_language_form").submit(); 

 



});

}

function RemoveDiv(val){

//alert(val);
var elem = document.getElementById(val);
 elem.parentNode.removeChild(elem);

for(var i=0; i<existsDestArr.length; i++){
if(existsDestArr[i] == val){
  var ind = i;
} 
}
existsDestArr.splice(ind, 1);

}
</script>

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
                  // alert("successfully deleted");
                  location.reload();
                }
              }).done(function( msg ) {
              });
          }
          else{
            txt = "You pressed Cancel!";
          }
          //alert("OKAY");
}
    </script>



<script>
//   function DownloadProject(id){
//   alert(id);
//   alert(id);
  

//               $.ajax({
//                 type: "GET", 
//                 headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
//                data: {'id' :  id },
//                dataType: "application/octet-stream",
//                 url: "{{route('download_project')}}", 
//                 success:function(message){ 
//                   alert(message);
//                    alert("successfully deleted");
//                   //location.reload();
//                 }
//               })
     
// }
  </script>

  <style>
    .overlay {
    background-color:rgba(255,255,255,0.7);
    background-image: url("assets/upload/loading.gif");
    position: fixed;
    width: 100%;
    height: 100%;
    z-index: 1000;
    top: 0px;
    left: 0px;
    background-repeat: no-repeat;
    background-position: center;
}
</style>