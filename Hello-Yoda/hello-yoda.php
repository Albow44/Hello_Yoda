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

//Menu Page
add_action( 'admin_menu', 'yoda_menu_page' );
function yoda_menu_page() {
	add_menu_page(
		'Hello Yoda',
		'Yoda',
		'read',
		'yodamenu',
		'yoda_menu_output',
		'https://img.icons8.com/color/24/000000/yoda.png'
		
	);
}
function yoda_menu_output(){
  echo'<h1>The Hello Yoda Plugin by Alan</h1>
  <p>"Learn WordPress, you must!" --Yoda</p>';
}

//SubMenu
add_action( 'admin_menu', 'yoda_submenu_page');
function yoda_submenu_page(){
add_submenu_page(
   	'yodamenu',
    'Add A New Quote',
    'Add Quote',
    'read',
    'yodasub',
    'yoda_submenu_output'
);
}
function yoda_submenu_output(){
	// check user capabilities
    if (!current_user_can('read')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="quotes.php" method="post">
            <?php
			echo'
			<br>
				<label for="uquote"> Quote:</label> 
				<p><input type="text" name="uquote"></p>
				<label for="uquotee"> Quotee:</label> 
			 	<p><input type="text" name="uquotee"></p><br>
				<input type="submit">'
            ?>
        </form>
    </div>
    <?php
}
function hello_yoda_get_quotes() {
	/** These are the lyrics to Hello Dolly */
	$quotes = "Hello, Yoda
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

	// Here we split it into lines.
	$quotes = explode( "\n", $quotes );

	// And then randomly choose a line.
	return wptexturize( $quotes[ mt_rand( 0, count( $quotes ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later.
function hello_yoda() {
	$chosen = hello_yoda_get_quotes();
	$lang   = '';
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}

	printf(
		'<p id="yoda"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
		__( 'Quote from Master Yoda:', 'hello-yoda' ),
		$lang,
		$chosen
	);
}

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
        color: #3cb371;

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

//Hello Vader Admin Privaleges

function hello_vader_get_quotes() {
	/** These are the lyrics to Hello Dolly */
    $quotes = "No, I am your father.
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
    Tell your sister you were right about me.
    ";

	// Here we split it into lines.
	$quotes = explode( "\n", $quotes );

	// And then randomly choose a line.
	return wptexturize( $quotes[ mt_rand( 0, count( $quotes ) - 1 ) ] );
}

function hello_vader() {
	$chosen = hello_vader_get_quotes();
	$lang   = '';
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}

	printf(
		'<p id="vader"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
		__( 'Quote from Darth Vader:', 'hello-vader' ),
		$lang,
		$chosen
	);
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
// Load vader Quotes for admin, Yoda for all other users
function hello_yoda_load(){
    if (current_user_can('install_plugins')){
        add_action( 'admin_head', 'vader_css' );
        add_action( 'admin_notices', 'hello_vader' );
    } else{
        add_action( 'admin_head', 'yoda_css' );
        add_action( 'admin_notices', 'hello_yoda' );
    }
  }
  add_action( 'plugins_loaded', 'hello_yoda_load' );

  

