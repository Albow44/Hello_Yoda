<?php
/**
 * @package Hello_yoda
 * @version 1.0
 */
/*
 Plugin Name: Hello Yoda
 Description: This is not just a plugin, it symbolizes the hope and enthusiasm of generations of Star Wars fans. The wise quotes from Legendary Jedi Master Yoda will appear in the upper right of your admin page. May the Force Be With yinz.
 Author: Alan Aumiller II
 Version: 1.0
 */

// Menu and Submenu(s)
add_action('admin_menu', 'my_menu_pages');
function my_menu_pages(){
	add_menu_page(
		'Hello Yoda', 
		'Yoda', 
		'read', 
		'yoda-menu', 
		'my_menu_output', 
		'https://img.icons8.com/color/24/000000/yoda.png' 
	);
	$hook = add_submenu_page(
		'yoda-menu', 
		'Add A New Quote', 
		'Add Quote', 
		'read', 
		'yodasub', 
		'yoda_submenu_output' 
	);

	$hook2 = add_submenu_page(
		'yoda-menu', 
		'Add A New Quote', 
		'Remove Quote', 
		'read', 
		'yodasub2', 
		'yoda_submenu_output_quotes' 
	);
	remove_submenu_page('yoda-menu', 'yoda-menu');
	add_action('load-' . $hook, 'hello_yoda_insert');
	add_action('load-' . $hook2, 'hello_yoda_delete');
}

// Content of the main menu page
function my_menu_output(){
	echo '<h1>The Hello Yoda Plugin by Josh</h1>
	<p>"Pass on what you have learned." --Yoda </p>';
}


function yoda_submenu_output_quotes(){
	global $wpdb;
$results = $wpdb->get_results("SELECT id, quote, quotee FROM {$wpdb->prefix}quotes");

echo '<table class=\'table\'>
		<thead>
			<tr>
				<th>id</th>
				<th>Quote</th>
				<th>Quotee</th>
			</tr>
		</thead>';
foreach($results as $row){
?>
	<tr>
		<td><?php echo $row->id?></td>
		<td><?php echo $row->quote?></td>
		<td><?php echo $row->quotee?></td>
	</tr>

<?php

	}
	echo '</table>
	<form action="" method="POST">
	<label for="input">Enter ID:</label>
		<input name="input" type="number">
		<input type="submit" value="Delete">
	</form>';
}
function yoda_submenu_output(){
?>
		<div class='wrap'>
			<h1><?= esc_html(get_admin_page_title()); ?></h1>
			<form action="<?php menu_page_url('yodasub')?>" method='POST'>
				<label for='quote'> Quote:</label> 
				<p><input type='text' name='quote'></p>
				<label for='quotee'> Quotee:</label> 
					<p><input type='text' name='quotee'></p><br>
				<input type='submit'>
			</form>
		</div>
	
    <?php
}
function hello_yoda_get_quotes() {
	/** These are Yoda Quotes */
	
	if (!current_user_can('activate_plugins')){
		

		$quotes = "
		Fear is the path to the dark side. 
		Fear leads to anger. Anger leads to hate. Hate leads to suffering.
		The fear of loss is a path to the Dark Side.
		Always pass on what you have learned.
		Patience you must have my young Padawan.
		Powerful you have become, the dark side I sense in you.
		Train yourself to let go of everything you fear to lose.
		Do or do not. There is no try.
		You will find only what you bring in.
		Control, control, you must learn control!
		Difficult to see. Always in motion is the future.
		Soon will I rest, yes, forever sleep. 
		Twilight is upon me, soon night must fall.
		Your path you must decide.
		If no mistake you have made, losing you are. 
		Adventure. Excitement. A Jedi craves not these things.
		In the end, cowards are those who follow the dark side.
		Impossible to see the light, the future is.
		Wars not make one great.
		Feel the force!
	
		";
	} else{
		$quotes =  "No, I am your father.
		He’s as clumsy as he is stupid.
		I find your lack of faith disturbing.
		You don't know the power of the Dark Side.
		Do not underestimate my power.
		Obi Wan has taught you well.
		Bring the rebels to me.
		I am altering the deal; pray I don't alter it furthur.
		Be careful not to choke on your aspirations.
		You are part of The Rebel Alliance and a traitor!
		The Emporer will show you the true nature of The Force.
		I see through the lies of the Jedi.
		That name no longer has any meaning to me. 
		Tell your sister you were right about me.";
	}
	

	// Here we split it into lines.
	$quotes = explode( "\n", $quotes );
	global $wpdb;
	if (current_user_can('activate_plugins')){
		$results = $wpdb->get_results("SELECT id, quote, quotee FROM {$wpdb->prefix}quotes WHERE quotee = 'vader'");

		} else {
		
		$results = $wpdb->get_results("SELECT id, quote, quotee FROM {$wpdb->prefix}quotes WHERE quotee = 'yoda'");

		}
   
    foreach($results as $row){
    $quote = $row->quote;
    array_push($quotes,$quote);
    }
	// Randomly choose a line.
	return wptexturize( $quotes[ mt_rand( 0, count( $quotes ) - 1 ) ] );
}

