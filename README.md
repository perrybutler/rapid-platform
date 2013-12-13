Rapid Platform  
==============  
  
Rapid Platform is a streamlined approach for building websites/webapps and rapid prototyping. It's a plugin for WordPress (a content management system). Built from scratch with responsive web design principles and best practices at it's core.  

Join our [Google+ community](https://plus.google.com/u/0/b/110639079393921179427/communities/108899206842693997128) or visit the [official website](http://therapidplatform.com) for more information.  

![rp](http://files.glassocean.net/github/rp.png)

Features
--------

### Responsive Grids

A fluid grid based on fixed percentages, with columns that automatically stack vertically for smartphones. It's one of the easiest grid systems to use; Rapid Platform can build a four column responsive grid with the following shortcodes:  

    [rp_grid count="4" span="1"]html_content_goes_here[/rp_grid]  
    [rp_grid count="4" span="1"]html_content_goes_here[/rp_grid]  
    [rp_grid count="4" span="1"]html_content_goes_here[/rp_grid]  
    [rp_grid count="4" span="1"]html_content_goes_here[/rp_grid]  
  
Weirded out by the brackets? Shortcodes are kind of like markdown...syntax for a highly simplified text editor which converts (gets pre-processed) into actual PHP/HTML content.

We're tired of seeing Framework X offering "20 layout templates" and Premium Theme Y offering "premium layout templates for $30 more". What's the obsession with all of these hard-coded layout templates? See our home page remakes of [Twitter Bootstrap](http://therapidplatform.com/showcase/bootstrap/) or [WordPress.org](http://therapidplatform.com/showcase/wordpress-org/) to see what kind of layouts we're able to achieve **in as little as 5 minutes** with Rapid Grids. By the way, our home page remakes are fully responsive by default :)

![rp_grid](http://files.glassocean.net/github/rp-grid.png)

### Rapid UI

A robust set of UI controls and components ready to drop into a page. OAuth2.0/OpenID Login (Google/Facebook/Yahoo/MyOpenID), Lightbox Popups, Buttons, Tabs, Sliders, Google Web Fonts, Glyph Icons, etc:  

    [rp_login_form]  
    [rp_lightbox]  
    [rp_button]  
    [rp_choice type="tabs"]  
    <p style="font-family:Open Sans;">...</p>  
    <span class="icon-download">Download</span>  

![responsive ui](http://files.glassocean.net/github/rp-responsive-ui.png)

### Rapid Settings

Automatically creates entire settings pages in the WordPress admin dashboard with forms and fields tied into the database. Currently no UI designer for this, but a simple text file can do all the magic:

![rapid settings a](http://therapidplatform.com/wp-content/uploads/2012/11/options1.jpg)

...instantly becomes this:

![rapid settings b](http://therapidplatform.com/wp-content/uploads/2012/11/options2.jpg)

...and when the text file changes, the forms, fields and database are automatically updated. Traditionally, you might find yourself stripping out the field elements, deleting the validation/sanitation logic for those fields, modifying the database/sql procedures that couple these fields to the database, etc.

Roadmap
-------

### Release

Commit v0.2 which includes the new object-oriented architecture, a boatload of bugfixes, and many new controls/widgets/components.

### Fully embrace markdown

* Wait for WP native support, or implement via open-source lib?
* Authors/publishers need a better workflow and editor, bottom line!!! We don't want authors/publishers having to fiddle with code. Average Joe needs a good workflow, and what could be easier than markdown?
* Authors/publishers should have the ability to mix shortcodes with markdown and this should be easy to learn because the syntax is similar. Through the use of a plain text editor, authors/publishers would have the ability to design highly advanced "application-like" functionality without digging into php templates or javascript code.
* A markdown + shortcode editor with drag/drop enhancements would be far more ideal than the hybrid TinyMCE editor currently in WordPress.
* The editor in WordPress is clunky. The Visual tab in the editor is not very visual because you only see a very basic hard-wrapped preview of your content rather than the real thing. The HTML tab in the editor is not very HTML because your elements will get re-parsed (and sometimes mangled) by WP/TinyMCE if you switch back to the Visual tab. It's just not cool when your HTML elements disappear.
* The Ghost blogging platform takes a similar approach to what we're hoping to achieve. Let's take it a few steps further and combine the real-time capabilities of Ghost's markdown with shortcodes and LESS!
* The "new" markdown + shortcode editor would replace instances of the TinyMCE editor throughout WordPress. This puts us in line with one of our other roadmap features: live editing.

### Live editing

**Live editing is currently being developed in a side project I'm working on and will find its way into Rapid Platform once the first generation of live editing has matured. It's like a fancy HTML5 WYSIWYG (InnovaStudios, Aloha, Unify) without all the clutter and fuss.**

* Authors/editors (when given permission through the backend) should have the ability to view the website in their browser and make live edits to the content using specially tagged regions.
* It should work like this: An author/editor notices a typo in the Title of a Blog Post, so he turns on Edit Mode which creates an overlay on top of the live website, highlighing those specially tagged regions. From this point the author/editor can click on any of the highlighted regions to make a live edit, so he clicks the Title region and a textbox appears where he can change the Title of the Blog Post. After changing the Title, he clicks OK to view the change, and we store all changes in a "dirty-state" object. Finally, the author/editor can commit his changes which pushes the "dirty-state" object through a formatter/sanitizer, making any needed updates to the matching content in the database.

More neat stuff goes here.
