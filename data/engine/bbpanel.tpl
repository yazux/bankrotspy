<style>
  .bb_hide{
  background-color: rgba(178,178,178,0.5);
  padding: 5px;
  border: 1px solid #708090;
  display: none;
  overflow: auto;
  max-width: 70px;
  max-height: 150px;
  position: absolute;
  }
  .bb_hide_lang{
  background-color: rgba(178,178,178,0.5);
  padding: 5px;
  border: 1px solid #708090;
  display: none;
  overflow: auto;
  max-width: 150px;
  position: absolute;
  }
  .bb_opt:hover .bb_hide{
  display: block;
  }
  .bb_opt:hover .bb_hide_lang{
  display: block;
  }
  .bb_color a.lang{
  float:left;
  width:33px;
  height:16px;
  margin:1px;
  border: 1px solid black;
  font-size: 8pt;
  line-height:16px;
  overflow:hidden;
  background-color:#767676;
  color:white !important;
  }
  .bb_color a.lang:hover {
  background-color: #979797;
  text-decoration: none;
  }

  .bb_color a{
  float:left;
  width:10px;
  height:10px;
  margin:1px;
  border: 1px
  solid black;
  }
  div.tagspanel {
  border : 1px  solid #747474;
  border-bottom: 0px;
  border-right: 0px;
  background: #a3a3a3;
  padding: 0px;
  margin-top: 8px;
  margin-right: 3px;
  overflow: hidden;
  } 
</style>
<script language="JavaScript" type="text/javascript">
  function tag(text1, text2)
  {
    if((document.selection))
    {
      document.mess.{$msg}.focus();
      document.mess.document.selection.createRange().text = text1+document.mess.document.selection.createRange().text+text2;
    }
    else if(document.forms['mess'].elements['{$msg}'].selectionStart!=undefined)
    {
      var element = document.forms['mess'].elements['{$msg}'];
      var len = document.mess.{$msg}.selectionStart;
      var str = element.value;
      var scroll =  document.mess.{$msg}.scrollTop; 
      var start = element.selectionStart;
      var length = element.selectionEnd - element.selectionStart;
      element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);
      var scroll2 = scroll + text1.length + text2.length + length;
      document.mess.{$msg}.scrollTop = scroll2;
      var len2 = text1.length + len + text2.length + length;
   
      document.mess.{$msg}.setSelectionRange(len2,len2);
      document.mess.{$msg}.focus();
    }
    else 
    {
      document.mess.{$msg}.value += text1+text2;
    }
  }
</script>


<div class="tagspanel"><table class="tags_pan"><tr>   
<td><a title="{$lang_link}" href="javascript:tag('[url=]', '[/url]')"><i class="icon-globe"></i></a>
</td><td><a title="{$lang_bold}" href="javascript:tag('[b]', '[/b]')"><i class="icon-bold"></i></a>
</td><td><a title="{$lang_it}" href="javascript:tag('[i]', '[/i]')"><i class="icon-italic"></i></a>
</td><td><a title="{$lang_und}" href="javascript:tag('[u]', '[/u]')"><i class="icon-underline"></i></a>
</td><td><a title="{$lang_str}" href="javascript:tag('[s]', '[/s]')"><i class="icon-strike"></i></a>
</td><td><a title="{$lang_quote}" href="javascript:tag('[c]', '[/c]')"><i class="icon-comment"></i></a>
</td><td><a title="{$lang_sp}" href="javascript:tag('[spoiler]', '[/spoiler]')"><i class="icon-plus-circled"></i></a>
</td><td><span class="bb_opt" width="100%" style="display: block; cursor:pointer"><a title="{$lang_code}" href="javascript:tag('[code]', '[/code]')"><i class="icon-code"></i></a>{$prog}</span>
</td><td><a title="{$lang_math}" href="javascript:tag('[math]', '[/math]')"><i class="icon-pi-outline"></i></a>
</td><td><span class="bb_opt" width="100%" style="display: block; cursor:pointer"><a title="{$lang_t_col}" href="javascript:tag('[color=]', '[/color]')"><i class="icon-brush"></i></a>{$font_color}</span>
</td><td><span class="bb_opt" width="100%" style="display: block; cursor:pointer"><a title="{$lang_back}" href="javascript:tag('[bg=]', '[/bg]')"><i class="icon-tint"></i></a>{$bg_color}</span>
</td><td><a title="{$lang_video}" href="javascript:tag('[youtube]', '[/youtube]')"><i class="icon-youtube-play"></i></a>
</td><td><a title="{$lang_cent}" href="javascript:tag('[center]', '[/center]')"><i class="icon-align-center"></i></a>
</td><td><a title="{$lang_right}" href="javascript:tag('[right]', '[/right]')"><i class="icon-align-right"></i></a>
</td></tr></table></div>