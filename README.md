# Hypothesis Aggregator
A WordPress plugin that allows you to aggregate Hypothes.is annotations on a single page or post on your WordPress blog.

## Server Requirements
Note that your WordPress server environment may need specific libraries and configuration to support this plugin. Known requirements are listed below, but this list may not be complete. You can investigate your WordPress server environment using something like a [phpinfo file](http://php.net/manual/en/function.phpinfo.php).
1. [cURL for PHP](http://php.net/manual/en/book.curl.php): If a phpinfo file on your WordPress server doesn't show cURL support, then you need to figure out how to make cURL available on your server (a topic beyond the scope of this guide).

## Install
1. Click “Clone or Download” (the green button on the right).
2. Download to ZIP.
3. Log in to your WordPress site
4. Go to Plugins >> Add New
5. Click “Upload Plugin” and upload the ZIP file
6. Once it’s uploaded and installed, click “Activate,” and you’re ready to go

## Using the plugin
Create a new page or post in WordPress, and as you write, include the following shortcode:

    [hypothesis]

Now, that alone won’t do anything. You need to feed it some search terms, like one of the following:

    [hypothesis user = 'kris.shaffer']

    [hypothesis tags = 'IndieWeb']

    [hypothesis text = "Domain of One's Own"]

    [hypothesis user = 'kris.shaffer' tags = 'IndieEdTech']

Hypothes.is Aggregator accepts user, tags, and text search parameters, on their own or in combination with each other. Currently it does not support lists of users or tags, but that is in the works for a future version.

After adding one line of this “shortcode,” publish the post, and you should see on that page a list of annotations.
