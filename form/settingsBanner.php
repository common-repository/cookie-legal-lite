<?php
	
	function rgb2hex($rgb, $uppercase=false, $shorten=false){
		$rgb = explode(",",$rgb);
		$r = $rgb[0];
		$g = $rgb[1];
		$b = $rgb[2];
		$out = "";
		if ($shorten && ($r + $g + $b) % 17 !== 0) $shorten = false;
		foreach (array($r, $g, $b) as $c){
			$hex = base_convert($c, 10, 16);
			if ($shorten) $out .= $hex[0];
			else $out .= ($c < 16) ? ("0".$hex) : $hex;
		}
		return $uppercase ? strtoupper($out) : $out;
	}
	
	function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);
		
		if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		return $r.",".$g.",".$b; 
	}
	
	$ie = array(0,0,0,0,0,0,0,0,0,0);
	if(isset($_POST['banner'])){	
		if($_POST['text'] == ""){$ie[1] = 1;}
		if($_POST['click'] == ""){$ie[2] = 1;}
		if( ($_POST['trans'] < 0) || ($_POST['trans'] > 100) ){$ie[3] = 1;}
		if( ($_POST['rounds'] < 0) || ($_POST['rounds'] > 25) ){$ie[6] = 1;}
		$args = array(
			"title"=>$input_text = sanitize_text_field(stripslashes($_POST['title'])),
			"text"=>$input_text = sanitize_text_field(stripslashes($_POST['text'])),
			"click"=>$input_text = sanitize_text_field(stripslashes($_POST['click'])),
			"trans"=>$input_text = sanitize_text_field($_POST['trans']/100),
			"color"=>$input_text = sanitize_text_field(hex2rgb($_POST['color'])),
			"width"=>$input_text = sanitize_text_field($_POST['width']),
			"rounds"=>$input_text = sanitize_text_field($_POST['rounds']),
			"color_t"=>$input_text = sanitize_text_field($_POST['color_t']),
			"pos_o"=>$input_text = sanitize_text_field($_POST['pos_o']),
			"pos_h"=>$input_text = sanitize_text_field($_POST['pos_h']),
			"color_click"=>$input_text = sanitize_text_field($_POST['color_click']),
			"border_color"=>$input_text = sanitize_text_field($_POST['border_color']),
			"color_info"=>$input_text = sanitize_text_field($_POST['color_info']),
			"color_t_click"=>$input_text = sanitize_text_field($_POST['color_t_click']),
		);
		if(!in_array("1",$ie)){
			$options = get_option("cl_banner");
			if($options == FALSE){
				add_option('cl_banner',$args);
			}else{
				update_option('cl_banner',$args);
			}	
		}
	}else{
		$args = array(
			"title"=>"Titolo del banner",
			"text"=>"Questo è il testo del banner.",
			"click"=>"Accetto",
			"trans"=>0.85,
			"color"=>"0,0,0",
			"width"=>"total",
			"rounds"=>"10",
			"color_t"=>"FFFFFF",
			"pos_o"=>"center",
			"pos_h"=>"top",
			"color_click"=>"5BC0DE",
			"border_color"=>"46B8DA",
			"color_info"=>"2980B9",
			"color_t_click"=>"FFFFFF",
		);
	}
	$options = get_option("cl_banner",$args);
	
	$title = $options['title'];
	$text = $options['text'];
	$click = $options['click'];
	$trans = $options['trans']*100;
	$color = rgb2hex($options['color']);
	$width = $options['width'];
	$rounds = $options['rounds'];
	$color_t = $options['color_t'];
	$pos_o = $options['pos_o'];
	$pos_h = $options['pos_h'];
	$color_click = $options['color_click'];
	$border_color = $options['border_color'];
	$color_info = $options['color_info'];
	$color_t_click = $options['color_t_click'];
