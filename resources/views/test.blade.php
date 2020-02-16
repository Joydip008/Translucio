<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"><script async="" src="//www.google-analytics.com/analytics.js"></script><script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<!-- jQuery -->
<title>phpzag.com : Dome of Image Upload and Image Crop in Modal with PHP and jQuery</title>
<script src="dist_files/jquery.imgareaselect.js" type="text/javascript"></script>
<script src="dist_files/jquery.form.js"></script>
<link rel="stylesheet" href="dist_files/imgareaselect.css">
<script src="functions.js"></script>
<style type="text/css">
:root .adsbygoogle
{ display: none !important; }
:root *[kp60owt][hidden] { display: none !important; }</style></head>
<body class="">
<div role="navigation" class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="http://www.phpzag.com" class="navbar-brand">PHPZAG.COM</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="http://www.phpzag.com">Home</a></li>
           
          </ul>
         
        </div><!--/.nav-collapse -->
      </div>
    </div>
	
	<div class="container" style="min-height:500px;">
	<br>
	<script async="" src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<!-- Responsive Header -->
	<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-1169273815439326" data-ad-slot="1311700855" data-ad-format="auto" kp60owt="" hidden=""></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script><div class="container">
	<h2>Example: Image Upload and Image Crop in Modal with PHP and jQuery</h2>		
	
	<div>
		<img class="img-circle" id="profile_picture" height="128" data-src="default.jpg" data-holder-rendered="true" style="width: 140px; height: 140px;" src="default.jpg">
		<br><br>
		<a type="button" class="btn btn-primary" id="change-profile-pic">Change Profile Picture</a>
	</div>
	<div id="profile_pic_modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				   <h3>Change Profile Picture</h3>
				</div>
				<div class="modal-body">
					<form id="cropimage" method="post" enctype="multipart/form-data" action="change_pic.php">
						<strong>Upload Image:</strong> <br><br>
						<input type="file" name="profile-pic" id="profile-pic">
						<input type="hidden" name="hdn-profile-id" id="hdn-profile-id" value="1">
						<input type="hidden" name="hdn-x1-axis" id="hdn-x1-axis" value="">
						<input type="hidden" name="hdn-y1-axis" id="hdn-y1-axis" value="">
						<input type="hidden" name="hdn-x2-axis" value="" id="hdn-x2-axis">
						<input type="hidden" name="hdn-y2-axis" value="" id="hdn-y2-axis">
						<input type="hidden" name="hdn-thumb-width" id="hdn-thumb-width" value="">
						<input type="hidden" name="hdn-thumb-height" id="hdn-thumb-height" value="">
						<input type="hidden" name="action" value="" id="action">
						<input type="hidden" name="image_name" value="" id="image_name">
						
						<div id="preview-profile-pic"></div>
					<div id="thumbs" style="padding:5px; width:600p"></div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="save_crop" class="btn btn-primary">Crop &amp; Save</button>
				</div>
			</div>
		</div>
	</div>
		
	<div style="margin:50px 0px 0px 0px;">
		<a class="btn btn-default read-more" style="background:#3399ff;color:white" href="http://www.phpzag.com/image-upload-and-crop-in-modal-with-php-and-jquery/" title="">Back to Tutorial</a>			
	</div>		
</div>
<div class="insert-post-ads1" style="margin-top:20px;">
<script async="" src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<!-- Responsive Header -->
	<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-1169273815439326" data-ad-slot="7951723253" data-ad-format="auto" kp60owt="" hidden=""></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script>
</div>
</div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-49752503-1', 'auto');
  ga('send', 'pageview');

</script>



</body></html>