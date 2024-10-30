=== m1.DownloadList ===
Contributors: maennchen1.de
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3QB5NVYBXHSP4&source=url
Tags: attachment, attachments, download, downloads, file, filebase, filelist, filemanager, filelist, downloadlist, link, files, folder, folders, ftp, http, images, list, media, mp3, pdf
Requires at least: 4.0
Tested up to: 6.6
Requires at least PHP: 7.0
Tested up to PHP: 8.2.6
Stable tag: 0.19
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin easily displays the folders and files from a selected directory. It can be placed by shortcode in any post.

== Description ==
This plugin easily displays the folders and files from a selected directory. It can be placed by shortcode with the parameters path and target in any post. Uploads must be done by a separate ftp program. No managing options.

* compatible up to PHP7.2
* need PHP extension [mb_string](http://php.net/mb_string)

= available optional shortcode parameters =
* path = directory path, starting by web root (default: wp-content/uploads/)
* target = browser window name
* sort = by name ASC/DESC (default: ASC)
* sort-order = filename/filetype/ftime/filetime/foldertime (default: filename)
* label = custom top level label
* nosize = displays no file size
* hidedirs = displays no folders, only files
* filetype = (comma separated list) filter files by their extension
* hidefiletype = (comma separated list) hide files with filetype
* hidefilename = (comma separated list) hide named files and folders
* noext = hide the file extensions
* nobreadcrumb = hide breadcrumb / title
* ftime = display file and folder modification date and time (standard = "1" or use date formatting like "Y-m-d H:i"), see [PHP date formatting](http://php.net/date)
* filetime = same as ftime, just for files
* foldertime = same as ftime, just for folders

(most of it can be combined together)

= shortcode examples =
1. displays content of `wp-content/uploads/`: `[m1dll]` 
1. displays content of `your/foldername/here/`: `[m1dll path="your/foldername/here/"]`
1. displays content of `your/foldername/here/` and sort descending: `[m1dll path="your/foldername/here/" sort="DESC"]`
1. displays content of `your/foldername/here/`, open files in a new window: `[m1dll path="your/foldername/here/" target="_blank"]` 
1. displays content of `your/foldername/here/`, change label 'downloads' to 'our downloads': `[m1dll path="your/foldername/here/" label="our downloads"]`
1. displays content of `wp-content/uploads/`, displays no file size: `[m1dll nosize="1"]`
1. displays content of `wp-content/uploads/`, displays no folders: `[m1dll hidedirs="1"]`
1. displays content of `wp-content/uploads/`, displays only pdf- and docx-documents: `[m1dll filetype="pdf,docx"]`
1. displays content of `wp-content/uploads/`, do not display pdf- and docx-documents: `[m1dll hidefiletype="pdf,docx"]`
1. displays content of `wp-content/uploads/`, do not display file secret.txt and secret.docx: `[m1dll hidefilename="secret.txt,secret.docx"]`
1. displays content of `wp-content/uploads/`, displays no file extensions: `[m1dll noext="1"]`
1. displays content of `wp-content/uploads/`, displays no breadcrumb: `[m1dll nobreadcrumb="1"]`
1. displays content of `wp-content/uploads/`, with file and folder time with own format `[m1dll ftime="Y-m-d, H:i"]`
1. displays content of `wp-content/uploads/`, with file and folder date and time (standard from WordPress) `[m1dll ftime="1"]`
1. displays content of `wp-content/uploads/`, with file and folder self formated date `[m1dll ftime="Y-m-d"]`
1. displays content of `wp-content/uploads/`, with file date and time (standard from WordPress) `[m1dll filetime="1"]`
1. displays content of `wp-content/uploads/`, with folder date and time (standard from WordPress) `[m1dll foldertime="1"]`


== Installation ==
1. Upload the folder `m1.downloadlist` to your directory (`wp-config/plugins/`)
1. Activate the Plugin 
1. place the shortcode in your post
1. test and please give us a review, thx! <3: https://wordpress.org/support/view/plugin-reviews/m1downloadlist

== Frequently Asked Questions ==
 
= Can I manage files with "media" in WordPress? =

No. Just upload your files and folders to an appreciate folder by FTP and link it by the shortcode `[m1dll path="your/foldername/here/"]`.

= I got problems with special characters in folders and/or filenames. How can I fix it? =

1. Check PHP version (suggested PHP7.0 or above).
2. Check enabled PHP module mb_string is enabled.
3. Check enabled UTF8 Apache Headers. (Ask your provider in doubt.)
4. Check file permissions.

== Screenshots ==
1. place the shortcode in your post
2. display the directory listing

== Changelog ==
= 0.19 =
* feature: wordpress 6.6 compatibility
= 0.18 =
* feature: php 8.2 compability
* feature: wordpress 6.2.2 compability
= 0.17 =
* bugfix: parameter nosize doesn't work
= 0.16 =
* feature: display file date and time (3 new parameters: ftime, filetime, foldertime)
* feature: new parameter 'sort-oder' to choose a sortable parameter like name or date
* feature: CSS-class-names for filetime and filesize
= 0.15 =
* bugfix: removed debugging message
= 0.14 =
* feature: new parameter 'hidefiletype'
* feature: new parameter 'hidefilename'
* bugfix: remove notice message for parameter "target" thx@rameshmehay
* bugfix: show office documents icons (docx, xlsx, pptx)
= 0.13 =
* bugfix: problems with subdirs
= 0.12 =
* bugfix: notice message
= 0.11 =
* feature: compatible up to PHP7.1
* stability enhancements
= 0.10 =
* bugfix: handling with UTF-8 filenames
= 0.9 =
* feature: new parameter 'nobreadcrumb'
= 0.8 =
* bugfix: sort order
= 0.7 =
* feature: new parameter 'hidedirs'
* feature: new parameter 'filetype'; thx@noerw
* feature: new parameter 'noext'; thx@rwdrummond
* bugfix: remove anoying PHP-notices / debugging-messages; thx@gonowjohn
= 0.6 =
* feature: new parameter 'label'
* feature: new parameter 'nosize'
= 0.5 =
* feature: can handle more than 1 shortcode 
= 0.4 =
* feature: added localization german & english (+ pot-file) hope someone help to translate it!
= 0.3 =
* bugfix: display folder and file icons (thx to Lutz Müller)
* feature: sort ascending and descending
= 0.2 =
* bugfix: utf8_encode
* bugfix: plugin path
= 0.1 =
* initial release

== Upgrade Notice ==

= 0.10 =
Fix some problems with UTF-8 filenames
