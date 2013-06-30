<form>
  <fieldset class="shadow-small">
    <legend>Text fields & label</legend>
    <label for="name">Input Text</label><br/>
    <input type="text" id="name" placeholder="input text required" required />
    <input type="password" value="123456" disabled />
    <input type="email" id="name" placeholder="input email" /><br/>
    <input type="text" placeholder=".width-tiny" class="width-tiny" /><br/>
    <input type="text" placeholder=".width-mini" class="width-mini" /><br/>
    <input type="text" placeholder=".width-small" class="width-small" /><br/>
    <input type="text" placeholder=".width-medium" class="width-medium" /><br/>
    <input type="text" placeholder=".width-large" class="width-large" /><br/>
    <input type="text" placeholder=".width-xlarge" class="width-xlarge" /><br/>
    <input type="text" placeholder=".width-xxlarge" class="width-xxlarge" /><br/>
  </fieldset>
  <fieldset class="shadow-small">
    <legend>Buttons</legend>
    <input type="button" value="button" />
    <input type="button" value="button disabled" disabled />
    <a href="#" class="btn">.btn</a>
    <input type="button" value="off"
           data-toggle="button"
           data-toggle-value="on;off"
           data-toggle-class="btn-blue"/>
    <input type="button" value="initial state"
           data-toggle="button"
           data-toggle-value="clicked 01;clicked 02"
           data-toggle-class=""/>
    <div class="inline dropdown">
      <button type="button" class="trigger btn-warning">
        dropdown
        <span class="caret"></span>
      </button>
      <ul class="drop-menu">
        <li><a href="#">Item</a></li>
        <li><a href="#">Item</a></li>
        <li><a href="#">Item</a></li>
      </ul>
    </div>
    <div class="inline dropup">
      <button type="button" class="trigger btn-warning">
        dropup
        <span class="caret"></span>
      </button>
      <ul class="drop-menu">
        <li><a href="#">Item</a></li>
        <li class="submenu">
          <a href="#" class="trigger">Item
            <span class="caret"></span>
          </a>
          <ul class="drop-menu">
            <li><a href="#">Item</a></li>
            <li><a href="#">Item</a></li>
            <li><a href="#">Item</a></li>
          </ul>
        </li>
        <li><a href="#">Item</a></li>
      </ul>
    </div>
    <br/>
    <input type="button" class="btn-small" value=".btn-small" />
    <input type="button" class="btn-large" value=".btn-large" />
    <input type="button" class="btn-xlarge" value=".btn-xlarge" />
    <br/>
    <input type="button" class="success" value=".text-success" />
    <input type="button" class="warning" value=".text-warning" />
    <input type="button" class="info" value=".text-info" />
    <input type="button" class="error" value=".text-info" />
    <br/>
    <input type="button" class="btn-blue" value=".btn-blue" />
    <input type="button" class="btn-black" value=".btn-info" />
    <input type="button" class="btn-success" value=".btn-success" />
    <input type="button" class="btn-warning" value=".btn-warning" />
    <input type="button" class="btn-info" value=".btn-info" />
    <input type="button" class="btn-danger" value=".btn-danger">
    <input type="button" class="btn-danger" value=".btn-danger disabled" disabled>
  </fieldset>
  <fieldset class="shadow-small">
    <legend>.input-group</legend>
    <span class="input-group large">
      <input type="button" value="button 01" />
      <input type="button" value="button 02" />
      <input type="button" value="button 03" />
    </span>
    <span class="input-group"
          data-toggle="button-group-radio">
      <input type="button" value="active" />
      <input type="button" value="only" />
      <input type="button" value="one" />
    </span>
    <span class="input-group" data-toggle="button-group-checkbox">
      <input type="button" class="btn-danger" value="active" />
      <input type="button" class="btn-danger" value="more" />
      <input type="button" class="btn-danger" value="than one" />
    </span>
    <span class="input-group">
      <input type="button" class="btn-danger" value="active" />
      <div class="dropup">
        <button type="button" class="trigger btn-danger">
          <span class="caret"></span>
        </button>
        <ul class="drop-menu">
          <li><a>Item</a></li>
          <li><a>Item</a></li>
          <li><a>Item</a></li>
        </ul>
      </div>
    </span>
    <br/>
    <span class="input-group">
      <label for="textIn">label</label>
      <input type="text" placeholder="input text" id="textIn"/>
      <input type="button" class="btn-danger" value="button" />
    </span>
    <span class="input-group">
      <span>span</span>
      <input type="text" placeholder="input text" />
      <input type="button" value="button" class="btn-blue" />
    </span>
  </fieldset>
  <fieldset class="shadow-small">
    <legend>.textarea-group</legend>
    <span class="textarea-group">
      <label for="msg1">label</label>
      <textarea id="msg1" rows="4" class="width-large" name="msg1">textarea</textarea>
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
  <fieldset class="shadow-small">
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
  <fieldset class="shadow-small">
    <legend>File input</legend>
    <input type="file" />
  </fieldset>
  <fieldset class="shadow-small">
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
<form class="form-input-perline form-input-padding-large" >
  <fieldset class="shadow-small">
    <legend>.form-input-perline .form-input-padding-large</legend>
    <h4>Contact Form</h4>
    <div class="float-left margined-right childs-valign-top"
         id="height-referene">
      <span class="input-group prepend-mini">
        <label for="nameIn">Name</label>
        <input type="text" id="nameIn" />
      </span>
      <span class="input-group prepend-mini">
        <label for="emailIn">E-mail</label>
        <input type="text" id="emailIn" />
      </span>
      <span class="input-group prepend-mini" id="width-reference">
        <label for="subject">Subject</label>
        <input type="text" id="subject" />
      </span>
    </div>
    <textarea id="msg3" rows="4" data-height-from="height-referene"
                data-width-from="width-reference">data-width-from</textarea>
    <span class="textarea-group" >
      <label for="msg3">Message</label>
      
    </span>

    <input type="button" value="Send" class="btn-blue"/>
  </fieldset>
</form>
