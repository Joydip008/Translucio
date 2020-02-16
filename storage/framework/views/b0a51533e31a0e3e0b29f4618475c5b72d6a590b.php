<?php $__env->startSection('title', 'Translucio|Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Wrapper. Contains page content -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard .
        <small>Customer Details</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content"> 
      <div class="row">
        <div class="col-md-4">
          <div class="box box-success customer-profile">
            <div class="box-body bx-form lng_frm">
                <div class="my-profile-left-dashboard">
                  <div class="dash_part_1">
                  <div class="client-people-profile-img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/client.png')); ?>" class="img-responsive"></div>
                  <div class="people-title-dashboard">
                  <h3><?php echo e($clientDetails['name']); ?> <?php echo e($clientDetails['last_name']); ?></h3>
                </div>
                </div>

                <div class="my-profile-left-dashboard-1">
                  <div><label>Company Name:</label> <span><?php echo e($clientDetails['company_name']); ?></span></div>
                  <div><label>Address:</label> <span><?php echo e($clientDetails['city']); ?></span></div>
                  <div><label>Company VAT No:</label> <span><?php echo e($clientDetails['vat_number']); ?></span></div>
                </div>

                <div class="my-profile-left-dashboard-2">
                  <h3 class="overview_title">My Profile</h3>
                  <a href="#" onclick="PersonalInformation()" class="dash_btn_personal_information"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/profile.png')); ?>" class="img-responsive">personal Information</a>

                  <a href="#" onclick="OrderHistory()" class="dash_btn_change_information"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/order-history.png')); ?>" class="img-responsive">Order History</a>

                  <a href="#" onclick="PlanDetails()" class="dash_btn_api_information"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/plan-details.png')); ?>" class="img-responsive">Plan Details</a>
                </div>
               </div>
            </div>
          </div>
        </div>


        <div id="PersonalDetails">
        <div class="col-md-8">
          <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title personal-heading">Personal Information</h3>
              </div>
            <div class="box-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="client-details-area">
                        <table class="table">
                            <tbody>
                              <tr>
                                <th scope="row">Client Name</th>
                                <td><?php echo e($clientDetails['name']); ?> <?php echo e($clientDetails['last_name']); ?></td>
                              </tr>
                              <tr>
                                <th scope="row">Email</th>
                                <td><?php echo e($clientDetails['email']); ?></td>
                              </tr>
                                <tr>
                                <th scope="row">Credits in plan</th>
                                <td style="color: #6eb400;"><?php echo e($CurrentPlans['plan_name']); ?>/<?php echo e($CurrentPlans['period_time']); ?></td>
                              </tr>
                                 <tr>
                                <th scope="row">Credit Used(+)</th>
                                <td style="color: #f4104b;">Credit Include : <?php echo e($CreditIncludes); ?>  || Credit Available : <?php echo e($TotalCredit); ?></td>
                              </tr>
                                  <tr>
                                <th scope="row">Number of Project</th>
                                <td><?php echo e($TotalNumberProjects); ?></td>
                              </tr>
                                   <tr>
                                <th scope="row">Number of Paragraphs</th>
                                <td><?php echo e($TotalNumberParagraph); ?></td>
                              </tr> 
                                     <tr>
                                <th scope="row">Creation Date</th>
                                <td><?php echo e($clientDetails['created_at']); ?></td>
                              </tr>
                                <tr>
                                <th scope="row">Upgrade Date</th>
                                <td><?php echo e($clientDetails['updated_at']); ?></td>
                              </tr>
                                <tr>
                                <th scope="row">Last use date</th>
                                <td><?php echo e($clientDetails['last_login_at']); ?></td>
                              </tr>
                                <tr>
                                <th scope="row">Last use Proofreading</th>
                                <td>------------</td>
                              </tr>
                            </tbody>
                          </table>
                      </div>
                  </div>
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.box -->
        </div>
        </div>





      <div id="OrderHistory" style="display:none">

      <div class="col-md-8">
            <div class="box bx-head-diff">
              <div class="box-header with-border-1">
                <h3 class="box-title personal-heading">Order History</h3>
              </div>
              <div class="box-body bx-bd-diff">
                <div class="row">
                  <div class="col-md-12">
                    <div class="client_order_history">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Invoiced Item</th>
                            <th scope="col">Amount in Euro(€)</th>
                            <th scope="col">Action (pdf download)</th>
                          </tr>
                        </thead> 
                        <tbody>
                        <?php $__currentLoopData = $AllSubscriptionsDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($Subscription['created_at']); ?></th>
                              <td><?php echo e($Subscription['invoice_number']); ?></td>
                              <td>€ <?php echo e($Subscription['cost']); ?></td>
                              <td>
                                <div class="order_dwn">
                                <!-- class="pdf_dwn" download=""   -->
                                  <a href="<?php echo e(url('/admin/download-invoice')); ?>" >
                                    <img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/pdf.png')); ?>" class="img-fluid">Download</a>
                                  <!-- <a href=".ignismyModal" class="nte" data-toggle="modal">
                                    <img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/note_icn1.png')); ?>">
                                  </a> -->
                                </div>
                              </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                      </table>
                      <div class="col-md-12">
                        <div class="pagination_right_order">
                          <ul>
                            <li><a href="javascript:void(0)"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                            </li>
                            <li><a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                            </li>
                            <li><a href="javascript:void(0)" class="activea">1</a>
                            </li>
                            <li><a href="javascript:void(0)" class="bg-none">2</a>
                            </li>
                            <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                            </li>
                            <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.row -->
              </div>
            </div>
            <!-- /.box -->
          </div>
          <!-- /.col -->
        </div>
        
      <div id="PlanDetails" style="display:none">

        <div class="col-md-8">
            <div class="box">
              <div class="box-header with-border">
                  <h3 class="box-title personal-heading">Plan Details</h3>
                </div>
              <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="client-details-area">
                          <table class="table">
                              <tbody> 
                                <tr>
                                  <th scope="row">Plan Name</th>
                                  <td style="color: #7170EA;font-weight: 600;"><?php echo e($CurrentPlans['plan_name']); ?>/<?php echo e($CurrentPlans['period_time']); ?></td>
                                </tr>
                                <tr> 
                                  <th scope="row">Start Date</th>
                                  <td><?php echo e($clientDetails['created_at']); ?></td>
                                </tr>
                                  <tr> 
                                  <th scope="row">Last Payment Date</th>
                                  <td><?php echo e($LastPaymentDate); ?></td>
                                </tr>
                                  <tr>
                                  <th scope="row">Next Payment Date</th>
                                  <td style="color: #eb3142"><?php echo e($NextPaymentDate); ?></td>
                                </tr>
                                  <tr>
                                  <th scope="row">Amount</th>
                                  <td style="color: #47576e;font-weight: 600;">€ <?php echo e($CurrentPlans['cost']); ?></td>
                                </tr>
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
              </div>
            </div>
            <!-- /.box -->
          </div>
          <!-- /.col -->


        </div>

      </div>









      </div>

    </section>

  </div>


  <script>
// window.onload = function() {
//   document.getElementById('OrderHistory').style.display = 'none';
//   document.getElementById('PlanDetails').style.display = 'none';
// };
  </script>




  
<!-- ./wrapper -->
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>


<?php $__env->stopSection(); ?> 

<script>



function OrderHistory(){
  document.getElementById("OrderHistory").style.display = "block"; // Display 
  document.getElementById("PersonalDetails").style.display = "none"; //Hideen 
  document.getElementById("PlanDetails").style.display = "none"; // Hidden 
}

function PersonalInformation(){
  document.getElementById("PersonalDetails").style.display = "block"; // Display
  document.getElementById("OrderHistory").style.display = "none"; // Hidden
  document.getElementById("PlanDetails").style.display = "none"; // Hidden 
}


function PlanDetails(){
  document.getElementById("OrderHistory").style.display = "none"; // hidden 
  document.getElementById("PersonalDetails").style.display = "none"; //Hideen 
  document.getElementById("PlanDetails").style.display = "block"; // display 
}

</script>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/admin/clients/client_details.blade.php ENDPATH**/ ?>