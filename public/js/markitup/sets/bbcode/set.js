// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
myBbcodeSettings = {
  nameSpace:          "bbcode", // Useful to prevent multi-instances CSS conflict
  previewParserPath:  "/blabs/preview",
  previewAutoRefresh: true,
  onTab:          {keepDefault:false, openWith:'     ', placeHolder:'' },
  markupSet: [
      {name:'Bold', key:'B', openWith:'[b]', closeWith:'[/b]', className:"bold"}, 
      {name:'Italic', key:'I', openWith:'[i]', closeWith:'[/i]', className:"italic"}, 
      {name:'Underline', key:'U', openWith:'[u]', closeWith:'[/u]', className:"underline"},
      {name:'Subscript', openWith:'[sub]', closeWith:'[/sub]', className:"subscript"}, 
      {name:'Superscript', openWith:'[sup]', closeWith:'[/sup]', className:"superscript"}, 
      {separator:'---------------' },
      {name:'E-Mail', key:'M', openWith:'[mail=[![E-Mail]!]]', closeWith:'[/mail]', placeHolder:'email link text here...', className:"email"}, 
      {name:'Link', key:'L', openWith:'[url=[![Url]!]]', closeWith:'[/url]', placeHolder:'Your text to link here...', className:"anchor"},
      {separator:'---------------' },
      {name:'Colors', openWith:'[color=[![Color]!]]', closeWith:'[/color]', className:"colors", dropMenu: [
          {name:'Yellow', openWith:'[color=yellow]', closeWith:'[/color]', className:"col1-1" },
          {name:'Orange', openWith:'[color=orange]', closeWith:'[/color]', className:"col1-2" },
          {name:'Red', openWith:'[color=red]', closeWith:'[/color]', className:"col1-3" },
          {name:'Blue', openWith:'[color=blue]', closeWith:'[/color]', className:"col2-1" },
          {name:'Purple', openWith:'[color=purple]', closeWith:'[/color]', className:"col2-2" },
          {name:'Green', openWith:'[color=green]', closeWith:'[/color]', className:"col2-3" },
          {name:'White', openWith:'[color=white]', closeWith:'[/color]', className:"col3-1" },
          {name:'Gray', openWith:'[color=gray]', closeWith:'[/color]', className:"col3-2" },
          {name:'Black', openWith:'[color=black]', closeWith:'[/color]', className:"col3-3" }
      ]},
      {separator:'---------------' },
      {name:'Bulleted list', openWith:'[list]\n', closeWith:'\n[/list]', className:"bullet"}, 
      {name:'Numeric list', openWith:'[olist]\n', closeWith:'\n[/olist]', className:"numeric"}, 
      {name:'List item', key:'P', openWith:'[li]', closeWith:'[/li]', placeHolder:' ', className:"listitem"}, 
      {separator:'---------------' },
      {name:'Quotes', openWith:'[quote]', closeWith:'[/quote]', className:"quotes"}, 
      {name:'Code', openWith:'[code]', closeWith:'[/code]', className:"plaincode"}, 
      {separator:'---------------' },
      {name:'Clean', className:"clean", replaceWith:function(h) { return h.selection.replace(/\[(.*?)\]/g, ""); } },
      {name:'Preview', className:"preview", call:'preview' }
   ]
}