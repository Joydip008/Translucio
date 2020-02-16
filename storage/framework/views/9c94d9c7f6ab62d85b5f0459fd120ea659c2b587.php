<?php $__env->startSection('title'); ?>
TRANSLICIO | My Page
<?php $__env->stopSection(); ?>
<?php $__env->startSection('add-meta'); ?>
<style type="text/css">
  .t-red{
     font-size: 80%;
     color: #dc3545;
  }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<!-- start buy credit secltion -->
<div class="translate-document-main personal-main" id="main">
    <div class="container custom-con">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 class="translate-heading profile-heading">My Project</h2>
          <form action="<?php echo e(url('/submit-project')); ?>" method="POST" id="add_language_form">
      <?php if(Session::has('message')): ?>
      <div class="alert alert-info"><?php echo e(Session::get('message')); ?></div>
      <?php endif; ?>
      <?php if(Session::has('messageError')): ?>
      <div class="alert alert-danger"><?php echo e(Session::get('messageError')); ?></div>
      <?php endif; ?>
          <?php echo csrf_field(); ?>
            <div class="my-project-main">
              <div class="my-project-frm">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="name">Project Name &nbsp;<i class="fa fa-star star"></i></label>
                      <?php if(!empty($Project)): ?>
                      <input type="hidden" name="id" value="<?php echo e($Project['id']); ?>">
                      <input type="text" class="form-control" id="" name="project_name" value="<?php echo e($Project['project_name']); ?>" required>
                      <?php else: ?>
                      <input type="text" class="form-control" id="" name="project_name"  value="<?php echo e(old('project_name')); ?>" autocomplete="project_name" autofocus required>
                      <?php endif; ?>
                      <?php if($errors->has('project_name')): ?>
                      <span class="help-block">
                        <strong class="error-msg mb-2"><?php echo e($errors->first('project_name')); ?></strong>
                      </span>
                    <?php endif; ?>
                    </div>
                  
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="name">Website URL &nbsp;<i class="fa fa-star star"></i></label>
                      <?php if(!empty($Project)): ?>
                        <input type="text" class="form-control" id="" placeholder="Eg : www.transluc.io" name="website_url" value="<?php echo e($Project['website_url']); ?>" required >  
                      <?php else: ?>
                        <input type="text" class="form-control" id="" placeholder="Eg : www.transluc.io" name="website_url"  value="<?php echo e(old('website_url')); ?>" autocomplete="website_url" autofocus required >
                      <?php endif; ?>
                      <?php if($errors->has('website_url')): ?>
                      <span class="help-block">
                        <strong class="error-msg mb-2"><?php echo e($errors->first('website_url')); ?></strong>
                      </span>
                    <?php endif; ?>
                    </div>
                  
                  </div>
                  <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                      <label for="exampleFormControlSelect1">Current Website Language &nbsp;<i class="fa fa-star star"></i></label>
                      <input type="hidden" id="currentLanguage_id">
                      <select class="form-control" id="current_website_language" name="current_website_language" required onchange="CurrentLanguageId(this.value)" value="<?php echo e(old('current_website_language')); ?>">
                      <option value=""> -- Select One --</option>
                        <?php $__currentLoopData = $LanguagesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($Project)): ?>
                                <option value="<?php echo e($Language->from_language); ?>" <?php echo e(($Language->from_language == $Project['current_language_id']) ? 'selected' : ''); ?>><?php echo e($Language->name); ?></option>
                            <?php else: ?>
                          
                                <option value="<?php echo e($Language->from_language); ?>" ><?php echo e($Language->name); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>          
                    </select>
                    <?php if($errors->has('current_language_id')): ?>
                      <span class="help-block">
                        <strong class="error-msg mb-2"><?php echo e($errors->first('current_language_id')); ?></strong>
                      </span>
                    <?php endif; ?>
                    </div>
                  
                  </div>
                  <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                      <label for="exampleFormControlSelect1">Project Category &nbsp;<i class="fa fa-star star"></i></label>
                      <select class="form-control" id="" name="project_category" required>
                      <option value=""> -- Select One --</option>
                        <?php $__currentLoopData = $ProjectCatagories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ProjectCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($Project)): ?>
                                <option value="<?php echo e($ProjectCategory->id); ?>" <?php echo e(($ProjectCategory->id == $Project['project_category']) ? 'selected' : ''); ?>><?php echo e($ProjectCategory->catagories); ?></option>
                            <?php else: ?>
                            <option value="<?php echo e($ProjectCategory->id); ?>" <?php echo e(($ProjectCategory->id == old('project_category')) ? 'selected' : ''); ?>><?php echo e($ProjectCategory->catagories); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>          
                    </select>
                    <?php if($errors->has('project_category')): ?>
                      <span class="help-block">
                        <strong class="error-msg mb-2"><?php echo e($errors->first('project_category')); ?></strong>
                      </span>
                    <?php endif; ?>
                    </div>
                  
                  </div>
                </div>
              </div>
            </div>


            <div class="my-project-main string-correction">
              <div class="my-project-frm">

      <div class="row">
                <div class="col-md-12 col-sm-12">
                  <div class="str_project_main">
                  <!-- <div class="form-group">
                    <label for="exampleFormControlSelect1">If string corrections must be used from other projects ?</label>
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


                            <?php if(!empty($ProjectLists)): ?>
                                <?php $__currentLoopData = $ProjectLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                          <div>
                                          <?php if(!empty($Project)): ?>
                                                <input id="checkbox-<?php echo e($pro['id']); ?>" <?php echo e((in_array($pro->id,$arrayPSC) ? 'checked' : '')); ?> class="checkbox-custom" name="project_select[]" type="checkbox" value="<?php echo e($pro['id']); ?>" data-name="<?php echo e($pro['project_name']); ?>">
                                                <label for="checkbox-<?php echo e($pro['id']); ?>" class="checkbox-custom-label"><?php echo e($pro['project_name']); ?></label>
                                            <?php else: ?>
                                              <input id="checkbox-<?php echo e($pro['id']); ?>" class="checkbox-custom" name="project_select[]" type="checkbox" value="<?php echo e($pro['id']); ?>" data-name="<?php echo e($pro['project_name']); ?>">
                                              <label for="checkbox-<?php echo e($pro['id']); ?>" class="checkbox-custom-label"><?php echo e($pro['project_name']); ?></label>
                                          <?php endif; ?>
                                            </div>
                                        </li>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php else: ?>
                                  
                                <li>
                                    <div>
                                  
                                    <label for="checkbox-1" class="checkbox-custom-label">No Project</label>
                                  </div>
                                  </li>

                              <?php endif; ?>

                              
                            </ul>
                          </div>
                        </dd>
                      </dl>
                    </div>
                  </div> -->
                </div>
                </div>
                <div class="col-md-12">
                  <div class="switch-section">
                    <p>Metadata Translation</p>
                    <label class="switch">
                      <?php if($Project['metadata_translation'] == 1): ?>
                      <input type="checkbox" checked name="metadata_translation"> <span class="slider round"></span>
                      <?php else: ?>
                      <input type="checkbox" name="metadata_translation"> <span class="slider round"></span>
                      <?php endif; ?>
                    </label>
                  </div>
                  <div class="switch-section">
                    <p>Media Translations</p>
                    <label class="switch">
                    <?php if($Project['media_translation'] == 1): ?>
                      <input type="checkbox" checked name="media_translation"> <span class="slider round"></span>
                    <?php else: ?>
                      <input type="checkbox" name="media_translation"> <span class="slider round"></span>
                    <?php endif; ?>
                    </label>
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
                    <label for="exampleFormControlSelect1">Add Language &nbsp;<i class="fa fa-star star"></i></label>
                    <div class="form-group">
              
                    <input type="hidden" id="lang_sel_id">
                    <input type="hidden" id="dest_lang_id">
                    
                    <select class="form-control lang_diff" id="Language_Pair_Id" onchange="langChange(this.value,this)">
                    <option value=""> -- Select One --</option>

                    <?php if(!empty($Project)): ?>
                      <?php if(!empty($DestinationProjectLanguage)): ?>
                    
                        <?php $__currentLoopData = $LanguagePairList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $LanList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                          <option value="<?php echo e($LanList['to_language']); ?>"><?php echo e($LanList['name']); ?></option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                      <?php endif; ?>
                    <?php endif; ?>

                            
                    </select> 


                    <button type="button" class="add_lang" onclick="return AddLanguage(<?php echo $Language['id']; ?>);" required>Add Language</button>
                    <?php if($errors->has('Visibility')): ?>
                      <span class="help-block">
                        <strong class="error-msg mb-2">The destination language field is required.</strong>
                      </span>
                    <?php endif; ?>
                  </div>
                  
                </div>
              
                <div class="col-md-12" id="add_language" required data-value="<?php echo e($Project['current_language_id']); ?>">
                    <div id="IfChangeHide">
                    <?php if(!empty($DestinationProjectLanguage)): ?>
                      <?php $__currentLoopData = $DestinationProjectLanguage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $LanList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 

                            <div class="visibility_section" id="<?php echo e($LanList['language_id']); ?>" id="IfChangeHide1">
                              <div class="spanish-line" id="LanguageName">
                                <?php echo e($LanList['name']); ?></div>
                              <div class="lang_vis">
                                <p>Language Visibility :</p>
                                <div class="form-group mb-0 c-cus">
                                  <select class="form-control lang_type" id="visibility_status2" name="Visibility[]">
                                  <option value="0" <?php echo e(($LanList->visibility_status == "0") ? 'selected' : ''); ?>>Public</option>
                                    <option value="1" <?php echo e(($LanList->visibility_status == "1") ? 'selected' : ''); ?>>Private</option>
                                    <option value="2" <?php echo e(($LanList->visibility_status == "2") ? 'selected' : ''); ?>>Hidden</option>
                                  
                                  </select>
                                </div>
                              </div>
                              <div class="cross-section"  onclick="RemoveDiv('<?php echo e($LanList['language_id']); ?>')">
                                
                                  <img src="<?php echo e(asset('assets/images/icons/cross.png')); ?>" class="img-fluid">
                                
                              </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php endif; ?>
                    </div>
                </div>
              


                </div>
              </div>
            </div>
            
            <button type="submit" class="btn btn_project_submit" onclick="Submit()">Submit</button>

            <?php if(!empty($Project) || (Session::has('message'))): ?>
           
          </form>

          <div class="my-project-main integrate-part" id="">
            <div class="my-project-frm">
              <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="search_sec m-search">
                  <form action="">
                    <!-- <div class="form-group">
                      <label>API Key</label>
                      <?php if(!empty($Project['api_key'])): ?>
                      <input type="text" value="<?php echo e($Project['api_key']); ?>" name="search" id="GfGInput1" disabled>
                      <?php elseif((Session::has('message'))): ?>
                      <input type="text" value="<?php echo e(Session::get('api_key')); ?>" name="search" id="GfGInput1" disabled>
                      <?php else: ?>
                      <input type="text" value="003026bbc133714df1834b8638bb496e-8f4b3d9a-e9" name="search" id="GfGInput" disabled>
                      <?php endif; ?>
                      
                      <button onclick="CopyAPiKey()" type="text" class="btn_cpy_ser ser_1"><i class="fa fa-clone" aria-hidden="true"></i>Copy</button>
                    </div> -->
                    <?php
                        $script='<script type="text/javascript">var chidbx ="MzU=";
                        window.onload=(function(d,s,u){
                        var e=d.getElementsByTagName(s)[0],$=d.createElement(s);
                        $.src=atob(u);
                        e.parentNode.insertBefore($,e) 
                        })(document,"script","aHR0cHM6Ly9kZXYudHJhbnNsdWMuaW8vYXNzZXRzL3RyYW5zbGF0b3IvdHJhbnNsLmpz");
                        </script>';
                        ?>
                        <input type="text" class="offscreen" id="script_id"  value="<?php echo e($script); ?>" >
                    <div class="form-group mb-0">

                      <label>Show Integration Script</label>
                      <a href="javascript:void(0)" onclick="CopyScript()" type="text"  class="btn_cpy_ser ser_1"><i class="fa fa-clone" aria-hidden="true"></i>Copy</a>
                      <!-- <img src="assets/images/integrate.PNG"> -->
                      <div class="inte-code">
                      <code>
                      <!-- <span><</span>script type="text/javascript" src="https://cdn.weglot.com.min.js"> <span><</span>/script>
                        
                      <span><</span>script>
                      
                              Weglot.initialize({
                       
                         <?php if(!empty($Project['api_key'])): ?>
                       <input type="text" value="<?php echo e($Project['api_key']); ?>" name="search" id="GfGInput1" disabled>
                       <?php elseif((Session::has('message'))): ?>
                       <input type="text" value="<?php echo e(Session::get('api_key')); ?>" name="search" id="GfGInput1" disabled>
                       <?php else: ?>
                       <input type="text" value="wg_bb78f5965fe4e789f8126e962ea3d1446" name="search" id="GfGInput" disabled>
                       <?php endif; ?>
                               })
                        
                      <span><</span>/script> -->
                      <span><</span>script type="text/javascript">
                        var chidbx ="MzU=";
                        window.onload=(function(d,s,u){
                        var e=d.getElementsByTagName(s)[0],$=d.createElement(s);
                        $.src=atob(u);
                        e.parentNode.insertBefore($,e) 
                        })(document,"script","aHR0cHM6Ly9kZXYudHJhbnNsdWMuaW8vYXNzZXRzL3RyYW5zbGF0b3IvdHJhbnNsLmpz");
                        <span><</span>/script>
                       
                        
                          </code>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              </div>
            </div>
          </div>

          <!-- If translated time null or up 60 days then delete option display -->
       
          <?php if($DeleteOption == 1): ?>
          <div class="my-project-main delete-part">
            <div class="my-project-frm">
              <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="delete-heading">
                      <?php if((Session::has('message'))): ?> 
                      <p>Delete This Project <span onclick="DeleteProject('<?php echo e(Session()->get('id')); ?>')"><a href="">Delete Project</a></span>
                      <?php else: ?>
                      <p>Delete This Project <span onclick="DeleteProject('<?php echo e($Project['id']); ?>')"><a href="">Delete Project</a></span>
                      <?php endif; ?>
                  </p>
                </div>
                <div class="note_area">
                  <div class="note_img">
                    <img src="<?php echo e(asset('assets/images/icons/note.png')); ?>">
                  </div>
                  <div class="note_content">
                    <p><span>Note :</span> before you delete this project!</p> <small>Once you delete this project there is no way to recovering data and all translations associated will be deleted too/</small>
                  </div>
                </div>
              </div>
              </div> 
            </div>
          </div>
         
          <?php endif; ?>

