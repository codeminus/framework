<form>
  <fieldset class="shadow-width-small">
    <legend>Text fields & label</legend>
    <label for="name">Input Text</label><br/>
    <input type="text" id="name" placeholder="input text required" required />
    <input type="password" value="123456" disabled />
    <input type="email" id="name" placeholder="input email" /><br/>
    <input type="text" placeholder=".width-tiny" class="width-tiny" />
    <input type="text" placeholder=".width-mini" class="width-mini" />
    <input type="text" placeholder=".width-small" class="width-small" />
    <input type="text" placeholder=".width-medium" class="width-medium" />
    <input type="text" placeholder=".width-large" class="width-large" />
    <input type="text" placeholder=".width-xlarge" class="width-xlarge" />
    <input type="text" placeholder=".width-xxlarge" class="width-xxlarge" />
  </fieldset>
  <fieldset>
    <legend>Buttons</legend>
    <input type="submit" value="submit" />
    <input type="button" value="button input" />
    <input type="button" value="disabled" disabled />
    <input type="reset" value="reset" />
    <button>button tag</button>
    <a href="#" class="btn">button anchor tag</a>
    <span class="btn">button span tag</span><br/>
    <a href="#" class="btn btn-blue">anchor tag .btn .btn-blue</a>
    <input type="button" value="button .btn .btn-blue disabled" class="btn btn-blue" disabled />
    <a href="#" class="btn btn-red" >anchor tag .btn .btn-red</a>
    <input type="button" value="button .btn .btn-red disabled" class="btn btn-red" disabled />
  </fieldset>
  <fieldset>
    <legend>.input-group</legend>
    <span class="input-group">
      <input type="button" value="button 01" />
      <input type="button" value="button 02" />
      <input type="button" value="button 03" />
    </span>
    <span class="input-group">
      <label for="textIn">label</label>
      <input type="text" placeholder="input text" id="textIn"/>
      <input type="submit" value="submit" class="btn btn-blue" />
    </span>
    <span class="input-group">
      <span>span</span>
      <input type="text" placeholder="input text" />
      <input type="submit" value="submit" class="btn btn-blue" />
    </span>
  </fieldset>
  <fieldset>
    <legend>.textarea-group</legend>
    <span class="textarea-group">
      <label for="msg1">label</label>
      <textarea id="msg1" rows="4" class="width-large" name="msg1">sdfsd</textarea>
      <span class="input-group float-right">
        <input type="button" value="button 01" />
        <input type="button" value="button 02" />
        <input type="button" value="button 03" />
      </span>
    </span>
    <span class="textarea-group">
      <label for="msg2">label</label>
      <textarea id="msg2" rows="4" class="width-large" name="msg2">textarea</textarea>
      <span>span</span>
    </span>
    </span>
  </fieldset>
  <fieldset>
    <legend>datalist & select</legend>
    <input type="text" list="browsers" placeholder="type the name of a browser">
    <datalist id="browsers">
      <option value="Internet Explorer">
      <option value="Firefox">
      <option value="Chrome">
      <option value="Opera">
      <option value="Safari">
    </datalist>
    <select>
      <option >Option 01</option>
      <option>Option 02</option>
      <option>Option 03</option>
      <option>Option 04</option>
    </select>
  </fieldset>
  <fieldset>
    <legend>File input</legend>
    <input type="file" />
  </fieldset>
  <fieldset>
    <legend>Radio & checkbox</legend>
    <input type="radio" name="gender" id="m"/>
    <label for="m">Male</label>
    <input type="radio" name="gender" id="f"/>
    <label for="f">Female</label><br/>
    <input type="checkbox" name="taste" id="b" />
    <label for="b">I like brazillian food!</label><br/>
    <input type="checkbox" name="taste" id="c"/>
    <label for="c">I like chinese food!</label><br/>
  </fieldset>
</form>
<form class="form-input-perline" >
  <fieldset>
    <legend>.form-input-perline</legend>
    <span class="input-group prepend-mini">
      <label for="nameIn">Name</label>
      <input type="text" id="nameIn" />
    </span>
    <span class="input-group prepend-mini">
      <label for="emailIn">E-mail</label>
      <input type="text" id="emailIn" />
    </span>
    <span class="input-group prepend-mini">
      <label for="subject">Subject</label>
      <input type="text" id="subject" />
    </span>
    <span class="textarea-group">
      <label for="msg3">Message</label>
      <textarea id="msg3" rows="4" class="width-large"></textarea>
    </span>
  </fieldset>
</form>
