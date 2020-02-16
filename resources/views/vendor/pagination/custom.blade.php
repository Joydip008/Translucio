@if(!$link_limit)
    @if ($paginator->lastPage() > 1)
    <ul class="pagination">
        <!-- First Page Direct  -->
        <li class="">
            @if($paginator->currentPage() == 1)
                <label class="disabled fa fa-angle-double-left" aria-hidden="true"></label>
            @else
           
                <a href="{{ $paginator->url(1) }}"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
            @endif
        </li>
        <!-- Next Page  -->
        <li class="">
            @if($paginator->currentPage()==1)
                <label class="disabled fa fa fa-angle-left" aria-hidden="true"></label>
            @else
                <a href="{{ $paginator->url($paginator->currentPage()-1) }}"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
            @endif
        </li>
      
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
            </li>
        @endfor
        <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
            @if($paginator->currentPage() == $paginator->lastPage())
                <label class="disabled bg_dark fa fa-angle-right" aria-hidden="true"></label>
            @else
            <a href="{{ $paginator->url($paginator->currentPage()+1) }}" class="bg_dark fa fa-angle-right" aria-hidden="true"></a>
            @endif
        </li>

       <!-- Last page -->
        <li class="{{$paginator->lastPage()}}">
            @if($paginator->currentPage() == $paginator->lastPage())
                <label class="disabled bg_dark fa fa-angle-double-right" aria-hidden="true"></label>
            @else
                <a href="{{ $paginator->url($paginator->lastPage()) }}"class="bg_dark fa fa-angle-double-right" aria-hidden="true"></a>
            @endif
        </li>
    </ul>
    @endif
@else
    @if ($paginator->lastPage() > 1)
        <ul class="pagination">
            <!-- First Page Direct -->
            <li class="">
                @if($paginator->currentPage() == 1)
                <label class="disabled fa fa-angle-double-left" aria-hidden="true"></label>
                @else
                <a href="{{ $paginator->url(1) }}"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                @endif
             </li>
             <!-- Previous Page -->
             <li class="">
                @if($paginator->currentPage()==1)
                    <label class="disabled fa fa-angle-left" aria-hidden="true"></label>
                @else
                    <a href="{{ $paginator->url($paginator->currentPage()-1) }}"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                @endif
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
            @for ($i = $from; $i <= $to; $i++)
                    <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                        <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
            @endfor
            <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                @if($paginator->currentPage() == $paginator->lastPage())
                <label class="disabled bg_dark fa fa-angle-right" aria-hidden="true"></label>
                @else
                <a href="{{ $paginator->url($paginator->currentPage() + 1) }}" class="bg_dark fa fa-angle-right" aria-hidden="true"></a>
                @endif
            </li>
            <!-- Last Page -->
            <li class="{{$paginator->lastPage()}}">
                @if($paginator->currentPage() == $paginator->lastPage())
                    <label class="disabled bg_dark fa fa-angle-double-right" aria-hidden="true"></label>
                @else
                    <a href="{{ $paginator->url($paginator->lastPage()) }}"class="bg_dark fa fa-angle-double-right" aria-hidden="true"></a>
                @endif
            </li>
        </ul>
    @endif
@endif