<?php endif; ?>

        
        </div>
      </div>
    </div>
  </div>
  </div>


   


   <!-- start js section -->
   <script src="<?php echo e(asset('assets/js/jquery-1.9.1.min.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/bootstrap.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/custom.js')); ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
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
 
  var copyGfGText = document.getElementById("GfGInput1"); 
  
  /* Select the text field */
  copyGfGText.select(); 
  copyGfGText.setSelectionRange(0, 99999)
  /* Copy the text inside the text field */
  document.execCommand("copy"); 
  alert("Copied the text: " + copyGfGText.value);
});
}  
  </script>
  <?php $__env->stopSection(); ?>

  <script>


var AddLanguages=[];
var existsDestArr=[];
var DestinationProjectLanguage=[];
var TemexistsDestArr=[];
var TemVisibility=[];

var MaxNumberLanguagesCounter = 0;

window.onload = function() {
  var DestinationProjectLanguage = <?php echo json_encode($DestinationProjectLanguage, JSON_HEX_TAG); ?>;

  for(var i=0; i<DestinationProjectLanguage.length; i++){
    var lang_id = DestinationProjectLanguage[i]['language_id'];
    var status = DestinationProjectLanguage[i]['visibility_status'];
    existsDestArr.push(lang_id);
    TemVisibility.push(status);
  }
}
function AddLanguage(language_id){

  
 
    var $form = $('#add_language_form');
     
     var lang_id=$('#lang_sel_id').val();

     var lang_text=$('#dest_lang_id').val();
  
     /* please select a language*/
   
   
    var n = existsDestArr.includes(lang_id);
  //  if(MaxNumberLanguagesCounter<<?php echo json_encode($NumberOfLanguage, JSON_HEX_TAG); ?>){

  
    if(n==false){

    
     if(lang_id){
      
      
      MaxNumberLanguagesCounter++;
        //$form.append($('<input type="hidden" name="AddLanguages[]">').val(existsDestArr));
      
        countCheck=1;

        var html='<div class="visibility_section" id='+lang_id+'><div class="spanish-line" id="LanguageName">'+lang_text+'</div><div class="lang_vis"><p>Language Visibility :</p><div class="form-group mb-0 c-cus"><select class="form-control lang_type" id="visibility_status1" name="Visibility[]"><option value="0">Public</option><option value="1">Private</option><option value="2">Hidden</option></select></div></div><div class="cross-section"  onclick="RemoveDiv('+lang_id+')"><img src="<?php echo e(asset('assets/images/icons/cross.png')); ?>" class="img-fluid"></div></div>';

        $('#add_language').append(html);
        //langChange(null);
        existsDestArr.push(lang_id); 

     }
     else{
        alert("Please select a language");
     }
    }
    else{
      alert("Already added in your List");
    }
  // }
  // else{
  //   alert("Your subscribed maximum language support reached ! ");
  // }
}

