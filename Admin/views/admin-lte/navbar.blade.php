<ul class="sidebar-menu">
    <?php foreach( $menu as $title => $child ):?>
        @if( ! $child->isValid() )
            <?php continue;?>
        @endif
        @if( $child->isLeaf() )
            <li class="<?=$child->isActive()?'active':''?>">
                <a href="<?=$child->getURL()?>">
                    <i class="fa fa-dashboard"></i><span><?=$title?></span>
                </a>
            </li>
        @else
            <li class="treeview <?=$child->isActive()?'active':''?>">
                <a href="#">
                    <i class="fa fa-bar-chart-o"></i>
                    <span><?=$title?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <?php foreach( $child as $leafTitle => $leaf ):?>
                        @if( ! $leaf->isValid() )
                            <?php continue;?>
                        @endif
                        <li class="<?=$leaf->isActive()?'active':''?>"><a href="<?=$leaf->getURL()?>"><i class="fa fa-angle-double-right"></i><?=$leafTitle?></a></li>
                    <?php endforeach;?>
                </ul>
            </li>
        @endif
    <?php endforeach;?>
</ul>