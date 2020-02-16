<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo e(route('chat_box')); ?>" method="post">
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Origin Language &nbsp;<i class="fa fa-star star"></i></label>
                <input type="hidden" id="currentLanguage_id">
                <select class="form-control" id="current_website_language" name="origin_language" required >
                <option value=""> -- Select One --</option>
                <?php $__currentLoopData = $LanguagesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($Language->sortname); ?>" ><?php echo e($Language->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>          
            </select>
        </div>  

        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Destination Language &nbsp;<i class="fa fa-star star"></i></label>
                <input type="hidden" id="currentLanguage_id">
                <select class="form-control" id="current_website_language" name="destination_language" required >
                <option value=""> -- Select One --</option>
                <?php $__currentLoopData = $LanguagesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($Language->sortname); ?>" ><?php echo e($Language->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>          
            </select>
        </div> 

        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Api Select &nbsp;<i class="fa fa-star star"></i></label>
                <input type="hidden" id="currentLanguage_id">
                <select class="form-control" id="current_website_language" name="api" required >
                <option value=""> -- Select One --</option>
                <option value="A" >Amazon</option>
                <option value="D" >Deepl</option>
                <option value="G" >Google</option>
            </select>
        </div> 

        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="exampleFormControlSelect1">DB CHECK &nbsp;<i class="fa fa-star star"></i></label>
                <input type="hidden" id="currentLanguage_id">
                <select class="form-control" id="current_website_language" name="db" >
                <option value=""> -- Select One --</option>
                <option value="Y" >DB Check</option>
            </select>
        </div> 

        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Wite Text &nbsp;<i class="fa fa-star star"></i></label>
            </div>
            <textarea rows="4" cols="50" name="text">
            </textarea>
        </div> 
        <button type="submit" class="btn btn_project_submit">Submit</button>
    </form>      
    <?php if(!empty($TranslatedText)): ?>
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Translated Text &nbsp;<i class="fa fa-star star"></i></label>
            </div>
            <textarea rows="4" cols="50"><?php echo e($TranslatedText); ?>

            </textarea>
        </div>  
    <?php endif; ?>  
</body>
</html><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/user/myProject/test_chat_box.blade.php ENDPATH**/ ?>