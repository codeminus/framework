<h4>Adding Codeminus CSS Library to your pages</h4>
<p>
  To use Codeminus CSS Library, include the CSS and js files to your page
  as shown in the example below:
</p>
<pre class="code code-line-numbered code-highlight">
&lt;!DOCTYPE html&gt;
&lt;html&gt;
  &lt;head&gt;
    &lt;title&gt;&lt;title&gt;
    &lt;link href="org/codeminus/css/codeminus.css" rel="stylesheet" /&gt;
  &lt;/head&gt;
  &lt;body&gt;
    Your page content
    &lt;script src="http://code.jquery.com/jquery.js"&gt;&lt;/script&gt;
    &lt;script src="org/codeminus/js/codeminus.js"&gt;&lt;/script&gt;
  &lt;/body&gt;
&lt;/html&gt;
</pre>
<h5 class="underline">CSS files</h5>
<p>
  Codeminus CSS Library is separated in different modules in order to give you
  the option to import only the ones you need to your project.
</p>
<table class="table-border-rounded">
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
    <td>Code source styling</td>
  </tr>
  <tr>
    <td>containers.css</td>
    <td>Content box styling</td>
  </tr>
  <tr>
    <td>famfamfam.css</td>
    <td>
      <a href="http://famfamfam.com" target="blank">famfamfam</a>
      icons in sprite form
    </td>
  </tr>
</table>
<ul>
  <li>famfamfam.css
    <span class="text-disabled">
      (<a href="http://famfamfam.com" target="blank">famfamfam</a>
      icons in sprite form)
    </span>
  </li>
  <li>
    forms.css
    <span class="text-disabled">
      (form elements styles)
    </span>
  </li>
  <li>
    glyphicon.css
    <span class="text-disabled">
      (<a href="http://glyphicons.com" target="blank">glyphicons free</a>
      icons in sprite form)
    </span>
  </li>
  <li>
    media.css
    <span class="text-disabled">
      (media classes)
    </span>
  </li>
  <li>
    navs.css
    <span class="text-disabled">
      (navigation menus)
    </span>
  </li>
  <li>
    normalize.css
    <span class="text-disabled">
      (makes browsers render all elements more consistently)
    </span>
  </li>
  <li>
    tables.css
    <span class="text-disabled">
      (table styles)
    </span>
  </li>
</ul>
<p>
  By default, <strong>codeminus.css</strong> imports all CSS files, just in case
  you dont want to import all theses files yourself:
</p>
<pre class="code code-line-numbered code-highlight">
@import "normalize.css";
@import "base.css";
@import "codes.css";
@import "containers.css";
@import "forms.css";
@import "media.css";
@import "navs.css";
@import "tables.css";
@import "glyphicon.css";
@import "famfamfam.css";
</pre>
<p>
  It is highly recommended that you always include 
  <strong>\org\codeminus\css\base.css</strong> before any other module.
  It implements basic tag styling and makes browsers render all elements more
  consistently by using 
  <a href="http://necolas.github.io/normalize.css/" target="blank">
    Normalize
  </a>, a project by Nicolas Gallagher, co-created with Jonathan Neal.
</p>
<h5 class="underline">A personalized css file</h5>
<p>
  Lets say you only want to use form element styling and the glyphicons sprite
  from our library. Your <strong>personalized.css</strong> file would look like:
</p>
<pre class="code code-line-numbered code-highlight">
@import "base.css";
@import "forms.css";
@import "glyphicon.css";

/*Your CSS declarations here*/
</pre>