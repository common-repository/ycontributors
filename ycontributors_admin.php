<?
/* 
yContributors, v 0.5
http://yonatanr.net
by Yonatan Reinberg
Creates pretty searchable member/contributor archives and index.  Dont forget to <a href="options-general.php?page=yContributors">set it up</a>.
 
 If you like this or need support, visit us at http://social-ink.net, plugin url is http://www.social-ink.net/blog/ycontributors-wordpress-plugin-for-author-archives-and-contributor-index-with-photos-and-excerpts
 
Copyright 2011  Yonatan Reinberg (email : yoni [a t ] s o cia l-ink DOT net) - http://social-ink.net
*/

 ?>

<?php 
	//update with current version number
	$ycontributors_version = '0.5';

	if($_POST['form_submitted'] == 'Y') {
		//This is after they entered settings. So lets save them.

		$id_url = $_POST['ycontributors_pageid'];
		update_option('ycontributors_pageid', $id_url);	

		$user_posts = $_POST['ycontributors_userposts'];
		update_option('ycontributors_userposts', $user_posts);			
		
		$ycontributors_userposts_excerpt = $_POST['ycontributors_userposts_excerpt'];
		update_option('ycontributors_userposts_excerpt', $ycontributors_userposts_excerpt);				
		
		$allexcludedusers = $_POST['excludedusers'];
		if($allexcludedusers != "")
			$excludeid = implode(",",$_POST['excludedusers']);
		update_option('ycontributors_excludeid', $excludeid);			
		
		$ycontributors_searchenabled = $_POST['ycontributors_searchenabled'];
		update_option('ycontributors_searchenabled', $ycontributors_searchenabled);			
		
		$ycontributors_tableform = $_POST['ycontributors_tableform'];
		update_option('ycontributors_tableform', $ycontributors_tableform);			
		
		$ycontributors_userphoto = $_POST['ycontributors_userphoto'];
		update_option('ycontributors_userphoto', $ycontributors_userphoto);				
		
		$ycontributors_statsenabled = $_POST['ycontributors_statsenabled'];
		update_option('ycontributors_statsenabled', $ycontributors_statsenabled);			
	

		?>
		
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>

<?php
	} else {
		//list options
		$id_url = get_option('ycontributors_pageid');
		$user_posts = get_option('ycontributors_userposts');
		$excludeid = get_option('ycontributors_excludeid');	
		$allexcludedusers = explode(",",$excludeid);
		

		$ycontributors_searchenabled = get_option('ycontributors_searchenabled');
		$ycontributors_tableform = get_option('ycontributors_tableform');
		$ycontributors_userphoto = get_option('ycontributors_userphoto');
		$ycontributors_statsenabled = get_option('ycontributors_statsenabled');
	
	}

	?>
	
	
	
	<? 	$icon_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__)) . '/images/user_18x24.png'; 
		$small_icon_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__)) . '/images/user_12x16.png'; ?>

