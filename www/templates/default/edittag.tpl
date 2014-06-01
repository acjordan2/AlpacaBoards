{include file="header.tpl"}
<h1>Edit Tag</h1>
<h2>{$taginfo.title}</h2>
<script type="text/javascript">
  function disable() {
    var limit = document.forms[0].elements.length;
    for (i=0;i<limit;i++) {
      document.forms[0].elements[i].disabled = true;
    }
  }
</script>
<form action="tags.php" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>Description</legend>
        <textarea style="width:100%">{$taginfo.description}</textarea>
    </fieldset>
    <fieldset>
        <legend>Access</legend>
        <input type="radio" name="access" value="0" />
        Anyone can access this tag, the users listed below may not participate <i>(public)</i>
        <br />
        <input type="radio" name="access" value="1" />
        No one can access this tag, except the users listed below <i>(private)</i>
        <br />
        <input type="text" name="users" style="width:100%"/>
    </fieldset>
    <fieldset>
        <legend>Participation</legend>
        <input type="radio" name="participation" value="0" /> 
        Anyone can create topics or post
        <br />
        <input type="radio" name="participation" value="1" />
        Only moderators can create topics, anyone can post messages
        <br />
        <input type="radio" name="participation" value="2" />
        Only moderators can create topics or post messages
        <br />
    </fieldset>
    <fieldset>
        <legend>Restrictions</legend>
        <input type="checkbox" name="permanent" /> 
        Permanent <i>(Once added, this tag cannot be removed)</i>
        <br />
        <input type="checkbox" name="inceptive" />
        Inceptive <i>(This tag must added to a topic at the time of topic creation)</i>
        <br />
        <i>Note: Tag moderators can bypass the above restrictions.</i>
    </fieldset>
    <fieldset>
        <legend>Interactions</legend>
        Parents <i>(While browsing tags listed here, [{$taginfo.title}] will also appear. <b>Related tags cannot be tagged together</b>)</i><br />
        <input type="text" name="parents" style="width:100%" />
        <br />

        Children <i>(While browsing by [{$taginfo.title}], these tags will also appear. <b>Related tags cannot be tagged together</b>)</i>
        <input type="text" name="children" style="width:100%" /><br />

        Mutually exclusive <i>(The below tags are not allowed on the same topic together with [{$taginfo.title}])</i><br />
        <input type="text" name="exclusive" style="width:100%" /><br />

        Dependent <i>(The below tags must be present in order to tag a topic with [{$taginfo.title}])</i><br />
        <input type="text" name="dependant" style="width:100%" /><br />
    </fieldset>
    <fieldset>
        <legend>Moderators</legend>
        <input type="text" name="moderators" style="width:100%" /><br />
    </fieldset>
    <fieldset>
        <legend>Administrators</legend>
        <input type="text" name="administrators" style="width:100%" /><br />
    </fieldset>
    <input type="hidden" name="token" value="{$token}" />
    <input type="submit" name="save" value="save" />
</form>
<script>disable();</script>
<br />
<br />
{include file="footer.tpl"}
