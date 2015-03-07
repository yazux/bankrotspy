<script language="javascript">
 var Width = document.getElementById("mainwidth").offsetWidth;
 if(!Width) Width = 700;
 Width = Width-70-70;
 if(Width > 450)
   Width = 450;
 Height = Width/4*3;
 document.write('<object width="'+Width+'" height="'+Height+'"><param name="movie" value="https://www.youtube.com/v/{$link}?rel=0&amp;showinfo=0&amp;version=3"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="https://www.youtube.com/v/{$link}?rel=0&amp;showinfo=0&amp;version=3" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="'+Width+'" height="'+Height+'"></embed></object>'); 
 </script>
