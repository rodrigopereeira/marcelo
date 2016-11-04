<?php
    // ==========================================================================
    //   Não Editar 
    // ==========================================================================
    if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
        die ('Por favor, não carregue essa página diretamente. Obrigado');
    if ( post_password_required() ) { ?>
    <p class="nocomments">Esse artigo está protegido por senha. Insira a senha para poder visualizar os comentários.</p>
    <?php
    return;
    }
?>

<?php 
/**


Se Houver Comentário 


**/
if ( have_comments() ) : ?>

    <!-- heading comentario -->
    <h3 class="heading-mini">
        <?php comments_number( '0 Comentários Seja o primeiro a comentar', '1 Comentário', '% Comentários' ); ?>
    </h3>
    <!-- fim heading comentario -->


    <!-- lista de comentarios -->
    <ol class="commentlist">
        <?php wp_list_comments('type=comment&callback=foxtemas_lista_comentarios'); ?>
    </ol>
    <!-- fim lista comentarios -->


    <div class="clearfix"></div>

    <!-- navi comment rodape -->
    <div class="navi-comment">
        <div class="alignleft">
        <?php previous_comments_link() ?>
        </div>

        <div class="alignright">
        <?php next_comments_link() ?>
        </div>
    </div>
    <!-- fim nav comment rodape -->
    <div class="clearfix"></div>


<?php 
/**


Se não houver comentários 


*/
else: // this is displayed if there are no comments so far ?>


    <?php  if ( comments_open() ) : ?>

    <!-- Comentários Aberto -->

    <?php else : // comments are closed ?>
    
    <!-- Comentários Fechados -->
    <p class="nocomments">Comentário Fechado.</p>

    <?php endif; // fim se comentarios aberto ou fechado ?>


<?php endif; // fim se houver comentarios ou não?>






<?php 
/*
Se comentário Aberto if #1
*/
if ( comments_open() ) : ?>

<!--respond-->
<div id="respond">

    <?php 
    /* 
    Se necessário estar logado if #2
    */
    if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
    
        <!-- se comentário precisa estar logado -->
        <p class="need-login">Você precisa estar <strong> <a href="<?php echo wp_login_url( get_permalink() ); ?>"> logado </a></strong> para poder publicar um comentário neste artigo.</p>

    <?php 
    /*
    Se não necessário estar logado else #2
    */else : ?>


    <span class="heading-mini">
        <?php comment_form_title( 'Deixe um comentário', 'Respondendo comentário de %s' ); ?>
    </span>

    <!-- Inicio forms -->
    <div class="forms">

        <!--cancel-->
        <div class="cancel-comment-reply">
            <?php cancel_comment_reply_link('Cancelar Resposta'); ?>
        </div>
        <!--/cancel-->

        <div class="clearfix"></div>

        <!-- inicio formulário -->
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" class="form-comentario" id="commentform">

            <?php 
            /*
            Se usuário logado if #3
            */
            if ( is_user_logged_in() ) : ?> 
                <p class="links-comentario">Logado como <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"> <?php echo $user_identity; ?> </a> - <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Sair dessa conta">
                    Sair da conta » </a> </p>
            <?php 
            /*
            Se usuário não logado else #3
            */else : ?>

            <!--campos formulários-->
            <div class="inputs-comentario">

                <input class="input-text" type="text" name="author" id="author" value="" size="22" tabindex="1" placeholder="Nome" />

                <input class="input-text" type="text" name="email" id="email" value="" size="22" tabindex="2" placeholder="E-mail (não será publicado)" />

                <input class="input-text" type="text" name="url" id="url" value="" size="22" tabindex="3" placeholder="Site" />

                <div class="clearfix"></div>

            </div>
            <!-- fim campos formulários -->

            <?php endif; // endif #3 ?>

            <!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

            <div class="caixa-mensagem <?php if ( is_user_logged_in() ) { ?>full<?php }?>">
                <textarea name="comment" class="textarea-comentario" rows="7" placeholder="Comentário" id="comment" tabindex="4"></textarea>
            </div>

            <div class="clearfix"></div>

            <p>
                <input name="submit" type="submit" class="enviar-button" id="submit" tabindex="5" value="Comentar" />
                <?php comment_id_fields(); ?>
            </p>

            <div class="clearfix"></div>

            <?php do_action('comment_form', $post->ID); ?>
        </form>

    </div>
    <!-- fim .forms -->

<?php endif; // endif #2 ?>

</div>
<!-- fim #respond -->

<?php endif; // endif #1 ?>
