          tinymce.init({
                 selector: 'textarea.wdjaedit',
                 menubar: false,
                 toolbar_items_size: 'small',
                 language:'zh_CN',
                 convert_urls: false,
                 remove_script_host: false,
                 branding: false,
                 width: '100%', 
                 min_height: 500,
                 max_height:650,
                 plugins: 'print preview clearhtml searchreplace layout fullscreen image media code codesample table charmap hr pagebreak nonbreaking anchor advlist lists textpattern emoticons indent2em lineheight formatpainter letterspacing wordcount autoresize codesample link ',
                 toolbar1: '|undo redo fontselect fontsizeselect forecolor backcolor bold italic underline strikethrough alignment blockquote link unlink layout removeformat table image charmap hr formatpainter cut copy paste searchreplace fullscreen codesample',
                 toolbar_mode: 'sliding',
                 end_container_on_empty_block:true,
                 paste_data_images:true,
                 fontsize_formats: '12px 14px 16px 18px 24px 36px 48px 56px 72px',
                 font_formats: '微软雅黑=Microsoft YaHei,Helvetica Neue,PingFang SC,sans-serif;苹果苹方=PingFang SC,Microsoft YaHei,sans-serif;宋体=simsun,serif;仿宋体=FangSong,serif;黑体=SimHei,sans-serif;Arial=arial,helvetica,sans-serif;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats;',
          }).then(function(res){
             });
function editor_insert(strid, strers)
{
  tinyMCE.execCommand("mceInsertContent", false, strers);
}