function langChange(val,all)
{
  var dest_lang = document.getElementById("Language_Pair_Id");
    console.log(dest_lang.options[dest_lang.selectedIndex].text);
    $('#lang_sel_id').val(val);
    $('#dest_lang_id').val(dest_lang.options[dest_lang.selectedIndex].text);
}

function RemoveDiv(val){

  //alert(val);
    var elem = document.getElementById(val);
     elem.parentNode.removeChild(elem);
     MaxNumberLanguagesCounter--;
    
  for(var i=0; i<existsDestArr.length; i++){
    if(existsDestArr[i] == val){
      var ind = i;
    } 
  }
  existsDestArr.splice(ind, 1);
  
}




</script>


<script>
function CopyAPiKey() {
  var copyText = document.getElementById("GfGInput1");
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
  alert("Copied the text: " + copyText.value);
}

</script>


<script>
  var count=0;
function CurrentLanguageId(val)
{
  
  var check = document.getElementById("add_language").getAttribute("data-value"); 
 
  if(val!=check && count==0){
    count=1;
    document.getElementById("IfChangeHide").style.display = "none";
    
    var divsToHide = document.getElementsByClassName("visibility_section"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++){
        divsToHide[i].style.visibility = "hidden"; // or
        divsToHide[i].style.display = "none"; // depending on what you're doing
    }

    TemexistsDestArr = existsDestArr;
    existsDestArr.length= 0;
    $("#visibility_status1").val([]);
    $("#visibility_status2").val([]);

  }
  else{
    document.getElementById("IfChangeHide").style.display = "block";
    var divsToHide = document.getElementsByClassName("visibility_section"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++){
        divsToHide[i].style.visibility = "hidden"; // or
        divsToHide[i].style.display = "none"; // depending on what you're doing   form-group mb-0 c-cus
    }

    existsDestArr.length= 0;
    $("#visibility_status1").val([]);
    $("#visibility_status2").val([]);
    
  }
     
      $.ajax({
        headers: {'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'},
        url:"<?php echo e(url('/get-language-pair')); ?>",
		    type: "POST",
		    data: {'id':val},
        dataType : 'json',
		   success: function(response){
		    console.log(response);
      
      
       var obj = response;
         $("#Language_Pair_Id").empty();
         $('#lang_sel_id').val(response[0].id);
      $('#dest_lang_id').val(response[0].name);
         $.each(obj, function(index, element) {
           $("#Language_Pair_Id").append("<option value='"+ element.id +"' >" + element.name + "</option>");
         });
      
		    }
		});

}
function CopyScript()
{
  // // document.getElementById("script_id").style.display = "block";
  // $('#script_id').show();

  var copyText = document.getElementById("script_id");
    
    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /*For mobile devices*/
  
    /* Copy the text inside the text field */
    document.execCommand("copy");
  
    /* Alert the copied text */
    // alert("Copied the text: " + copyText.value);
}


