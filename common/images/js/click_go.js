//点击处理等待专用
var click_html;
click_html = '<div class="popup_mask" id="pop_mask" style="display: none;"></div>';
click_html += '<div class="popup_page on" id="pop_page" style="display: none;">';
click_html += '<div class="content">';
click_html += '    <table cellpadding="10" cellspacing="0" class="tableF">';
click_html += '        <tr>';
click_html += '        <td>正在处理中，请稍后...</td>';
click_html += '        </tr>';
click_html += '    </table>';
click_html += '</div>';
click_html += '</div>';
document.write (click_html);
function click_go(){
  get_id("pop_mask").style.display = 'block';
  get_id("pop_page").style.display = 'block';
  get_id("pop_mask").className = 'popup_mask on';
  get_id("pop_page").className = 'popup_page on';
}