<div class="wrap">
	<h1>yContr<img src="<? echo $icon_url ?>" />butors by yonatan reinberg</h1>
		
	<div class="postbox-container" style="width:60%; margin-right:5%; " >

					<form name="yphplista_options" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
					
						<table class="form-table">
						
							<input type="hidden" name="form_submitted" value="Y">
							
									<h3>output settings</h3>		
									<div class="settings_table" style="margin-left:15px;">
									
									<p style="background: url(<? echo $small_icon_url ?>) no-repeat scroll 0 0 transparent; padding-left: 20px;">by default, yContributors lists all users. check the box next to the <b>users you wish to exclude</b> (be sure to exclude users with 0 posts for proper functionality):</p>
										<div class="userstuff" style="margin-left:15px;">
											<ul>
											<?	$blogusers = get_users($userquery);
												$numusers = count($blogusers);

												for($i=0;$i<$numusers;$i++) { 

													$curwriter = $blogusers[$i];
													$curauthor = $curwriter->display_name; $curauthorid = $curwriter->ID;
													?>
													
													<li><input type="checkbox" name="excludedusers[]" value="<? echo $curauthorid ?>" <?php if($allexcludedusers != "") { if(in_array($curauthorid,$allexcludedusers)) echo 'checked=checked'; } ?> />
														&nbsp;<a href="user-edit.php?user_id=<? echo $curauthorid ?>"><? echo $curauthor ?></a>&nbsp;&nbsp;&nbsp;<font style="font-style:italic;">(user id <? echo $curauthorid ?>, total <? authorpostcount($curauthorid) ?> posts)</font></p></li>

											<?	} ?>
											</ul>
											<p>&rarr; or manually enter a comma separated list of user <b>ids</b>: <input type="text" name="ycontributors_excludeid" value="<?php echo $excludeid; ?>" size="6"></p>
											
										</div>
										
									<p style="background: url(<? echo $small_icon_url ?>) no-repeat scroll 0 0 transparent; padding-left: 20px;">number of posts to show: <input type="text" name="ycontributors_userposts" value="<?php echo $user_posts; ?>" size="1">&nbsp;&nbsp;&nbsp;&nbsp; (0 shows none)</p>
									<p style="background: url(<? echo $small_icon_url ?>) no-repeat scroll 0 0 transparent; padding-left: 20px;">show post excerpts: <input type="checkbox" name="ycontributors_userposts_excerpt" id="ycontributors_userposts_excerpt" value="true" <?php if ($ycontributors_userposts_excerpt) { echo 'checked=checked'; } ?> />&nbsp;&nbsp;&nbsp;&nbsp; (not available in table form)</p>
									
									</div>

									<h3>display settings</h3>
									<div class="settings_table" style="margin-left:15px;">
										<p style="background: url(<? echo $small_icon_url ?>) no-repeat scroll 0 0 transparent; padding-left: 20px;">show dynamic search form <input type="checkbox" name="ycontributors_searchenabled" id="ycontributors_searchenabled" value="true" <?php if ($ycontributors_searchenabled) { echo 'checked=checked'; } ?> />&nbsp;&nbsp;&nbsp;&nbsp; (relies on javascript, people who view your site without JS enabled won't be able to use it)</p>
										<p style="background: url(<? echo $small_icon_url ?>) no-repeat scroll 0 0 transparent; padding-left: 20px;">use table-style layout <input type="checkbox" name="ycontributors_tableform" id="ycontributors_tableform" value="true" <?php if ($ycontributors_tableform) { echo 'checked=checked'; } ?> /></p>
										<p style="background: url(<? echo $small_icon_url ?>) no-repeat scroll 0 0 transparent; padding-left: 20px;">add user photo field <input type="checkbox" name="ycontributors_userphoto" id="ycontributors_userphoto" value="true" <?php if ($ycontributors_userphoto) { echo 'checked=checked'; } ?> /> (relies on <a href="http://wordpress.org/extend/plugins/user-photo/">User Photo WP plugin</a> being installed, could give errors otherwise)</p>
										<input type="hidden" name="ycontributors_statsenabled" id="ycontributors_statsenabled" value="true" <?php if ($ycontributors_statsenabled) { echo 'checked=checked'; } ?> />
									</div>
									<hr />
					
									<p class="submit">
									<input type="submit" class="button-primary" name="Submit" value="<?php _e('Update Options', 'ycontributors_trdom' ) ?>" />
									</p>
									
									
					
						</table>
					</form>
	</div>
	
	<div class="postbox-container" style="width:20%;">

					<h3 class="hndle"><span>what it is</span></h3>
					
						<p>copyright yonatan reinberg 2011 - v<? echo $ycontributors_version ?>, social-ink</p>
						<p>visit <a href="http://www.social-ink.net/blog/ycontributors-wordpress-plugin-for-author-archives-and-contributor-index-with-photos-and-excerpts">plugin homepage</a> | <a href="">WP page</a></p>
						<p>to use after setting up, go into your post/page and add shortcode [ycontributors] wherever you want the contributors list to appear, or use &lt;? ycontributors() ?&gt; in your template files.</p>
						<p>edit user profiles under <a href="users.php">Users</a> or by clicking that user's name to the left; description column is the "descriptive biography" field - (hint: use <a href="http://wordpress.org/extend/plugins/rich-text-biography/">Rich Text Biography</a> for a better field). </p>
						<p>remember that to style an individual author's post list, you must code your own wp template called <i>authors.php</i>. if you need help with that, please be in touch with social ink!</p>
						<hr />
						<? $beer_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__)) . '/images/icon_beer.gif'; ?>
						<p><img src="<? echo $beer_url ?>" style="float:left;margin-right:15px;margin-bottom:15px;" />did this plugin really help you out? <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=accounts@social-ink.net&currency_code=&amount=&return=&item_name=Buy+Me+A+Beer+Social+Ink+donation">buy me a beer (suggested $5)!</a></p>
					
							
						
			

		</div>

	
 </div>