</script>

<script>

function Submit(){

  var $form = $('#add_language_form');
 
  $form.submit(function(event) {
    /* Check If Origin Language Id Change*/
    var NewOriginLanguageId = document.getElementById('current_website_language').value;
    var OldOriginLanguageId = document.getElementById('add_language').value;
    if(NewOriginLanguageId==OldOriginLanguageId){
      $form.append($('<input type="hidden" name="AddLanguages[]" required>').val(TemexistsDestArr));
    }
    else{
      $form.append($('<input type="hidden" name="AddLanguages[]" required>').val(existsDestArr));
    }
    
    $form.find('.submit').prop('disabled', true);

    document.getElementById("add_language_form").submit();
  });

}
  </script>

  <script>

function DeleteProject(id){
  
  
  var r = confirm("Are you sure to delete!");
  
	        if (r == true) {
           
                  $.ajax({
                type: "GET", 
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                dataType: "json",
                data: {'id' :  id },
                url: "<?php echo e(url('/delete-project')); ?>", 
                success:function(message){ 
                  if(message.success == true){
                      location.reload();
                  }
                  else{
                    alert("can not delete project within 60days of create date!");
                  }
              });
          }
          else{
            txt = "You pressed Cancel!"; 
          }
         
}
    </script>
<?php echo $__env->make('layouts.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/user/myProject/add_new_project.blade.php ENDPATH**/ ?>