=== Attachments++ ===
Contributors: dan.rossiter
Tags: attachments,embed,google viewer,video,audio
Requires at least: 2.5
Tested up to: 3.6
Stable tag: 0.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plussify your attachments! Attachments++ allows auto-embedding of most document,
video and audio files. No need to download that MS Word doc to read.

== Description ==

Attachments++ enables auto-embedding of most document, video, and audio filetypes
directly into their respective attachment page. No longer will you have to
download that one-page MS Word document just to read it.

Additionally, the plugin functions with *any* theme you decide to use. It
seamlessly integrates into whatever environment it is being used.

*Attachments++ is still being developed, but it is already a valuable tool for
any WordPress site. If you have any suggestions, please do not hesitate to voice
them in the support forum! Additionally, if this plugin has helped you, please
take a moment to [rate
it](http://wordpress.org/support/view/plugin-reviews/attachments-plus-plus#postform)!*

== Installation ==

1. Upload `attachments-pp` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in Admin Dashboard.

== Frequently Asked Questions ==

**What is the minimum recommended WordPress version for Attachments++?**

Although Attachments++ can run on lower versions, 3.6 or higher is *strongly
recommended*. Lower versions will not be able to embed audio or video files,
though all documents will be supported.

**What filetypes are supported by Attachments++?**

That is a long, ever-growing list. As of writing this, a total of **51** 
filetypes are supported with WordPress version 3.6 or higher, and **40 **
filetypes for lesser versions. These filetypes include all common documents
from Microsoft Office (doc/docx, ppt/pptx, etc.), as well as many more obscure
filetypes like Adobe Illustrator, Photoshop, and AutoCad files. If you can think
of a file then it is probably supported or will be in the future.

**Does Attachments++ integrate with any other plugins?**

It does indeed. It is already designed to integrate with <a
href=\"http://wordpress.org/plugins/google-document-embedder/\">Google Doc
Embedder</a>, if installed, and plans are in place to support more plugins in
the future. If you know of a plugin that would make sense to integrate, please
feel free to suggest it on the
<a href=\"http://wordpress.org/support/plugin/attachments-plus-plus/\">support forum</a>!

**It's not working! Help!**

Firstly, are you sure it's not working? Some filetypes are not supported yet,
and for those the attachment page should not change at all. To test functionality,
try uploading a PDF or DOC file (only because those are generally the easiest to
come by). Once uploaded, go to the attachment page for that doc (found though
at *Admin Dashboard -> Media Library -> View* (for the PDF or DOC you just
uploaded). If that page looks the same as it did, then something probably is
wrong and you should post on the support forum. Also, it goes without saying,
if you get a bunch of big ugly error messages after installing Attachments++, that
should also be posted on the support forum.

== Changelog ==

= 0.6 =

* General code cleanup.
* Support for use of a filter to modify the embedders to be used on attachments
  (documentation on this filter soon to come).

= 0.5 (alpha 1) =

This is the first public release of Attachments++.

*   Supports all WP 3.6 embedding if user is running correct version.
*   Supports integration with <a href=\"http://wordpress.org/plugins/google-document-embedder/\">Google Doc Embedder Plugin</a> if installed.
*   Supports use of <a href=\"https://docs.google.com/viewer\">Google Doc Viewer</a> as last resort.
*   Much work to be done in terms of optimizing and making the plugin more robust, but is fully-functional and already a great tool.