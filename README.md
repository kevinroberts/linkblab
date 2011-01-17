Linkblab - A Reddit Clone in PHP w/ Zend FW
==========================================

Thoughts on the project
----------------------------------

Just like Gabriel Weinberg brought to my attention over at http://www.gabrielweinberg.com/blog/2010/11/code-icebergs.html, creating a website like Reddit is a lot like the coding equivalelent of an iceberg in that, "they expose what a casual observer or competitor imagines is a  weekend hackathon, but underneath there is a humongous mass of necessarily  complicated code." I know this application mught be a little fugly on the outside, but on the inside it was an engineering labor of love. I tried to incorporate the same functionality Reddit offers with some of my own tweaks as I attempted to reverse engineer the Python code base.


TODO 
----------------------

- Build comments view. Add custom route for it in bootstrap. 
- Change Line 268 in site.js on live site
- Render links for blab specific pages
- Putting "NSFW" in some subblabs automatically (toggle in blab settings?).
- alt. Name for karma -- cred? 
- change line 17 of decoda.php on live site

To Change for Production
----------------------
- Remove all references to linkblab.local