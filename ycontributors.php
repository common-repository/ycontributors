<?php
/* 
Plugin Name: yContributors
Plugin URI: http://www.social-ink.net/blog/ycontributors-wordpress-plugin-for-author-archives-and-contributor-index-with-photos-and-excerpts
Author URI: http://yonatanr.net
Version: 0.51
Author: Yonatan Reinberg
Description: Creates pretty searchable member/contributor archives and index.  Dont forget to <a href="options-general.php?page=yContributors">set it up</a>.
 
 If you like this or need support, visit us at http://social-ink.net
 
Copyright 2011  Yonatan Reinberg (email : yoni [a t ] s o cia l-ink DOT net) - http://social-ink.net
*/

	//defaults for the options
	
	add_option('ycontributors_pageid', '2378');	//page its on
	add_option('ycontributors_excludeid','0');		//users to exclude
	add_option('ycontributors_userposts','0');	//number of posts to display by default
	add_option('ycontributors_userposts_excerpt','false');	//display post excerpt?
	add_option('ycontributors_searchenabled','false');	//is search enabled
	add_option('ycontributors_tableform','false');	//table form or css form?
	add_option('ycontributors_userphoto','true');	//show the photo?
	add_option('ycontributors_statsenabled','false');	//statistics?
	
	//options/admin stuff
		add_action('admin_menu', 'ycontributors_add_menu');		//options page


	function ycontributors_admin() {  
		 include('ycontributors_admin.php');  
	 }  
		 
		
	function ycontributors_add_menu() {  
			add_options_page("yContributors", "yContributors", 'manage_options', "yContributors", "ycontributors_admin");  	  
	}  

	//script inits

	function ycontributors_enqueue_scripts() {
		$plugin_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));
		wp_enqueue_script('jquery-autocomplete', $plugin_url . '/js/jquery-ui-1.8.10.custom.min.js', array('jquery'));
	}
	if (!is_admin()) {
		add_action('init', 'ycontributors_enqueue_scripts');
	}	

	add_action('wp_head', 'ycontributors_jquery_header');	//header
	
		
	//shortcode hook
	add_shortcode('ycontributors', 'ycontributors_output');	

	//jquery header script
	function ycontributors_jquery_header() {
	
		$plugin_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));
		
		echo "\n\n<!-- start yContributors script by yonatan reinberg/social ink (c) 2011 - http://social-ink.net; yoni@social-ink.net -->\n\n";
		
		echo '<link href="' . $plugin_url . '/ycontributors.css" rel="stylesheet" type="text/css" />' . "\n";	
			
		//start the script here
		
		//use list form by default, table form if checked in options
		$table_form = false;
			if(get_option('ycontributors_tableform')=="true") $table_form=true;		
		
			$scriptoutput = "<script type=\"text/javascript\">\n";
			
			$scriptoutput .= "//<![CDATA[\n\n"; //cdata to validate XHTML
			
			$scriptoutput .= "var \$j_p = jQuery.noConflict();\n";
			
			$scriptoutput .= "\$j_p(function(){\n
							\n";
			
			//open array
			$scriptoutput .= "var contributorArray = [";
			
			//populate array with all users
			$excludeuserstring = get_option('ycontributors_excludeid');

			$excludeusers = explode(",", $excludeuserstring);
			$userquery = array(
					'exclude' => $excludeusers
				);	
			$blogusers = get_users($userquery);

			$numusers = count($blogusers);
			for($i=0;$i<$numusers;$i++) { 
					$curwriter = $blogusers[$i]; $curauthor = $curwriter->display_name; $curauthorid = $curwriter->ID;	
					$scriptoutput .= '{"value": "' . $curauthor . '", "label" : "' . $curauthor . '", "divchoice": "' . $curauthorid . '"},';							
				}
				
			//close array
			$scriptoutput .= "				];\n\n";
							
			//init jquery autocomplete, http://jqueryui.com/demos/autocomplete/
			$scriptoutput .= "\$j_p(\"#ycontrib_search\").autocomplete({
									search: function(event, ui) {
										\$j_p('.ycontrib_oneauthor').fadeTo('fast','.5');
									},		
									
									close: function(event, ui) {
										\$j_p('.ycontrib_oneauthor').fadeTo('fast','1');
									},										

									source: contributorArray,
									delay: 0,
									appendTo: \".ycontributors\",
									minLength: 0,
									
									select: function( event, ui ) {
										var goodelement = '#blogauthor' +  ui.item.divchoice;
										\$j_p('.ycontrib_oneauthor').fadeOut('fast');
										\$j_p(goodelement).fadeIn('fast');
										\$j_p(goodelement).fadeTo('fast','1');
																					
									}
				});";
				
			$scriptoutput .= "\$j_p(\"#clearcontribs\").click(function(){
									\$j_p(\"#ycontrib_search\").val('');
									\$j_p('.ycontrib_oneauthor').fadeTo('fast','1');
									\$j_p('.ycontrib_oneauthor').show('');
								});";				
								
			$scriptoutput .= "\$j_p(\"#ycontrib_search\").blur(function(){
									//\$j_p(\"#ycontrib_search\").val('');
									if(\$j_p(this).val()=='')	{					
										\$j_p('.ycontrib_oneauthor').fadeTo('fast','1');
										\$j_p('.ycontrib_oneauthor').show('');			
									}										

								});";			
			if($table_form) {		
				$scriptoutput .= "\$j_p('.ycontrib_oneauthor').hover(function(){  
									\$j_p(this).find('td').addClass('hovered');  
									}, function(){  
									\$j_p(this).find('td').removeClass('hovered');  
								});";
								

			}
			
							
			$scriptoutput .= "});\n";	//close JQuery ouput
			
			$scriptoutput .= "	//]]>"; //close cdata to validate XHTML
			
			
			$scriptoutput .= "\n</script>";								
							
		echo $scriptoutput;
		
		echo "\n\n<!-- end yContributors script by yonatan reinberg/social ink (c) 2011 - http://social-ink.net; yoni@social-ink.net -->\n\n";
	}
	
	//quick function to return individual authors post count based on user id. surprisingly not in WP basic calls...
	function authorpostcount($userid) {
	
		$stats_enabled = false;
			if(get_option('ycontributors_statsenabled')=="true") $stats_enabled=true;	
			
		global $wpdb;
		$query = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_author = '" . $userid . "'";
		$post_count = $wpdb->get_var($query);
		if($stats_enabled) echo $post_count;
	}
	
	//just a shortened function to return the in-page output
	function ycontributors() {
		ycontributors_output(); 
	}

	//guts of script to calculate and push output
	function ycontributors_output() {

		//GRAB ALL OPTIONS
		
		//deprecated, but could be used in future to limit script loading on nonuseful pages, cutting overhead
		$id_url = get_option('ycontributors_pageid');				
		
		//enable search box?
		$search_enabled = false;
			if(get_option('ycontributors_searchenabled')=="true") $search_enabled=true;				
		
		//show excerpt?
		$user_posts_excerpt = false;
			if(get_option('ycontributors_userposts_excerpt')=="true") $user_posts_excerpt=true;			
		
		//use list form by default, table form if checked in options
		$table_form = false;
			if(get_option('ycontributors_tableform')=="true") $table_form=true;
		
		//how many posts to show
		$userposts = get_option('ycontributors_userposts');
		
		//excluded users
		$excludeuserstring = get_option('ycontributors_excludeid');
		$excludeusers = explode(",", $excludeuserstring);
		
		//show photos?
		$show_photos = false;
			if(get_option('ycontributors_userphoto')=="true") $show_photos=true;
		
		//show statistics?
		$stats_enabled = false;
			if(get_option('ycontributors_statsenabled')=="true") $stats_enabled=true;		
			
		//build array to get users and get users
		
		$userquery = array(
				'exclude' => $excludeusers
			);
		
		$blogusers = get_users($userquery);

		$numusers = count($blogusers); ?>
		
			<div class="ycontributors">
			
			<? if($search_enabled) { ?>
				<div class="ycontributors_search">
				
					<noscript><p>Please note that search functionality is unavailable if javascript is disabled</p></noscript>
					
					<form action="" onsubmit="return false;">
						<p>
							<label for="ycontrib_search">Find an author:</label>
							<input type="text" id="ycontrib_search" value="" />
							<button id="clearcontribs">Clear search</button>
						</p>
					</form>
				</div>
			<? } ?>				
			
			<?  /*if($stats_enabled) { ?>
				<div class="ycontributors_stats">
					We're proud to have <? echo $numusers ?> users.
				</div>
			<? } ?*/  ?>
			
			
			<?	// TABLE OR LIST FORMAT???
			
			if($table_form) {	//table/data form ?>

				<table class="ycontributors_table">
					<thead>
						<tr>
							<? if($show_photos && function_exists('userphoto')) { ?><th>Photo</th><? } ?>
							<th>Name</th>
							<th>Biography</th>
							<th>Posts</th>
						</tr>
					</thead>
					
					<tbody>
					
					<? for($i=0;$i<$numusers;$i++) { 

						$curwriter = $blogusers[$i];
						$curauthor = $curwriter->user_nicename; $curauthorid = $curwriter->ID; ?>
						
							<tr class="ycontrib_oneauthor"  id="blogauthor<? echo $curauthorid ?>">
								
								<? $args = array(
									'author_name' => $curauthor,
									'showposts' => 1,
									);	
									
									$my_query = new WP_Query($args);
										
									if( $my_query->have_posts() ) { 
										while ($my_query->have_posts()) : $my_query->the_post(); ?>
											<? if($show_photos && function_exists('userphoto')) { ?> <td class="yc_author_photo"><?php userphoto($curauthorid); ?></td> <? } ?>
											<td class="yc_author_name"><?php the_author(); ?></td>
											<td class="yc_author_bio"><?php the_author_description(); ?></td>
											<td class="yc_author_seeall"><a href="<?php echo get_author_posts_url($curauthorid) ?>">See all <? authorpostcount($curauthorid) ?> posts by <?php the_author(); ?></a></td>	
								
										<? 	endwhile;  }
											wp_reset_query();?>
								</tr>
						<? } ?>
						</tbody>
					</table>
					
			<? } else {	//its normal "div" form			

				 for($i=0;$i<$numusers;$i++) { 
				 
					$curwriter = $blogusers[$i];
					$curauthor = $curwriter->user_nicename; $curauthorid = $curwriter->ID;
					$author_loop = 0;				 

					$args = array(
					'author_name' => $curauthor,
					'showposts' => $userposts,
					);	?>
							
					<div class="ycontrib_oneauthor" id="blogauthor<? echo $curauthorid ?>">							
						
							   <?  	$my_query = new WP_Query($args);
									if( $my_query->have_posts() ) { 

									while ($my_query->have_posts()) : $my_query->the_post(); 
									
											$author_loop++;
										
											if($author_loop==1) { ?>
												
												<div class="yc_author_meta">
													<? if($show_photos && function_exists('userphoto')) { ?> <div class="yc_author_photo"><?php userphoto($curauthorid); ?></div> <? } ?>
													<div class="yc_author_name"><?php the_author(); ?></div>
													<div class="yc_author_bio"><?php the_author_description(); ?></div>
													
													<? if($userposts>0) { //latest posts header ?>
														<div class="yc_author_seeall">Showing recent <? echo $userposts ?> posts (<a href="<?php echo get_author_posts_url($curauthorid) ?>">See all posts by <?php the_author(); ?></a>)</div>
														
														</div>	<!-- close yc_autor_meta -->
														
														<div class="yc_author_latestposts">
													<? } else { ?>
												</div>	<!-- close yc_autor_meta -->
														
													<? } ?>
																						
											<? 	} ?>
													<? if($userposts>0) { //we want to show latest posts ?>
															<div class="yc_author_summary" id="blogauthorsummary<? echo $curauthorid ?>_<? echo $author_loop ?>"> 
																<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
																<? if($user_posts_excerpt) { //	want excerpt ?>
																	<div class="blogauthorexcerpt"><? the_excerpt(); ?></div>
																<? } ?>
															</div>
													<? } ?>
										
										<?  endwhile;
											wp_reset_query(); ?>
											
											<? if($userposts>0) { //we want to show latest posts ?>
												</div>
											<? } ?>
								<?	} // end if_have_posts?>
									
												
					
					</div> <!-- close each individual author -->
				<?  }	
			}?>		
				
		<div class="yc_clearfix"></div>
		
		</div>
		
	<? } ?>
