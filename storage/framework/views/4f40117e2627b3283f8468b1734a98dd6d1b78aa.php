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
            <?php if(Session::has('message')): ?>
      <div class="alert alert-info"><?php echo e(Session::get('message')); ?></div>
      <?php endif; ?>
      <?php if(Session::has('messageError')): ?>
      <div class="alert alert-danger"><?php echo e(Session::get('messageError')); ?></div>
      <?php endif; ?>
              <form action="<?php echo e(url('/my-project')); ?>" method="get">
              <?php echo csrf_field(); ?>
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
            <?php if($haveDataCheck > 0): ?>
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
                        <th class="th-lg">Paragraphs(paragraphs pending approval)</th>
                        <th class="th-lg">Action</th>
                      </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php $__currentLoopData = $Projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td rowspan="<?php echo e($pro['number']); ?>">
                          <?php if($pro['project_type']==1): ?>
                          <!-- <?php echo e(url('/edit-project-web/'.$pro['id'])); ?> -->
                            <a href="<?php echo e(url('/edit-project-web/'.$pro['id'])); ?>"><?php echo e($pro['project_name']); ?></a>
                          <?php else: ?>
                          <!-- <?php echo e(url('/edit-doc-project/'.$pro['id'])); ?> -->
                          <?php echo e($pro['project_name']); ?>

                          <!-- <?php echo e($pro['project_name']); ?> -->
                          <?php endif; ?>
                          <div class="delete-heading">
                         <a href="javascript:;"  onclick="DeleteProject('<?php echo e($pro['id']); ?>')"  class="text-danger p-0 pt-2 d-inline-block">Delete Project</a>
                           
                          </div> 
                        </td>
                        <?php if($pro['project_type']==1): ?>
                        <td rowspan="<?php echo e($pro['number']); ?>" class="text-center">Webproject 
                        </td>
                        <?php else: ?>
                        <td rowspan="<?php echo e($pro['number']); ?>" class="text-center">Document
                        </td>
                        <?php endif; ?>
                        <td rowspan="<?php echo e($pro['number']); ?>"><?php echo e($pro['created_at']); ?></td>
                        <td rowspan="<?php echo e($pro['number']); ?>" class="text-center"> <a href="javascript:;">Me</a>
                        </td>
                        <td rowspan="<?php echo e($pro['number']); ?>"><?php echo e($pro['CurrentLanguageName']); ?></td>

                        <!-- Destination Language -->
                        
                        <td><?php echo e($pro['DestinationLanguageName'][0]); ?></td>


                        <?php if($pro['DestinationLanguageStatus'][0] == 0): ?>
                        <td class="text-center"> <span class="status public">Public</span>
                        <?php elseif($pro['DestinationLanguageStatus'][0] == 1): ?>
                        <td class="text-center"> <span class="status private">Private</span>
                        <?php else: ?>
                        <td class="text-center"> <span class="status hidden">Hidden</span>
                        <?php endif; ?>
                        </td> 
                        <td class="text-center">Original ( Character : <?php echo e($pro['OriginalTextCount'][0]); ?> / Word : <?php echo e($pro['OriginalTextCountWord'][0]); ?> ) || Machine Translated : <?php echo e($pro['TranslatedTextCount'][0]); ?> || User Translated : <?php echo e($pro['TotalTranslatedTextCount'][0]); ?> | Total Paragraphs : <?php echo e($pro['ProjectTotalParagraphs'][0]); ?> / Approval : <?php echo e($pro['ProjectApprovalParagraphs'][0]); ?> / Pending : <?php echo e($pro['ProjectPendingParagraphs'][0]); ?>

                        </td>
                        <td class="text-center"> 
                          <a href="<?php echo e(url('/proof-reading/'.$pro['id'].'/'.$pro['DestinationLanguagesId'][0])); ?>" class="edit-btn">Proofreading</a>
                         
                          <a href="<?php echo e(url('/string-correction/'.$pro['id'].'/'.$pro['DestinationLanguagesId'][0].'/1')); ?>" class="edit-btn str_correction">String corrections</a>
                        </td>
                      </tr>


                      <?php for($i=1; $i<$pro['number']; $i++): ?>

                      <tr>
                        <td><?php echo e($pro['DestinationLanguageName'][$i]); ?></td>
                        <?php if($pro['DestinationLanguageStatus'][$i] == 0): ?>
                        <td class="text-center"> <span class="status public">Public</span>
                        <?php elseif($pro['DestinationLanguageStatus'][$i] == 1): ?>
                        <td class="text-center"> <span class="status private">Private</span>
                        <?php else: ?>
                        <td class="text-center"> <span class="status hidden">Hidden</span>
                        <?php endif; ?>
                        </td>
                        <td class="text-center">Original ( Character : <?php echo e($pro['OriginalTextCount'][$i]); ?> / Word : <?php echo e($pro['OriginalTextCountWord'][0]); ?> ) || Machine Translated : <?php echo e($pro['TranslatedTextCount'][$i]); ?> || User Translated : <?php echo e($pro['TotalTranslatedTextCount'][$i]); ?> | Total Paragraphs: <?php echo e($pro['ProjectTotalParagraphs'][$i]); ?> / Approval : <?php echo e($pro['ProjectApprovalParagraphs'][$i]); ?> / Pending : <?php echo e($pro['ProjectPendingParagraphs'][$i]); ?>

                        </td>
                       <td class="text-center">
                          <a href="<?php echo e(url('/proof-reading/'.$pro['id'].'/'.$pro['DestinationLanguagesId'][$i])); ?>" class="edit-btn">Proofreading</a>
                          <a href="<?php echo e(url('/string-correction/'.$pro['id'].'/'.$pro['DestinationLanguagesId'][$i].'/1')); ?>" class="edit-btn str_correction">String corrections</a>
                        </td>
                      </tr>
                      <?php endfor; ?>
                   
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <!--Table body-->
                  </table>
                  

                  <!--Table-->
                </div>
                
                <div class="col-md-12">
                  <div class="pagination_right">
                  <?php echo $__env->make('vendor.pagination.custom', ['paginator' => $results, 'link_limit' => 3], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                   
                  </div>
                </div>
              </div>
            </div>
            <?php else: ?>


            <div class="credit_all_histohry">
              <div class="history_table history_table_diff">
                <div class="history_table_part">
                  <!--Table-->
                  <table class="table table-bordered table-responsive">
                    <thead>
                      <tr>
                      </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                          No Project ! 
                    </tbody>
                    <!--Table body-->
                  </table>
                  

                  <!--Table-->
                </div>
                
                <div class="col-md-12">
                  <div class="pagination_right">
                 
                  </div>
                </div>
              </div>
            </div>



            <?php endif; ?>
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
        <div class="modal-body add_mid"> <a href="<?php echo e(url('/edit-project-web')); ?>" class="btn_new"><i class="material-icons">add</i>New Webproject</a>
          <a href="<?php echo e(route('add_new_doc_project')); ?>" class="btn_new btn_diff"><i class="material-icons">add</i>New Document</a>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>
  <!-- end add project popup -->
  <!-- start js section -->
  <script src="<?php echo e(asset('assets/js/jquery-1.9.1.min.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/bootstrap.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/custom.js')); ?>"></script>
  <?php $__env->stopSection(); ?>
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
                url: "<?php echo e(route('delete_project')); ?>", 
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
<?php echo $__env->make('layouts.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/user/myProject/my_project_list.blade.php ENDPATH**/ ?>