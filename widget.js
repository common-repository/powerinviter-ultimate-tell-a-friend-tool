PowerInviterObj=function(){

var powerinviter_domain='http://www.powerinviter.com';

if(typeof powerinviter_user=='undefined'){powerinviter_user='auto'}
if(typeof powerinviter_form=='undefined'){powerinviter_form=1}
if(typeof powerinviter_zindex=='undefined'){powerinviter_zindex=1000}
if(typeof powerinviter_form_linkid=='undefined'){powerinviter_form_linkid=''}
if(typeof powerinviter_form_refid=='undefined'){powerinviter_form_refid=''}
if(typeof powerinviter_imgtype=='undefined'){powerinviter_imgtype=''}
if(typeof powerinviter_tab_orientation=='undefined'){powerinviter_tab_orientation='right'}
if(typeof powerinviter_tab_color=='undefined'){powerinviter_tab_color='orange'}
if(typeof powerinviter_tab_offset=='undefined'){powerinviter_tab_offset=200}
if(typeof powerinviter_display_tab=='undefined'){powerinviter_display_tab=0}
if(typeof powerinviter_page_url=='undefined'){powerinviter_page_url=''}
if(typeof powerinviter_page_title=='undefined'){powerinviter_page_title=''}
if(typeof powerinviter_page_description=='undefined'){powerinviter_page_description=''}
if(typeof powerinviter_page_parse=='undefined'){powerinviter_page_parse='no'}
if(typeof powerinviter_imgpath=='undefined'||powerinviter_imgpath==''){powerinviter_imgpath='http://static.powerinviter.com/images'}
if(typeof powerinviter_engine=='undefined'){powerinviter_engine=''}
if(!powerinviter_user){powerinviter_user='auto';powerinviter_form=1;}

this.CreateLayers=function()
{
document.write('<style type="text/css">')
document.write('#powerinviter_block{position:fixed;width:640px;height:460px;top:0px;left:0px;margin:0px;border-style:none;border-width:0px;z-index:'+powerinviter_zindex+';}')
document.write('#powerinviter_fader{position:fixed;width:100%;height:100%;left:0px;top:0px;right:0px;bottom:0px;background-color:#000000;border-style:none;border-width:0px;z-index:'+(powerinviter_zindex-1)+';opacity:0.6;-moz-opacity:0.6;-khtml-opacity:0.6;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=60)}')
document.write('</style>')
document.write('<div id="powerinviter_fader" style="display:none;" onclick="PowerInviter.CloseInviteForm()"></div><div id="powerinviter_block" style="display:none;"></div>')

if(powerinviter_imgtype=="tab"||powerinviter_display_tab==1){this.CreateTabLayer()}
}

this.CreateTabLayer=function()
{
telltab_pos=0;
powerinviter_tab_image=powerinviter_imgpath+'/telltab_'+powerinviter_tab_color+'_'+powerinviter_tab_orientation+'.png';
if(powerinviter_tab_orientation=='left'||powerinviter_tab_orientation=='right'){telltab_img_w=30;telltab_img_h=190}else{telltab_img_w=190;telltab_img_h=30}
if(powerinviter_tab_orientation=='left'){telltab_pos='left:0px;top:'+powerinviter_tab_offset+'px'}
if(powerinviter_tab_orientation=='right'){telltab_pos='right:0px;top:'+powerinviter_tab_offset+'px'}
if(powerinviter_tab_orientation=='top'){telltab_pos='top:0px;left:'+powerinviter_tab_offset+'px'}
if(powerinviter_tab_orientation=='bottom'){telltab_pos='bottom:0px;left:'+powerinviter_tab_offset+'px'}

document.write('<style type="text/css">.invitetab{position:fixed;z-index:'+(powerinviter_zindex-2)+';'+telltab_pos+'}* html .invitetab{position:absolute}</style>\n');
document.write('<div class="invitetab">\n');
document.write('<a href="'+powerinviter_domain+'/form/'+powerinviter_user+'_'+powerinviter_form+'/" target="_blank"  onclick="PowerInviter.ShowInviteForm();return false;">\n');
document.write('<image src="'+powerinviter_tab_image+'" border="0" style="margin:0;padding:0;border-style:none;border-width:0" />\n');
document.write('</a></div>');
}

this.getScrollPos=function(){return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.scrollTop:document.body.scrollTop}
this.getWindowWidth=function(){return document.compatMode=='CSS1Compat'&&!window.opera?document.documentElement.clientWidth:document.body.clientWidth}
this.getWindowHeight=function(){return window.innerHeight||(document.documentElement && document.documentElement.clientHeight)||(document.body.clientHeight)}

this.ShowInviteForm=function(data)
{
if(!data){data={user:'', form:'', url:'', title:'', description:'', zindex:'', linkid:'', refid:'', parsing:'', encoding:'', postID:''}}

//if(powerinviter_user=='auto'){powerinviter_page_parse='yes'}

powerinviter_current_user=powerinviter_user
powerinviter_current_form=powerinviter_form
powerinviter_current_url=""
powerinviter_page_title=""
powerinviter_page_description=""

if(data.user){powerinviter_current_user=data.user}
if(data.form){powerinviter_current_form=data.form}
if(data.url){powerinviter_current_url=data.url}
if(data.title){powerinviter_page_title=data.title}
if(data.description){powerinviter_page_description=data.description}
if(data.zindex){powerinviter_zindex=data.zindex}
if(data.linkid){powerinviter_form_linkid=data.linkid}
if(data.refid){powerinviter_form_refid=data.refid}
if(data.parsing){powerinviter_page_parse=data.parsing}

if(powerinviter_engine=="blogger"){powerinviter_page_title=document.getElementById('powerinviter_'+data.postID).innerHTML}

if(!powerinviter_current_url){powerinviter_current_url=document.location.href||window.location}
if(!powerinviter_page_title){powerinviter_page_title=document.title}
if(!powerinviter_page_description){powerinviter_page_description=""}

powerinviter_current_url=powerinviter_current_url.substring(0,100)
powerinviter_page_title=powerinviter_page_title.substring(0,200)
powerinviter_page_description=powerinviter_page_description.substring(0,200)

powerinviter_current_url=encodeURIComponent(powerinviter_current_url)
powerinviter_page_title=encodeURIComponent(powerinviter_page_title)
powerinviter_page_description=encodeURIComponent(powerinviter_page_description)

powerinviter_page_encoding=data.encoding||document.charset||document.characterSet
powerinviter_page_data='&url='+powerinviter_current_url+'&parse='+powerinviter_page_parse+'&title='+powerinviter_page_title+'&description='+powerinviter_page_description+'&encoding='+powerinviter_page_encoding+"&engine="+powerinviter_engine

//if(document.getElementById('powerinviter_block').innerHTML==""){
var powerinviter_contents='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;margin:0px"><tr><td>&nbsp;</td><td align="center" width="32"><img src="'+powerinviter_imgpath+'//close_w.png" width="32" height="32" alt="X" border="0" onclick="PowerInviter.CloseInviteForm()" style="margin:0px;padding:0px;border-style:none;border-width:0px;cursor:pointer;align:right"></td></tr></table>\n'+
'<iframe src="'+powerinviter_domain+'/widget/'+powerinviter_current_user+'_'+powerinviter_current_form+'_'+powerinviter_form_refid+'/?id='+powerinviter_form_linkid+powerinviter_page_data+'" style="width:640px;height:460px;border-style:none;border-width:0px;" marginwidth="0" marginheight="0" frameborder="0" align="left">error</iframe>'
document.getElementById('powerinviter_block').innerHTML=powerinviter_contents
//}

use_fader=1;
if(typeof powerinviter_fader=='string'){if(powerinviter_fader=='off'){use_fader=0}}
if(use_fader==1)
{
//document.getElementById('powerinviter_fader').style.top=this.getScrollPos()+'px';
document.getElementById('powerinviter_fader').style.display="block"
}

var tellafriend_block=document.getElementById('powerinviter_block');
tellafriend_block.style.left=Math.round(this.getWindowWidth()/2-320)+'px';
tellafriend_block.style.top=Math.round(this.getWindowHeight()/2-270)+'px';

this.DynamicFadeOn('powerinviter_block',0)
}

this.DynamicFadeOn=function(obj,opvalue)
{
var o=document.getElementById(obj);

var p;
if (typeof document.body.style.opacity == 'string') p = 'opacity';
else if (typeof document.body.style.MozOpacity == 'string') p = 'MozOpacity';
else if (typeof document.body.style.KhtmlOpacity == 'string') p = 'KhtmlOpacity';
else if (document.body.filters && navigator.appVersion.match(/MSIE ([\d.]+);/)[1]>=5.5) p = 'filter';

//o.style.filter='alpha(opacity='+opvalue*100+')';
//document.getElementById(obj).style.opacity=opvalue;

o.style.display="block";
}

this.CloseInviteForm=function()
{
document.getElementById('powerinviter_block').style.display="none";
document.getElementById('powerinviter_fader').style.display="none";
}

}

PowerInviter=new PowerInviterObj()
PowerInviter.CreateLayers()
function ShowInviteForm(data){PowerInviter.ShowInviteForm(data)}