// Echo chosen line, we'll position it later.
function hello_yoda() {
	$chosen = hello_yoda_get_quotes();
	$lang   = '';
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}

	if(current_user_can('activate_plugins')){
		printf(
			'<p id="vader"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
			__( 'Quote from Darth Vader:', 'hello-yoda' ),
			$lang,
			$chosen
		);
	} else{
		printf(
		'<p id="yoda"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
		__( 'Quote from Master Yoda:', 'hello-yoda' ),
		$lang,
		$chosen
		);
	}
	
}

// Now we set that function up to execute when the admin_notices action is called.
add_action( 'admin_notices', 'hello_yoda' );

// We need some CSS to position the paragraph.
function yoda_css() {
	echo "
	<style type='text/css'>
	#yoda {
		float: right;
		padding: 5px 10px;
		margin: 0;
		font-size: 12px;
        line-height: 1.6666;
        color: green;

	}

	.table{
		text-align: center;
	}
	.rtl #yoda {
		float: left;
	}
	.block-editor-page #yoda {
		display: none;
	}
	@media screen and (max-width: 782px) {
		#yoda,
		.rtl #yoda {
			float: none;
			padding-left: 0;
			padding-right: 0;
		}
	}
	</style>
	";
}

function vader_css() {
	echo "
	<style type='text/css'>
	#vader {
		float: right;
		padding: 5px 10px;
		margin: 0;
		font-size: 12px;
        line-height: 1.6666;
        color: red;

	}
	td{
		padding: 25px;
		border-left: 2px solid;
	}
	.table, th{
		table-layout: fixed;
		word-wrap: break-word;
		margin: 25px;
		width: auto;
		text-align: center;
		border-collapse: collapse;
		border: 2px solid;
		font-size: 20px;
	}
	.rtl #vader {
		float: left;
	}
	.block-editor-page #vader {
		display: none;
	}
	@media screen and (max-width: 782px) {
		#vader,
		.rtl #vader {
			float: none;
			padding-left: 0;
			padding-right: 0;
		}
	}
	</style>
	";
}
// Create a table in the database to hold the quotes
function hello_yoda_activation(){
	global $wpdb;
	$wpdb->query("CREATE TABLE {$wpdb->prefix}quotes(
		id int AUTO_INCREMENT,
		quote varchar(255),
		quotee varchar(255),
		PRIMARY KEY  (id))" 
	);
}
register_activation_hook(__FILE__, 'hello_yoda_activation');

// Insert new quotes into the database
function hello_yoda_insert(){
	global $wpdb;

	if ('POST' === $_SERVER['REQUEST_METHOD']){
		global $wpdb;
		
		$quote = filter_var($_POST['quote'], FILTER_SANITIZE_STRING);
		$quotee = filter_var($_POST['quotee'], FILTER_SANITIZE_STRING);

		if ($quote != '' && ($quotee == 'Vader' || $quotee == 'Yoda')){
			$stmt = $wpdb->prepare("INSERT INTO {$wpdb->prefix}quotes (quote,quotee) VALUES (%s, %s)", $quote, $quotee);
			$wpdb->query($stmt);
		}
	
	}
}

function hello_yoda_delete(){

	if ('POST' === $_SERVER['REQUEST_METHOD']){
		global $wpdb;
		
		$id = filter_var($_POST['input'], FILTER_SANITIZE_STRING);
		$stmt = $wpdb->prepare("DELETE FROM {$wpdb->prefix}quotes WHERE id=%s", $id);
		if ($id != ''){
			try{
				$wpdb->query($stmt);
			} catch (Exception $e){
				echo "<script>alert(\'Error!\');</script>";
			}
			
		}
	}

}
// Loads the plugin after all files have been loaded
function hello_yoda_load(){
    if (current_user_can('activate_plugins')){
        add_action( 'admin_head', 'vader_css' );
        add_action( 'admin_notices', 'hello_yoda' );
    } else{
        add_action( 'admin_head', 'yoda_css' );
        add_action( 'admin_notices', 'hello_yoda' );
    }

  }
  add_action( 'plugins_loaded', 'hello_yoda_load' );

  