?>	
	<div class="wrap">
    	<div id="containerCookieLegal" class="header-CookieLegalSettings">
        	<div class="imageCLS col-lg-6">
            	<img src="<?php echo plugins_url('img/logoCookieLegal-lite.png', __FILE__); ?>" alt="Cookie Legal Settings - Impostazioni del Banner Cookie Legal" />
            </div>
            <div class="typeSettings col-lg-6">
            	<h1>Impostazioni Banner</h1>
            </div>
        </div>
		<div id="containerCookieLegal" class="informazioneCookieLegal">
            <form class="form-horizontal" method="post" action="#" name="post">
                <div class="form-group">
                    <label for="title" class="col-sm-2 control-label">Titolo del Banner</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="text" class="col-sm-2 control-label">Testo del Banner</label>
                    <div class="col-sm-10">
                        <?php
                            if($ie[1] == 1){
                                echo '<p class="error">Errore! Il <b>Testo del Banner</b> non può rimanere vuoto!</p>';
                            }
                            echo '<textarea id="text" class="form-control" name="text" rows="3">'.$text.'</textarea>';								
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="click" class="col-sm-2 control-label">Testo Pulsante Accetta</label>
                    <div class="col-sm-10">
                        <?php if($ie[2] == 1)echo '<p class="error">Errore! Il campo <b>Testo Pulsante Accetta</b> non può rimanere vuoto!</p>'; ?>
                        <input type="text" class="form-control" id="click" name="click" value="<?php echo $click; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="trans" class="col-sm-2 control-label">Trasparenza Banner</label>
                    <div class="col-sm-10">
                        <?php if($ie[3] == 1)echo '<p class="error">Errore! Il campo <b>Trasparenza Banner</b> accetta valori compresi tra 0 e 100!</p>'; ?>
                        <input type="text" class="form-control" id="trans" name="trans" value="<?php echo $trans; ?>">
                    </div>
                </div>
                 <div class="form-group">
                    <label for="color" class="col-sm-2 control-label">Colore Banner</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control color" id="color" name="color" value="<?php echo $color; ?>">
                    </div>
                </div>
                 <div class="form-group">
                    <label for="width" class="col-sm-2 control-label">Larghezza Banner</label>
                    <div class="col-sm-10">
                        <select name="width" class="form-control">
                           <?php
                                if($width == "total"){
                                    echo '<option value="total" selected="selected">Larghezza Massima</option>';
                                }else{
                                    echo '<option value="total" >Larghezza Massima</option>';
                                }
                                if($width == "medium"){
                                    echo '<option value="medium" selected="selected">Larghezza Media</option>';
                                }else{
                                    echo '<option value="medium" >Larghezza Media</option>';
                                }
                                if($width == "mini"){
                                    echo '<option value="mini" selected="selected">Banner Piccolo</option>';
                                }else{
                                    echo '<option value="mini" >Banner Piccolo</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                 <div class="form-group">
                    <label for="rounds" class="col-sm-2 control-label">Bordi Arrotondati</label>
                    <div class="col-sm-10">
                        <?php if($ie[6] == 1)echo '<p class="error">Errore! Il campo <b>Bordi Arrotondati</b> accetta valori compresi tra 0 e 25!</p>'; ?>
                        <input type="text" class="form-control" id="rounds" name="rounds" value="<?php echo $rounds; ?>">
                    </div>
                </div>
                 <div class="form-group">
                    <label for="color_t" class="col-sm-2 control-label">Colore Testo</label>
                    <div class="col-sm-10">
                        
                        <input type="text" class="form-control color" id="color_t" name="color_t" value="<?php echo $color_t; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="pos_o" class="col-sm-2 control-label">Posizione Orizzontale</label>
                    <div class="col-sm-10">
                        <select name="pos_o" class="form-control" id="pos_o">
                            <?php
                                if($pos_o == "left"){
                                    echo '<option value="left" selected="selected">Sinistra</option>';
                                }else{
                                    echo '<option value="left" >Sinistra</option>';
                                }
                                if($pos_o == "center"){
                                    echo '<option value="center" selected="selected">Centro</option>';
                                }else{
                                    echo '<option value="center" >Centro</option>';
                                }
                                if($pos_o == "right"){
                                    echo '<option value="right" selected="selected">Destra</option>';
                                }else{
                                    echo '<option value="right" >Destra</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pos_h" class="col-sm-2 control-label">Posizione Verticale</label>
                    <div class="col-sm-10">
                        <select name="pos_h" class="form-control" id="pos_h">
                            <?php
                                if($pos_h == "left"){
                                    echo '<option value="top" selected="selected">Sopra</option>';
                                }else{
                                    echo '<option value="top" >Sopra</option>';
                                }
                                if($pos_h == "center"){
                                    echo '<option value="center" selected="selected">Centro</option>';
                                }else{
                                    echo '<option value="center" >Centro</option>';
                                }
                                if($pos_h == "bottom"){
                                    echo '<option value="bottom" selected="selected">Sotto</option>';
                                }else{
                                    echo '<option value="bottom" >Sotto</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                 <div class="form-group">
                    <label for="color_click" class="col-sm-2 control-label">Colore Pulsanti</label>
                    <div class="col-sm-10">
                        
                        <input type="text" class="form-control color" id="color_click" name="color_click" value="<?php echo $color_click; ?>">
                    </div>
                </div>
                 <div class="form-group">
                    <label for="border_color" class="col-sm-2 control-label">Colore Bordo Pulsanti</label>
                    <div class="col-sm-10">
                        
                        <input type="text" class="form-control color" id="border_color" name="border_color" value="<?php echo $border_color; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="color_t_click" class="col-sm-2 control-label">Colore Testo Pulsante Accetto</label>
                    <div class="col-sm-10">
                        
                        <input type="text" class="form-control color" id="color_t_click" name="color_t_click" value="<?php echo $color_t_click; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="color_info" class="col-sm-2 control-label">Colore Link Cookie Policy</label>
                    <div class="col-sm-10">
                        
                        <input type="text" class="form-control color" id="color_info" name="color_info" value="<?php echo $color_info; ?>">
                    </div>
                </div>
                <div class="">
                    <input type="submit" name="banner" value="Salva Impostaioni" class="button button-primary button-large" />
                </div>
            </form>
        </div>  
	</div>

