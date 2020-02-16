<?php if(!$link_limit): ?>
    <?php if($paginator->lastPage() > 1): ?>
    <ul class="pagination">
        <!-- First Page Direct  -->
        <li class="">
            <?php if($paginator->currentPage() == 1): ?>
                <label class="disabled fa fa-angle-double-left" aria-hidden="true"></label>
            <?php else: ?>
           
                <a href="<?php echo e($paginator->url(1)); ?>"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
            <?php endif; ?>
        </li>
        <!-- Next Page  -->
        <li class="">
            <?php if($paginator->currentPage()==1): ?>
                <label class="disabled fa fa fa-angle-left" aria-hidden="true"></label>
            <?php else: ?>
                <a href="<?php echo e($paginator->url($paginator->currentPage()-1)); ?>"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
            <?php endif; ?>
        </li>
      
        <?php for($i = 1; $i <= $paginator->lastPage(); $i++): ?>
            <li class="<?php echo e(($paginator->currentPage() == $i) ? ' active' : ''); ?>">
                <a href="<?php echo e($paginator->url($i)); ?>"><?php echo e($i); ?></a>
            </li>
        <?php endfor; ?>
        <li class="<?php echo e(($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : ''); ?>">
            <?php if($paginator->currentPage() == $paginator->lastPage()): ?>
                <label class="disabled bg_dark fa fa-angle-right" aria-hidden="true"></label>
            <?php else: ?>
            <a href="<?php echo e($paginator->url($paginator->currentPage()+1)); ?>" class="bg_dark fa fa-angle-right" aria-hidden="true"></a>
            <?php endif; ?>
        </li>

       <!-- Last page -->
        <li class="<?php echo e($paginator->lastPage()); ?>">
            <?php if($paginator->currentPage() == $paginator->lastPage()): ?>
                <label class="disabled bg_dark fa fa-angle-double-right" aria-hidden="true"></label>
            <?php else: ?>
                <a href="<?php echo e($paginator->url($paginator->lastPage())); ?>"class="bg_dark fa fa-angle-double-right" aria-hidden="true"></a>
            <?php endif; ?>
        </li>
    </ul>
    <?php endif; ?>
<?php else: ?>
    <?php if($paginator->lastPage() > 1): ?>
        <ul class="pagination">
            <!-- First Page Direct -->
            <li class="">
                <?php if($paginator->currentPage() == 1): ?>
                <label class="disabled fa fa-angle-double-left" aria-hidden="true"></label>
                <?php else: ?>
                <a href="<?php echo e($paginator->url(1)); ?>"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                <?php endif; ?>
             </li>
             <!-- Previous Page -->
             <li class="">
                <?php if($paginator->currentPage()==1): ?>
                    <label class="disabled fa fa-angle-left" aria-hidden="true"></label>
                <?php else: ?>
                    <a href="<?php echo e($paginator->url($paginator->currentPage()-1)); ?>"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                <?php endif; ?>
            </li>
             <?php
                $half_total_links = floor($link_limit / 2);
                $from = ($paginator->currentPage() - $half_total_links) < 1 ? 1 : $paginator->currentPage() - $half_total_links;
                $to = ($paginator->currentPage() + $half_total_links) > $paginator->lastPage() ? $paginator->lastPage() : ($paginator->currentPage() + $half_total_links);
                if ($from > $paginator->lastPage() - $link_limit) {
                   $from = ($paginator->lastPage() - $link_limit) + 1;
                   $to = $paginator->lastPage();
                }
                if ($to <= $link_limit) {
                    $from = 1;
                    $to = $link_limit < $paginator->lastPage() ? $link_limit : $paginator->lastPage();
                }
            ?>
            <?php for($i = $from; $i <= $to; $i++): ?>
                    <li class="<?php echo e(($paginator->currentPage() == $i) ? ' active' : ''); ?>">
                        <a href="<?php echo e($paginator->url($i)); ?>"><?php echo e($i); ?></a>
                    </li>
            <?php endfor; ?>
            <li class="<?php echo e(($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : ''); ?>">
                <?php if($paginator->currentPage() == $paginator->lastPage()): ?>
                <label class="disabled bg_dark fa fa-angle-right" aria-hidden="true"></label>
                <?php else: ?>
                <a href="<?php echo e($paginator->url($paginator->currentPage() + 1)); ?>" class="bg_dark fa fa-angle-right" aria-hidden="true"></a>
                <?php endif; ?>
            </li>
            <!-- Last Page -->
            <li class="<?php echo e($paginator->lastPage()); ?>">
                <?php if($paginator->currentPage() == $paginator->lastPage()): ?>
                    <label class="disabled bg_dark fa fa-angle-double-right" aria-hidden="true"></label>
                <?php else: ?>
                    <a href="<?php echo e($paginator->url($paginator->lastPage())); ?>"class="bg_dark fa fa-angle-double-right" aria-hidden="true"></a>
                <?php endif; ?>
            </li>
        </ul>
    <?php endif; ?>
<?php endif; ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/vendor/pagination/custom.blade.php ENDPATH**/ ?>