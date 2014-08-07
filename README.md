[![Donate][1]][2] [![git tips][3]][4]

#CMS Utils for Prestashop

##Smarty tags

###{cms_title}

Return title of CMS page.

**Parameters**

 - page - page id (required)
 - assign (optional) 

###{cms_content}

Return title of CMS page.

**Parameters**

 - page - page id (required)
 - assign (optional) 

###{cms_get_data}

Return any fields of CMS page.

**Parameters**

 - page - page id (required)
 - block (required)
 - assign (optional) 

###{cms_selflink}

**What does this do?**

Creates a link to another CMS content page inside your template or content.

**How do I use it?**

Just insert the tag into your template/page like: `{cms_selflink page="1"}` or `{cms_selflink href="1"}`

**What parameters does it take?**

 - (optional) page - Page ID (optional) 
 - anchorlink - Specifies an anchor to add to the generated URL. (optional) 
 - urlparam - Specify additional parameters to the URL. Do not use this in conjunction with the anchorlink parameter (optional) 
 - tabindex =”a value” - Set a tabindex for the link. (optional) 
 - dir start/next/first/last - Links to the the next or previous page, or the first page (first) or last  page (last). Note! Only one of the above may be used in the same cms_selflink statement!! (optional) 
 - text - Text to show for the link. If not given, the Page Name is used instead. (optional) 
 - target - Optional target for the a link to point to. Useful for frame and
   JavaScript situations. (optional) 
 - class - Class for the a tag. Useful for styling the link. (optional) 
 - id - Optional css_id for the a tag. (optional)  
 - more - place - additional options inside the a tag. (optional)  
 - label - Label to usein with the link if applicable. (optional)  
 - label_side left/right - Side of link to place the label (defaults to “left”). (optional)
 - title - Text to use in the title attribute. If none is given, then the title of the page will be used  for the title. (optional) 
 - href - Specifies that only the result URL  to the page alias specified will be returned. This is essentially  equal to {cms_selflink page=”alias” urlonly=1}. Example: `<a href=”{cms_selflink href=”alias”}”><img src=”“></a>`. (optional)
 - urlonly - Specifies that only the resulting url should be output. All
   parameters related to generating links are ignored. (optional) 
 - image - A url of an image to use in the link. Example:  `{cms_selflink dir=”next” image=”next.png” text=”Next”}` (optional) 
 - alt - Alternative  text to be used with image (alt=”” will be used if no alt parameter
   is given). (optional)
 - width - Width to be used with image (no width   attribute will be used on output img tag if not provided.). (optional) 
 - height - Height to be used with image (no height attribute   will be used on output img tag if not provided.). (optional)
 - imageonly - If using an image, whether to suppress display of text   links. If you want no text in the link at all, also set lang=0 to   suppress the label. Example: `{cms_selflink dir=”next”   image=”next.png” text=”Next” imageonly=1}` (optional) assign - Assign -  the results to the named smarty variable.

**Dou you want more?** 
Paid support or customization on [zdeno@kuzmany.biz][6]

  [1]: http://img.shields.io/badge/paypal-donate-red.svg?style=flat
  [2]: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=MRQZT5MB8DJLC&lc=SK&item_name=Kuzmany.biz%20-%20support%20my%20open%20source%20projects&currency_code=EUR&bn=PP-DonationsBF:btn_donate_LG.gif:NonHosted
  [3]: http://img.shields.io/gittip/kuzmany.png?style=flat&color=green
  [4]: https://www.gittip.com/kuzmany/
  [5]: http://www.prestashop.com/forums/topic/342343-free-module-tabs-from-cms/?p=1725978
  [6]: mailto:zdeno@kuzmany.biz
  
