<h1>Welcome</h1>
<b>You're about to discover a Wordpress module that lets you easily create a notification system between 2 users, using Jetengine and Jetformbuilder.</b><br>
This module lets you:
<ul>
	<li> Add a "bell" icon wherever you want, with an automatic notification counter (with a shortcode), without reloading the page</li> 
	<li> Choose where to redirect the user when the icon is clicked.</li>
	<li> Change the color of the icon and counter</li>
	<li> Send an email to the user who received the message</li>
	<li> Modify email title and body</li>
</ul>
We're at the Alpha version of development, so there's a lot of testing to do before we can release the module definitively, and we need your help.

<h2>Requirements:</h2>
<ul>
<li> Wordpress 6.2.0 minimum</li>
<li> Jetengine (to create a custom post type and a meta field)</li>
<li> Jetformbuilder (to create the request form)</li>
</ul>

<h2>Setting up:</h2> 
1 - First of all, you need to create a custom post type in your Wordpress.<br> 
You can use Jetengine, or any other module. (Tutorial from Crocoblock to help you: https://crocoblock.com/knowledge-base/jetengine/how-to-create-a-custom-post-type-based-on-jetengine-plugin/).<br>
For our example, we'll call it <b>"Messages"</b>.<br>
Then, once you've created your CPT, you need to add a meta text field, which you can call anything you like. We've called it <b>"id-user".</b><br><br>

<img src="https://marketplace.jrwebconcept.fr/wp-content/uploads/2023/09/post-types.png" width="100%">
<br><br>

2 - When this is done, you need to create a form, ideally with Jetformbuilder. To do this, you need to create various fields. 
<ul>
<li> A first hidden field, user_id with the value "Current User ID", which will retrieve the id of the logged-in user.</li>
<li> A second field, with the meta field created earlier, so for our example it's "id-user". The form will automatically integrate the id of the post's author, who will receive the notification and email.</li>
<li> Then a last field to add the message</li>
</ul>

<img src="https://marketplace.jrwebconcept.fr/wp-content/uploads/2023/09/form-2.png" width="100%">
	
Then in the section: "Post Submit Actions", you need to add a new action "Insert/Update Post".
<ul>
<li> Fetch the previously created post type</li>
<li> The status of the post</li>
<li> And in map, we advise you to do as the example below.</li>
</ul>

<img src="https://marketplace.jrwebconcept.fr/wp-content/uploads/2023/09/form-3.png" width="100%">

Then update this part, as well as the form.<br>

For the rest, we'll assume that your marketplace has different users, with different product sheets/posts, so for the module to work, you'll need to integrate your jetformbuilder form on the post of the user who is to receive the message.
<br>For our example, we've created a marketplace with different posts created by different users. On one of the posts, we added a "Contact" button with an Elementor popup in action.<br><br>

<img src="https://marketplace.jrwebconcept.fr/wp-content/uploads/2023/09/front-1.png" width="100%">

And we integrated the Jetformbuilder form<br>

<img src="https://marketplace.jrwebconcept.fr/wp-content/uploads/2023/09/front-2.png" width="100%">

Now that you've created the custom post type, its meta field and the form, you can install the plugin, if you haven't already, and we'll be able to configure it.<br>

After installation, you'll see a tab in the menu called Notifications settings. From here, you'll need to select :
<ul>
<li> The relevant post type</li>
<li> The meta field, which is the same as the one you created in the custom post type. We've added an automatic search to find the right field.</li>
<li> The redirection link when you click on the icon.</li>
<li> And you'll be able to set the color of the icon and the message counter.</li>
</ul>
And of course there's the icon shortcode and counter, which you can add wherever you like. [notification_bell]<br><br>

<img src="https://marketplace.jrwebconcept.fr/wp-content/uploads/2023/09/settings-1.png" width="100%">

Next, you can set the title and body of the email sent to the user in the tab below, called "Email settings". This email will automatically be sent to the user id present in the meta field selected above.

<img src="https://marketplace.jrwebconcept.fr/wp-content/uploads/2023/09/email-1.png" width="100%">

https://github.com/Artnco/user-notification/assets/145267846/4deae5cc-081d-401e-8701-46a8ba8592f5

<h2>Feedback:</h2>
<b>We are currently in the Alpha phase of module creation.</b> We strongly advise against using this module on a site in production.<br>
We will not be held responsible for any problems that may arise until the module has reached its final version.<br><br>
If you have any suggestions, a technical problem installing the module, or anything else, don't hesitate to let us know, with as many details as possible (images, videos, etc.). And we'll be offering a lifetime license to anyone who makes a significant contribution to improving this module.<br><br>
Thanks to all
