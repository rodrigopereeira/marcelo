<?php 
// ==========================================================================
//   Comentários
// ==========================================================================
function foxtemas_lista_comentarios($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; 
?>

    <?php 
    // ==========================================================================
    //   Lista Comentários
    // ==========================================================================
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>"> 

    <?php 
    // ==========================================================================
    //   Inicio comentário 
    // ==========================================================================
    ?>
    <div id="comment-<?php comment_ID(); ?>" class="comentario-campo"> 

    		<div class="bg-branco">  		    
    		    <!-- infos comment -->
    		    <div class="infos-comment">
    		    	<span class="nome-comment">
    		        	<?php printf(__('%s'), get_comment_author_link())?>  
    		        </span>
    		        
    		        <span class="data-comment">
    		        	EM <?php echo get_comment_date('j/m/Y'); ?>
    		        </span>
    		
    		        <div class="reply-link">
    		        	<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    		        </div>
    		        
    		        <div class="clearfix"></div>
    		        
    		        <div class="entry entry-comment">
    		        	<?php comment_text() ?>
    		        </div>
    		        

    		        <div class="clearfix"></div>
    		    </div>
    		    <!-- fim infos comment -->

    		    <div class="clearfix"></div>
    		</div>

    	<div class="clearfix"></div>

    </div>
    <?php 
    // ==========================================================================
    //   Fim comentário
    // ==========================================================================
    ?>
  <?php
}
?>