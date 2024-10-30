<?php	
	if(isset($_POST['policy'])){
		$args = array(
			"id"=>"-1",
			"title"=>"",
			"type"=>"",
			"link"=>"",
		);
		$options = get_option("cl_policy",$args);
		
		if($options['id'] != "-1"){
			$postToCorrect = get_post($options['id']);
			$testo = $postToCorrect->post_content;
			$testo = str_replace('<p><a href="#" class="buttonLegalPolicy button-accept" onclick="accept()">Accetto i Cookie</a></p>
	<p><a href="#" class="buttonLegalPolicy button-not-accept" onclick="not_accept()">Nego i Cookie</a></p>',"",$testo);
			$postToCorrect->post_content = $testo;
			$postToCorrect->comment_status = true;
			wp_update_post( $postToCorrect, $wp_error );
		}
		if(sanitize_text_field($_POST['article']) != "-1"){
			$post = get_post(sanitize_text_field($_POST['article']));
			$testo = $post->post_content;
			$testo = $testo.'<p><a href="#" class="buttonLegalPolicy button-accept" onclick="accept()">Accetto i Cookie</a></p>
		<p><a href="#" class="buttonLegalPolicy button-not-accept" onclick="not_accept()">Nego i Cookie</a></p>';	
		
			$post->post_content = $testo;
			$post->comment_status = false;
			wp_update_post( $post, $wp_error );
			$post = get_post(sanitize_text_field($_POST['article']));
			$args = array(
				"id"=>sanitize_text_field($_POST['article']),
				"title"=>get_the_title( sanitize_text_field($_POST['article']) ),
				"type"=>"",
				"link"=>$post->guid,
			);
		}else{
			$args = array(
				"id"=>"-1",
				"title"=>"",
				"type"=>"",
				"link"=>"#",
			);
		}
		$options = get_option("cl_policy");
		if($options == FALSE){
			add_option('cl_policy',$args);
		}else{
			update_option('cl_policy',$args);
		}	
	
	}else{
		$args = array(
			"id"=>"-1",
			"title"=>"",
			"type"=>"",
			"link"=>"#",
		);
	}
	$options = get_option("cl_policy",$args);
?>	
	<div class="wrap">
		<div class="header-CookieLegalSettings">
        	<div class="imageCLS col-lg-6">
            	<img src="<?php echo plugins_url('img/logoCookieLegal-lite.png', __FILE__); ?>" alt="Cookie Legal Settings - Impostazioni del Banner Cookie Legal" />
            </div>
            <div class="typeSettings col-lg-6">
            	<h1>Impostazioni Banner</h1>
            </div>
        </div>
		<div id="containerCookieLegal" class="informazioneCookieLegal">
            <p>Seleziona la pagina/articolo per la Cookie Policy. Se non hai ancora creato la tua Cookie Policy devi crearla prima nei tuoi articoli o pagine.</p>
            <h3>Articolo/Pagina Slezionato:<i><b> <?php if($options['id']=="-1"){echo 'Nessun Post Selezionato';}else{echo $options['title'];}?></b></i></h3>
            <form class="form-horizontal" method="post" action="#" name="post">
                <div class="form-group">
                    <label for="article" class="col-sm-2 control-label">Cookie Policy</label>
                    <div class="col-sm-10">
                        <select name="article" id="article" class="form-control">
                           <?php
                               if($options['id'] == "-1"){
                                    echo '<option value="-1" selected="selected" >Nessun Articolo/Pagina Selezionata</option>';
                               }else{
                                    echo '<option value="-1" >Nessun Articolo/Pagina Selezionata</option>';	  
                               }
							?>
                            	<optgroup label="Articoli">
                            <?php
							   	$args = array(
									'sort_order' => 'ASC',
									'sort_column' => 'post_title',
									'child_of' => 0,
									'parent' => -1,
									'offset' => 0,
									'post_type' => 'post',
									'post_status' => 'publish',
									'suppress_filters' => false
								);
								$all_posts = get_posts($args);
                               	foreach($all_posts as $post){
                                    if($options['id'] == $post->ID){
                                        echo '<option selected="selected" value="' . $post->ID  . '">' . get_the_title( $post->ID ) . '</option>';
                                    }else{
                                        echo '<option value="' . $post->ID  . '">' . get_the_title( $post->ID ) . '</option>';
                                    }
                               	}
                            ?>
                            	</optgroup>
                                <optgroup label="Pagine">
                            <?php
								$args = array(
									'sort_order' => 'ASC',
									'sort_column' => 'post_title',
									'hierarchical' => 1,
									'child_of' => 0,
									'parent' => -1,
									'offset' => 0,
									'post_type' => 'page',
									'post_status' => 'publish',
									'suppress_filters' => false
								);
								$all_pages = get_pages($args);
                               	foreach($all_pages as $page) {
                                    if($options['id'] == $page->ID){
                                        echo '<option selected="selected" value="' . $page->ID  . '">' . get_the_title( $page->ID ) . '</option>';
                                    }else{
                                        echo '<option value="' . $page->ID  . '">' . get_the_title( $page->ID ) . '</option>';
                                    }
                               	}
							?>    
                                </optgroup>
                        </select>
                    </div>
                </div>
               
                <div class="">
                    <input type="submit" name="policy" value="Salva Impostaioni" class="button button-primary button-large" />
                </div>
            </form>             
	</div>
</div>

<div class="wrap">
	<div class="informazioneCookieLegal">
    	  <h3>Ricorda che devi indicare nella cookie policy (Informativa estesa)</h3>
          <p>L’indicazione che alla pagina dell’informativa estesa è possibile negare il consenso all’installazione di 	qualunque cookie (Utility già prevista nella versione PREMIUM);</br>
<b>Provvedimento 8 maggio 2014 <i>(Pubblicato sulla Gazzetta Ufficiale n. 126 del 3 giugno 2014)</i></b></p>
    </div>
    <div class="informazioneCookieLegal">
    	<h3>Ricorda inoltre...</h3>
    	<p>Ricorda che devi inserire nel menù del footer o nel menù superiore header del sito web, il link alla cookie policy (Informativa estesa);</br>
        <b>Provvedimento 8 maggio 2014 <i>(Pubblicato sulla Gazzetta Ufficiale n. 126 del 3 giugno 2014)</i></b></p>
    </div>
</div>
