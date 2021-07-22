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
                 plugins: ' print preview clearhtml searchreplace layout fullscreen image media code codesample table charmap hr pagebreak nonbreaking anchor advlist lists textpattern emoticons bdmap indent2em lineheight formatpainter powerpaste letterspacing wordcount autoresize codesample link ',
                 toolbar1: '|code formatselect fontselect fontsizeselect forecolor backcolor bold italic underline strikethrough alignment indent2em outdent indent alignleft aligncenter alignright',
                 toolbar2: '|lineheight letterspacing bullist numlist blockquote subscript superscript link unlink layout removeformat table image media charmap hr pagebreak clearhtml bdmap formatpainter cut copy paste undo redo searchreplace fullscreen codesample',
                 toolbar_mode: 'sliding',
                  end_container_on_empty_block:true,
                  paste_data_images:true,
                  powerpaste_word_import: 'propmt',
                  powerpaste_html_import: 'propmt',
                  powerpaste_allow_local_images: true,
                  images_upload_handler: function (blobInfo, success, failure, progress) {
                    var xhr, formData;
                    var domain = document.domain;
                    var port = window.location.port;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '//'+domain+':'+port+'/upload.php');
                    xhr.upload.onprogress = function(e){
                        progress(e.loaded / e.total * 100);
                    }
                    xhr.onload = function() {
                      var json;
                      if (xhr.status == 403) {
                          failure('HTTP Error: ' + xhr.status, { remove: true });
                          return;
                      }
                      if (xhr.status < 200 || xhr.status >= 300) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                      }
                      console.log(xhr.responseText);
                      json = JSON.parse(xhr.responseText);
                      if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                      }
                      success(json.location);
                    };
                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                  },
                 fontsize_formats: '12px 14px 16px 18px 24px 36px 48px 56px 72px',
                 font_formats: '微软雅黑=Microsoft YaHei,Helvetica Neue,PingFang SC,sans-serif;苹果苹方=PingFang SC,Microsoft YaHei,sans-serif;宋体=simsun,serif;仿宋体=FangSong,serif;黑体=SimHei,sans-serif;Arial=arial,helvetica,sans-serif;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats;',
          }).then(function(res){
             });
function editor_insert(strid, strers)
{
  tinyMCE.execCommand("mceInsertContent", false, strers);
}