<div class="post-header-wrap clearfix">
    <div class="meta-left">
        <div class="post-icon">
            <i class="fa fa-picture-o"></i>
        </div>
        <div class="post-date">
            <span class="meta-date"><?php echo get_the_date( _x( 'j', '', 'foxeed-lite' )); ?></span>
            <span class="meta-month"><?php echo get_the_date( _x( 'M', '', 'foxeed-lite' )); ?></span>
        </div>
    </div>
    <div class="meta-right">
        <h2 class="post-title">
            <a class="news-title" href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php if ( get_the_title() ){ the_title(); } else { _e('Untitled Post','foxeed-lite'); } ?></a>
        </h2>
        <div class="skepost-meta clearfix">
            <span class="author-name"><?php _e('<i class="fa fa-user"></i> By ','foxeed-lite');?><?php the_author_posts_link(); ?>&nbsp;</span>
            <span class="comments"><?php _e('<i class="fa fa-comments"></i>','foxeed-lite');?><?php comments_popup_link(__('No Comments ','foxeed-lite'), __('1 Comment ','foxeed-lite'), __(' % Comments ','foxeed-lite'), '', __(' Comments off ','foxeed-lite') ); ?></span>
            <?php the_tags('<span class="tag-name">&nbsp;<i class="fa fa-tag"></i>',', ','</span>'); ?>
            <?php if (has_category()) { ?><span class="category">&nbsp;<i class="fa fa-folder-open"></i><?php the_category(', '); ?><?php } ?></span>
        </div>
    </div>
</div>