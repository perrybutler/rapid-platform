Rapid Platform  
==============  
  
Rapid Platform is a streamlined approach for building websites/webapps and rapid prototyping. It's a plugin for WordPress (a content management system). Built from scratch with responsive web design principles and best practices at it's core.  

Visit our official website [TheRapidPlatform.com](http://therapidplatform.com) for more information.

Grids
-----

A fluid grid based on fixed percentages, with columns that automatically stack vertically for smartphones. It's one of the easiest grid systems to use; Rapid Platform can build a four column responsive grid with the following shortcodes:  

    [rp_grid count="4" span="1"]html_content_goes_here[/rp_grid]  
    [rp_grid count="4" span="1"]html_content_goes_here[/rp_grid]  
    [rp_grid count="4" span="1"]html_content_goes_here[/rp_grid]  
    [rp_grid count="4" span="1"]html_content_goes_here[/rp_grid]  
  
Weirded out by the brackets? Shortcodes are kind of like markdown...syntax for a highly simplified text editor which converts (gets pre-processed) into actual PHP/HTML content.

![rp_grid](http://files.glassocean.net/github/rp-grid.png)

UI Controls & Widgets
---------------------

A robust set of UI controls and components ready to drop into a page. Oauth/OpenID Login (Google/Facebook/Yahoo/MyOpenID), Lightbox Popups, Buttons, Tabs, Sliders, Google Web Fonts, Glyph Icons, etc:  

    [rp_login_form]  
    [rp_lightbox]  
    [rp_button]  
    [rp_choice type="tabs"]  
    <p style="font-family:Open Sans;">...</p>  
    <span class="icon-download">Download</span>  

![responsive ui](http://files.glassocean.net/github/rp-responsive-ui.png)

Options Bootstrapper
--------------------

An automatic option page builder that creates entire options pages in the WordPress admin dashboard with forms and fields tied into the database. Currently no UI designer for this, but a simple text file can do all the magic:

![options bootstrap a](http://therapidplatform.com/wp-content/uploads/2012/11/options1.jpg)

...instantly becomes this:

![options bootstrap b](http://therapidplatform.com/wp-content/uploads/2012/11/options2.jpg)

...and when the text file changes, the forms, fields and database are automatically updated. Traditionally, you might find yourself stripping out the field elements, deleting the validation/sanitation logic for those fields, modifying the database/sql procedures that couple these fields to the database, etc.

More neat stuff goes here.
