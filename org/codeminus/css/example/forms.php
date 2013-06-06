<form>
  <fieldset class="shadow-small">
    <legend>Text fields & label</legend>
    <label for="name">Input Text</label><br/>
    <input type="text" id="name" placeholder="input text required" required />
    <input type="password" value="123456" disabled />
    <input type="email" id="name" value="wilson@codeminus.org" /><br/>
    <input type="text" placeholder=".tiny" class="tiny" />
    <input type="text" placeholder=".small" class="small" />
    <input type="text" placeholder=".medium" class="medium" />
    <input type="text" placeholder=".large" class="large" />
    <input type="text" placeholder=".xlarge" class="xlarge" />
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
      <label>label</label>
      <input type="text" value="text" />
      <input type="submit" value="submit" class="btn btn-blue" />
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
  <fieldset>
    <legend>textarea</legend>
    <textarea></textarea>
  </fieldset>
</form>