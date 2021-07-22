//图片预览专用
var preview_html;
preview_html = "<div class=\"popup_mask\" id=\"popup_mask\" style=\"display:none;\"></div>";
preview_html += "<div class=\"popup_page\" id=\"popup_page\">";
preview_html += "<a href=\"javascript:preview_images_close();\" target=\"_self\"><span class=\"close\"></span></a>";
preview_html += "<div class=\"content\">";
preview_html += "<div class=\"title\">";
preview_html += "<input type=\"text\" class=\"title\" value=\"\">";
preview_html += "</div>";
preview_html += "<div class=\"attPreview\" id=\"preview_images_bottom\">";
preview_html += "</div>";
preview_html += "</div>";
preview_html += "</div>";
document.write (preview_html);

function preview_images_close()
{
  get_id("popup_mask").style.display = 'none';
  get_id("popup_mask").className = 'popup_mask';
  get_id("popup_page").className = 'popup_page';
}

function preview_images(strurl, e)
{
  get_id("popup_mask").style.display = 'block';
  get_id("popup_mask").className = 'popup_mask on';
  get_id("popup_page").className = 'popup_page on';
  get_id("preview_images_bottom").innerHTML = "<img class=\"item\" src=\"" + strurl + "\" border=\"0\" onload=\"if (this.width > 300)this.width = 300;if (this.height > 180)this.height = 180;\" alt=\"" + strurl + "\">";
}

function preview_img(strurl, e)
{
  var curPosX, curPosY
var img_arr = strurl.split("#:#");
var img_arr_len = img_arr.length - 1;
var img_url = img_arr[img_arr_len];
var img_title = img_arr[0];
var img_desc = img_arr[1];
  get_id("popup_mask").style.display = 'block';
  get_id("popup_mask").className = 'popup_mask on';
  get_id("popup_page").className = 'popup_page on';
  get_id("preview_images_bottom").innerHTML = "<img class=\"item\" src=\"" + img_url + "\" border=\"0\" onload=\"if (this.width > 300)this.width = 300;if (this.height > 180)this.height = 180;\" title=\"" + img_title + "\" alt=\"" + img_desc + "\">";
}