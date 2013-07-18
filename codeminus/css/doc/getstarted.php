<h4>Adding Codeminus front-end Framework to your pages</h4>
<p>
  To use Codeminus Front-end Framework, include the CSS and javascript files to your page
  as shown in the example below:
</p>
<pre class="code code-line-numbered code-highlight">
&lt;!DOCTYPE html&gt;
&lt;html&gt;
  &lt;head&gt;
    &lt;title&gt;&lt;title&gt;
    &lt;link href="codeminus/css/codeminus.css" rel="stylesheet" /&gt;
  &lt;/head&gt;
  &lt;body&gt;
    Your page content
    &lt;script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"&gt;&lt;/script&gt;
    &lt;script src="codeminus/js/codeminus.js"&gt;&lt;/script&gt;
  &lt;/body&gt;
&lt;/html&gt;
</pre>
<h5 class="underline">CSS files</h5>
<p>
  Codeminus CSS Library is separated in different modules in order to give you
  the option to import only the ones you need to your project.
</p>
<table class="table-border-rounded table-condensed">
  <tr>
    <th>File</th>
    <th>Description</th>
  </tr>
  <tr>
    <td>base.css</td>
    <td>Basic tag styling and normalize.css importation</td>
  </tr>
  <tr>
    <td>codeminus.css</td>
    <td>Imports all css files except <b>famfamfam.css</b></td>
  </tr>
  <tr>
    <td>codeminus.min.css</td>
    <td>A minified file containing all css declarations except <b>famfamfam.css</b></td>
  </tr>
  <tr>
    <td>codes.css</td>
    <td>Basic lightweight Code source styling.</td>
  </tr>
  <tr>
    <td>containers.css</td>
    <td>Content box styling</td>
  </tr>
  <tr>
    <td>famfamfam.css</td>
    <td>
      1000 icons in sprite form from 
      <a href="http://famfamfam.com" target="blank">famfamfam</a>
    </td>
  </tr>
  <tr>
    <td>forms.css</td>
    <td>Form components and element styling</td>
  </tr>
  <tr>
    <td>glyphicon.css</td>
    <td>
      400 icons in sprite form from
      <a href="http://glyphicons.com" target="blank">Glyphicons</a>
    </td>
  </tr>
  <tr>
    <td>images.css</td>
    <td>Image styling</td>
  </tr>
  <tr>
    <td>layouts.css</td>
    <td>A grid system with 15 columns for a 920px wide container.</td>
  </tr>
  <tr>
    <td>navs.css</td>
    <td>Navigation components</td>
  </tr>
  <tr>
    <td>normalize.css</td>
    <td>
      It implements basic tag styling and makes browsers render all elements
      more consistently by using
      <a href="http://necolas.github.io/normalize.css/" target="blank">
        Normalize
      </a>
    </td>
  </tr>
  <tr>
    <td>tables.css</td>
    <td>Table styling</td>
  </tr>
</table>
<p>
  By default, <strong>codeminus.css</strong> imports all CSS files, except <strong>famfamfam.css</strong>, just in case
  you dont want to import all theses files yourself:
</p>
<pre class="code code-line-numbered code-highlight">
@import "base.css";
@import "codes.css";
@import "containers.css";
@import "forms.css";
@import "images.css";
@import "layouts.css";
@import "navs.css";
@import "tables.css";
@import "glyphicon.css";
</pre>
<p>
  It is highly recommended that you always include 
  <strong>\codeminus\css\base.css</strong> before any other module.
  It implements basic tag styling and makes browsers render all elements more
  consistently by using 
  <a href="http://necolas.github.io/normalize.css/" target="blank">
    Normalize
  </a>, a project by Nicolas Gallagher, co-created with Jonathan Neal.
</p>
<h5 class="underline">A personalized css file</h5>
<p>
  Lets say you only want to use form element styling and the famfamfam sprite
  from our library. Your <strong>assets/css/personalized.css</strong> file would look like
  this:
</p>
<pre class="code code-line-numbered code-highlight">
@import "../../codeminus/css/base.css";
@import "../../codeminus/css/forms.css";
@import "../../codeminus/css/famfamfam.css";

/*Your CSS declarations here*/
</pre>
<p>And your page would include the personalized css files like this:</p>
<pre class="code code-line-numbered code-highlight">
&lt;!DOCTYPE html&gt;
&lt;html&gt;
  &lt;head&gt;
    &lt;title&gt;&lt;title&gt;
    &lt;link href="assets/css/personalized.css" rel="stylesheet" /&gt;
  &lt;/head&gt;
  &lt;body&gt;
    Your page content
    &lt;script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"&gt;&lt;/script&gt;
    &lt;script src="codeminus/js/codeminus.js"&gt;&lt;/script&gt;
  &lt;/body&gt;
&lt;/html&gt;
</pre>