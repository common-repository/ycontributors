=== yContributors ===
Contributors: yonisink
Donate link: http://www.social-ink.net/donating-to-social-ink
Tags: contributors,members,authors,author archive, contributors, blog contributors, blog authors, contributor page, smart author archives, show all authors, contributors recent posts, authors recent posts, multi-user archive, multi-user recent posts, get_user posts,jquery
Requires at least: 3.1
Tested up to: 3.1
Stable Tag: 0.5

yContributors easily creates pretty, jQuery-searchable contributor/author indices and author and contributor archives.

== Description ==

yContributors creates a one-step author or contributors archive page that enables you to show any number of recent posts by each author, along with profile information pulled from each user's wordpress user profile.  This plugin shows all your members, either in table or descending form, with a jQuery searchable autocomplete-style (type and find) way to get to members.  Options include excluding certain members, showing latest posts and author statistics.  Also, works with User Photos Plugin (unrelated to this) to display profile photos.

For more information visit http://social-ink.net or http://www.social-ink.net/blog/ycontributors-wordpress-plugin-for-author-archives-and-contributor-index-with-photos-and-excerpts

Please note, as with every plugin or theme modification, you should do a backup of your files beforehand.  Although we've tested this across many installs, we are not responsible for anything it does to your system and do not guarantee support.

== Installation ==

1. Upload 'ycontributors' directory to your  '/wp-content/plugins/' directory
2. Activate the yContributors plugin through the 'Plugins' page in WordPress
3. Set it up with your settings.
4. Add a new page or post to your wordpress and type in the [ycontributors] shortcode wherever you want the member index to appear, or use <? ycontributors() ?> in your template files.


== Frequently Asked Questions ==

= How do I get the photo and rich text in description? =

Remember, [for now] yContributors doesn't do anything to your profiles, it just displays them in a cool way to the outer world.  We like two great WP plugins that work with yContributors, User Photo (http://wordpress.org/extend/plugins/user-photo/) and Rich Text Biography (http://wordpress.org/extend/plugins/rich-text-biography/) that should help you. 

= Can I change how things look? =

Certainly. CSS is your best friend. everything is id'd and class'd.

= There's a weird discrepancy between the search results and the rows of users that show =

That's because you haven't excluded users with 0 posts.  Go to the back end and make sure to check the boxes next to the names of anyone with 0 posts.

= Spacing issues = 

See above question on 0 posts, but also you'll probably need to style it.  I purposefully left it sparsely styled so you can use your stylesheet.  Try the table format to see if it works better for you, or please be in touch with us for customization.

= The "see all posts by x" page is all screwed up =

Remember that to style an individual author's post list, you must code your own wp template called authors.php.

== Screenshots ==

1. yContributors output after a quick search.
2. yContributors options page.

== Changelog ==

= 0.51 =
* Fixed permissions problems.

= 0.5 =
* First version released.

== Upgrade Notice ==

= 0.5 =
* Nothing to see here, folks.
